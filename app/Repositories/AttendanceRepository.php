<?php

namespace App\Repositories;

use App\Models\Attendance;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class AttendanceRepository
 * @package App\Repositories
 * @version December 07, 2021, 07:50 am UTC
 *
 * @method Role findWithoutFail($id, $columns = ['*'])
 * @method Role find($id, $columns = ['*'])
 * @method Role first($columns = ['*'])
*/
class AttendanceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'clock_in_time',
        'clock_out_time',
        'clock_in_ip',
        'clock_out_ip',
        'working_from',
        'late',
        'half_day',
        'attendance_type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Attendance::class;
    }
}