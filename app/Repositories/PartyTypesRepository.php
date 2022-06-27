<?php


namespace App\Repositories;

use App\Models\PartyTypes;
use Prettus\Repository\Eloquent\BaseRepository;


/**
 * Interface PartyTypesRepositoryRepository.
 *
 * @package namespace App\Repositories;
 */
class PartyTypesRepository extends BaseRepository
{
   /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return PartyTypes::class;
    }
}
