<?php

namespace App\Repositories;

use App\Models\SalesInvoiceItems;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class SalesInvoiceItemsRepository
 * @package App\Repositories
 *
 * @method Category findWithoutFail($id, $columns = ['*'])
 * @method Category find($id, $columns = ['*'])
 * @method Category first($columns = ['*'])
*/
class SalesInvoiceItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'sales_invoice_id',
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
        return SalesInvoiceItems::class;
    }
}
