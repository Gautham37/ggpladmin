<?php

namespace App\Repositories;

use App\Models\WebsiteTestimonials;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CategoryRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method WebsiteTestimonials findWithoutFail($id, $columns = ['*'])
 * @method WebsiteTestimonials find($id, $columns = ['*'])
 * @method WebsiteTestimonials first($columns = ['*'])
*/
class WebsiteTestimonialsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'rating'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return WebsiteTestimonials::class;
    }
}
