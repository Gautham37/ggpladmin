<?php

namespace App\Repositories;

use App\Models\PaymentIn;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PaymentInRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:39 pm UTC
 *
 * @method Payment findWithoutFail($id, $columns = ['*'])
 * @method Payment find($id, $columns = ['*'])
 * @method Payment first($columns = ['*'])
*/
class PaymentInRepository extends BaseRepository
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
        return PaymentIn::class;
    }
}
