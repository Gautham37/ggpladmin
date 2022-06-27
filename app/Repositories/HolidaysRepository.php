<?php

namespace App\Repositories;

use App\Models\Holidays;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class HolidaysRepository
 * @package App\Repositories
 * @version December 07, 2021, 07:50 am UTC
 *
 * @method Role findWithoutFail($id, $columns = ['*'])
 * @method Role find($id, $columns = ['*'])
 * @method Role first($columns = ['*'])
*/
class HolidaysRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'date',
        'occassion'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Holidays::class;
    }
}