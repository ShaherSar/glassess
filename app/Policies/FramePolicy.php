<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\ModelPolicyAbstract;

class FramePolicy extends ModelPolicyAbstract
{
    public function viewAnyUser(User $user){
        return true;
    }
}
