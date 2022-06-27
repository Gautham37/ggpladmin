<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('area')->nullable();

            $table->bigInteger('quality_grade')->unsigned()->index()->nullable();
            $table->foreign('quality_grade')->references('id')->on('quality_grade')->onDelete('cascade');
            $table->enum('product_status',['active','inactive'])->default('active');
            $table->bigInteger('stock_status')->unsigned()->index()->nullable();
            $table->foreign('stock_status')->references('id')->on('stock_status')->onDelete('cascade');
            
            /*$table->bigInteger('value_added_service_affiliated')->unsigned()->index()->nullable();
            $table->foreign('value_added_service_affiliated')->references('id')->on('value_added_service_affiliated')->onDelete('cascade');

            $table->double('vas_charges_amt',16,2)->nullable();
            $table->double('vas_charges_unit_quantity',16,2)->nullable();
            $table->string('vas_charges_unit_type')->nullable();*/

            $table->string('name_lang_1')->nullable();
            $table->string('name_lang_2')->nullable();

            $table->bigInteger('category_id')->unsigned()->index()->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->bigInteger('subcategory_id')->unsigned()->index()->nullable();
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
            $table->bigInteger('department_id')->unsigned()->index()->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->string('alpha')->nullable();
            $table->string('product_code_short')->nullable();
            $table->enum('product_varient',['yes','no'])->nullable();
            $table->string('product_varient_number')->nullable();
            
            $table->string('con')->nullable();
            $table->string('product_code')->nullable();
            $table->string('short_product_code')->nullable();

            $table->double('price',16,2)->default(0.00)->nullable();
            $table->double('purchase_price',16,2)->default(0.00)->nullable();
            $table->double('discount_price',16,2)->default(0.00)->nullable();
            $table->string('hsn_code')->nullable();
            $table->double('tax',16,2)->default(0.00)->nullable();
            $table->text('description')->nullable();

            //$table->string('unit');

            $table->bigInteger('unit')->unsigned()->index()->nullable();
            $table->foreign('unit')->references('id')->on('uom')->onDelete('cascade');

            $table->double('stock',16,3)->default(0.000)->nullable();
            $table->tinyInteger('alternative_unit')->default(0);

            $table->bigInteger('secondary_unit')->unsigned()->index()->nullable();
            $table->foreign('secondary_unit')->references('id')->on('uom')->onDelete('cascade');
            //$table->string('secondary_unit')->nullable();
            $table->double('secondary_unit_quantity',16,3)->default(0.000)->nullable();

            $table->bigInteger('tertiary_unit')->unsigned()->index()->nullable();
            $table->foreign('tertiary_unit')->references('id')->on('uom')->onDelete('cascade');
            //$table->string('tertiary_unit')->nullable();
            $table->double('tertiary_unit_quantity',16,3)->default(0.000)->nullable();

            $table->bigInteger('custom_unit')->unsigned()->index()->nullable();
            $table->foreign('custom_unit')->references('id')->on('uom')->onDelete('cascade');
            //$table->string('custom_unit')->nullable();
            $table->double('custom_unit_quantity',16,3)->default(0.000)->nullable();

            $table->bigInteger('bulk_buy_unit')->unsigned()->index()->nullable();
            $table->foreign('bulk_buy_unit')->references('id')->on('uom')->onDelete('cascade');
            //$table->string('bulk_buy_unit')->nullable();
            $table->double('bulk_buy_unit_quantity',16,3)->default(0.000)->nullable();

            $table->bigInteger('wholesale_purchase_unit')->unsigned()->index()->nullable();
            $table->foreign('wholesale_purchase_unit')->references('id')->on('uom')->onDelete('cascade');
            //$table->string('wholesale_purchase_unit')->nullable();
            $table->double('wholesale_purchase_unit_quantity',16,3)->default(0.000)->nullable();

            $table->bigInteger('pack_weight_unit')->unsigned()->index()->nullable();
            $table->foreign('pack_weight_unit')->references('id')->on('uom')->onDelete('cascade');
            //$table->string('pack_weight_unit')->nullable();
            $table->double('pack_weight_unit_quantity',16,3)->default(0.000)->nullable();  


            $table->enum('product_size',['small','medium','large','x-lage'])->nullable();
            $table->string('spare')->nullable();
            $table->string('spare_2')->nullable();
            $table->string('ave_weight_if_known')->nullable();
            $table->text('ave_p_u_1_weight')->nullable();

            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('deliverable')->default(1);
            $table->tinyInteger('online_store')->default(1);
            $table->tinyInteger('low_stock_warning')->default(0);
            $table->double('low_stock_unit',16,3)->default(0.000)->nullable();

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
        Schema::dropIfExists('products');
    }
}
