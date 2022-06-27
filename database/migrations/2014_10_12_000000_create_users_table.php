<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->char('api_token', 60)->unique()->nullable()->default(null);
            $table->integer('customer_group_id')->nullable()->default(null);
            $table->enum('gender',['male','female','others'])->nullable()->default(null);
            $table->date('date_of_birth')->nullable()->default(null);
            $table->tinyInteger('is_staff')->default(0);
            $table->string('device_token')->nullable();
            //$table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            //$table->timestamp('trial_ends_at')->nullable();
            $table->string('braintree_id')->nullable();
            $table->string('paypal_email')->nullable();
            $table->rememberToken();

            $table->double('points')->default(0);
            $table->integer('level')->nullable();
            $table->string('referred_by')->nullable();
            $table->string('affiliate_id')->index()->nullable();
            $table->string('social_login_id')->nullable();
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
        Schema::dropIfExists('users');
    }
}
