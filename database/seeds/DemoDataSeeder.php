<?php

namespace Marqant\MarqantPay\Seeds;

use Illuminate\Database\Seeder;

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
        $this->call(PlansTableSeeder::class);
    }
}