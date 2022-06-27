<?php

namespace App\Repositories;

use App\Models\LeaveTypes;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class LeaveTypesRepository
 * @package App\Repositories
 * @version December 07, 2021, 07:50 am UTC
 *
 * @method Role findWithoutFail($id, $columns = ['*'])
 * @method Role find($id, $columns = ['*'])
 * @method Role first($columns = ['*'])
*/
class LeaveTypesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type_name',
        'color',
        'no_of_leaves',
        'paid'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return LeaveTypes::class;
    }
}