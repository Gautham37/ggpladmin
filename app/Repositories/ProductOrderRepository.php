<?php

namespace App\Repositories;

use App\Models\ProductOrder;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProductOrderRepository
 * @package App\Repositories
 * @version August 31, 2019, 11:18 am UTC
 *
 * @method ProductOrder findWithoutFail($id, $columns = ['*'])
 * @method ProductOrder find($id, $columns = ['*'])
 * @method ProductOrder first($columns = ['*'])
*/
class ProductOrderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'product_name',
        'product_code',
        //'product_hsn_code',
        'quantity',
        'unit',
        'unit_price',
        'discount',
        'discount_amount',
        'tax',
        'tax_amount',
        'amount',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductOrder::class;
    }
}
