<?php

namespace App\Services;

/**
* Maps enterprise roles to their custom allowances (themes, etc.).
*
* Each enterprise customer increments the role number.
* Example: role 3 → Mr Appliance.
*
* @package App\Services
*
* @since 0.0.7
*/
class EnterpriseService
{
    /**
    * Base free themes available to everyone.
    *
    * @var array<string>
    */
    public const BASE_FREE_THEMES = ['minimal', 'dark_glass', 'dark'];

    /**
    * Enterprise plan registry keyed by role.
    *
    * @var array<int,array{themes:array<int,string>,name?:string}>
    */
    protected array $plans = [
        3 => [
            'name' => 'Mr Appliance',
            'themes' => ['mr_appliance'],
        ],
    ];

    /**
    * Get allowed theme slugs for a given role, intersected with available themes.
    *
    * @param int $role
    * @param array<string> $available
    *
    * @return array<string>
    */
    public function themesForRole(int $role, array $available): array
    {
        $available = array_map('strtolower', $available);
        $base = self::BASE_FREE_THEMES;

        if ($role >= 3 && isset($this->plans[$role])) {
            $base = array_merge($base, $this->plans[$role]['themes'] ?? []);
        }

        return array_values(array_intersect($available, array_unique($base)));
    }

    /**
    * Resolve allowed themes for a role, respecting admin access and enterprise plans.
    *
    * @param int $role
    * @param array<string> $available
    *
    * @return array<string>
    */
    public function allowedThemesForRole(int $role, array $available): array
    {
        $available = array_map('strtolower', $available);

        if ($role === 2) {
            return $available; // admin: all
        }

        if ($role >= 3) {
            $themes = $this->themesForRole($role, $available);
            if (!empty($themes)) {
                return $themes;
            }
        }

        return array_values(array_intersect($available, self::BASE_FREE_THEMES));
    }

    /**
    * Determine if a role is enterprise.
    *
    * @param int $role
    *
    * @return bool
    */
    public function isEnterpriseRole(int $role): bool
    {
        return $role >= 3 && isset($this->plans[$role]);
    }
}
