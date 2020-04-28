# Marquant Pay

This project aims to make it as easy as possible to implement a payment providers into large projects. Inspired by the
 `laravel/cashier` package, which is limited to stripe at the moment, we try to bring in more flexibility into the
  topic by allowing multiple payment providers to coexist.
  
## Installation

Require the package through composer.

```shell script
compsoer require marqant-lab/marqant-pay
```

Create the migrations for at least one billable model.

```shell script
php artisan marqant-pay:migrations App\\User
# or
php artisan marqant-pay:migrations "App\User"
```

## Providers

For each payment provider that you want to connect to, you will first have to pull in a payment gateway through
 composer. For example here we pull in the stripe gateway. Make sure to checkout the documentation of the provider
 package you want to pull in.

```shell script
composer require marqant-lab/marqant-pay-stripe
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

**Note that you might need to do the same for the payment gateway package that you pull in, so make sure to checkout the
 documentation of it.**

## Plans & Subscriptions

Check out the [marqant-lab/marqant-pay-subscriptions](https://github.com/marqant-lab/marqant-pay-subscriptions
) package on how to pull in subscriptions functionality into your project.

## Tests

To run tests, you first need to set up a sqlite database that we use to get snapshots of the database state. Run the
 following command from within your project root to create the sqlite database.
 
```shell script
touch database/database.sqlite
```

Next you will have to add the tests of this package to the phpunit test suite in the `phpunit.xml` file in the root
 of your Laravel project.
 
```xml
    <testsuites>
        ...
        <testsuite name="MarqantPay">
            <directory suffix="Test.php">./vendor/marqant-lab/marqant-pay/tests</directory>
        </testsuite>
    </testsuites>
```

Then you should be able to just run your tests with the following commands.

```shell script
phpunit
# or
./vendor/bin/phpunit
```

If you want to just run a specific test or a method on a test, then you can filter them out like shown below.

```shell script
phpunit --filter=<class or method>
```

If you need to perserve a snapshot of what is going on in the test database, then you can comment out the following
 line in your `phpunit.xml` file.

```xml
        <!--<server name="DB_DATABASE" value=":memory:"/>-->
```

With this line commented out, Laravel will use the `database/database.sqlite` file to store values.

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