<?php
// CartTest

use Spatie\SchemaOrg\Car;
use State\Gated\Cart;
use State\Gated\Gate;
use State\Gated\PaymentGate;

it('adds payment gates to cart', function () {
    Cart::add(Gate::create('my_gate', gateConfig()));

    expect(Cart::get()[0])->toBeInstanceOf(PaymentGate::class);
});

it('removes from cart', function() {
    Cart::add(Gate::create('my_gate', gateConfig()));

    expect(Cart::get())->toHaveCount(1);

    Cart::remove('my_gate');

    expect(Cart::get())->toHaveCount(0);
});

it('computes the total cost', function() {
    Cart::add(Gate::create('my_gate', gateconfig()));

    Cart::add(Gate::create('my_gate2', gateconfig()));

    expect(Cart::total())->toBe(11998);
});

it('clears the cart', function () {
    Cart::add(Gate::create('my_gate', gateConfig()));

    expect(Cart::get())->toHaveCount(1);

    Cart::clear();

    expect(Cart::get())->toHaveCount(0);
});