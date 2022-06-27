<?php

namespace App\Repositories;

use App\Models\PurchaseReturn;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PurchaseReturnRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method PurchaseReturn findWithoutFail($id, $columns = ['*'])
 * @method PurchaseReturn find($id, $columns = ['*'])
 * @method PurchaseReturn first($columns = ['*'])
*/
class PurchaseReturnRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'purchase_invoice_id',
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
        return PurchaseReturn::class;
    }
}
