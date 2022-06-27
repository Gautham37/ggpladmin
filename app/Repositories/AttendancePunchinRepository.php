<?php

namespace App\Repositories;

use App\Models\AttendancePunchin;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class AttendancePunchinRepository
 * @package App\Repositories
 * @version December 07, 2021, 07:50 am UTC
 *
 * @method Role findWithoutFail($id, $columns = ['*'])
 * @method Role find($id, $columns = ['*'])
 * @method Role first($columns = ['*'])
*/
class AttendancePunchinRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'attendance_id',
        'punch_time',
        'punch_type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AttendancePunchin::class;
    }
}