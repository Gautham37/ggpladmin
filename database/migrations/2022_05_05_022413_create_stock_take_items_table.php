<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTakeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_take_items', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('stock_take_id')->unsigned()->index()->nullable();
            $table->foreign('stock_take_id')->references('id')->on('stock_take')->onDelete('cascade');

            $table->bigInteger('product_id')->unsigned()->index()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->string('product_name')->nullable();
            $table->string('product_code')->nullable();

            $table->double('current',16,3)->default(0.000);
            $table->bigInteger('current_unit')->unsigned()->index()->nullable();
            $table->foreign('current_unit')->references('id')->on('uom')->onDelete('cascade');

            $table->double('counted',16,3)->default(0.000);
            $table->bigInteger('counted_unit')->unsigned()->index()->nullable();
            $table->foreign('counted_unit')->references('id')->on('uom')->onDelete('cascade');

            $table->double('missing',16,3)->default(0.000)->nullable();
            $table->bigInteger('missing_unit')->unsigned()->index()->nullable();
            $table->foreign('missing_unit')->references('id')->on('uom')->onDelete('cascade');

            $table->double('wastage',16,3)->default(0.000)->nullable();
            $table->bigInteger('wastage_unit')->unsigned()->index()->nullable();
            $table->foreign('wastage_unit')->references('id')->on('uom')->onDelete('cascade');

            $table->text('notes')->nullable();

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
        Schema::dropIfExists('stock_take_items');
    }
}
