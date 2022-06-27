<?php

namespace App\Repositories;

use App\Models\PurchaseOrder;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PurchaseOrderRepository
 * @package App\Repositories
 *
 * @method PurchaseOrder findWithoutFail($id, $columns = ['*'])
 * @method PurchaseOrder find($id, $columns = ['*'])
 * @method PurchaseOrder first($columns = ['*'])
*/
class PurchaseOrderRepository extends BaseRepository
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
        'discount_total',
        'tax_total',
        'round_off',
        'total',
        'notes',
        'status',
        'terms_and_conditions',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PurchaseOrder::class;
    }
}
