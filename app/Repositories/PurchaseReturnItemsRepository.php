<?php

namespace App\Repositories;

use App\Models\PurchaseReturnItems;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PurchaseReturnItemsRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method PurchaseReturnItems findWithoutFail($id, $columns = ['*'])
 * @method PurchaseReturnItems find($id, $columns = ['*'])
 * @method PurchaseReturnItems first($columns = ['*'])
*/
class PurchaseReturnItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'purchase_return_id',
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
        return PurchaseReturnItems::class;
    }
}
