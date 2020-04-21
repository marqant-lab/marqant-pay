<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanProviderPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_provider', function (Blueprint $table) {
            // create plan_id column
            // ! make sure to use the same column type
            //   as in the referenced column
            //   e.g. integer or bigInteger
            $table->bigInteger('plan_id')
                ->unsigned()
                ->nullable();

            // make plan_id column a foreign key
            $table->foreign('plan_id')
                ->references('id')
                ->on('plans')
                ->onDelete('cascade');

            // create provider_id column
            // ! make sure to use the same column type
            //   as in the referenced column
            //   e.g. integer or bigInteger
            $table->bigInteger('provider_id')
                ->unsigned()
                ->nullable();

            // make provider_id column a foreign key
            $table->foreign('provider_id')
                ->references('id')
                ->on('providers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_provider');
    }
}
