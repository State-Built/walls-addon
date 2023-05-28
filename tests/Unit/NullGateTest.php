<?php
// NullGateTest

use Statamic\Facades\User;
use State\Gated\NullGate;

beforeEach(function() {
    $this->gate = (new NullGate)->setHandle('null');
});

it('userCanPass is false when user does not have gate', function () {
    $user = User::make();

    expect($this->gate->userCanPass($user))->toBeFalse();
});


it('userCanPass is true when user does have gate', function () {
    $user = User::make();
    $user->set('gates', [['handle' => 'null']]);

    expect($this->gate->userCanPass($user))->toBeTrue();
});