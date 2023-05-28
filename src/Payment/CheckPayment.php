<?php

namespace State\Gated\Payment;

use Stripe\PaymentIntent;

class CheckPayment
{

    public function checkPayment(string $paymentIntent)
    {
        $intent = PaymentIntent::retrieve($paymentIntent);

        return $intent->status == 'succeeded';
    }
    
}