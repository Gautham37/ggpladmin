<?php

namespace App\Repositories;

use App\Models\DeliveryChallan;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method Category findWithoutFail($id, $columns = ['*'])
 * @method Category find($id, $columns = ['*'])
 * @method Category first($columns = ['*'])
*/
class DeliveryChallanRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'delivery_code',
        'delivery_date',
        'delivery_valid_date',
        'market_id',
        'delivery_taxable_amount',
        'delivery_additional_charge_description',
        'delivery_additional_charge',
        'delivery_discount_percent',
        'delivery_discount_amount',
        'delivery_gst_percent',
        'delivery_sgst_amount',
        'delivery_cgst_amount',
        'delivery_igst_amount',
        'delivery_round_off',
        'delivery_total_amount',
        'delivery_cash_paid',
        'delivery_payment_method',
        'delivery_balance_amount',
        'delivery_notes',
        'delivery_terms_and_conditions'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DeliveryChallan::class;
    }
}
