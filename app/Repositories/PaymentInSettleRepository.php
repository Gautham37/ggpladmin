<?php

namespace App\Repositories;

use App\Models\PaymentInSettle;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PaymentInSettleRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:39 pm UTC
 *
 * @method Payment findWithoutFail($id, $columns = ['*'])
 * @method Payment find($id, $columns = ['*'])
 * @method Payment first($columns = ['*'])
*/
class PaymentInSettleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'payment_in_id',
        'settle_type',
        'amount',
        'sales_invoice_id',
        'purchase_return_id',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PaymentInSettle::class;
    }
}
