<?php


namespace State\Walls\Http\Controllers\Payment;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Statamic\Contracts\Auth\User;
use Statamic\Facades\User as UserFacade;
use Statamic\Facades\Entry;
use State\Walls\Cart;
use State\Walls\Events\PaymentAcceptedEvent;
use State\Walls\Payment\CheckPayment;
use State\Walls\PaymentWall;

class SuccessfulPayment
{
    public function __invoke(Request $request)
    {
        $user = UserFacade::current();
        $paymentIntent = $request->input('payment_intent');

        if ($this->paymentNotOkay($paymentIntent)) {
            return redirect(config('walls.checkout.url', '/checkout'));
        }

        $walls = Cart::get();
        $walls->each(function (PaymentWall $gate) use ($user) {
            $gate->addToUser($user);
        });

        $this->createEntry($paymentIntent, $walls, $user);

        Cart::clear();

        PaymentAcceptedEvent::dispatch($user, $paymentIntent, $walls);

        return redirect(config('walls.checkout.redirect_url', '/'));
    }

    protected function createEntry(mixed $paymentIntent, Collection $walls, User $user): void
    {
        Entry::make()
            ->collection(config('walls.orders.collection', 'orders'))
            ->set('title', $paymentIntent)
            ->set('stripe_id', $paymentIntent)
            ->set('walls', $walls->map->getId()->toArray())
            ->set('user', $user->id())
            ->set('purchase_date', now())
            ->save();
    }

    protected function paymentNotOkay(mixed $paymentIntent): bool
    {
        return !(new CheckPayment)->checkPayment($paymentIntent);
    }
}
