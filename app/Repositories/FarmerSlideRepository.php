<?php

namespace App\Repositories;

use App\Models\FarmerSlide;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class FarmerSlideRepository
 * @package App\Repositories
 * @version September 1, 2020, 7:27 pm UTC
 *
 * @method Slide findWithoutFail($id, $columns = ['*'])
 * @method Slide find($id, $columns = ['*'])
 * @method Slide first($columns = ['*'])
*/
class FarmerSlideRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order',
        'text',
        'button',
        'text_position',
        'text_color',
        'button_color',
        'background_color',
        'indicator_color',
        'image_fit',
        'enabled'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FarmerSlide::class;
    }
}
