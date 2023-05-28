<?php
// PaymentGateTest

use Statamic\Facades\User;
use State\Gated\PaymentGate;

beforeEach(function () {
    $this->gate = (new PaymentGate)->setHandle('ecourse');
});

it('fails when user does not have gate', function () {
    $user = User::make();

    expect($this->gate->userCanPass($user))->toBeFalse();
});

it('fails when gate has expired', function () {
    $user = tap(User::make())->set('gates', [
        [
            'handle' => 'ecourse',
            'expires_at' => now()->subDay()->toIso8601String(),
        ],
    ]);

    expect($this->gate->userCanPass($user))->toBeFalse();
});

it('succeeds when user has gate', function() {
    $user = tap(User::make())->set('gates', [
        ['handle' => 'ecourse'],
    ]);

    expect($this->gate->userCanPass($user))->toBeTrue();
});


it('succeeds when gate is not expired', function () {
    $user = tap(User::make())->set('gates', [
        [
            'handle' => 'ecourse',
            'expires_at' => now()->addDay()->toIso8601String(),
        ],
    ]);

    expect($this->gate->userCanPass($user))->toBeTrue();
});
