<?php

namespace App\Repositories;

use App\Models\PaymentOutSettle;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PaymentOutSettleRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:39 pm UTC
 *
 * @method PaymentOutSettle findWithoutFail($id, $columns = ['*'])
 * @method PaymentOutSettle find($id, $columns = ['*'])
 * @method PaymentOutSettle first($columns = ['*'])
*/
class PaymentOutSettleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'payment_in_id',
        'settle_type',
        'amount',
        'sales_return_id',
        'purchase_invoice_id',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PaymentOutSettle::class;
    }
}
