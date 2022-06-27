<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWastageDisposalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wastage_disposal', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('inventory_id')->unsigned()->index()->nullable();
            $table->foreign('inventory_id')->references('id')->on('inventory_track')->onDelete('cascade');

            $table->bigInteger('product_id')->unsigned()->index()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->enum('type',['normal','charity'])->default('normal')->nullable();
            $table->bigInteger('market_id')->unsigned()->index()->nullable();
            $table->foreign('market_id')->references('id')->on('markets')->onDelete('cascade');                            

            $table->double('quantity',16,3)->default(0.000);
            $table->bigInteger('unit')->unsigned()->index()->nullable();
            $table->foreign('unit')->references('id')->on('uom')->onDelete('cascade');

            $table->text('description')->nullable();

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
        Schema::dropIfExists('wastage_disposal');
    }
}
