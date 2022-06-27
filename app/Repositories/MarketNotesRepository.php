<?php

namespace App\Repositories;

use App\Models\MarketNotes;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class MarketNotesRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method MarketNotes findWithoutFail($id, $columns = ['*'])
 * @method MarketNotes find($id, $columns = ['*'])
 * @method MarketNotes first($columns = ['*'])
 */
class MarketNotesRepository extends BaseRepository implements CacheableInterface
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'notes',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MarketNotes::class;
    }

}