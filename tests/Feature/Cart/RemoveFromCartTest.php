<?php
// RemoveFromCart

use State\Walls\Cart;
use State\Walls\Wall;

it('removes items from the cart', function () {
    Cart::add(Wall::create('test', gateConfig()));

    $this->postJson('/walls/cart/remove', ['wall' => 'test'])
         ->assertStatus(201);

    expect(Cart::get())->toBeEmpty();
});
