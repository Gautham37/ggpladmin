<?php

namespace App\Repositories;

use App\Models\PurchaseInvoice;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PurchaseInvoiceRepository
 * @package App\Repositories
 *
 * @method PurchaseInvoice findWithoutFail($id, $columns = ['*'])
 * @method PurchaseInvoice find($id, $columns = ['*'])
 * @method PurchaseInvoice first($columns = ['*'])
*/
class PurchaseInvoiceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'purchase_order_id',
        'vendor_stock_id',
        'code',
        'date',
        'valid_date',
        'sub_total',
        'additional_charge_description',
        'additional_charge',
        'delivery_charge',
        'discount_total',
        'tax_total',
        'round_off',
        'total',
        'cash_paid',
        'payment_method',
        'amount_due',
        'notes',
        'terms_and_conditions',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PurchaseInvoice::class;
    }
}
