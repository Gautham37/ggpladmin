<?php

namespace App\Repositories;

use App\Models\ProductStatus;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class QualityGradeRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method Category findWithoutFail($id, $columns = ['*'])
 * @method Category find($id, $columns = ['*'])
 * @method Category first($columns = ['*'])
*/
class ProductStatusRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'status',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductStatus::class;
    }
}
