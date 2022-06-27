<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaxRatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('tax_rates')->delete();

        \DB::table('tax_rates')->insert(array (
                array('id' => '1','name' => 'GST 0.01%','rate' => '0.01','created_at' => NULL,'updated_at' => NULL),
                array('id' => '2','name' => 'GST 0.25%','rate' => '0.25','created_at' => NULL,'updated_at' => NULL),
                array('id' => '3','name' => 'GST 3%','rate' => '3','created_at' => NULL,'updated_at' => NULL),
                array('id' => '4','name' => 'GST 5%','rate' => '5','created_at' => NULL,'updated_at' => NULL),
                array('id' => '5','name' => 'GST 12%','rate' => '12','created_at' => NULL,'updated_at' => NULL),
                array('id' => '6','name' => 'GST 18%','rate' => '18','created_at' => NULL,'updated_at' => NULL),
                array('id' => '7','name' => 'GST 28%','rate' => '28','created_at' => NULL,'updated_at' => NULL)
        ) );

    }
}
