<?php

namespace App\Repositories;

use App\Models\DeliveryTips;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class DeliveryTipsRepository
 * @package App\Repositories
 * @version May 05, 2022, 12:22 pm UTC
 *
 * @method DeliveryTips findWithoutFail($id, $columns = ['*'])
 * @method DeliveryTips find($id, $columns = ['*'])
 * @method DeliveryTips first($columns = ['*'])
*/
class DeliveryTipsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'order_id',
        'amount'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DeliveryTips::class;
    }
}
