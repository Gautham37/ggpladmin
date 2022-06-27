<?php

namespace App\Repositories;

use App\Models\ValueAddedServiceAffiliated;
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
class ValueAddedServiceAffiliatedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ValueAddedServiceAffiliated::class;
    }
}
