<?php

namespace State\Walls\Http\Controllers\Cart;

use Illuminate\Http\Request;
use State\Walls\Cart;

class RemoveFromCart
{
    public function __invoke(Request $request, CartResponder $responder)
    {
        $request->validate(['wall' => 'required']);

        Cart::remove($request->input('wall'));

        return $responder->respond();
    }
}