<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_addresses', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->text('street_no')->nullable();
            $table->text('street_type')->nullable();
            $table->text('landmark_1')->nullable();
            $table->text('landmark_2')->nullable();

            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->text('town')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
            $table->text('pincode')->nullable();
            
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            
            $table->tinyInteger('is_default')->nullable();
            
            $table->string('notes')->nullable();
            
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
        Schema::dropIfExists('delivery_addresses');
    }
}
