<?php
// GateProtectorTest

use Illuminate\Http\Exceptions\HttpResponseException;
use Statamic\Contracts\Entries\QueryBuilder;
use Statamic\Facades\Entry;
use Statamic\Facades\User;
use State\Walls\WallsProtector;

beforeEach(function () {
    $this->protector = tap(new WallsProtector())->setConfig([
        'driver' => 'walls',
        'allowed' => ['ecourse'],
        'redirect_url' => '/ecourse',
    ]);

    // Fake the QueryBuilder for wall entries.
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

it('fails if user does not have wall', function () {
    $user = User::make();

    Entry::make();

    $this->actingAs($user);
    $this->protector->protect();
})->throws(HttpResponseException::class);

it('passes when the user has the wall', function () {
    $user = User::make();

    $user->set('walls', [['handle' => 'ecourse']]);

    Entry::make();

    $this->actingAs($user);

    // should not throw any exceptions or call abort()
    expect($this->protector->protect())->toBeNull();
});
