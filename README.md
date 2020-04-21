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


