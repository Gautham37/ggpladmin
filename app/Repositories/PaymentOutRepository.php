<?php

namespace App\Repositories;

use App\Models\PaymentOut;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PaymentOutRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:39 pm UTC
 *
 * @method PaymentOut findWithoutFail($id, $columns = ['*'])
 * @method PaymentOut find($id, $columns = ['*'])
 * @method PaymentOut first($columns = ['*'])
*/
class PaymentOutRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'code',
        'date',
        'payment_method',
        'notes',
        'settle_invoice',
        'total',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PaymentOut::class;
    }
}
