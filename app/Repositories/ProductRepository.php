<?php
/**
 * File name: ProductRepository.php
 * Last modified: 2020.06.07 at 07:02:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Repositories;

use App\Models\Product;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class ProductRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method Product findWithoutFail($id, $columns = ['*'])
 * @method Product find($id, $columns = ['*'])
 * @method Product first($columns = ['*'])
 */
class ProductRepository extends BaseRepository implements CacheableInterface
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'area',
        'quality_grade',
        'product_status',
        'stock_status',
        /*'value_added_service_affiliated',
        'vas_charges_amt',
        'vas_charges_unit_quantity',
        'vas_charges_unit_type',*/
        'name_lang_1',
        'name_lang_2',
        'category_id',
        'subcategory_id',
        'department_id',
        'season_id',
        'color_id',
        //'nutrition_id',
        'taste_id',
        'alpha',
        'product_code_short',
        'product_varient',
        'product_varient_number',
        'con',
        'product_code',
        'short_product_code',
        'price',
        'purchase_price',
        'discount_price',
        'hsn_code',
        'tax',
        'description',
        'unit',
        'stock',
        'alternative_unit',
        'secondary_unit',
        'secondary_unit_quantity',
        'tertiary_unit',
        'tertiary_unit_quantity',
        'custom_unit',
        'custom_unit_quantity',
        'bulk_buy_unit',
        'bulk_buy_unit_quantity',
        'wholesale_purchase_unit',
        'wholesale_purchase_unit_quantity',
        'pack_weight_unit',
        'pack_weight_unit_quantity',
        'product_size',
        'spare',
        'spare_2',
        'ave_weight_if_known',
        'ave_p_u_1_weight',
        'featured',
        'deliverable',
        'online_store',
        'low_stock_warning',
        'sugar_level',
        'weight_loss',
        'freeze_well',
        'grows_on_tree',
        'salad_vegetable',
        'low_stock_unit',

        'nutrition_benefit',
        'health_benefit',
        'product_life',
        'ambient_temprature',
        'storage_type',
        'storage_method',
        'range_standard',
        'short_description_product_code',
        'stock_level',
        'stock_purchased_date',
        'alternate_weight_kg',
        'other_key_search_words',
        'source_confirm',
        'reason_discontinuation',

        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Product::class;
    }

    /**
     * get my products
     **/
    /*public function myProducts()
    {
        return Product::join("user_markets", "user_markets.market_id", "=", "products.market_id")
            ->where('user_markets.user_id', auth()->id())->get();
    }

    public function groupedByMarkets()
    {
        $products = [];
        foreach ($this->all() as $model) {
            if(!empty($model->market)){
            $products[$model->market->name][$model->id] = $model->name;
        }
        }
        return $products;
    }*/
}
