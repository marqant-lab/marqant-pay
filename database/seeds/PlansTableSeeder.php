<?php

namespace Marqant\MarqantPay\Seeds;

use Illuminate\Database\Seeder;
use Marqant\MarqantPay\Models\Plan;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create monthly plan
        Plan::updateOrCreate([
            'slug' => 'monthly',
        ], [
            'name'        => 'Monthly',
            'description' => 'Default monthly plan.',
            'type'        => 'monthly',
            'amount'      => 9.99,
            'active'      => true,
        ]);

        // create monthly plan
        Plan::updateOrCreate([
            'slug' => 'yearly',
        ], [
            'name'        => 'Yearly',
            'description' => 'Default yearly plan.',
            'type'        => 'yearly',
            'amount'      => 96,
            'active'      => true,
        ]);
    }
}