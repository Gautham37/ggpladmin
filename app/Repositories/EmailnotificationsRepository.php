<?php

namespace App\Repositories;

use App\Models\Emailnotifications;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class EmailnotificationsRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method Emailnotifications findWithoutFail($id, $columns = ['*'])
 * @method Emailnotifications find($id, $columns = ['*'])
 * @method Emailnotifications first($columns = ['*'])
*/
class EmailnotificationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'subject',
        'message',
        'party_type_id',
        'party_sub_type_id',
        'type',
        'schedule_date',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Emailnotifications::class;
    }
}
