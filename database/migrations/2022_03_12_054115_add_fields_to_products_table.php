<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->bigInteger('season_id')->unsigned()->index()->nullable()->after('department_id');
            $table->foreign('season_id')->references('id')->on('product_seasons');

            $table->bigInteger('color_id')->unsigned()->index()->nullable()->after('season_id');
            $table->foreign('color_id')->references('id')->on('product_colors');

            $table->bigInteger('nutrition_id')->unsigned()->index()->nullable()->after('color_id');
            $table->foreign('nutrition_id')->references('id')->on('product_nutritions');

            $table->bigInteger('taste_id')->unsigned()->index()->nullable()->after('nutrition_id');
            $table->foreign('taste_id')->references('id')->on('product_tastes');

            $table->string('sugar_level')->nullable()->after('low_stock_warning');
            $table->tinyInteger('weight_loss')->default(0)->after('sugar_level');
            $table->tinyInteger('freeze_well')->default(0)->after('weight_loss');
            $table->tinyInteger('grows_on_tree')->default(0)->after('freeze_well');
            $table->tinyInteger('salad_vegetable')->default(0)->after('grows_on_tree');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
