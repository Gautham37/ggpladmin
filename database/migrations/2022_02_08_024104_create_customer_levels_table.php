<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 127);
            $table->text('description')->nullable();
            $table->double('group_points',16,2)->default(0.00);
            $table->double('monthly_spend_from',16,2)->default(0.00);
            $table->double('monthly_spend_to',16,2)->default(0.00);
            $table->boolean('active')->nullable()->default(1);

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
        Schema::dropIfExists('customer_levels');
    }
}
