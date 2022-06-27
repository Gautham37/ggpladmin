<?php

namespace App\Repositories;

use App\Models\StaffDesignation;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class StaffDesignationRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method StaffDesignation findWithoutFail($id, $columns = ['*'])
 * @method StaffDesignation find($id, $columns = ['*'])
 * @method StaffDesignation first($columns = ['*'])
 */

class StaffDesignationRepository extends BaseRepository
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
        return StaffDesignation::class;
    }
}
