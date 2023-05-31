<?php

namespace State\Walls\Http\Controllers\Cart;

use Illuminate\Http\Request;

class CartResponder
{
    public function respond()
    {
        if(request()->expectsJson()) {
            return response([], 201);
        }

        if($redirect = request()->string('redirect')) {
            return redirect($redirect);
        }

        return back();
    }
}