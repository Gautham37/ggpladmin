<?php

namespace App\Repositories;

use App\Models\DeliveryTrack;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class DeliveryTrackRepository
 * @package App\Repositories
 * @version May 05, 2022, 12:22 pm UTC
 *
 * @method DeliveryTrack findWithoutFail($id, $columns = ['*'])
 * @method DeliveryTrack find($id, $columns = ['*'])
 * @method DeliveryTrack first($columns = ['*'])
*/
class DeliveryTrackRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'type',
        'category',
        'order_id',
        'sales_invoice_id',
        'status',
        'active',
        'notes',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DeliveryTrack::class;
    }
}
