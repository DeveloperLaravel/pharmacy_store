<?php

namespace App\Policies;

use App\Models\Medicine;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicinePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view medicines');
    }

    public function view(User $user, Medicine $medicine): bool
    {
        return $user->can('view medicines');
    }

    public function create(User $user): bool
    {
        return $user->can('create medicines');
    }

    public function update(User $user, Medicine $medicine): bool
    {
        return $user->can('edit medicines');
    }

    public function delete(User $user, Medicine $medicine): bool
    {
        return $user->can('delete medicines');
    }

    public function restore(User $user, Medicine $medicine): bool
    {
        return false;
    }

    public function forceDelete(User $user, Medicine $medicine): bool
    {
        return false;
    }
}
