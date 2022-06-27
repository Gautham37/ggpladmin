<?php

namespace App\Repositories;

use App\Models\PartySubTypes;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Interface PartySubTypesRepositoryRepository.
 *
 * @package namespace App\Repositories;
 */
class PartySubTypesRepository extends BaseRepository
{
   /**
     * @var array
     */
    protected $fieldSearchable = [
        'party_type_id',
        'role_id',
        'name',
        'prefix_value',
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
        return PartySubTypes::class;
    }
}
