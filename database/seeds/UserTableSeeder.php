<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();

        \DB::table('users')->insert(array(
            0 => 
            array (
                'id'                    => 1,
                'name'                  => 'Solution 22',
                'email'                 => 'support@solution22.com.au',
                'password'              => '$2y$10$YOn/Xq6vfvi9oaixrtW8QuM2W0mawkLLqIxL.IoGqrsqOqbIsfBNu',
                'api_token'             => 'PivvPlsQWxPl1bB5KrbKNBuraJit0PrUZekQUgtLyTRuyBq921atFtoR1HuA',
                'customer_group_id'     => NULL,
                'gender'                => NULL,
                'date_of_birth'         => NULL,
                'is_staff'              => 0,
                'device_token'          => NULL,
                'stripe_id'             => NULL,
                'stripe_id'             => NULL,
                'card_brand'            => NULL,
                'card_last_four'        => NULL,
                'trial_ends_at'         => NULL,
                'braintree_id'          => NULL,
                'paypal_email'          => NULL,
                'remember_token'        => 'T4PQhFvBcAA7k02f7ejq4I7z7QKKnvxQLV0oqGnuS6Ktz6FdWULrWrzZ3oYn',
                'points'                => 0,
                'level'                 => NULL,
                'referred_by'           => NULL,
                'affiliate_id'          => NULL,
                'social_login_id'       => NULL
            )
        ));
    }
}
