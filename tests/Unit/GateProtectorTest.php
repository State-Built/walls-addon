<?php
// GateProtectorTest

use Illuminate\Http\Exceptions\HttpResponseException;
use Statamic\Contracts\Entries\QueryBuilder;
use Statamic\Facades\Entry;
use Statamic\Facades\User;
use State\Gated\GateProtector;

beforeEach(function () {
    $this->protector = tap(new GateProtector())->setConfig([
        'driver' => 'gated',
        'allowed' => ['ecourse'],
        'redirect_url' => '/ecourse',
    ]);

    // Fake the QueryBuilder for gate entries.
    app()->bind(QueryBuilder::class, fn() => new class {
        public function where()
        {
            return $this;
        }

        public function first()
        {
            return collect([
                'type' => ['value' => 'null'],
            ]);
        }
    });
});

it('fails when unauthenticated')
    ->protector->protect()
    ->throws(HttpResponseException::class);

it('fails if user does not have gate', function () {
    $user = User::make();

    Entry::make();

    $this->actingAs($user);
    $this->protector->protect();
})->throws(HttpResponseException::class);

it('passes when the user has the gate', function () {
    $user = User::make();

    $user->set('gates', [['handle' => 'ecourse']]);

    Entry::make();

    $this->actingAs($user);

    // should not throw any exceptions or call abort()
    expect($this->protector->protect())->toBeNull();
});