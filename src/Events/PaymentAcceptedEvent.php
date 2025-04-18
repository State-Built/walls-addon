<?php

namespace State\Walls\Events;

use Statamic\Contracts\Auth\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class PaymentAcceptedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public $paymentIntent,
        public Collection $walls
    ) {
    }
}

