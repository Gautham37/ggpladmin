<?php

namespace App\Repositories;

use App\Models\VendorStockItems;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class VendorStockItemsRepository
 * @package App\Repositories
 *
 * @method VendorStockItems findWithoutFail($id, $columns = ['*'])
 * @method VendorStockItems find($id, $columns = ['*'])
 * @method VendorStockItems first($columns = ['*'])
*/
class VendorStockItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'vendor_stock_id',
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
        'description',
        'actual_image',
        'is_deleted',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return VendorStockItems::class;
    }
}
