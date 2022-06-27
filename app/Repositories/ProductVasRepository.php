<?php

namespace App\Repositories;

use App\Models\ProductVas;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProductVasRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method Category findWithoutFail($id, $columns = ['*'])
 * @method Category find($id, $columns = ['*'])
 * @method Category first($columns = ['*'])
*/
class ProductVasRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'vas_id',
        'product_id',
        'price',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductVas::class;
    }
}
