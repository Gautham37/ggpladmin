<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permissions')->delete();

        \DB::table('permissions')->insert(
            array(
                array('id' => '1','name' => 'users.profile','guard_name' => 'web','created_at' => '2020-03-30 03:28:02','updated_at' => '2020-03-30 03:28:02'),
                array('id' => '2','name' => 'dashboard','guard_name' => 'web','created_at' => '2020-03-30 03:28:02','updated_at' => '2020-03-30 03:28:02'),
                array('id' => '3','name' => 'medias.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:02','updated_at' => '2020-03-30 03:28:02'),
                array('id' => '4','name' => 'medias.delete','guard_name' => 'web','created_at' => '2020-03-30 03:28:02','updated_at' => '2020-03-30 03:28:02'),
                array('id' => '5','name' => 'medias','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '6','name' => 'permissions.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '7','name' => 'permissions.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '8','name' => 'permissions.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '9','name' => 'permissions.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '10','name' => 'roles.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '11','name' => 'roles.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '12','name' => 'roles.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '13','name' => 'roles.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '14','name' => 'customFields.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '15','name' => 'customFields.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '16','name' => 'customFields.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '17','name' => 'customFields.show','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '18','name' => 'customFields.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:03','updated_at' => '2020-03-30 03:28:03'),
                array('id' => '19','name' => 'customFields.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '20','name' => 'customFields.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '21','name' => 'users.login-as-user','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '22','name' => 'users.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '23','name' => 'users.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '24','name' => 'users.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '25','name' => 'users.show','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '26','name' => 'users.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '27','name' => 'users.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '28','name' => 'users.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '29','name' => 'app-settings','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '30','name' => 'markets.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '31','name' => 'markets.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '32','name' => 'markets.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '33','name' => 'markets.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '34','name' => 'markets.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:05','updated_at' => '2020-03-30 03:28:05'),
                array('id' => '35','name' => 'markets.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:05','updated_at' => '2020-03-30 03:28:05'),
                array('id' => '36','name' => 'categories.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:05','updated_at' => '2020-03-30 03:28:05'),
                array('id' => '37','name' => 'categories.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:05','updated_at' => '2020-03-30 03:28:05'),
                array('id' => '38','name' => 'categories.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:05','updated_at' => '2020-03-30 03:28:05'),
                array('id' => '39','name' => 'categories.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:05','updated_at' => '2020-03-30 03:28:05'),
                array('id' => '40','name' => 'categories.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:05','updated_at' => '2020-03-30 03:28:05'),
                array('id' => '41','name' => 'categories.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:05','updated_at' => '2020-03-30 03:28:05'),
                array('id' => '42','name' => 'faqCategories.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:06','updated_at' => '2020-03-30 03:28:06'),
                array('id' => '43','name' => 'faqCategories.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:06','updated_at' => '2020-03-30 03:28:06'),
                array('id' => '44','name' => 'faqCategories.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:06','updated_at' => '2020-03-30 03:28:06'),
                array('id' => '45','name' => 'faqCategories.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:06','updated_at' => '2020-03-30 03:28:06'),
                array('id' => '46','name' => 'faqCategories.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:06','updated_at' => '2020-03-30 03:28:06'),
                array('id' => '47','name' => 'faqCategories.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:06','updated_at' => '2020-03-30 03:28:06'),
                array('id' => '48','name' => 'orderStatuses.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:06','updated_at' => '2020-03-30 03:28:06'),
                array('id' => '49','name' => 'orderStatuses.show','guard_name' => 'web','created_at' => '2020-03-30 03:28:06','updated_at' => '2020-03-30 03:28:06'),
                array('id' => '50','name' => 'orderStatuses.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:06','updated_at' => '2020-03-30 03:28:06'),
                array('id' => '51','name' => 'orderStatuses.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '52','name' => 'products.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '53','name' => 'products.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '54','name' => 'products.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '55','name' => 'products.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '56','name' => 'products.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '57','name' => 'products.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '58','name' => 'galleries.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '59','name' => 'galleries.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '60','name' => 'galleries.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:08','updated_at' => '2020-03-30 03:28:08'),
                array('id' => '61','name' => 'galleries.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:08','updated_at' => '2020-03-30 03:28:08'),
                array('id' => '62','name' => 'galleries.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:08','updated_at' => '2020-03-30 03:28:08'),
                array('id' => '63','name' => 'galleries.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:08','updated_at' => '2020-03-30 03:28:08'),
                array('id' => '64','name' => 'productReviews.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:08','updated_at' => '2020-03-30 03:28:08'),
                array('id' => '65','name' => 'productReviews.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:08','updated_at' => '2020-03-30 03:28:08'),
                array('id' => '66','name' => 'productReviews.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:08','updated_at' => '2020-03-30 03:28:08'),
                array('id' => '67','name' => 'productReviews.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:08','updated_at' => '2020-03-30 03:28:08'),
                array('id' => '68','name' => 'productReviews.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:08','updated_at' => '2020-03-30 03:28:08'),
                array('id' => '69','name' => 'productReviews.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:08','updated_at' => '2020-03-30 03:28:08'),
                array('id' => '76','name' => 'options.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:09','updated_at' => '2020-03-30 03:28:09'),
                array('id' => '77','name' => 'options.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:09','updated_at' => '2020-03-30 03:28:09'),
                array('id' => '78','name' => 'options.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:09','updated_at' => '2020-03-30 03:28:09'),
                array('id' => '79','name' => 'options.show','guard_name' => 'web','created_at' => '2020-03-30 03:28:10','updated_at' => '2020-03-30 03:28:10'),
                array('id' => '80','name' => 'options.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:10','updated_at' => '2020-03-30 03:28:10'),
                array('id' => '81','name' => 'options.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:10','updated_at' => '2020-03-30 03:28:10'),
                array('id' => '82','name' => 'options.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:10','updated_at' => '2020-03-30 03:28:10'),
                array('id' => '83','name' => 'payments.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:10','updated_at' => '2020-03-30 03:28:10'),
                array('id' => '84','name' => 'payments.show','guard_name' => 'web','created_at' => '2020-03-30 03:28:10','updated_at' => '2020-03-30 03:28:10'),
                array('id' => '85','name' => 'payments.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:10','updated_at' => '2020-03-30 03:28:10'),
                array('id' => '86','name' => 'faqs.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:10','updated_at' => '2020-03-30 03:28:10'),
                array('id' => '87','name' => 'faqs.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:11','updated_at' => '2020-03-30 03:28:11'),
                array('id' => '88','name' => 'faqs.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:11','updated_at' => '2020-03-30 03:28:11'),
                array('id' => '89','name' => 'faqs.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:11','updated_at' => '2020-03-30 03:28:11'),
                array('id' => '90','name' => 'faqs.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:11','updated_at' => '2020-03-30 03:28:11'),
                array('id' => '91','name' => 'faqs.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:11','updated_at' => '2020-03-30 03:28:11'),
                array('id' => '92','name' => 'marketReviews.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:11','updated_at' => '2020-03-30 03:28:11'),
                array('id' => '93','name' => 'marketReviews.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:11','updated_at' => '2020-03-30 03:28:11'),
                array('id' => '94','name' => 'marketReviews.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:12','updated_at' => '2020-03-30 03:28:12'),
                array('id' => '95','name' => 'marketReviews.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:12','updated_at' => '2020-03-30 03:28:12'),
                array('id' => '96','name' => 'marketReviews.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:12','updated_at' => '2020-03-30 03:28:12'),
                array('id' => '97','name' => 'marketReviews.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:12','updated_at' => '2020-03-30 03:28:12'),
                array('id' => '98','name' => 'favorites.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:12','updated_at' => '2020-03-30 03:28:12'),
                array('id' => '99','name' => 'favorites.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:12','updated_at' => '2020-03-30 03:28:12'),
                array('id' => '100','name' => 'favorites.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:12','updated_at' => '2020-03-30 03:28:12'),
                array('id' => '101','name' => 'favorites.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:12','updated_at' => '2020-03-30 03:28:12'),
                array('id' => '102','name' => 'favorites.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:12','updated_at' => '2020-03-30 03:28:12'),
                array('id' => '103','name' => 'favorites.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:13','updated_at' => '2020-03-30 03:28:13'),
                array('id' => '104','name' => 'orders.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:13','updated_at' => '2020-03-30 03:28:13'),
                array('id' => '105','name' => 'orders.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:13','updated_at' => '2020-03-30 03:28:13'),
                array('id' => '106','name' => 'orders.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:13','updated_at' => '2020-03-30 03:28:13'),
                array('id' => '107','name' => 'orders.show','guard_name' => 'web','created_at' => '2020-03-30 03:28:13','updated_at' => '2020-03-30 03:28:13'),
                array('id' => '108','name' => 'orders.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:13','updated_at' => '2020-03-30 03:28:13'),
                array('id' => '109','name' => 'orders.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:13','updated_at' => '2020-03-30 03:28:13'),
                array('id' => '110','name' => 'orders.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:13','updated_at' => '2020-03-30 03:28:13'),
                array('id' => '111','name' => 'notifications.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:14','updated_at' => '2020-03-30 03:28:14'),
                array('id' => '112','name' => 'notifications.show','guard_name' => 'web','created_at' => '2020-03-30 03:28:14','updated_at' => '2020-03-30 03:28:14'),
                array('id' => '113','name' => 'notifications.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:14','updated_at' => '2020-03-30 03:28:14'),
                array('id' => '114','name' => 'carts.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:14','updated_at' => '2020-03-30 03:28:14'),
                array('id' => '115','name' => 'carts.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:14','updated_at' => '2020-03-30 03:28:14'),
                array('id' => '116','name' => 'carts.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:14','updated_at' => '2020-03-30 03:28:14'),
                array('id' => '117','name' => 'carts.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:14','updated_at' => '2020-03-30 03:28:14'),
                array('id' => '118','name' => 'currencies.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:14','updated_at' => '2020-03-30 03:28:14'),
                array('id' => '119','name' => 'currencies.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:15','updated_at' => '2020-03-30 03:28:15'),
                array('id' => '120','name' => 'currencies.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:15','updated_at' => '2020-03-30 03:28:15'),
                array('id' => '121','name' => 'currencies.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:15','updated_at' => '2020-03-30 03:28:15'),
                array('id' => '122','name' => 'currencies.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:15','updated_at' => '2020-03-30 03:28:15'),
                array('id' => '123','name' => 'currencies.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:15','updated_at' => '2020-03-30 03:28:15'),
                array('id' => '124','name' => 'deliveryAddresses.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:15','updated_at' => '2020-03-30 03:28:15'),
                array('id' => '125','name' => 'deliveryAddresses.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:15','updated_at' => '2020-03-30 03:28:15'),
                array('id' => '126','name' => 'deliveryAddresses.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:15','updated_at' => '2020-03-30 03:28:15'),
                array('id' => '127','name' => 'deliveryAddresses.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:16','updated_at' => '2020-03-30 03:28:16'),
                array('id' => '128','name' => 'deliveryAddresses.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:16','updated_at' => '2020-03-30 03:28:16'),
                array('id' => '129','name' => 'deliveryAddresses.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:16','updated_at' => '2020-03-30 03:28:16'),
                array('id' => '130','name' => 'drivers.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:16','updated_at' => '2020-03-30 03:28:16'),
                array('id' => '131','name' => 'drivers.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:16','updated_at' => '2020-03-30 03:28:16'),
                array('id' => '132','name' => 'drivers.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:16','updated_at' => '2020-03-30 03:28:16'),
                array('id' => '133','name' => 'drivers.show','guard_name' => 'web','created_at' => '2020-03-30 03:28:16','updated_at' => '2020-03-30 03:28:16'),
                array('id' => '134','name' => 'drivers.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:16','updated_at' => '2020-03-30 03:28:16'),
                array('id' => '135','name' => 'drivers.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:16','updated_at' => '2020-03-30 03:28:16'),
                array('id' => '136','name' => 'drivers.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:17','updated_at' => '2020-03-30 03:28:17'),
                array('id' => '137','name' => 'earnings.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:17','updated_at' => '2020-03-30 03:28:17'),
                array('id' => '138','name' => 'earnings.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:17','updated_at' => '2020-03-30 03:28:17'),
                array('id' => '139','name' => 'earnings.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:17','updated_at' => '2020-03-30 03:28:17'),
                array('id' => '140','name' => 'earnings.show','guard_name' => 'web','created_at' => '2020-03-30 03:28:17','updated_at' => '2020-03-30 03:28:17'),
                array('id' => '141','name' => 'earnings.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:17','updated_at' => '2020-03-30 03:28:17'),
                array('id' => '142','name' => 'earnings.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:17','updated_at' => '2020-03-30 03:28:17'),
                array('id' => '143','name' => 'earnings.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:17','updated_at' => '2020-03-30 03:28:17'),
                array('id' => '144','name' => 'driversPayouts.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:17','updated_at' => '2020-03-30 03:28:17'),
                array('id' => '145','name' => 'driversPayouts.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:17','updated_at' => '2020-03-30 03:28:17'),
                array('id' => '146','name' => 'driversPayouts.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:18','updated_at' => '2020-03-30 03:28:18'),
                array('id' => '147','name' => 'driversPayouts.show','guard_name' => 'web','created_at' => '2020-03-30 03:28:18','updated_at' => '2020-03-30 03:28:18'),
                array('id' => '148','name' => 'driversPayouts.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:18','updated_at' => '2020-03-30 03:28:18'),
                array('id' => '149','name' => 'driversPayouts.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:18','updated_at' => '2020-03-30 03:28:18'),
                array('id' => '150','name' => 'driversPayouts.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:18','updated_at' => '2020-03-30 03:28:18'),
                array('id' => '151','name' => 'marketsPayouts.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:18','updated_at' => '2020-03-30 03:28:18'),
                array('id' => '152','name' => 'marketsPayouts.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:18','updated_at' => '2020-03-30 03:28:18'),
                array('id' => '153','name' => 'marketsPayouts.store','guard_name' => 'web','created_at' => '2020-03-30 03:28:18','updated_at' => '2020-03-30 03:28:18'),
                array('id' => '154','name' => 'marketsPayouts.show','guard_name' => 'web','created_at' => '2020-03-30 03:28:18','updated_at' => '2020-03-30 03:28:18'),
                array('id' => '155','name' => 'marketsPayouts.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:18','updated_at' => '2020-03-30 03:28:18'),
                array('id' => '156','name' => 'marketsPayouts.update','guard_name' => 'web','created_at' => '2020-03-30 03:28:19','updated_at' => '2020-03-30 03:28:19'),
                array('id' => '157','name' => 'marketsPayouts.destroy','guard_name' => 'web','created_at' => '2020-03-30 03:28:19','updated_at' => '2020-03-30 03:28:19'),
                array('id' => '158','name' => 'permissions.create','guard_name' => 'web','created_at' => '2020-03-30 03:29:15','updated_at' => '2020-03-30 03:29:15'),
                array('id' => '159','name' => 'permissions.store','guard_name' => 'web','created_at' => '2020-03-30 03:29:15','updated_at' => '2020-03-30 03:29:15'),
                array('id' => '160','name' => 'permissions.show','guard_name' => 'web','created_at' => '2020-03-30 03:29:15','updated_at' => '2020-03-30 03:29:15'),
                array('id' => '161','name' => 'roles.create','guard_name' => 'web','created_at' => '2020-03-30 03:29:15','updated_at' => '2020-03-30 03:29:15'),
                array('id' => '162','name' => 'roles.store','guard_name' => 'web','created_at' => '2020-03-30 03:29:15','updated_at' => '2020-03-30 03:29:15'),
                array('id' => '163','name' => 'roles.show','guard_name' => 'web','created_at' => '2020-03-30 03:29:16','updated_at' => '2020-03-30 03:29:16'),
                array('id' => '164','name' => 'fields.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '165','name' => 'fields.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '166','name' => 'fields.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '167','name' => 'fields.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '168','name' => 'fields.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '169','name' => 'fields.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '170','name' => 'optionGroups.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '171','name' => 'optionGroups.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '172','name' => 'optionGroups.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '173','name' => 'optionGroups.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '174','name' => 'optionGroups.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '175','name' => 'optionGroups.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '176','name' => 'requestedMarkets.index','guard_name' => 'web','created_at' => '2020-08-14 03:28:02','updated_at' => '2020-08-14 03:28:02'),
                array('id' => '183','name' => 'coupons.index','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '184','name' => 'coupons.create','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '185','name' => 'coupons.store','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '186','name' => 'coupons.edit','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '187','name' => 'coupons.update','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '188','name' => 'coupons.destroy','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '189','name' => 'slides.index','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '190','name' => 'slides.create','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '191','name' => 'slides.store','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '192','name' => 'slides.edit','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '193','name' => 'slides.update','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '194','name' => 'slides.destroy','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '195','name' => 'expenses.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '196','name' => 'expenses.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '197','name' => 'expenses.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '198','name' => 'expenses.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '199','name' => 'expenses.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '200','name' => 'expenses.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '201','name' => 'expensesCategory.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '202','name' => 'expensesCategory.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '203','name' => 'expensesCategory.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '204','name' => 'expensesCategory.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '205','name' => 'expensesCategory.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '206','name' => 'expensesCategory.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '207','name' => 'reports.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '208','name' => 'purchase.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '209','name' => 'purchase.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '210','name' => 'purchase.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '211','name' => 'purchase.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '212','name' => 'purchase.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '213','name' => 'purchase.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '214','name' => 'purchaseOrder.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '215','name' => 'purchaseOrder.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '216','name' => 'purchaseOrder.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '217','name' => 'purchaseOrder.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '218','name' => 'purchaseOrder.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '219','name' => 'purchaseOrder.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '220','name' => 'reports.show','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '222','name' => 'reports.expenseTransaction','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '223','name' => 'reports.expenseCategory','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '224','name' => 'purchaseOrder.show','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '225','name' => 'purchaseInvoice.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '226','name' => 'purchaseInvoice.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '227','name' => 'purchaseInvoice.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '228','name' => 'purchaseInvoice.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '229','name' => 'purchaseInvoice.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '230','name' => 'purchaseInvoice.show','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '231','name' => 'purchaseInvoice.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '232','name' => 'paymentOut.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '233','name' => 'paymentOut.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '234','name' => 'paymentOut.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '235','name' => 'paymentOut.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '236','name' => 'paymentOut.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '237','name' => 'paymentOut.show','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '238','name' => 'paymentOut.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '239','name' => 'app.invoiceThemes','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '240','name' => 'products.stock','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '241','name' => 'deliveryChallan.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '242','name' => 'deliveryChallan.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '243','name' => 'deliveryChallan.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '244','name' => 'deliveryChallan.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '245','name' => 'deliveryChallan.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '246','name' => 'deliveryChallan.show','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '247','name' => 'deliveryChallan.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '248','name' => 'app.thermalPrint','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '249','name' => 'staffs.profile','guard_name' => 'web','created_at' => '2020-03-30 03:28:02','updated_at' => '2020-03-30 03:28:02'),
                array('id' => '250','name' => 'staffs.index','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '251','name' => 'staffs.create','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '252','name' => 'staffs.edit','guard_name' => 'web','created_at' => '2020-03-30 03:28:04','updated_at' => '2020-03-30 03:28:04'),
                array('id' => '253','name' => 'products.show','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '254','name' => 'products.view','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '255','name' => 'CustomerGroups.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '256','name' => 'CustomerGroups.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '257','name' => 'CustomerGroups.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '258','name' => 'CustomerGroups.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '259','name' => 'CustomerGroups.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '260','name' => 'CustomerGroups.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '261','name' => 'products.createProductPrice','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '262','name' => 'products.updateProductPrice','guard_name' => 'web','created_at' => '2020-03-30 03:28:07','updated_at' => '2020-03-30 03:28:07'),
                array('id' => '263','name' => 'salesInvoice.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '264','name' => 'salesInvoice.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '265','name' => 'salesInvoice.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '266','name' => 'salesInvoice.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '267','name' => 'salesInvoice.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '268','name' => 'salesInvoice.show','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '269','name' => 'salesInvoice.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '270','name' => 'purchaseInvoice.createPOInvoice','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '271','name' => 'paymentIn.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '272','name' => 'paymentIn.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '273','name' => 'paymentIn.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '274','name' => 'paymentIn.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '275','name' => 'paymentIn.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '276','name' => 'paymentIn.show','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '277','name' => 'paymentIn.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '278','name' => 'markets.view','guard_name' => 'web','created_at' => '2020-03-30 03:28:05','updated_at' => '2020-03-30 03:28:05'),
                array('id' => '279','name' => 'purchaseReturn.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '280','name' => 'purchaseReturn.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '281','name' => 'purchaseReturn.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '282','name' => 'purchaseReturn.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '283','name' => 'purchaseReturn.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '284','name' => 'purchaseReturn.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '285','name' => 'purchaseReturn.show','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '286','name' => 'salesReturn.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '287','name' => 'salesReturn.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '288','name' => 'salesReturn.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '289','name' => 'salesReturn.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '290','name' => 'salesReturn.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '291','name' => 'salesReturn.show','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '292','name' => 'salesReturn.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '293','name' => 'supplierRequest.index','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '294','name' => 'supplierRequest.create','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '295','name' => 'supplierRequest.store','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '296','name' => 'supplierRequest.edit','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '297','name' => 'supplierRequest.update','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '298','name' => 'supplierRequest.show','guard_name' => 'web','created_at' => '2020-04-12 03:34:40','updated_at' => '2020-04-12 03:34:40'),
                array('id' => '299','name' => 'supplierRequest.destroy','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '300','name' => 'purchaseInvoice.createSRInvoice','guard_name' => 'web','created_at' => '2020-04-12 03:34:39','updated_at' => '2020-04-12 03:34:39'),
                array('id' => '301','name' => 'products.savePriceVariations','guard_name' => 'web','created_at' => '2021-06-23 19:48:46','updated_at' => '2021-06-23 19:48:46'),
                array('id' => '302','name' => 'products.importPriceVariations','guard_name' => 'web','created_at' => '2021-06-23 19:56:44','updated_at' => '2021-06-23 19:56:44'),
                array('id' => '303','name' => 'websiteSlides.index','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '304','name' => 'websiteSlides.create','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '305','name' => 'websiteSlides.store','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '306','name' => 'websiteSlides.edit','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '307','name' => 'websiteSlides.update','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '308','name' => 'websiteSlides.destroy','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '309','name' => 'CustomerLevels.index','guard_name' => 'web','created_at' => '2021-06-24 19:47:47','updated_at' => '2021-06-24 19:47:47'),
                array('id' => '310','name' => 'CustomerLevels.create','guard_name' => 'web','created_at' => '2021-06-24 19:47:58','updated_at' => '2021-06-24 19:47:58'),
                array('id' => '311','name' => 'CustomerLevels.store','guard_name' => 'web','created_at' => '2021-06-24 19:47:58','updated_at' => '2021-06-24 19:47:58'),
                array('id' => '312','name' => 'CustomerLevels.edit','guard_name' => 'web','created_at' => '2021-06-24 19:48:25','updated_at' => '2021-06-24 19:48:25'),
                array('id' => '313','name' => 'CustomerLevels.update','guard_name' => 'web','created_at' => '2021-06-24 19:48:25','updated_at' => '2021-06-24 19:48:25'),
                array('id' => '314','name' => 'CustomerLevels.destroy','guard_name' => 'web','created_at' => '2021-06-24 19:48:49','updated_at' => '2021-06-24 19:48:49'),
                array('id' => '315','name' => 'rewards.index','guard_name' => 'web','created_at' => '2021-06-24 19:47:47','updated_at' => '2021-06-24 19:47:47'),
                array('id' => '316','name' => 'rewards.create','guard_name' => 'web','created_at' => '2021-06-24 19:47:58','updated_at' => '2021-06-24 19:47:58'),
                array('id' => '317','name' => 'rewards.store','guard_name' => 'web','created_at' => '2021-06-24 19:47:58','updated_at' => '2021-06-24 19:47:58'),
                array('id' => '318','name' => 'rewards.edit','guard_name' => 'web','created_at' => '2021-06-24 19:48:25','updated_at' => '2021-06-24 19:48:25'),
                array('id' => '319','name' => 'rewards.update','guard_name' => 'web','created_at' => '2021-06-24 19:48:25','updated_at' => '2021-06-24 19:48:25'),
                array('id' => '320','name' => 'rewards.destroy','guard_name' => 'web','created_at' => '2021-06-24 19:48:49','updated_at' => '2021-06-24 19:48:49'),
                array('id' => '321','name' => 'emailnotifications.index','guard_name' => 'web','created_at' => '2021-07-05 06:12:52','updated_at' => '2021-07-05 06:12:52'),
                array('id' => '322','name' => 'emailnotifications.create','guard_name' => 'web','created_at' => '2021-07-05 06:14:11','updated_at' => '2021-07-05 06:14:11'),
                array('id' => '323','name' => 'emailnotifications.store','guard_name' => 'web','created_at' => '2021-07-05 06:14:32','updated_at' => '2021-07-05 06:14:32'),
                array('id' => '324','name' => 'emailnotifications.edit','guard_name' => 'web','created_at' => '2021-07-05 06:14:56','updated_at' => '2021-07-05 06:14:56'),
                array('id' => '325','name' => 'emailnotifications.destroy','guard_name' => 'web','created_at' => '2021-07-05 06:15:16','updated_at' => '2021-07-05 06:15:16'),
                array('id' => '326','name' => 'inventory.index','guard_name' => 'web','created_at' => '2021-07-05 06:12:52','updated_at' => '2021-07-05 06:12:52'),
                array('id' => '327','name' => 'emailnotifications.show','guard_name' => 'web','created_at' => '2021-07-05 06:15:16','updated_at' => '2021-07-05 06:15:16'),
                array('id' => '328','name' => 'websiteTestimonials.index','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '329','name' => 'websiteTestimonials.create','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '330','name' => 'websiteTestimonials.store','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '331','name' => 'websiteTestimonials.edit','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '332','name' => 'websiteTestimonials.update','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '333','name' => 'websiteTestimonials.destroy','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '334','name' => 'specialOffers.index','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '335','name' => 'specialOffers.create','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '336','name' => 'specialOffers.store','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '337','name' => 'specialOffers.edit','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '338','name' => 'specialOffers.update','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '339','name' => 'specialOffers.destroy','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '340','name' => 'deliveryZones.index','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '341','name' => 'deliveryZones.create','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '342','name' => 'deliveryZones.store','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '343','name' => 'deliveryZones.edit','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '344','name' => 'deliveryZones.update','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '345','name' => 'deliveryZones.destroy','guard_name' => 'web','created_at' => '2020-08-24 03:28:02','updated_at' => '2020-08-24 03:28:02'),
                array('id' => '346','name' => 'subcategory.index','guard_name' => 'web','created_at' => '2021-11-18 05:00:44','updated_at' => NULL),
                array('id' => '347','name' => 'subcategory.create','guard_name' => 'web','created_at' => '2021-11-18 05:01:17','updated_at' => NULL),
                array('id' => '348','name' => 'subcategory.store','guard_name' => 'web','created_at' => '2021-11-18 05:01:33','updated_at' => NULL),
                array('id' => '349','name' => 'subcategory.edit','guard_name' => 'web','created_at' => '2021-11-18 05:01:33','updated_at' => NULL),
                array('id' => '350','name' => 'subcategory.update','guard_name' => 'web','created_at' => '2021-11-18 05:02:01','updated_at' => NULL),
                array('id' => '351','name' => 'subcategory.destroy','guard_name' => 'web','created_at' => '2021-11-18 05:02:01','updated_at' => NULL),
                array('id' => '352','name' => 'wastageDisposal.index','guard_name' => 'web','created_at' => '2021-11-18 05:15:56','updated_at' => NULL),
                array('id' => '353','name' => 'products.showSubcategory','guard_name' => 'web','created_at' => '2021-11-18 14:44:33','updated_at' => NULL),
                array('id' => '354','name' => 'partystream.index','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '355','name' => 'partystream.create','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '356','name' => 'partystream.edit','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '357','name' => 'partyTypes.index','guard_name' => 'web','created_at' => '2021-11-22 11:55:21','updated_at' => NULL),
                array('id' => '358','name' => 'partyTypes.create','guard_name' => 'web','created_at' => '2021-11-22 11:58:00','updated_at' => NULL),
                array('id' => '359','name' => 'partyTypes.store','guard_name' => 'web','created_at' => '2021-11-22 11:55:47','updated_at' => NULL),
                array('id' => '360','name' => 'partyTypes.edit','guard_name' => 'web','created_at' => '2021-11-22 11:55:47','updated_at' => NULL),
                array('id' => '361','name' => 'partyTypes.update','guard_name' => 'web','created_at' => '2021-11-22 11:56:21','updated_at' => NULL),
                array('id' => '362','name' => 'partyTypes.destroy','guard_name' => 'web','created_at' => '2021-11-22 11:56:21','updated_at' => NULL),
                array('id' => '363','name' => 'partySubTypes.index','guard_name' => 'web','created_at' => '2021-11-22 11:55:21','updated_at' => NULL),
                array('id' => '364','name' => 'partySubTypes.create','guard_name' => 'web','created_at' => '2021-11-22 11:58:00','updated_at' => NULL),
                array('id' => '365','name' => 'partySubTypes.store','guard_name' => 'web','created_at' => '2021-11-22 11:55:47','updated_at' => NULL),
                array('id' => '366','name' => 'partySubTypes.edit','guard_name' => 'web','created_at' => '2021-11-22 11:55:47','updated_at' => NULL),
                array('id' => '367','name' => 'partySubTypes.update','guard_name' => 'web','created_at' => '2021-11-22 11:56:21','updated_at' => NULL),
                array('id' => '368','name' => 'partySubTypes.destroy','guard_name' => 'web','created_at' => '2021-11-22 11:56:21','updated_at' => NULL),
                array('id' => '369','name' => 'partystream.store','guard_name' => 'web','created_at' => '2021-11-22 08:35:34','updated_at' => NULL),
                array('id' => '370','name' => 'partystream.update','guard_name' => 'web','created_at' => '2021-11-22 08:35:39','updated_at' => NULL),
                array('id' => '371','name' => 'partystream.destroy','guard_name' => 'web','created_at' => '2021-11-22 08:35:43','updated_at' => NULL),
                array('id' => '373','name' => 'markets.showPartySubTypes','guard_name' => 'web','created_at' => '2021-11-22 15:45:30','updated_at' => NULL),
                array('id' => '374','name' => 'departments.index','guard_name' => 'web','created_at' => '2021-11-25 08:40:28','updated_at' => NULL),
                array('id' => '375','name' => 'departments.create','guard_name' => 'web','created_at' => '2021-11-25 08:40:28','updated_at' => NULL),
                array('id' => '376','name' => 'departments.store','guard_name' => 'web','created_at' => '2021-11-25 08:41:35','updated_at' => NULL),
                array('id' => '377','name' => 'departments.edit','guard_name' => 'web','created_at' => '2021-11-25 08:41:35','updated_at' => NULL),
                array('id' => '378','name' => 'departments.update','guard_name' => 'web','created_at' => '2021-11-25 08:42:09','updated_at' => NULL),
                array('id' => '379','name' => 'departments.destroy','guard_name' => 'web','created_at' => '2021-11-25 08:42:09','updated_at' => NULL),
                array('id' => '380','name' => 'products.showDepartments','guard_name' => 'web','created_at' => '2021-11-25 10:21:49','updated_at' => NULL),
                array('id' => '382','name' => 'staffdepartment.index','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '383','name' => 'staffdepartment.create','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '384','name' => 'staffdepartment.update','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '385','name' => 'staffdepartment.edit','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '386','name' => 'staffdepartment.store','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '387','name' => 'staffdepartment.destroy','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '388','name' => 'staffdesignation.index','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '389','name' => 'staffdesignation.create','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '390','name' => 'staffdesignation.store','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '391','name' => 'staffdesignation.edit','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '392','name' => 'staffdesignation.update','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '394','name' => 'staffdesignation.destroy','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '395','name' => 'staffs.store','guard_name' => 'web','created_at' => '2021-11-30 11:17:26','updated_at' => NULL),
                array('id' => '396','name' => 'staffs.update','guard_name' => 'web','created_at' => '2021-11-30 11:17:26','updated_at' => NULL),
                array('id' => '397','name' => 'staffs.destroy','guard_name' => 'web','created_at' => '2021-11-30 15:49:46','updated_at' => NULL),
                array('id' => '398','name' => 'complaints.index','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '399','name' => 'complaints.edit','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '401','name' => 'complaints.update','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '403','name' => 'complaints.destroy','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '404','name' => 'complaints.create','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '405','name' => 'complaints.show','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '406','name' => 'complaints.store','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '407','name' => 'complaints.comments','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '409','name' => 'complaints.closeComplaints','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '410','name' => 'complaints.viewComments','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '411','name' => 'complaints.saveCloseComplaints','guard_name' => 'web','created_at' => '2021-12-23 12:09:09','updated_at' => NULL),
                array('id' => '412','name' => 'staffs.showStaffDepartments','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '414','name' => 'driverReviews.index','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '416','name' => 'deliveryTips.index','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '417','name' => 'customerFarmerReviews.index','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '418','name' => 'qualityGrade.update','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '419','name' => 'qualityGrade.store','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '420','name' => 'qualityGrade.index','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '421','name' => 'qualityGrade.edit','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '422','name' => 'qualityGrade.destroy','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '423','name' => 'qualityGrade.create','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '424','name' => 'productStatus.update','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '425','name' => 'productStatus.store','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '426','name' => 'productStatus.index','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '427','name' => 'productStatus.edit','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '428','name' => 'productStatus.destroy','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '429','name' => 'productStatus.create','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '430','name' => 'stockStatus.update','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '431','name' => 'stockStatus.store','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '432','name' => 'stockStatus.index','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '433','name' => 'stockStatus.edit','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '434','name' => 'stockStatus.destroy','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '435','name' => 'stockStatus.create','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '436','name' => 'valueAddedServiceAffiliated.update','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '437','name' => 'valueAddedServiceAffiliated.store','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '438','name' => 'valueAddedServiceAffiliated.edit','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '439','name' => 'valueAddedServiceAffiliated.index','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '440','name' => 'valueAddedServiceAffiliated.destroy','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array('id' => '441','name' => 'valueAddedServiceAffiliated.create','guard_name' => 'web','created_at' => NULL,'updated_at' => NULL),
                array(
                    'id' => '442',
                    'name' => 'productSeasons.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '443',
                    'name' => 'productSeasons.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '444',
                    'name' => 'productSeasons.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '445',
                    'name' => 'productSeasons.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '446',
                    'name' => 'productSeasons.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '447',
                    'name' => 'productSeasons.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '448',
                    'name' => 'productSeasons.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '449',
                    'name' => 'productColors.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '450',
                    'name' => 'productColors.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '451',
                    'name' => 'productColors.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '452',
                    'name' => 'productColors.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '453',
                    'name' => 'productColors.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '454',
                    'name' => 'productColors.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '455',
                    'name' => 'productColors.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '456',
                    'name' => 'productNutritions.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '457',
                    'name' => 'productNutritions.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '458',
                    'name' => 'productNutritions.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '459',
                    'name' => 'productNutritions.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '460',
                    'name' => 'productNutritions.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '461',
                    'name' => 'productNutritions.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '462',
                    'name' => 'productNutritions.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '463',
                    'name' => 'productTastes.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '464',
                    'name' => 'productTastes.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '465',
                    'name' => 'productTastes.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '466',
                    'name' => 'productTastes.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '467',
                    'name' => 'productTastes.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '468',
                    'name' => 'productTastes.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '469',
                    'name' => 'productTastes.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '470',
                    'name' => 'products.import',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '471',
                    'name' => 'products.importproducts',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '472',
                    'name' => 'subcategory.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '473',
                    'name' => 'salesInvoice.print',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '474',
                    'name' => 'salesReturn.print',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '475',
                    'name' => 'paymentIn.print',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '476',
                    'name' => 'purchaseOrder.print',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '477',
                    'name' => 'purchaseInvoice.print',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '478',
                    'name' => 'vendorStock.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '479',
                    'name' => 'vendorStock.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '480',
                    'name' => 'vendorStock.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '481',
                    'name' => 'vendorStock.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '482',
                    'name' => 'vendorStock.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '483',
                    'name' => 'vendorStock.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '484',
                    'name' => 'vendorStock.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '485',
                    'name' => 'paymentOut.print',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '486',
                    'name' => 'purchaseReturn.print',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '487',
                    'name' => 'vendorStock.print',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '488',
                    'name' => 'inventory.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '489',
                    'name' => 'inventory.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '490',
                    'name' => 'inventory.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '491',
                    'name' => 'inventory.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '492',
                    'name' => 'inventory.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '493',
                    'name' => 'inventory.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '494',
                    'name' => 'expenses.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '495',
                    'name' => 'quotes.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '496',
                    'name' => 'quotes.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '497',
                    'name' => 'quotes.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '498',
                    'name' => 'quotes.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '499',
                    'name' => 'quotes.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '500',
                    'name' => 'quotes.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '501',
                    'name' => 'quotes.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '502',
                    'name' => 'quotes.print',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '503',
                    'name' => 'marketActivity.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '504',
                    'name' => 'marketActivity.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '505',
                    'name' => 'marketActivity.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '506',
                    'name' => 'marketActivity.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '507',
                    'name' => 'marketActivity.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '508',
                    'name' => 'marketActivity.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '509',
                    'name' => 'marketActivity.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '510',
                    'name' => 'marketNotes.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '511',
                    'name' => 'marketNotes.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '512',
                    'name' => 'marketNotes.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '513',
                    'name' => 'marketNotes.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '514',
                    'name' => 'marketNotes.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '515',
                    'name' => 'marketNotes.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '516',
                    'name' => 'marketNotes.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '517',
                    'name' => 'attendance.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '518',
                    'name' => 'attendance.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '519',
                    'name' => 'attendance.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '520',
                    'name' => 'attendance.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '521',
                    'name' => 'attendance.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '522',
                    'name' => 'attendance.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '523',
                    'name' => 'attendance.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '524',
                    'name' => 'attendance.summary',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '525',
                    'name' => 'attendance.mark',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '526',
                    'name' => 'attendance.punch',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '527',
                    'name' => 'paymentFor.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '528',
                    'name' => 'paymentFor.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '529',
                    'name' => 'paymentFor.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '530',
                    'name' => 'paymentFor.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '531',
                    'name' => 'paymentFor.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '532',
                    'name' => 'paymentFor.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '533',
                    'name' => 'paymentFor.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '534',
                    'name' => 'stockTake.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '535',
                    'name' => 'stockTake.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '536',
                    'name' => 'stockTake.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '537',
                    'name' => 'stockTake.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '538',
                    'name' => 'stockTake.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '539',
                    'name' => 'stockTake.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '540',
                    'name' => 'stockTake.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '541',
                    'name' => 'wastageDisposal.list',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '542',
                    'name' => 'wastageDisposal.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '543',
                    'name' => 'wastageDisposal.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '544',
                    'name' => 'wastageDisposal.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '545',
                    'name' => 'wastageDisposal.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '546',
                    'name' => 'wastageDisposal.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '547',
                    'name' => 'wastageDisposal.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '548',
                    'name' => 'inventory.list',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '549',
                    'name' => 'orders.print',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '550',
                    'name' => 'deliveryTracker.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '551',
                    'name' => 'deliveryTracker.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '552',
                    'name' => 'deliveryTracker.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '553',
                    'name' => 'deliveryTracker.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '554',
                    'name' => 'deliveryTracker.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '555',
                    'name' => 'deliveryTracker.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '556',
                    'name' => 'deliveryTracker.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '557',
                    'name' => 'deliveryTracker.summary',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '558',
                    'name' => 'stockTake.print',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '559',
                    'name' => 'markets.import',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '560',
                    'name' => 'markets.importmarkets',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '561',
                    'name' => 'charity.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '562',
                    'name' => 'charity.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '563',
                    'name' => 'charity.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '564',
                    'name' => 'charity.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '565',
                    'name' => 'charity.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '566',
                    'name' => 'charity.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '567',
                    'name' => 'charity.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '568',
                    'name' => 'farmerSlides.index',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '569',
                    'name' => 'farmerSlides.create',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '570',
                    'name' => 'farmerSlides.store',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '571',
                    'name' => 'farmerSlides.show',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '572',
                    'name' => 'farmerSlides.edit',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '573',
                    'name' => 'farmerSlides.update',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                ),
                array(
                    'id' => '574',
                    'name' => 'farmerSlides.destroy',
                    'guard_name' => 'web',
                    'created_at' => NULL,
                    'updated_at' => NULL
                )
            )
        );
    }
}
