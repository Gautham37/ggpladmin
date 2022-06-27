<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('product_id')->unsigned()->index()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->string('vas_1')->nullable();
            $table->string('vas_dollar_1')->nullable();
            $table->string('vas_2')->nullable();
            $table->string('vas_dollar_2')->nullable();
            $table->string('vas_3')->nullable();
            $table->string('vas_dollar_3')->nullable();
            $table->string('nutrition_benefit')->nullable();
            $table->string('health_benefit')->nullable();
            $table->string('product_life')->nullable();
            $table->string('ambient_temprature')->nullable();
            $table->string('storage_type')->nullable();
            $table->string('storage_method')->nullable();
            $table->string('range_standard')->nullable();
            $table->string('short_description_product_code')->nullable();
            $table->string('stock_level')->nullable();
            $table->date('stock_purchased_date')->nullable();
            $table->string('alternate_weight_kg')->nullable();
            $table->string('other_key_search_words')->nullable();
            $table->string('source_confirm')->nullable();
            $table->string('reason_discontinuation')->nullable();

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
        Schema::dropIfExists('product_details');
    }
}
