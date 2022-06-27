<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustomerLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('customer_levels')->delete();

        \DB::table('customer_levels')->insert(
            array(
                array(
                    'id'            => '1',
                    'name'          => 'Level 1',
                    'description'   => '',
                    'group_points'  => '2',
                    'monthly_spend' => '10000',
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
            )
        );
    }
}
