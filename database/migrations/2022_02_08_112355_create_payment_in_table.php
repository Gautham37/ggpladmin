<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_in', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('market_id')->unsigned()->index()->nullable();
            $table->foreign('market_id')->references('id')->on('markets')->onDelete('cascade');

            $table->string('code');
            $table->date('date');

            $table->bigInteger('payment_method')->unsigned()->index()->nullable();
            $table->foreign('payment_method')->references('id')->on('payment_mode');

            $table->longText('notes')->nullable();

            $table->boolean('settle_invoice')->nullable()->default(0);
            $table->double('total',16,2)->default(0.00);

            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');

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
        Schema::dropIfExists('payment_in');
    }
}
