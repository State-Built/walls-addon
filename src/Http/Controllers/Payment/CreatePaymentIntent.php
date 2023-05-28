<?php


namespace State\Gated\Http\Controllers\Payment;


use Illuminate\Http\Request;
use State\Gated\Payment\PaymentIntentFactory;
use State\Gated\PaymentGate;

class CreatePaymentIntent
{
    public function __invoke(Request $request, PaymentIntentFactory $intentFactory)
    {
        $intent = $intentFactory->user($request->user())->build();

        return [
            'clientSecret' => $intent->client_secret,
        ];
    }
}