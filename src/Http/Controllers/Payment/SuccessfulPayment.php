<?php


namespace State\Walls\Http\Controllers\Payment;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Statamic\Auth\User;
use Statamic\Facades\Entry;
use State\Walls\Cart;
use State\Walls\Payment\CheckPayment;
use State\Walls\PaymentWall;
use Stripe\PaymentIntent;

class SuccessfulPayment
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $paymentIntent = $request->input('payment_intent');

        if ($this->paymentNotOkay($paymentIntent)) {
            return redirect(config('walls.checkout.url', '/checkout'));
        }

        $gates = Cart::get();
        $gates->each(function (PaymentWall $gate) use ($user) {
            $gate->addToUser($user);
        });

        $this->createEntry($paymentIntent, $gates, $user);

        Cart::clear();

        return redirect(config('walls.checkout.redirect_url', '/'));
    }

    protected function createEntry(mixed $paymentIntent, Collection $gates, User $user): void
    {
        Entry::make()
            ->collection(config('walls.orders.collection', 'orders'))
            ->set('title', $paymentIntent)
            ->set('stripe_id', $paymentIntent)
            ->set('walls', $gates->map->getId()->toArray())
            ->set('user', $user->id())
            ->set('purchase_date', now())
            ->save();
    }

    protected function paymentNotOkay(mixed $paymentIntent): bool
    {
        return !(new CheckPayment)->checkPayment($paymentIntent);
    }
}