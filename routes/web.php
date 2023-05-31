<?php


Route::prefix('walls')
     ->name('walls.')
     ->namespace('\State\Walls\Http\Controllers')
     ->group(function () {
         Route::post('payment-intent', 'Payment\CreatePaymentIntent')
              ->name('payment.create-intent')
              ->middleware('auth');

         Route::get('success', 'Payment\SuccessfulPayment')
              ->name('payment.success')
              ->middleware('auth');

         Route::post('cart/add', 'Cart\AddToCart')->name('cart.add');
         Route::post('cart/remove', 'Cart\RemoveFromCart')->name('cart.remove');
     });