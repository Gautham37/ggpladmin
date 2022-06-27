<?php
namespace App\Repositories;

use App\Models\SupplyPoints;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class SupplyPointsRepository
 * @package App\Repositories
 *
 * @method SupplyPoints findWithoutFail($id, $columns = ['*'])
 * @method SupplyPoints find($id, $columns = ['*'])
 * @method SupplyPoints first($columns = ['*'])
 */
class SupplyPointsRepository extends BaseRepository
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'active',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SupplyPoints::class;
    }

}