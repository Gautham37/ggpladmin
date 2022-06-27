<?php

namespace App\Repositories;

use App\Models\WastageDisposal;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class WastageDisposalRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method WastageDisposal findWithoutFail($id, $columns = ['*'])
 * @method WastageDisposal find($id, $columns = ['*'])
 * @method WastageDisposal first($columns = ['*'])
 */
class WastageDisposalRepository extends BaseRepository implements CacheableInterface
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'inventory_id',
        'product_id',
        'type',
        'market_id',
        'quantity',
        'unit',
        'description',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return WastageDisposal::class;
    }
}
