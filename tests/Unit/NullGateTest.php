<?php
// NullGateTest

use Statamic\Facades\User;
use State\Walls\NullWall;

beforeEach(function() {
    $this->wall = (new NullWall)->setHandle('null');
});

it('userCanPass is false when user does not have wall', function () {
    $user = User::make();

    expect($this->wall->userCanPass($user))->toBeFalse();
});


it('userCanPass is true when user does have wall', function () {
    $user = User::make();
    $user->set('walls', [['handle' => 'null']]);

    expect($this->wall->userCanPass($user))->toBeTrue();
});