<?php

namespace App\Repositories;

use App\Models\StockTake;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Interface StockTakeRepository.
 *
 * @package namespace App\Repositories;
 */
class StockTakeRepository extends BaseRepository
{
   /**
     * @var array
     */
    protected $fieldSearchable = [
        'code',
        'date',
        'type',
        'notes',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return StockTake::class;
    }
}
