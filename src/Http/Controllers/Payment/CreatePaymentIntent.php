<?php


namespace State\Walls\Http\Controllers\Payment;


use State\Walls\Payment\PaymentIntentFactory;
use Statamic\Facades\User;

class CreatePaymentIntent
{
    public function __invoke(PaymentIntentFactory $intentFactory)
    {
        $intent = $intentFactory->user(User::current())->build();

        return [
            'clientSecret' => $intent->client_secret,
        ];
    }
}
