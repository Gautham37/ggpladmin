<?php

namespace App\Repositories;

use App\Models\StockTakeItems;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Interface StockTakeItemsRepository.
 *
 * @package namespace App\Repositories;
 */
class StockTakeItemsRepository extends BaseRepository
{
   /**
     * @var array
     */
    protected $fieldSearchable = [
        'stock_take_id',
        'product_id',
        'product_name',
        'product_code',
        'current',
        'current_unit',
        'counted',
        'counted_unit',
        'missing',
        'missing_unit',
        'wastage',
        'wastage_unit',
        'notes',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return StockTakeItems::class;
    }
}
