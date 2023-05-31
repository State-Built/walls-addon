<?php
// AddToCart

use Statamic\Contracts\Entries\QueryBuilder;
use State\Walls\Cart;
use State\Walls\Wall;
use State\Walls\PaymentWall;

it('adds to cart', function () {

    // Fake the QueryBuilder and return a stub wall config.
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

    $this->postJson('walls/cart/add', [
        'wall' => 'my_gate',
    ])->assertStatus(201);

    expect(Cart::get()[0])->toBeInstanceOf(PaymentWall::class);
});

it('404s', function () {
    $this->postJson('walls/cart/add', [
        'wall' => 'foo',
    ])->assertStatus(404);
});