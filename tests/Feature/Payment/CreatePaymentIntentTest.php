<?php


namespace GatedTests\Payment;


use Statamic\Facades\User;
use State\Gated\Cart;
use State\Gated\Gate;
use State\Gated\Payment\PaymentIntentFactory;
use Stripe\PaymentIntent;

it('creates a payment intent', function () {
    Cart::add(Gate::create('test', gateConfig()));

    $intentFactory = $this->mock(PaymentIntentFactory::class);

    // fluent calls to set class properties
    $intentFactory->shouldReceive('user')->once()->andReturnSelf();

    $paymentIntent = new PaymentIntent('pi_123');

    $paymentIntent->client_secret = 'abc123';

    $intentFactory->shouldReceive('build')
                  ->once()
                  ->andReturn($paymentIntent);

    $this->actingAs(User::make())
         ->postJson('gated/payment-intent', ['gate' => 'test'])
         ->assertOk()
         ->assertJson(['clientSecret' => 'abc123']);
});