<?php
// AddToCart

use Statamic\Contracts\Entries\QueryBuilder;
use State\Gated\Cart;
use State\Gated\Gate;
use State\Gated\PaymentGate;

it('adds to cart', function () {

    // Fake the QueryBuilder and return a stub gate config.
    app()->bind(QueryBuilder::class, fn() => new class {
        public function where()
        {
            return $this;
        }

        public function first()
        {
            return collect(gateConfig());
        }
    });

    $this->postJson('gated/cart/add', [
        'gate' => 'my_gate',
    ])->assertStatus(201);

    expect(Cart::get()[0])->toBeInstanceOf(PaymentGate::class);
});

it('404s', function () {
    $this->postJson('gated/cart/add', [
        'gate' => 'foo',
    ])->assertStatus(404);
});