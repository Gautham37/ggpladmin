<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentModeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payment_mode')->delete();

        \DB::table('payment_mode')->insert(
            array(
                array('id' => '1','name' => 'CASH','description' => NULL,'status' => '1','created_at' => NULL,'updated_at' => NULL),
                array('id' => '2','name' => 'CHEQUE','description' => NULL,'status' => '1','created_at' => NULL,'updated_at' => NULL),
                array('id' => '3','name' => 'BANK','description' => NULL,'status' => '1','created_at' => NULL,'updated_at' => NULL),
                array('id' => '4','name' => 'CARD','description' => NULL,'status' => '1','created_at' => NULL,'updated_at' => NULL),
                array('id' => '5','name' => 'STRIPE','description' => NULL,'status' => '1','created_at' => NULL,'updated_at' => NULL)
            )
        );
    }
}
