<?php


namespace State\Walls\Tags;

use Illuminate\Support\Facades\Auth;
use Statamic\Tags\Tags;
use State\Walls\Cart;
use State\Walls\Wall;
use State\Walls\Http\Controllers\Payment\SuccessfulPayment;

class Walls extends Tags
{

    public function head(): string
    {
        $csrfToken = csrf_token();
        $publishableKey = config('walls.payments.stripe.publishable_key');

        return <<<HEAD
            <meta name="csrf_token" content="{$csrfToken}">
            <meta name="stripe_publishable_key" content="{$publishableKey}">
            <script src="https://js.stripe.com/v3/"></script>
        HEAD;
    }

    public function styles(): string
    {
        return '<link rel="stylesheet" href="/vendor/state/walls/css/checkout.css">';
    }

    public function scripts(): string
    {
        return '<script src="/vendor/state/walls/js/checkout.js"></script>';
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
        return $this->renderButton(route('walls.cart.add'), 'Add to cart');
    }

    public function removeFromCart(): string
    {
        return $this->renderButton(route('walls.cart.remove'), 'Remove from cart');
    }

    private function renderButton(string $route, string $defaultText)
    {
        $csrfField = csrf_field();
        $gate = $this->params->get('wall');
        $buttonText = $this->params->get('text', $defaultText);
        $class = $this->params->get('class');
        $redirect = $this->params->get('redirect');

        if (blank($gate)) {
            throw new \Exception("Add to cart: wall param is required");
        }

        return <<<ADD_TO_CART
            <form action="{$route}" method="post">
                {$csrfField}
                <input type="hidden" name="wall" value="{$gate}">
                <input type="hidden" name="redirect" value="{$redirect}">
                <button class="{$class}" type="submit">
                    {$buttonText}
                </button>
            </form>
        ADD_TO_CART;
    }

    public function owned()
    {
        if (!Auth::check()) {
            return [];
        }

        $userWalls = Auth::user()->get('walls', []);

        return array_map(function ($userWall) {
            $userWall['entry'] = Wall::findBySlug($userWall['handle'])->toArray();
            // todo include expired bool if it's a payment type.

            return $userWall;
        }, $userWalls);
    }

    public function userOwns() : bool
    {
        $user = Auth::user();

        if ($user) {
            $owned = collect($user->get('walls', []))->map->handle;

            return $owned->contains($this->params->get('wall'));
        }

        return false;
    }

}