# Marquant Pay

This project aims to make it as easy as possible to implement a payment providers into large projects. Inspired by the
 `laravel/cashier` package, which is limited to stripe at the moment, we try to bring in more flexibility into the
  topic by allowing multiple payment providers to coexist.
  
## Installation

Require the package through composer.

```bash
compsoer require marqant/marqant-pay
```

Create the migrations for at least one billable model.

```bash
php artisan marqant-pay:migrations App\\User
# or
php artisan marqant-pay:migrations "App\User"
```

## Providers

For each payment provider that you want to connect to, you will first have to pull in a payment gateway through
 composer. For example here we pull in the stripe gateway.

```bash
composer require marqant/marqant-pay-stripe
```

Then you will have to add it to the `marqant-pay.php` config file. Create it if it doesn't exist yet.

```php

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
];
```

Next you will have to create an entry for it in the database.

## Billables

TODO!

## Plans & Subscriptions

If you want to charge your customers in intervals, then you need to setup a plan for that so a customer can issue a
 subscription. 

This package comes with a `Plan`, so you don't need to set it up yourself but you should know how they work.

Plans are a representation of a plan at your chosen payment provider. The `Plan` model is our representation of this
 object in our own database.

If a billable has a subscription, then this means it has a many to many relationsip with a plan, and is therefore
 subscribed to it.

## Tests

TODO!