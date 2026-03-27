<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\User;

class DoctorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->can('doctors.view');
    }

    public function view(User $user, Doctor $doctor)
    {
        return $user->can('doctors.view');
    }

    public function create(User $user)
    {
        return $user->can('doctors.create');
    }

    public function update(User $user, Doctor $doctor)
    {
        return $user->can('doctors.update');
    }

    public function delete(User $user, Doctor $doctor)
    {
        return $user->can('doctors.delete');
    }
}
