<?php


namespace State\Gated\Tags;

use Illuminate\Support\Facades\Auth;
use Statamic\Tags\Tags;
use State\Gated\Cart;
use State\Gated\Gate;
use State\Gated\Http\Controllers\Payment\SuccessfulPayment;

class Gated extends Tags
{

    public function head(): string
    {
        $publishableKey = config('gated.payments.stripe.publishable_key');

        return <<<HEAD
            <meta name="stripe_publishable_key" content="{$publishableKey}">
            <script src="https://js.stripe.com/v3/"></script>
        HEAD;
    }

    public function styles(): string
    {
        return '<link rel="stylesheet" href="/vendor/state/gated/css/checkout.css">';
    }

    public function scripts(): string
    {
        return '<script src="/vendor/state/gated/js/checkout.js"></script>';
    }

    public function cart()
    {
        return [
            'total' => Cart::total() / 100,
            'items' => Cart::get()->map->toArray(),
        ];
    }

    public function addToCart(): string
    {
        return $this->renderButton(route('gated.cart.add'), 'Add to cart');
    }

    public function removeFromCart(): string
    {
        return $this->renderButton(route('gated.cart.remove'), 'Remove from cart');
    }

    private function renderButton(string $route, string $defaultText)
    {
        $csrfField = csrf_field();
        $gate = $this->params->get('gate');
        $buttonText = $this->params->get('text', $defaultText);
        $class = $this->params->get('class');
        $redirect = $this->params->get('redirect');

        if (blank($gate)) {
            throw new \Exception("Add to cart: gate param is required");
        }

        return <<<ADD_TO_CART
            <form action="{$route}" method="post">
                {$csrfField}
                <input type="hidden" name="gate" value="{$gate}">
                <input type="hidden" name="redirect" value="{$redirect}">
                <button class="{$class}" type="submit">
                    {$buttonText}
                </button>
            </form>
        ADD_TO_CART;
    }

    public function ownedGates()
    {
        if (!Auth::check()) {
            return [];
        }

        $userGates = Auth::user()->get('gates', []);

        $userGates = array_map(function ($userGate) {
            $userGate['gate'] = Gate::findBySlug($userGate['handle'])->toArray();
            return $userGate;
        }, $userGates);

        return $userGates;
    }

}