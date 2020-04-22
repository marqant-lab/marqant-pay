<?php

namespace Marqant\MarqantPay\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class BillableTest
 *
 * Runs tests on the User model, assuming it is a Billable.
 *
 * @package Tests\Feature
 */
class BillableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_if_we_can_charge_a_billable()
    {
        $user = factory(\App\User::class)->create([
            'provider' => 'stripe',
        ]);

        ddi($user);
    }
}
