<?php

namespace App\Repositories;

use App\Models\ComplaintsComments;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Interface SubcategoryRepositoryRepository.
 *
 * @package namespace App\Repositories;
 */
class ComplaintsCommentsRepository extends BaseRepository
{
   /**
     * @var array
     */
    protected $fieldSearchable = [
        'complaints_id',
        'staff_id',
        'comments',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ComplaintsComments::class;
    }
}
