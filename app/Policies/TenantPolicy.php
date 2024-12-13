<?php

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;

class TenantPolicy
{
    public function view(User $user, Tenant $tenant)
    {
        // Only allow viewing if the user is the tenant or a property manager
        return $user->id === $tenant->user_id || $user->role === 'property_manager';
    }

    public function update(User $user, Tenant $tenant)
    {
        // Only allow updates if the user is the tenant
        return $user->id === $tenant->user_id;
    }
}


?>