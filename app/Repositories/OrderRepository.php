<?php

namespace App\Repositories;

use App\Models\Order;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class OrderRepository
 * @package App\Repositories
 * @version August 31, 2019, 11:11 am UTC
 *
 * @method Order findWithoutFail($id, $columns = ['*'])
 * @method Order find($id, $columns = ['*'])
 * @method Order first($columns = ['*'])
*/
class OrderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order_code',
        'refund_order_code',
        'user_id',
        'order_status_id',
        'tax',
        'delivery_fee',
        'delivery_distance',
        'redeem_amount',
        'coupon_amount',
        'contribution_amount',
        'order_amount',
        'hint',
        'active',
        'driver_id',
        'delivery_address_id',
        'payment_id',
        'is_deleted',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }
}
