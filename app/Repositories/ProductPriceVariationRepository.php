<?php

namespace App\Repositories;

use App\Models\ProductPriceVariation;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProductPriceVariationRepository
 * @package App\Repositories
 *
 * @method ProductPriceVariation findWithoutFail($id, $columns = ['*'])
 * @method ProductPriceVariation find($id, $columns = ['*'])
 * @method ProductPriceVariation first($columns = ['*'])
*/
class ProductPriceVariationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'purchase_quantity_from',
        'purchase_quantity_to',
        'price_multiplier',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductPriceVariation::class;
    }
}
