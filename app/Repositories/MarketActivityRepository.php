<?php

namespace App\Repositories;

use App\Models\MarketActivity;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class MarketActivityRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method MarketActivity findWithoutFail($id, $columns = ['*'])
 * @method MarketActivity find($id, $columns = ['*'])
 * @method MarketActivity first($columns = ['*'])
 */
class MarketActivityRepository extends BaseRepository implements CacheableInterface
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'action',
        'notes',
        'assign_to',
        'status',
        'priority',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MarketActivity::class;
    }

}