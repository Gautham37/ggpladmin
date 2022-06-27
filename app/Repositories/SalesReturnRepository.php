<?php

namespace App\Repositories;

use App\Models\SalesReturn;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class SalesReturnRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method SalesReturn findWithoutFail($id, $columns = ['*'])
 * @method SalesReturn find($id, $columns = ['*'])
 * @method SalesReturn first($columns = ['*'])
*/
class SalesReturnRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'sales_invoice_id',
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
        return SalesReturn::class;
    }
}
