<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PartyTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('party_types')->delete();

        \DB::table('party_types')->insert(
            array(
                array('id' => '1','name' => 'Customer','description' => NULL,'active' => '1','created_at' => NULL,'updated_at' => NULL),
                array('id' => '2','name' => 'Supplier','description' => NULL,'active' => '1','created_at' => NULL,'updated_at' => NULL),
                array('id' => '3','name' => 'Farmer','description' => NULL,'active' => '1','created_at' => NULL,'updated_at' => NULL),
                array('id' => '4','name' => 'Staff','description' => NULL,'active' => '1','created_at' => NULL,'updated_at' => NULL)
            )
        );
    }
}
