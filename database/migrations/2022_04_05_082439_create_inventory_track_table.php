<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_track', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('product_id')->unsigned()->index()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->bigInteger('market_id')->unsigned()->index()->nullable();
            $table->foreign('market_id')->references('id')->on('markets')->onDelete('cascade'); 

            $table->enum('category',['opening','added','purchase','purchase_return','sales','sales_return','online','online_return'])->nullable();
            $table->enum('type',['add','reduce'])->nullable();
            $table->datetime('date');
            $table->double('quantity',16,3)->default(0.000);

            $table->bigInteger('unit')->unsigned()->index()->nullable();
            $table->foreign('unit')->references('id')->on('uom')->onDelete('cascade');

            $table->text('description')->nullable();
            $table->enum('usage',['normal','wastage','missing'])->default('normal');
            //$table->boolean('usage')->nullable()->default(1);

            $table->bigInteger('sales_invoice_item_id')->unsigned()->index()->nullable();
            $table->foreign('sales_invoice_item_id')->references('id')->on('sales_invoice_items')->onDelete('cascade');

            $table->bigInteger('sales_return_item_id')->unsigned()->index()->nullable();
            $table->foreign('sales_return_item_id')->references('id')->on('sales_return_items')->onDelete('cascade');

            $table->bigInteger('purchase_invoice_item_id')->unsigned()->index()->nullable();
            $table->foreign('purchase_invoice_item_id')->references('id')->on('purchase_invoice_items')->onDelete('cascade');

            $table->bigInteger('purchase_return_item_id')->unsigned()->index()->nullable();
            $table->foreign('purchase_return_item_id')->references('id')->on('purchase_return_items')->onDelete('cascade');

            $table->bigInteger('product_order_item_id')->unsigned()->index()->nullable();
            $table->foreign('product_order_item_id')->references('id')->on('product_orders')->onDelete('cascade');
            
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
        Schema::dropIfExists('inventory_track');
    }
}
