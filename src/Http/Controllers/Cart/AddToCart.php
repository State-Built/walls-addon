<?php

namespace State\Gated\Http\Controllers\Cart;

use Illuminate\Http\Request;
use State\Gated\Cart;
use State\Gated\Gate;

class AddToCart
{

    public function __invoke(Request $request)
    {
         $request->validate([
            'gate' => ['required']
        ]);

        $handle = $request->input('gate');

        $gate = Gate::findBySlug($handle);

        Cart::add($gate);

        if($request->expectsJson()) {
            return response([], 201);
        }

        return back();
    }

}