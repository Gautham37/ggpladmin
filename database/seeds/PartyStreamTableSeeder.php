<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PartyStreamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('party_streams')->delete();

        \DB::table('party_streams')->insert(
            array(
                array('id' => '1','name' => 'A - Platinum CX','description' => NULL,'active' => '1','created_at' => NULL,'updated_at' => NULL),
                array('id' => '2','name' => 'B - Gold CX','description' => NULL,'active' => '1','created_at' => NULL,'updated_at' => NULL),
                array('id' => '3','name' => 'C - Blue CX','description' => NULL,'active' => '1','created_at' => NULL,'updated_at' => NULL),
                array('id' => '4','name' => 'D - Other CX','description' => NULL,'active' => '1','created_at' => NULL,'updated_at' => NULL),
                array('id' => '5','name' => 'E - Abusive & Non Paying','description' => NULL,'active' => '1','created_at' => NULL,'updated_at' => NULL),
                array('id' => '6','name' => 'F - Blocked CX','description' => NULL,'active' => '1','created_at' => NULL,'updated_at' => NULL)
            )
        );
    }
}
