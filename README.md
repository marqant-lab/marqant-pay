# Marquant Pay

This project aims to make it as easy as possible to implement a payment providers into large projects. Inspired by the
 `laravel/cashier` package, which is limited to stripe at the moment, we try to bring in more flexibility into the
  topic by allowing multiple payment providers to coexist.
  
## Installation

Require the package through composer.

```shell script
compsoer require marqant/marqant-pay
```

Create the migrations for at least one billable model.

```shell script
php artisan marqant-pay:migrations App\\User
# or
php artisan marqant-pay:migrations "App\User"
```

## Providers

For each payment provider that you want to connect to, you will first have to pull in a payment gateway through
 composer. For example here we pull in the stripe gateway.

```shell script
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

A billable in the context of this package is every model that is prepared to be used as customer - because customers
 are billable 😉

The setup consists of the database table columns needed to store provider related data and the usage of the `Billable
` trait on the billable model.

To create the needed migrations run the following command but replace the `User` model if you want another model to
 be used as customer, eg. a `Company` model.

```shell script
php artisan marqant-pay:migrations App\\User
# or
php artisan marqant-pay:migrations "App\User"
```

Then you can just run the migrations as usual to apply the migrations.

```shell script
php artisan migrate
```

**Note that you might do the same for the payment gateway package that you pull in, so make sure to checkout the
 documentation of it.**

## Plans & Subscriptions

If you want to charge your customers in intervals, then you need to setup a plan for that so a customer can issue a
 subscription. This package comes with a `Plan`, so you don't need to set it up yourself but you should know how they work.

Plans are a representation of a plan at your chosen payment provider. The `Plan` model is our representation of this
 object in our own database.

If a billable has a subscription, then this means it has a many to many relationsip with a plan, and is therefore
 subscribed to it.
 
Subscriptions are managed through the payment provider gateway that you pull in. 

## Tests

To run tests, you first need to set up a sqlite database that we use to get snapshots of the database state. Run the
 following command from within your project root to create the sqlite database.
 
```shell script
touch database/database.sqlite
```

TODO: Finish documentation of testing.

## Development

TODO: Explain setup of development environment.

TODO: Add section for creating new payment providers.

### Extending the MarqantPayService

To extend the `MarqantPayService` class, which is what you use behind the scenes when using the MarqantPay facade
, you can create a Macro and attach it in the service provider of your package though the `mixin` method provided by
 the Macroable trait.
 
* [See API Documentation](https://laravel.com/api/7.x/Illuminate/Support/Traits/Macroable.html)

**Note** that you have to take care of your extensions, so only one macro of a kind is required. For example if two
 extensions would provide the macro for `MarqantPayService::subscribe` method, only one of those would be registered.