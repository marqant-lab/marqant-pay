<?php

namespace Marqant\MarqantPay\Tests\Traits;

use Illuminate\Database\Eloquent\Model;
use Marqant\MarqantPaySubscriptions\Models\Plan;

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

        return Plan::factory()->create(array_merge(
            $default_data,
            $options
        ));
    }
}
