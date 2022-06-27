<?php

namespace App\Repositories;

use App\Models\SupplierRequest;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class SupplierRequestRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method Category findWithoutFail($id, $columns = ['*'])
 * @method Category find($id, $columns = ['*'])
 * @method Category first($columns = ['*'])
*/
class SupplierRequestRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'sr_code',
        'sr_date',
        'sr_valid_date',
        'market_id',
        'sr_taxable_amount',
        'sr_notes',
        'purchase_invoice_id',
        'sr_status',
        'sr_driver'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SupplierRequest::class;
    }
}
