<?php

namespace App\Repositories;

use App\Models\Staffs;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Interface SubcategoryRepositoryRepository.
 *
 * @package namespace App\Repositories;
 */
class StaffsRepository extends BaseRepository
{
   /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'department_id',
        'designation_id',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'pincode',
        'description',
        'date_of_joining',
        'date_of_relieving',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Staffs::class;
    }
}
