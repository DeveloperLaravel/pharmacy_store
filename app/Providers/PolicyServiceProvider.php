<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Medicine;
use App\Models\StockMovement;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\MedicinePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\StockMovementPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PolicyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Medicine::class, MedicinePolicy::class);
        Gate::policy(StockMovement::class, StockMovementPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
    }
}
