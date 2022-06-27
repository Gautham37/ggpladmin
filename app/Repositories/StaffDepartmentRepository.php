<?php

namespace App\Repositories;

use App\Models\CustomerLevels;
use App\Models\PartyStreams;
use App\Models\StaffDepartment;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method Category findWithoutFail($id, $columns = ['*'])
 * @method Category find($id, $columns = ['*'])
 * @method Category first($columns = ['*'])
 */
class StaffDepartmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'is_active',
    ];


    /**
     * Configure the Model
     **/
    public function model()
    {
        return StaffDepartment::class;
    }
}
