<?php

namespace App\Repositories;

use App\Models\DeliveryAddress;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class DeliveryAddressRepository
 * @package App\Repositories
 * @version December 6, 2019, 1:57 pm UTC
 *
 * @method DeliveryAddress findWithoutFail($id, $columns = ['*'])
 * @method DeliveryAddress find($id, $columns = ['*'])
 * @method DeliveryAddress first($columns = ['*'])
*/
class DeliveryAddressRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'street_no',
        'street_type',
        'landmark_1',
        'landmark_2',
        'address_line_1',
        'address_line_2',
        'town',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'is_default',
        'notes',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DeliveryAddress::class;
    }
    
    public function initIsDefault($userId){
        DeliveryAddress::where("user_id",$userId)->where("is_default",true)->update(["is_default"=>false]);
    }
}
