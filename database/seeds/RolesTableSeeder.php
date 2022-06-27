<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->delete();

        \DB::table('roles')->insert( array(
            0 => 
            array (
                'id' => 1,
                'name' => 'superadmin',
                'guard_name' => 'web',
                'created_at' => '2021-12-06 16:37:56',
                'updated_at' => '2021-12-06 22:42:01',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'admin',
                'guard_name' => 'web',
                'created_at' => '2021-12-06 16:37:56',
                'updated_at' => '2021-12-06 22:42:01',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'customer',
                'guard_name' => 'web',
                'created_at' => '2021-12-06 16:37:56',
                'updated_at' => '2021-12-06 22:42:01',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'vendor',
                'guard_name' => 'web',
                'created_at' => '2021-12-06 16:37:56',
                'updated_at' => '2021-12-06 22:42:01',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'manager',
                'guard_name' => 'web',
                'created_at' => '2021-12-06 16:37:56',
                'updated_at' => '2021-12-06 22:42:01',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'supervisor',
                'guard_name' => 'web',
                'created_at' => '2021-12-06 16:37:56',
                'updated_at' => '2021-12-06 22:42:01',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'worker',
                'guard_name' => 'web',
                'created_at' => '2021-12-06 16:37:56',
                'updated_at' => '2021-12-06 22:42:01',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'driver',
                'guard_name' => 'web',
                'created_at' => '2021-12-06 16:37:56',
                'updated_at' => '2021-12-06 22:42:01',
            )

        ));
    }
}
