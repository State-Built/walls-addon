<?php


namespace State\Gated\Http\Controllers\Payment;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Statamic\Auth\User;
use Statamic\Facades\Entry;
use State\Gated\Cart;
use State\Gated\Payment\CheckPayment;
use State\Gated\PaymentGate;
use Stripe\PaymentIntent;

class SuccessfulPayment
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $paymentIntent = $request->input('payment_intent');

        if ($this->paymentNotOkay($paymentIntent)) {
            return redirect(config('gated.checkout.url', '/checkout'));
        }

        $gates = Cart::get();
        $gates->each(function (PaymentGate $gate) use ($user) {
            $gate->addToUser($user);
        });

        $this->createEntry($paymentIntent, $gates, $user);

        Cart::clear();

        return redirect(config('gated.checkout.redirect_url', '/'));
    }

    protected function createEntry(mixed $paymentIntent, Collection $gates, User $user): void
    {
        Entry::make()
            ->collection(config('gated.orders.collection', 'orders'))
            ->set('title', $paymentIntent)
            ->set('stripe_id', $paymentIntent)
            ->set('gates', $gates->map->getId()->toArray())
            ->set('user', $user->id())
            ->set('purchase_date', now())
            ->save();
    }

    protected function paymentNotOkay(mixed $paymentIntent): bool
    {
        return !(new CheckPayment)->checkPayment($paymentIntent);
    }
}