<?php

namespace App\Policies;

use App\Policies\ModelPolicyAbstract;
use App\Models\User;

class LensPolicy extends ModelPolicyAbstract
{
    public function viewAnyUser(User $user){
        return true;
    }
}
