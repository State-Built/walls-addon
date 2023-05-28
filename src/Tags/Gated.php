<?php


namespace State\Gated\Tags;

use Illuminate\Support\Facades\Auth;
use Statamic\Tags\Tags;
use State\Gated\Cart;
use State\Gated\Gate;
use State\Gated\Http\Controllers\Payment\SuccessfulPayment;

class Gated extends Tags
{

    public function head() : string
    {
        $csrfToken      = csrf_token();
        $publishableKey = config('gated.payments.stripe.publishable_key');

        return <<<HEAD
            <meta name="csrf_token" content="{$csrfToken}"> 
            <meta name="stripe_publishable_key" content="{$publishableKey}">
            <script src="https://js.stripe.com/v3/"></script>
        HEAD;
    }

    public function styles() : string
    {
        return '<link rel="stylesheet" href="/vendor/state/gated/css/checkout.css">';
    }

    public function scripts() : string
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

    public function addToCart() : string
    {
        return <<<ADD_TO_CART
            <button data-gated 
                    data-gate="{$this->params->get('gate')}"
                    class="{$this->params->get('class')}">
                {$this->params->get('text', 'Add to cart')}
            </button>
        ADD_TO_CART;
    }

    public function removeFromCart() : string
    {
        return <<<ADD_TO_CART
            <button data-gated-remove 
                    data-gate="{$this->params->get('gate')}"
                    class="{$this->params->get('class')}">
                {$this->params->get('text', 'Remove from cart')}
            </button>
        ADD_TO_CART;
    }


    public function ownedGates()
    {
        if(!Auth::check()) {
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