<?php

namespace App\Repositories;

use App\Models\Complaints;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Interface SubcategoryRepositoryRepository.
 *
 * @package namespace App\Repositories;
 */
class ComplaintsRepository extends BaseRepository
{
   /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'order_id',
        'name',
        'email',
        'phone',
        'complaints',
        'staff_id',
        'feedback',
        'staff_members',
        'option_type',
        'deduction_staff_id',
        'deduction_amount',
        'free_order_id',
        'refund_order_code',
        'status',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Complaints::class;
    }
}
