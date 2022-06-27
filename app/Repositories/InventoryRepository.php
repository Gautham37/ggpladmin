<?php

namespace App\Repositories;

use App\Models\InventoryTrack;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class InventoryRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method Product findWithoutFail($id, $columns = ['*'])
 * @method Product find($id, $columns = ['*'])
 * @method Product first($columns = ['*'])
 */
class InventoryRepository extends BaseRepository implements CacheableInterface
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'market_id',
        'category',
        'type',
        'date',
        'quantity',
        'unit',
        'description',
        'usage',
        'sales_invoice_item_id',
        'sales_return_item_id',
        'purchase_invoice_item_id',
        'purchase_return_item_id',
        'product_order_item_id',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return InventoryTrack::class;
    }

}
