<?php

namespace App\Repositories;

use App\Models\Subcategory;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Interface SubcategoryRepositoryRepository.
 *
 * @package namespace App\Repositories;
 */
class SubcategoryRepository extends BaseRepository
{
   /**
     * @var array
     */
    protected $fieldSearchable = [
        'department_id',
        'category_id',
        'name',
        'short_name',
        'description',
        'active',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Subcategory::class;
    }
}
