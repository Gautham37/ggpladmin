<?php

namespace App\Repositories;

use App\Models\ProductsRefund;
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
class ProductsRefundRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'location',
        'comments',
        'reason',
        'user_id',
        'product_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductsRefund::class;
    }
}
