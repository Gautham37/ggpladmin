<?php
/**
 * File name: ProductDetailsRepository.php
 * Last modified: 2020.06.07 at 07:02:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Repositories;

use App\Models\ProductDetails;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class ProductDetailsRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method Product findWithoutFail($id, $columns = ['*'])
 * @method Product find($id, $columns = ['*'])
 * @method Product first($columns = ['*'])
 */
class ProductDetailsRepository extends BaseRepository
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
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
        'reason_discontinuation'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductDetails::class;
    }

}
