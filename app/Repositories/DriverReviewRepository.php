<?php

namespace App\Repositories;

use App\Models\DriverReview;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProductReviewRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method ProductReview findWithoutFail($id, $columns = ['*'])
 * @method ProductReview find($id, $columns = ['*'])
 * @method ProductReview first($columns = ['*'])
*/
class DriverReviewRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'review',
        'rate',
        'driver_user_id',
        'order_id',
        'vendor_stock_id',
        'user_id',
        'active',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DriverReview::class;
    }
}
