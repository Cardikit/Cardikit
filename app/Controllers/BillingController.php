<?php

namespace App\Controllers;

use App\Core\Config;
use App\Core\Request;
use App\Core\Response;
use App\Models\User;
use App\Services\AuthService;
use Stripe\StripeClient;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class BillingController
{
    private const PRO_ROLE = 3;
    private const ADMIN_ROLE = 2;
    private const TRIAL_DAYS = 14;

    protected function stripe(): StripeClient
    {
        $secret = Config::get('STRIPE_SECRET_KEY');
        if (!$secret) {
            throw new \RuntimeException('Stripe secret key not configured');
        }

        return new StripeClient($secret);
    }

    public function checkout(Request $request): void
    {
        $userId = (new AuthService())->currentUserId();
        if (!$userId) {
            Response::json(['message' => 'Unauthorized'], 401);
            return;
        }

        try {
            $body = $request->body();
            $plan = $body['plan'] ?? 'monthly';
            $priceMap = [
                'monthly' => Config::get('STRIPE_PRICE_MONTHLY'),
                'annual' => Config::get('STRIPE_PRICE_ANNUAL'),
            ];

            $priceId = $priceMap[$plan] ?? null;
            if (!$priceId) {
                Response::json(['message' => 'Plan not available'], 422);
                return;
            }

            $successUrl = Config::get('STRIPE_SUCCESS_URL') ?: Config::get('APP_URL');
            $cancelUrl = Config::get('STRIPE_CANCEL_URL') ?: Config::get('APP_URL');

            if (!$successUrl || !$cancelUrl) {
                throw new \RuntimeException('Missing STRIPE_SUCCESS_URL or STRIPE_CANCEL_URL (or APP_URL)');
            }

            if (!filter_var($successUrl, FILTER_VALIDATE_URL) || !filter_var($cancelUrl, FILTER_VALIDATE_URL)) {
                throw new \RuntimeException('Stripe success/cancel URLs must be absolute (include http/https and host)');
            }

            $user = User::findById($userId);
            if (!$user) {
                Response::json(['message' => 'Unauthorized'], 401);
                return;
            }

            $customerId = $user['stripe_customer_id'] ?? null;
            $stripe = $this->stripe();

            $trialUsed = isset($user['trial_used']) ? (int) $user['trial_used'] : 0;
            $hasSubscriptionHistory = !empty($user['stripe_subscription_id']) || !empty($user['plan_status']);
            $trialEligible = $trialUsed === 0 && !$hasSubscriptionHistory;

            if (!$customerId) {
                $customer = $stripe->customers->create([
                    'email' => $user['email'] ?? null,
                    'name' => $user['name'] ?? null,
                    'metadata' => ['user_id' => $userId],
                ]);
                $customerId = $customer->id;
                (new User())->updateById($userId, ['stripe_customer_id' => $customerId]);
            }

            $subscriptionData = [
                'metadata' => ['user_id' => $userId, 'plan' => $plan],
            ];
            if ($trialEligible) {
                $subscriptionData['trial_period_days'] = self::TRIAL_DAYS;
            }

            $session = $stripe->checkout->sessions->create([
                'mode' => 'subscription',
                'customer' => $customerId,
                'line_items' => [
                    ['price' => $priceId, 'quantity' => 1],
                ],
                'metadata' => [
                    'user_id' => $userId,
                    'plan' => $plan,
                ],
                'client_reference_id' => (string) $userId,
                'subscription_data' => $subscriptionData,
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'allow_promotion_codes' => true,
            ]);

            $checkoutUrl = $session->url ?? null;
            if (!$checkoutUrl) {
                throw new \RuntimeException('Stripe did not return a checkout URL');
            }

            Response::json(['url' => $checkoutUrl]);
        } catch (\Throwable $e) {
            Response::json(['message' => 'Unable to start checkout', 'error' => $e->getMessage()], 500);
        }
    }

    public function portal(Request $request): void
    {
        $userId = (new AuthService())->currentUserId();
        if (!$userId) {
            Response::json(['message' => 'Unauthorized'], 401);
            return;
        }

        try {
            $baseReturn = Config::get('STRIPE_PORTAL_RETURN_URL') ?: Config::get('APP_URL');
            if (!$baseReturn) {
                throw new \RuntimeException('Missing STRIPE_PORTAL_RETURN_URL or APP_URL');
            }
            $returnUrl = rtrim($baseReturn, '/') . '/account';

            $user = User::findById($userId);
            if (!$user) {
                Response::json(['message' => 'Unauthorized'], 401);
                return;
            }

            $stripe = $this->stripe();
            $customerId = $user['stripe_customer_id'] ?? null;

            if (!$customerId) {
                $customer = $stripe->customers->create([
                    'email' => $user['email'] ?? null,
                    'name' => $user['name'] ?? null,
                    'metadata' => ['user_id' => $userId],
                ]);
                $customerId = $customer->id;
                (new User())->updateById($userId, ['stripe_customer_id' => $customerId]);
            }

            $portal = $stripe->billingPortal->sessions->create([
                'customer' => $customerId,
                'return_url' => $returnUrl,
            ]);

            Response::json(['url' => $portal->url]);
        } catch (\Throwable $e) {
            Response::json(['message' => 'Unable to open billing portal', 'error' => $e->getMessage()], 500);
        }
    }

    public function webhook(Request $request): void
    {
        try {
            $secret = Config::get('STRIPE_WEBHOOK_SECRET');
            if (!$secret) {
                Response::json(['message' => 'Webhook not configured'], 500);
                return;
            }

            $payload = $request->rawBody();
            $signature = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

            $event = Webhook::constructEvent($payload, $signature, $secret);
            $stripe = $this->stripe();

            switch ($event->type) {
                case 'checkout.session.completed':
                    $session = $event->data->object;
                    $subscriptionId = $session->subscription ?? null;
                    $customerId = $session->customer ?? null;
                    $userId = isset($session->metadata['user_id']) ? (int) $session->metadata['user_id'] : null;
                    $plan = $session->metadata['plan'] ?? null;

                    if ($subscriptionId && $customerId) {
                        $subscription = $stripe->subscriptions->retrieve($subscriptionId);
                        $this->syncSubscription($customerId, $subscription, $userId, $plan);
                    }
                    break;

                case 'customer.subscription.created':
                case 'customer.subscription.updated':
                case 'customer.subscription.deleted':
                    $subscription = $event->data->object;
                    $customerId = $subscription->customer ?? null;
                    $userId = isset($subscription->metadata['user_id']) ? (int) $subscription->metadata['user_id'] : null;
                    $plan = $subscription->metadata['plan'] ?? null;
                    if ($customerId) {
                        $this->syncSubscription($customerId, $subscription, $userId, $plan);
                    }
                    break;

                default:
                    // ignore other events
                    break;
            }

            Response::json(['received' => true]);
        } catch (SignatureVerificationException $e) {
            Response::json(['message' => 'Invalid signature'], 400);
        } catch (\UnexpectedValueException $e) {
            Response::json(['message' => 'Invalid payload'], 400);
        } catch (\Throwable $e) {
            Response::json(['message' => 'Webhook handling failed', 'error' => $e->getMessage()], 500);
        }
    }

    /**
    * Sync subscription status to user record and role.
    */
    protected function syncSubscription(string $customerId, $subscription, ?int $userId = null, ?string $plan = null): void
    {
        $userModel = new User();
        $user = $userId ? $userModel->findBy('id', $userId) : null;

        if (!$user) {
            $user = $userModel->findBy('stripe_customer_id', $customerId);
        }

        if (!$user) {
            return;
        }

        $status = (string) ($subscription->status ?? '');
        $priceId = $subscription->items->data[0]->price->id ?? $plan ?? null;
        $trialEnd = $subscription->trial_end ?? null;
        $periodEnd = $subscription->current_period_end ?? $trialEnd;
        $currentRole = isset($user['role']) ? (int) $user['role'] : 0;

        $update = [
            'stripe_customer_id' => $customerId,
            'stripe_subscription_id' => $subscription->id ?? null,
            'plan' => $priceId,
            'plan_status' => $status,
            'plan_ends_at' => $periodEnd ? date('Y-m-d H:i:s', (int) $periodEnd) : null,
        ];

        $activeStatuses = ['active', 'trialing', 'past_due'];
        if (in_array($status, $activeStatuses, true)) {
            if ($currentRole < self::ADMIN_ROLE) {
                $update['role'] = self::PRO_ROLE;
            } elseif ($currentRole === self::ADMIN_ROLE) {
                $update['role'] = self::ADMIN_ROLE;
            } elseif ($currentRole < self::PRO_ROLE) {
                $update['role'] = self::PRO_ROLE;
            }
            $update['trial_used'] = 1;
        } else {
            // cancellation/downgrade
            if ($currentRole === self::PRO_ROLE) {
                $update['role'] = 0;
            }
        }

        $userModel->updateById((int) $user['id'], $update);
    }

}
