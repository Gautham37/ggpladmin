<?php

namespace App\Repositories;

use App\Models\LoyalityPointUsage;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class LoyalityPointUsageRepository
 * @package App\Repositories
 * @version September 4, 2019, 3:38 pm UTC
 *
 * @method LoyalityPointUsage findWithoutFail($id, $columns = ['*'])
 * @method LoyalityPointUsage find($id, $columns = ['*'])
 * @method LoyalityPointUsage first($columns = ['*'])
*/
class LoyalityPointUsageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'order_id',
        'points',
        'amount',
        'order_type',
        'sales_invoice_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return LoyalityPointUsage::class;
    }
}
