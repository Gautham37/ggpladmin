<?php

namespace App\Repositories;

use App\Models\ProductNutritionDetail;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class ProductNutritionDetailRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method ProductNutritionDetail findWithoutFail($id, $columns = ['*'])
 * @method ProductNutritionDetail find($id, $columns = ['*'])
 * @method ProductNutritionDetail first($columns = ['*'])
 */
class ProductNutritionDetailRepository extends BaseRepository
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'product_nutrition_id',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductNutritionDetail::class;
    }

}
