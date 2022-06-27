<?php

namespace App\Repositories;

use App\Models\Charity;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CharityRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method Charity findWithoutFail($id, $columns = ['*'])
 * @method Charity find($id, $columns = ['*'])
 * @method Charity first($columns = ['*'])
*/
class CharityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'address_line_1',
        'address_line_2',
        'town',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'email',
        'mobile',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Charity::class;
    }
}
