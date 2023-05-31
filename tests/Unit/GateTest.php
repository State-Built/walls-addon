<?php
// GateTest


use Statamic\Auth\User;
use State\Walls\Wall;

it('can be created', function () {
    $gate = Wall::create('test', ['type' => ['value' => 'null']]);

    expect($gate)->not()->toBeNull();
});

it('can belong to a user', function () {
    $user = $this->mock(User::class);

    $user->shouldReceive('get')
         ->twice()
         ->withArgs(['walls', []])
         ->andReturn([['handle' => 'basic']]);

    $basicGate   = Wall::create('basic', ['type' => ['value' => 'null']]);
    $premiumGate = Wall::create('premium', ['type' => ['value' => 'null']]);

    expect($basicGate->userHasGate($user))->toBeTrue()
        ->and($premiumGate->userHasGate($user))->toBeFalse();
});
