<?php

namespace App\Repositories;

use App\Models\QualityGrade;
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
class QualityGradeRepository extends BaseRepository
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
        return QualityGrade::class;
    }
}
