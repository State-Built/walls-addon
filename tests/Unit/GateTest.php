<?php
// GateTest


use Statamic\Auth\User;
use State\Gated\Gate;

it('can be created', function () {
    $gate = Gate::create('test', ['type' => ['value' => 'null']]);

    expect($gate)->not()->toBeNull();
});

it('can belong to a user', function () {
    $user = $this->mock(User::class);

    $user->shouldReceive('get')
         ->twice()
         ->withArgs(['gates', []])
         ->andReturn([['handle' => 'basic']]);

    $basicGate   = Gate::create('basic', ['type' => ['value' => 'null']]);
    $premiumGate = Gate::create('premium', ['type' => ['value' => 'null']]);

    expect($basicGate->userHasGate($user))->toBeTrue()
        ->and($premiumGate->userHasGate($user))->toBeFalse();
});
