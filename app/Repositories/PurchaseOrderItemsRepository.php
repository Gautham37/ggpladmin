<?php

namespace App\Repositories;

use App\Models\PurchaseOrderItems;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PurchaseOrderItemsRepository
 * @package App\Repositories
 *
 * @method PurchaseOrderItems findWithoutFail($id, $columns = ['*'])
 * @method PurchaseOrderItems find($id, $columns = ['*'])
 * @method PurchaseOrderItems first($columns = ['*'])
*/
class PurchaseOrderItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'purchase_order_id',
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
        return PurchaseOrderItems::class;
    }
}
