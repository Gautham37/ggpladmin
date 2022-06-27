<?php

namespace App\Repositories;

use App\Models\Leaves;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class LeavesRepository
 * @package App\Repositories
 * @version December 07, 2021, 07:50 am UTC
 *
 * @method Role findWithoutFail($id, $columns = ['*'])
 * @method Role find($id, $columns = ['*'])
 * @method Role first($columns = ['*'])
*/
class LeavesRepository extends BaseRepository
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
        return Leaves::class;
    }
}