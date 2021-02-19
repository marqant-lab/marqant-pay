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
     | Billables
     |--------------------------------------------------------------------------
     |
     | The models considered as "Billables". All of them need to have the
     | Billable on them.
     |
    */

    'billables' => [
        App\Models\User::class,
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

    // 'invoice_service' => \Marqant\MarqantPay\Services\ProviderInvoice::class,
    'invoice_service' => \Marqant\MarqantPayInvoices\Services\PdfInvoice::class,

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

    'payment_failed_email_view' => 'marqant-pay::email.payment_failed',

    'payment_failed_support_email_view' => 'marqant-pay::email.payment_failed_support',

    /*
     |--------------------------------------------------------------------------
     | Support Emails
     |--------------------------------------------------------------------------
     |
     | Marqant Pay send technical emails.
     | It is your project support emails
     | For example: webhook 'payment_intent.payment_failed' event.
     | In this section you can define the email addresses to be used.
     |
     */

    'support_emails' => [],

    /*
     |--------------------------------------------------------------------------
     | Payment URL(s)
     |--------------------------------------------------------------------------
     |
     | Some technical urls(s) used for emails etc.
     |
     */

    'payment_urls' => [
        // base url to the site with customers accounts
        'base_url' => '',
        // url to update customer payment method
        'payment_sub_url' => '',
    ]
];
