<?php

namespace State\Gated\Http\Controllers\Cart;

use Illuminate\Http\Request;
use State\Gated\Cart;

class RemoveFromCart
{
    public function __invoke(Request $request)
    {
        $request->validate(['gate' => 'required']);

        Cart::remove($request->input('gate'));

        if($request->expectsJson()) {
            return response([], 201);
        }

        if($request->has('redirect')) {
            return redirect($request->input('redirect'));
        }

        return back();
    }
}