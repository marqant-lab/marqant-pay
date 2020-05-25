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
     | Billable classes
     |--------------------------------------------------------------------------
     |
     | In this section you can define all billable classes for your project.
     |
     */

    'billables' => [
        'user' => \App\User::class,
    ],

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

    /*
     |--------------------------------------------------------------------------
     | Currencies
     |--------------------------------------------------------------------------
     |
     | In this section you can setup the currencies that you want to use.
     |
     */

    'default_currency' => env('MARQANT_PAY_CURRENCY', 'eur'),

    /*
     |--------------------------------------------------------------------------
     | Invoice Service
     |--------------------------------------------------------------------------
     |
     | Per default marqant-pay uses the invoices provided by the payment
     | provider if available. If you want to change this behaviour, you can
     | change the class provided in this configuration option.
     |
     | Note: If you want to make use of custom PDF invoices, check out the
     |       marqant-lab/marqant-pay-invoices package.
     |
     */

    'invoice_service' => \Marqant\MarqantPay\Services\ProviderInvoice::class,

    /*
     |--------------------------------------------------------------------------
     | Tax
     |--------------------------------------------------------------------------
     |
     | In this section you can setup the taxes that you want to use.
     |
     */

    'tax' => env('MARQANT_PAY_TAX', 0.2),

    /*
     |--------------------------------------------------------------------------
     | Email Templates
     |--------------------------------------------------------------------------
     |
     | Marqant Pay fires events that trigger the notification of users through
     | emails. In this section you can define the email templates to be used.
     |
     */

    'invoice_email_view' => 'marqant-pay::email.invoice',

];
