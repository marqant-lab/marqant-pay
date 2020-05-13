<?php

namespace Marqant\MarqantPay\Tests\Traits;

use Illuminate\Support\Facades\Artisan;

trait MigrateFreshSeed
{
    /**
     * If true, setup has run at least once.
     *
     * @var boolean
     */
    protected static $setUpHasRunOnce = false;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!static::$setUpHasRunOnce) {
            Artisan::call('migrate:fresh');
            Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);

            static::$setUpHasRunOnce = true;
        }
    }
}