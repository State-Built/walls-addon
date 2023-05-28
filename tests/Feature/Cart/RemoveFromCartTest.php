<?php
// RemoveFromCart

use State\Gated\Cart;
use State\Gated\Gate;

it('removes items from the cart', function () {
    Cart::add(Gate::create('test', gateConfig()));

    $this->postJson('/gated/cart/remove', ['gate' => 'test'])
         ->assertStatus(201);

    expect(Cart::get())->toBeEmpty();
});
