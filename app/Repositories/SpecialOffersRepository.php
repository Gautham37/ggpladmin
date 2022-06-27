<?php

namespace App\Repositories;

use App\Models\SpecialOffers;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class SpecialOffersRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method WebsiteTestimonials findWithoutFail($id, $columns = ['*'])
 * @method WebsiteTestimonials find($id, $columns = ['*'])
 * @method WebsiteTestimonials first($columns = ['*'])
*/
class SpecialOffersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'description'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SpecialOffers::class;
    }
}
