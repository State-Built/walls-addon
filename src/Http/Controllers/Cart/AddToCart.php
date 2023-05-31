<?php

namespace State\Walls\Http\Controllers\Cart;

use Illuminate\Http\Request;
use State\Walls\Cart;
use State\Walls\Wall;

class AddToCart
{

    public function __invoke(Request $request, CartResponder $responder)
    {
         $request->validate([
            'wall' => ['required']
        ]);

        $handle = $request->input('wall');

        $gate = Wall::findBySlug($handle);

        Cart::add($gate);

        return $responder->respond();
    }

}