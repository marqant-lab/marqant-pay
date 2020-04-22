<?php

namespace Marqant\MarqantPay\Seeds;

use Illuminate\Database\Seeder;
use Marqant\MarqantPaySubscriptions\Seeds\PlansTableSeeder;
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
        $this->call(ProvidersTableSeeder::class);

        if (class_exists(PlansTableSeeder::class) && class_exists(PlanProviderTableSeeder::class)) {
            $this->call(PlansTableSeeder::class);
            $this->call(PlanProviderTableSeeder::class);
        }
    }
}