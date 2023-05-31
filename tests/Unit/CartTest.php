<?php
// CartTest

use Spatie\SchemaOrg\Car;
use State\Walls\Cart;
use State\Walls\Wall;
use State\Walls\PaymentWall;

it('adds payment walls to cart', function () {
    Cart::add(Wall::create('my_gate', gateConfig()));

    expect(Cart::get()[0])->toBeInstanceOf(PaymentWall::class);
});

it('removes from cart', function() {
    Cart::add(Wall::create('my_gate', gateConfig()));

    expect(Cart::get())->toHaveCount(1);

    Cart::remove('my_gate');

    expect(Cart::get())->toHaveCount(0);
});

it('computes the total cost', function() {
    Cart::add(Wall::create('my_gate', gateconfig()));

    Cart::add(Wall::create('my_gate2', gateconfig()));

    expect(Cart::total())->toBe(11998);
});

it('clears the cart', function () {
    Cart::add(Wall::create('my_gate', gateConfig()));

    expect(Cart::get())->toHaveCount(1);

    Cart::clear();

    expect(Cart::get())->toHaveCount(0);
});