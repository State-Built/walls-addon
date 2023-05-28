<?php

namespace State\Gated;

use Statamic\Contracts\Auth\User;

class NullGate extends Gate
{

    public function userCanPass(User $user): bool
    {
        return $this->userHasGate($user);
    }
}