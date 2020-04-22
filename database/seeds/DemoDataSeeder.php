<?php

namespace Marqant\MarqantPay\Seeds;

use Illuminate\Database\Seeder;
use Marqant\MarqantPaySubscriptions\Seeds\PlansTableSeeder;
use Marqant\MarqantPayStripe\Seeds\StripeProvidersTableSeeder;
use Marqant\MarqantPaySubscriptions\Seeds\PlanProviderTableSeeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // run seeders for marqant/marqant-pay-stripe package if available
        if (class_exists(StripeProvidersTableSeeder::class)) {
            $this->call(StripeProvidersTableSeeder::class);
        }

        // run seeders for marqant/marqant-pay-subscriptions package if available
        if (class_exists(PlansTableSeeder::class) && class_exists(PlanProviderTableSeeder::class)) {
            $this->call(PlansTableSeeder::class);
            $this->call(PlanProviderTableSeeder::class);
        }
    }
}