<?php

namespace State\Walls;

use Statamic\Contracts\Auth\User;

class NullWall extends Wall
{

    public function userCanPass(User $user): bool
    {
        return $this->userHasGate($user);
    }
}