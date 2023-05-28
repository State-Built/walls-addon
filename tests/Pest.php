<?php

use Illuminate\Support\Facades\Auth;
use Statamic\Facades\User;

uses(GatedTests\TestCase::class)->in('Unit', 'Feature');

uses()->beforeEach(function () {
    config()->set('gated.gates.test', [
        'driver' => 'payment',
        'price' => 1000
    ]);
})->in('Feature');


function gateConfig()
{
    return [
        "expires_after" => 60,
        "id" => "cb9d05a6-3364-4895-87a8-08de5c165f72",
        "price" => 59.99,
        "title" => "Ecourse",
        "type" => [
            "value" => "payment",
            "label" => "Payment",
            "key" => "payment",
        ],
        "handle" => "ecourse",
    ];
}