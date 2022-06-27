<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('faq_category_id')->unsigned()->index()->nullable();
            $table->foreign('faq_category_id')->references('id')->on('faq_categories')->onDelete('cascade');

            $table->text('question')->nullable();
            $table->text('answer')->nullable();

            $table->integer('app_type')->default(1)->comment('1-customer app, 2-farmer app, 3-delivery app ');

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
        Schema::dropIfExists('faqs');
    }
}
