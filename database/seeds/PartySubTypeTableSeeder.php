<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PartySubTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('party_sub_types')->delete();

        \DB::table('party_sub_types')->insert(
            array(
                array(
                    'id'            => '1',
                    'party_type_id' => '1',
                    'role_id'       => '3',
                    'name'          => 'Residential Customer',
                    'prefix_value'  => 'RC',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '2',
                    'party_type_id' => '1',
                    'role_id'       => '3',
                    'name'          => 'Business Customer',
                    'prefix_value'  => 'BC',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '3',
                    'party_type_id' => '1',
                    'role_id'       => '3',
                    'name'          => 'Sole Trader Customer',
                    'prefix_value'  => 'STC',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '4',
                    'party_type_id' => '1',
                    'role_id'       => '3',
                    'name'          => 'Charity Organisation Customer',
                    'prefix_value'  => 'COC',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '5',
                    'party_type_id' => '1',
                    'role_id'       => '3',
                    'name'          => 'Non Govt Organisation Customer',
                    'prefix_value'  => 'NGOC',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '6',
                    'party_type_id' => '2',
                    'role_id'       => '4',
                    'name'          => 'Business Vendor',
                    'prefix_value'  => 'BV',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '7',
                    'party_type_id' => '3',
                    'role_id'       => '4',
                    'name'          => 'Farmer Vendor',
                    'prefix_value'  => 'FV',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '8',
                    'party_type_id' => '2',
                    'role_id'       => '4',
                    'name'          => 'Home Trader Vendor',
                    'prefix_value'  => 'HTV',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '9',
                    'party_type_id' => '2',
                    'role_id'       => '4',
                    'name'          => 'Other Vendor',
                    'prefix_value'  => 'OV',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '10',
                    'party_type_id' => '4',
                    'role_id'       => '8',
                    'name'          => 'Delivery Driving Staff',
                    'prefix_value'  => 'DDS',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '11',
                    'party_type_id' => '4',
                    'role_id'       => '8',
                    'name'          => 'Transport Vendor Staff',
                    'prefix_value'  => 'TVS',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '12',
                    'party_type_id' => '4',
                    'role_id'       => '6',
                    'name'          => 'Executive Staff',
                    'prefix_value'  => 'ES',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '13',
                    'party_type_id' => '4',
                    'role_id'       => '5',
                    'name'          => 'Managerial Staff',
                    'prefix_value'  => 'MS',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '14',
                    'party_type_id' => '4',
                    'role_id'       => '6',
                    'name'          => 'Supervisory Staff',
                    'prefix_value'  => 'SS',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '15',
                    'party_type_id' => '4',
                    'role_id'       => '7',
                    'name'          => 'Worker Staff',
                    'prefix_value'  => 'WS',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
                array(
                    'id'            => '16',
                    'party_type_id' => '4',
                    'role_id'       => '7',
                    'name'          => 'Volunteer Staff',
                    'prefix_value'  => 'VS',
                    'description'   => NULL,
                    'active'        => 1,
                    'created_by'    => NULL,
                    'updated_by'    => NULL,
                    'created_at'    => NULL,
                    'updated_at'    => NULL
                ),
            )
        );

        
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


        \DB::table('customer_levels')->delete();

        \DB::table('customer_levels')->insert(
            array(
                array(
                    'id'                 => '1',
                    'name'               => 'BLUE',
                    'description'        => '',
                    'group_points'       => '1',
                    'monthly_spend_from' => '1001',
                    'monthly_spend_to'   => '5000',
                    'active'             => 1,
                    'created_by'         => NULL,
                    'updated_by'         => NULL,
                    'created_at'         => NULL,
                    'updated_at'         => NULL
                ),
                array(
                    'id'                 => '2',
                    'name'               => 'GOLD',
                    'description'        => '',
                    'group_points'       => '1.5',
                    'monthly_spend_from' => '5002',
                    'monthly_spend_to'   => '10000',
                    'active'             => 1,
                    'created_by'         => NULL,
                    'updated_by'         => NULL,
                    'created_at'         => NULL,
                    'updated_at'         => NULL
                ),
                array(
                    'id'                 => '3',
                    'name'               => 'PLATINUM',
                    'description'        => '',
                    'group_points'       => '2',
                    'monthly_spend_from' => '10001',
                    'monthly_spend_to'   => '1000000',
                    'active'             => 1,
                    'created_by'         => NULL,
                    'updated_by'         => NULL,
                    'created_at'         => NULL,
                    'updated_at'         => NULL
                ),
            )
        );


        \DB::table('order_statuses')->delete();

        \DB::table('order_statuses')->insert(
            array(
                array(
                    'id'                 => '1',
                    'status'             => 'In Progress',
                    'created_by'         => NULL,
                    'updated_by'         => NULL,
                    'created_at'         => NULL,
                    'updated_at'         => NULL
                ),
            )
        );


        \DB::table('expenses_categories')->delete();

        \DB::table('expenses_categories')->insert(
            array(
                array(
                    'id'                 => '1',
                    'name'               => 'Bank Fee and Charges',
                    'description'        => '',
                    'active'             => '1',
                    'created_by'         => NULL,
                    'updated_by'         => NULL,
                    'created_at'         => NULL,
                    'updated_at'         => NULL
                ),
                array(
                    'id'                 => '2',
                    'name'               => 'Employee Salaries & Advances',
                    'description'        => '',
                    'active'             => '1',
                    'created_by'         => NULL,
                    'updated_by'         => NULL,
                    'created_at'         => NULL,
                    'updated_at'         => NULL
                ),
                array(
                    'id'                 => '3',
                    'name'               => 'Printing & Stationery',
                    'description'        => '',
                    'active'             => '1',
                    'created_by'         => NULL,
                    'updated_by'         => NULL,
                    'created_at'         => NULL,
                    'updated_at'         => NULL
                ),
                array(
                    'id'                 => '4',
                    'name'               => 'Raw Material',
                    'description'        => '',
                    'active'             => '1',
                    'created_by'         => NULL,
                    'updated_by'         => NULL,
                    'created_at'         => NULL,
                    'updated_at'         => NULL
                ),
                array(
                    'id'                 => '5',
                    'name'               => 'Rent Expense',
                    'description'        => '',
                    'active'             => '1',
                    'created_by'         => NULL,
                    'updated_by'         => NULL,
                    'created_at'         => NULL,
                    'updated_at'         => NULL
                ),
                array(
                    'id'                 => '6',
                    'name'               => 'Repair & Maintenance',
                    'description'        => '',
                    'active'             => '1',
                    'created_by'         => NULL,
                    'updated_by'         => NULL,
                    'created_at'         => NULL,
                    'updated_at'         => NULL
                ),
                array(
                    'id'                 => '7',
                    'name'               => 'Telephone & Internet Expense',
                    'description'        => '',
                    'active'             => '1',
                    'created_by'         => NULL,
                    'updated_by'         => NULL,
                    'created_at'         => NULL,
                    'updated_at'         => NULL
                ),
                array(
                    'id'                 => '8',
                    'name'               => 'Transportation & Travel Expense',
                    'description'        => '',
                    'active'             => '1',
                    'created_by'         => NULL,
                    'updated_by'         => NULL,
                    'created_at'         => NULL,
                    'updated_at'         => NULL
                )
            )
        );

        \DB::table('website_slide')->delete();

        \DB::table('website_slide')->insert(
            array(
                array(
                    'id'                 => '1',
                    'order'              => '1',
                    'text'               => 'Get Farm Fresh Vegetables',
                    'description'        => '',
                    'button'             => 'Buy Now',
                    'text_position'      => 'center',
                    'text_color'         => '#fff',
                    'button_color'       => NULL,
                    'background_color'   => '#4eb92d',
                    'indicator_color'    => NULL,
                    'image_fit'          => 'cover',
                    'enabled'            => '1',
                    'created_at'         => NULL,
                    'updated_at'         => NULL,
                ),
                array(
                    'id'                 => '2',
                    'order'              => '2',
                    'text'               => 'Farm Fresh Vegetables',
                    'description'        => '',
                    'button'             => 'Buy Now',
                    'text_position'      => 'center',
                    'text_color'         => '#fff',
                    'button_color'       => NULL,
                    'background_color'   => '#4eb92d',
                    'indicator_color'    => NULL,
                    'image_fit'          => 'cover',
                    'enabled'            => '1',
                    'created_at'         => NULL,
                    'updated_at'         => NULL,
                )
            )
        );


        \DB::table('website_testimonials')->delete();

        \DB::table('website_testimonials')->insert(
            array(
                array(
                    'id'                 => '1',
                    'name'               => 'Jhone Doe',
                    'description'        => 'I did my weekly veggies shopping at this outlet a few weeks ago and was pleased with the range of items they had, plus it was very reasonably priced. it is easy to access and there is ample parking. Would highly recommend a visit',
                    'rating'             => '5',
                    'created_at'         => NULL,
                    'updated_at'         => NULL,
                ),
                array(
                    'id'                 => '2',
                    'name'               => 'Michael',
                    'description'        => 'I did my weekly veggies shopping at this outlet a few weeks ago and was pleased with the range of items they had, plus it was very reasonably priced. it is easy to access and there is ample parking. Would highly recommend a visit',
                    'rating'             => '5',
                    'created_at'         => NULL,
                    'updated_at'         => NULL,
                ),
            )
        );

        \DB::table('coupons')->delete();

        \DB::table('coupons')->insert(
            array(
                array(
                    'id'                => '1',
                    'code'              => 'New19',
                    'discount'          => '19',
                    'discount_type'     => 'fixed',
                    'description'       => 'New19: For New User 19 Bucks off',
                    'usage_limit'       => '100',
                    'expires_at'        => '2022-12-12',
                    'enabled'           => '1',
                    'created_by'        => NULL,
                    'updated_by'        => NULL,
                    'created_at'        => NULL,
                    'updated_at'        => NULL
                ),
                array(
                    'id'                => '2',
                    'code'              => 'Friends49',
                    'discount'          => '49',
                    'discount_type'     => 'fixed',
                    'description'       => 'Friends49: For Friends (Friend can send 49 Bucks voucher to new User)',
                    'usage_limit'       => '100',
                    'expires_at'        => '2022-12-12',
                    'enabled'           => '1',
                    'created_by'        => NULL,
                    'updated_by'        => NULL,
                    'created_at'        => NULL,
                    'updated_at'        => NULL
                ),
                array(
                    'id'                => '3',
                    'code'              => 'Mate69',
                    'discount'          => '69',
                    'discount_type'     => 'fixed',
                    'description'       => 'Mate69: For couples to send 69 bucks shopping yo their Lovers',
                    'usage_limit'       => '100',
                    'expires_at'        => '2022-12-12',
                    'enabled'           => '1',
                    'created_by'        => NULL,
                    'updated_by'        => NULL,
                    'created_at'        => NULL,
                    'updated_at'        => NULL
                ),
                array(
                    'id'                => '4',
                    'code'              => '99NotOut',
                    'discount'          => '99',
                    'discount_type'     => 'fixed',
                    'description'       => '99NotOut:) for Users making purchase of over 999 bucks',
                    'usage_limit'       => '100',
                    'expires_at'        => '2022-12-12',
                    'enabled'           => '1',
                    'created_by'        => NULL,
                    'updated_by'        => NULL,
                    'created_at'        => NULL,
                    'updated_at'        => NULL
                )
            )
        );

    }
}
