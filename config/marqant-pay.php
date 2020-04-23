<?php

/*
 |--------------------------------------------------------------------------
 | Configuration of Marqant Pay
 |--------------------------------------------------------------------------
 |
 | In this configuration file you can setup your payment provider and
 | configurations for it.
 |
 */

return [

    /*
     |--------------------------------------------------------------------------
     | Gateways
     |--------------------------------------------------------------------------
     |
     | In this section you can define all payment gateways that you need for
     | your project.
     |
     */

    'gateways' => [
        'stripe' => \Marqant\MarqantPayStripe\StripePaymentGateway::class,
    ],

    /*
     |--------------------------------------------------------------------------
     | Payment Methods
     |--------------------------------------------------------------------------
     |
     | In this section you can define the payment methods available through the
     | providers you pulled in.
     |
     */

    'payment_methods' => [
        'card' => \Marqant\MarqantPayStripe\PaymentMethods\Card::class,
    ],

    /*
     |--------------------------------------------------------------------------
     | Provider Model
     |--------------------------------------------------------------------------
     |
     | This is the model used as representation of the providers we connect
     | with through the gateways.
     |
     */

    'provider_model' => \Marqant\MarqantPay\Models\Provider::class,

    /*
     |--------------------------------------------------------------------------
     | Payment Model
     |--------------------------------------------------------------------------
     |
     | This is the model used as representation of the payments in our database.
     |
     */

    'payment_model' => \Marqant\MarqantPay\Models\Payment::class,

];