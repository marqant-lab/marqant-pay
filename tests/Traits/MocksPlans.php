<?php

namespace Marqant\MarqantPay\Tests\Traits;

use Illuminate\Database\Eloquent\Model;

trait MocksPlans
{
    /**
     * Create a Plan model with factory for testing.
     *
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createPlanModel(array $options = []): Model
    {
        $default_data = [];

        return factory(\Marqant\MarqantPaySubscriptions\Models\Plan::class)->create(array_merge($default_data,
            $options));
    }
}