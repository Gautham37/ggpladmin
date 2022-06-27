<?php

namespace App\Repositories;

use App\Models\SalesReturnItems;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class SalesReturnItemsRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method SalesReturnItems findWithoutFail($id, $columns = ['*'])
 * @method SalesReturnItems find($id, $columns = ['*'])
 * @method SalesReturnItems first($columns = ['*'])
*/
class SalesReturnItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'sales_return_id',
        'product_id',
        'product_name',
        'product_hsn_code',
        'mrp',
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
        return SalesReturnItems::class;
    }
}
