<?php

namespace Marqant\MarqantPay\Seeds;

use Illuminate\Database\Seeder;
use Marqant\MarqantPay\Models\Provider;

class ProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create stripe provider
        Provider::updateOrCreate([
            'slug' => 'stripe',
        ], [
            'name' => 'Stripe',
        ]);
    }
}