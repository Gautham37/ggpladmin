<?php

namespace App\Repositories;

use App\Models\DeliveryZones;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class DeliveryZonesRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method DeliveryZones findWithoutFail($id, $columns = ['*'])
 * @method DeliveryZones find($id, $columns = ['*'])
 * @method DeliveryZones first($columns = ['*'])
*/
class DeliveryZonesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'zone',
        'distance',
        'delivery_charge',
        'minimum_order'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DeliveryZones::class;
    }
}