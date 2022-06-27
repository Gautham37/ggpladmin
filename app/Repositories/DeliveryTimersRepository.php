<?php

namespace App\Repositories;

use App\Models\DeliveryTimers;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class DeliveryTimersRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method DeliveryTimers findWithoutFail($id, $columns = ['*'])
 * @method DeliveryTimers find($id, $columns = ['*'])
 * @method DeliveryTimers first($columns = ['*'])
*/
class DeliveryTimersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'driver_id',
        'clock_in',
        'clock_out',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DeliveryTimers::class;
    }
}
