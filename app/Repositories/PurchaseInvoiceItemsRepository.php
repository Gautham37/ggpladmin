<?php

namespace App\Repositories;

use App\Models\PurchaseInvoiceItems;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PurchaseInvoiceItemsRepository
 * @package App\Repositories
 *
 * @method PurchaseInvoiceItems findWithoutFail($id, $columns = ['*'])
 * @method PurchaseInvoiceItems find($id, $columns = ['*'])
 * @method PurchaseInvoiceItems first($columns = ['*'])
*/
class PurchaseInvoiceItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'purchase_invoice_id',
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
        return PurchaseInvoiceItems::class;
    }
}
