<?php

namespace App\Repositories;

use App\Models\SalesInvoice;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class SalesInvoiceRepository
 * @package App\Repositories
 *
 * @method Category findWithoutFail($id, $columns = ['*'])
 * @method Category find($id, $columns = ['*'])
 * @method Category first($columns = ['*'])
*/
class SalesInvoiceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'quote_id',
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
        return SalesInvoice::class;
    }
}
