<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SupplyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('supply_points')->delete();

        \DB::table('supply_points')->insert(
            array(
                array(
                    'id'            => '1',
                    'name'          => 'Kissan',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '2',
                    'name'          => 'Village Mandi',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '3',
                    'name'          => 'District Mandi',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '4',
                    'name'          => 'Cisty Mandi (GOVT) ',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '5',
                    'name'          => 'Wholesale Mandi',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '6',
                    'name'          => 'Local Town Mandi',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '7',
                    'name'          => 'Competetor',
                    'description'   => NULL,
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
