<?php

namespace App\Repositories;

use App\Models\CustomerFarmerReviews;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CustomerFarmerReviewsRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method ProductReview findWithoutFail($id, $columns = ['*'])
 * @method ProductReview find($id, $columns = ['*'])
 * @method ProductReview first($columns = ['*'])
*/
class CustomerFarmerReviewsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'driver_id',
        'review',
        'rate',
        'user_id',
        'option_type',
        'option_id',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CustomerFarmerReviews::class;
    }
}
