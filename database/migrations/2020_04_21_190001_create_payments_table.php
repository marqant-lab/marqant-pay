<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');

            // description to be used on invoices
            $table->string('description');

            // unique invoice nr of the payment
            $table->string('invoice_nr')
                ->unique();

            $table->string('provider');
            $table->integer('amount');
            $table->string('currency');
            $table->string('status');

            // pdf download (url from provider or custom pdf)
            $table->string('download')
                ->nullable();

            // setup polymorphic relationship to billable model
            $table->bigInteger('billable_id');
            $table->string('billable_type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
