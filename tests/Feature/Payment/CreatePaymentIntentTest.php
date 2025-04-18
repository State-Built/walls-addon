<?php


namespace WallsTests\Payment;


use Statamic\Facades\User;
use State\Walls\Cart;
use State\Walls\Events\PaymentAcceptedEvent;
use State\Walls\Wall;
use State\Walls\Payment\PaymentIntentFactory;
use Stripe\PaymentIntent;

it('creates a payment intent', function () {
    Cart::add(Wall::create('test', gateConfig()));

    $intentFactory = $this->mock(PaymentIntentFactory::class);

    // fluent calls to set class properties
    $intentFactory->shouldReceive('user')->once()->andReturnSelf();

    $paymentIntent = new PaymentIntent('pi_123');

    $paymentIntent->client_secret = 'abc123';

    $intentFactory->shouldReceive('build')
                  ->once()
                  ->andReturn($paymentIntent);

    $this->actingAs(User::make())
         ->postJson('walls/payment-intent', ['wall' => 'test'])
         ->assertOk()
         ->assertJson(['clientSecret' => 'abc123']);
});

