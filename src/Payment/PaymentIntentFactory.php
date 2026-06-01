<?php


namespace State\Walls\Payment;


use Statamic\Contracts\Auth\User;
use State\Walls\Cart;
use Stripe\Customer;
use Stripe\PaymentIntent;

class PaymentIntentFactory
{
    protected $user;

    public function user(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function build(): PaymentIntent
    {
        $customerId = $this->getOrCreateStripeCustomer($this->user);

        return PaymentIntent::create([ // todo: support multi-currency.
            'amount' => Cart::total(),
            'currency' => config('walls.payments.stripe.currency', 'usd'),
            'customer' => $customerId,
            'metadata' => [
                'items' => Cart::get()->map->getHandle(),
            ],
            'payment_method_types' => config('walls.payments.stripe.payment_method_types', ['card']),
        ]);
    }

    private function getOrCreateStripeCustomer(User $user): string
    {
        $customerId = $user->get('stripe_customer_id', false);

        if (!$customerId) {
            $customerId = Customer::create(['email' => $user->email()])->id;
            $this->saveCustomerId($user, $customerId);
        }

        return $customerId;
    }

    private function saveCustomerId(User $user, string $customerId)
    {
        $user->set('stripe_customer_id', $customerId);
        $user->save();
    }
}

