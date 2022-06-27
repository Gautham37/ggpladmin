<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');            
            $table->string('name');
            $table->string('code');
            $table->string('code_count');
            $table->string('description')->nullable();

            $table->text('street_no')->nullable();
            $table->text('street_name')->nullable();
            $table->text('street_type')->nullable();
            $table->text('landmark_1')->nullable();
            $table->text('landmark_2')->nullable();

            $table->text('address_line_1')->nullable();
            $table->text('address_line_2')->nullable();
            $table->text('town')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
            $table->text('pincode')->nullable();
            $table->text('manual_address')->nullable();
            $table->text('current_location_address')->nullable();
            $table->enum('gender',['male','female','others'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->text('information')->nullable();

            $table->bigInteger('type')->unsigned()->index()->nullable();
            $table->foreign('type')->references('id')->on('party_types')->onDelete('cascade');

            $table->bigInteger('sub_type')->unsigned()->index()->nullable();
            $table->foreign('sub_type')->references('id')->on('party_sub_types')->onDelete('cascade');

            $table->bigInteger('stream')->unsigned()->index()->nullable();
            $table->foreign('stream')->references('id')->on('party_streams')->onDelete('cascade');

            $table->string('gstin')->nullable();
            $table->double('balance')->default(0.00);

            $table->bigInteger('customer_group_id')->unsigned()->index()->nullable();
            $table->foreign('customer_group_id')->references('id')->on('customer_groups');

            $table->bigInteger('customer_level_id')->unsigned()->index()->nullable();
            $table->foreign('customer_level_id')->references('id')->on('customer_levels');

            $table->longText('hear_about_us')->nullable();
            $table->boolean('email_subscription')->nullable()->default(0);
            $table->boolean('sms_subscription')->nullable()->default(0);
            $table->boolean('party_alert')->nullable()->default(1);
            $table->string('party_alert_type')->nullable();
            $table->date('party_alert_end_date')->nullable();

            $table->boolean('policy_and_terms')->nullable()->default(0);
            $table->bigInteger('verified_by')->unsigned()->index()->nullable();
            $table->foreign('verified_by')->references('id')->on('users');

            $table->string('party_size')->nullable();
            
            $table->bigInteger('supply_point')->unsigned()->index()->nullable();
            $table->foreign('supply_point')->references('id')->on('supply_points')->onDelete('cascade');

            $table->string('membership_type')->nullable();
            $table->string('referred_by')->nullable();

            
            $table->bigInteger('staff_designation_id')->unsigned()->index()->nullable();
            $table->foreign('staff_designation_id')->references('id')->on('designations')->onDelete('cascade'); 
            $table->date('date_of_joining')->nullable();
            $table->date('probation_ended_on')->nullable();
            $table->date('termination_date')->nullable();
            $table->double('salary',16,2)->default(0.00)->nullable();
            $table->enum('salary_agreed',['yes','no'])->default('no')->nullable();


            $table->enum('preferred_language',['english','gujarati','hindi','punjabi','tamil'])->default('english');
            $table->enum('created_via',['admin_portal', 'website', 'mobile_app'])->default('admin_portal');

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
        Schema::dropIfExists('markets');
    }
}
