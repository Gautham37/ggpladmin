<?php

namespace App\Repositories;

use App\Models\DriversTimers;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class DriversTimersRepository
 * @package App\Repositories
 * @version March 25, 2020, 9:48 am UTC
 *
 * @method DriversTimers findWithoutFail($id, $columns = ['*'])
 * @method DriversTimers find($id, $columns = ['*'])
 * @method DriversTimers first($columns = ['*'])
*/
class DriversTimersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'clock_in',
        'clock_out',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DriversTimers::class;
    }
}
