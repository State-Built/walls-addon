<?php


namespace State\Walls\Http\Controllers\Payment;


use Illuminate\Http\Request;
use State\Walls\Payment\PaymentIntentFactory;
use State\Walls\PaymentWall;

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