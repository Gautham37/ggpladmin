<?php

namespace App\Repositories;

use App\Models\ProductSeasons;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProductSeasonsRepository
 * @package App\Repositories
 * @version September 4, 2019, 3:38 pm UTC
 *
 * @method Cart findWithoutFail($id, $columns = ['*'])
 * @method Cart find($id, $columns = ['*'])
 * @method Cart first($columns = ['*'])
*/
class ProductSeasonsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'active'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductSeasons::class;
    }
}
