<?php

use Illuminate\Support\Facades\Route;
use State\Walls\Http\Controllers\Cart\AddToCart;
use State\Walls\Http\Controllers\Cart\RemoveFromCart;
use State\Walls\Http\Controllers\Payment\CreatePaymentIntent;
use State\Walls\Http\Controllers\Payment\SuccessfulPayment;

Route::prefix('walls')
    ->name('walls.')
    ->group(function () {
        Route::post('payment-intent', CreatePaymentIntent::class)
            ->name('payment.create-intent')
            ->middleware('auth');

        Route::get('success', SuccessfulPayment::class)
            ->name('payment.success')
            ->middleware('auth');

        Route::post('cart/add', AddToCart::class)->name('cart.add');
        Route::post('cart/remove', RemoveFromCart::class)->name('cart.remove');
    });
