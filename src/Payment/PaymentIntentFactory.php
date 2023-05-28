<?php


namespace State\Gated\Payment;


use Statamic\Auth\User;
use State\Gated\Cart;
use State\Gated\PaymentGate;
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
            'currency' => config('gated.stripe.currency', 'usd'),
            'customer' => $customerId,
            'setup_future_usage' => 'off_session',
            'metadata' => [
                'items' => Cart::get()->map->getHandle(),
            ],
            'payment_method_types' => config('gated.stripe.payment_method_types', ['card']),
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