<?php

namespace State\Walls;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class Cart
{

    public static function add(Wall $product)
    {
        $cart = Session::get('cart', []);

        Session::put('cart', array_merge($cart, [$product->getHandle() => $product->getConfig()]));
    }


    public static function get() : Collection
    {
        $cart  = Session::get('cart', []);
        $gates = [];

        foreach ($cart as $handle => $config) {
            $gates[] = Wall::create($handle, $config);
        }

        return collect($gates);
    }

    public static function total() : int
    {
        return self::get()->reduce(function (int $carry, PaymentWall $gate) {
            return $carry + $gate->getPrice();
        }, 0);
    }

    public static function remove(string $string)
    {
        $cart = Session::get('cart', []);

        unset($cart[$string]);

        Session::put('cart', $cart);
    }

    public static function clear()
    {
        Session::forget('cart');
    }

}