<?php

namespace App\Repositories;

use App\Models\PaymentFor;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PaymentForRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:39 pm UTC
 *
 * @method PaymentFor findWithoutFail($id, $columns = ['*'])
 * @method PaymentFor find($id, $columns = ['*'])
 * @method PaymentFor first($columns = ['*'])
*/
class PaymentForRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'active'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PaymentFor::class;
    }
}
