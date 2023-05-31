<?php
// PaymentGateTest

use Statamic\Facades\User;
use State\Walls\PaymentWall;

beforeEach(function () {
    $this->wall = (new PaymentWall)->setHandle('ecourse');
});

it('fails when user does not have wall', function () {
    $user = User::make();

    expect($this->wall->userCanPass($user))->toBeFalse();
});

it('fails when wall has expired', function () {
    $user = tap(User::make())->set('walls', [
        [
            'handle' => 'ecourse',
            'expires_at' => now()->subDay()->toIso8601String(),
        ],
    ]);

    expect($this->wall->userCanPass($user))->toBeFalse();
});

it('succeeds when user has wall', function() {
    $user = tap(User::make())->set('walls', [
        ['handle' => 'ecourse'],
    ]);

    expect($this->wall->userCanPass($user))->toBeTrue();
});


it('succeeds when wall is not expired', function () {
    $user = tap(User::make())->set('walls', [
        [
            'handle' => 'ecourse',
            'expires_at' => now()->addDay()->toIso8601String(),
        ],
    ]);

    expect($this->wall->userCanPass($user))->toBeTrue();
});
