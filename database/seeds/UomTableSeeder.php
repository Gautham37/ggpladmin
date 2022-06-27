<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('uom')->delete();

        \DB::table('uom')->insert(
            array(
                  array('id' => '1','name' => 'BAL','description' => 'BALE','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '2','name' => 'BDL','description' => 'BUNDLES','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '3','name' => 'BKL','description' => 'BUCKLES','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '4','name' => 'BOU','description' => 'BILLION OF UNITS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '5','name' => 'BOX','description' => 'BOX','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '6','name' => 'BTL','description' => 'BOTTLES','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '7','name' => 'BUN','description' => 'BUNCHES','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '8','name' => 'CAN','description' => 'CANS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '9','name' => 'CBM','description' => 'CUBIC METERS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '10','name' => 'CCM','description' => 'CUBIC CENTIMETERS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '11','name' => 'CMS','description' => 'CENTIMETERS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '12','name' => 'CTN','description' => 'CARTONS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '13','name' => 'DOZ','description' => 'DOZENS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '14','name' => 'DRM','description' => 'DRUMS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '15','name' => 'GGK','description' => 'GREAT GROSS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '16','name' => 'GMS','description' => 'GRAMMES','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '17','name' => 'GRS','description' => 'GROSS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '18','name' => 'GYD','description' => 'GROSS YARDS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '19','name' => 'KGS','description' => 'KILOGRAMS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '20','name' => 'KLR','description' => 'KILOLITRE','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '21','name' => 'KME','description' => 'KILOMETRE','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '22','name' => 'MLT','description' => 'MILILITRE','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '23','name' => 'MTR','description' => 'METERS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '24','name' => 'MTS','description' => 'METRIC TON','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '25','name' => 'NOS','description' => 'NUMBERS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '26','name' => 'PAC','description' => 'PACKS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '27','name' => 'PCS','description' => 'PIECES','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '28','name' => 'PRS','description' => 'PAIRS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '29','name' => 'QTL','description' => 'QUINTAL','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '30','name' => 'ROL','description' => 'ROLLS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '31','name' => 'SET','description' => 'SETS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '32','name' => 'SQF','description' => 'SQUARE FEET','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '33','name' => 'SQM','description' => 'SQUARE METERS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '34','name' => 'SQY','description' => 'SQUARE YARDS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '35','name' => 'TBS','description' => 'TABLETS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '36','name' => 'TGM','description' => 'TEN GROSS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '37','name' => 'THD','description' => 'THOUSANDS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '38','name' => 'TON','description' => 'TONNES','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '39','name' => 'TUB','description' => 'TUBES','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '40','name' => 'UGS','description' => 'US GALLONS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '41','name' => 'UNT','description' => 'UNITS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '42','name' => 'YDS','description' => 'YARDS','created_at' => NULL,'updated_at' => NULL),
                  array('id' => '43','name' => 'OTH','description' => 'OTHERS','created_at' => NULL,'updated_at' => NULL)
            )
        );
    }
}
