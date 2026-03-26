<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockMovementPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view stock-movements');
    }

    public function view(User $user, StockMovement $stockMovement): bool
    {
        return $user->can('view stock-movements');
    }

    public function create(User $user): bool
    {
        return $user->can('create stock-movements');
    }

    public function update(User $user, StockMovement $stockMovement): bool
    {
        return $user->can('edit stock-movements');
    }

    public function delete(User $user, StockMovement $stockMovement): bool
    {
        return $user->can('delete stock-movements');
    }

    public function restore(User $user, StockMovement $stockMovement): bool
    {
        return false;
    }

    public function forceDelete(User $user, StockMovement $stockMovement): bool
    {
        return false;
    }
}
