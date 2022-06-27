<?php

namespace App\Repositories;

use App\Models\ProductTastes;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProductTastesRepository
 * @package App\Repositories
 * @version September 4, 2019, 3:38 pm UTC
 *
 * @method Cart findWithoutFail($id, $columns = ['*'])
 * @method Cart find($id, $columns = ['*'])
 * @method Cart first($columns = ['*'])
*/
class ProductTastesRepository extends BaseRepository
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
        return ProductTastes::class;
    }
}
