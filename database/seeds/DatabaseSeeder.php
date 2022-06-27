<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(TaxRatesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(AppSettingsTableSeeder::class);
        $this->call(UomTableSeeder::class);
        $this->call(PaymentModeTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleHasPermissionTableSeeder::class);
        $this->call(ModelHasRolesTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(PartyTypeTableSeeder::class);
        $this->call(PartyStreamTableSeeder::class);
        $this->call(PartySubTypeTableSeeder::class);
        //$this->call(CustomerLevelsTableSeeder::class);
        //$this->call(SupplyTableSeeder::class);
    }
}
