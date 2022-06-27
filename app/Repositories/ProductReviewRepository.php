<?php

namespace App\Repositories;

use App\Models\ProductReview;
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
class ProductReviewRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'review',
        'rate',
        'user_id',
        'product_id',
        'active'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductReview::class;
    }
}
