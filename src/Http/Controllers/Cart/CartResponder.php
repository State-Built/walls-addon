<?php

namespace State\Walls\Http\Controllers\Cart;

class CartResponder
{
    public function respond()
    {
        if (request()->expectsJson()) {
            return response([], 201);
        }

        if (request()->filled('redirect')) {
            return redirect(request()->input('redirect'));
        }

        return back();
    }
}