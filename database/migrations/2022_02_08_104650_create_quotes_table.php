<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('market_id')->unsigned()->index()->nullable();
            $table->foreign('market_id')->references('id')->on('markets')->onDelete('cascade');

            $table->string('code');
            $table->date('date');
            $table->date('valid_date');
            
            $table->double('sub_total',16,2)->default(0.00);
            $table->string('additional_charge_description')->nullable();
            $table->double('additional_charge',16,2)->default(0.00)->nullable();
            
            $table->double('delivery_charge',16,2)->default(0.00)->nullable();
            $table->double('discount_total',16,2)->default(0.00)->nullable();
            
            $table->double('tax_total',16,2)->default(0.00)->nullable();
            $table->double('round_off',16,2)->default(0.00)->nullable();
            $table->double('total',16,2)->default(0.00);
            
            $table->longText('notes')->nullable();
            $table->longText('terms_and_conditions')->nullable();

            $table->enum('status',['draft','sent','viewed','accepted','declined','invoiced'])->default('draft');
            
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
        Schema::dropIfExists('quotes');
    }
}
