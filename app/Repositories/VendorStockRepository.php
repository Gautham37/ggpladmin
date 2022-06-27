<?php

namespace App\Repositories;

use App\Models\VendorStock;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class VendorStockRepository
 * @package App\Repositories
 *
 * @method VendorStock findWithoutFail($id, $columns = ['*'])
 * @method VendorStock find($id, $columns = ['*'])
 * @method VendorStock first($columns = ['*'])
*/
class VendorStockRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'code',
        'date',
        'valid_date',
        'sub_total',
        'additional_charge_description',
        'additional_charge',
        'pickup_distance',
        'pickup_charge',
        'discount_total',
        'tax_total',
        'round_off',
        'total',
        'notes',
        'terms_and_conditions',
        'status',
        'is_deleted',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return VendorStock::class;
    }
}
