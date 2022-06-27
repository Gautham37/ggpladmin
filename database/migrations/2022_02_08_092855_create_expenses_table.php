<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('expense_category_id')->unsigned()->index()->nullable();
            $table->foreign('expense_category_id')->references('id')->on('expenses_categories')->onDelete('cascade');

            $table->date('date');

            $table->bigInteger('payment_mode')->unsigned()->index()->nullable();
            $table->foreign('payment_mode')->references('id')->on('payment_mode');

            $table->double('total_amount',16,2)->default(0.00);
            $table->longText('notes')->nullable();

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
        Schema::dropIfExists('expenses');
    }
}
