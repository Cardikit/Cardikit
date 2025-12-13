export interface User {
    id: number;
    name: string;
    email: string;
    role?: number;
    stripe_customer_id?: string | null;
    stripe_subscription_id?: string | null;
    plan?: string | null;
    plan_status?: string | null;
    plan_ends_at?: string | null;
    trial_used?: number | null;
    created_at: string;
    updated_at: string;
}
