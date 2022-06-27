<?php

namespace App\Repositories;

use App\Models\ShortLink;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ShortLinkRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method Category findWithoutFail($id, $columns = ['*'])
 * @method Category find($id, $columns = ['*'])
 * @method Category first($columns = ['*'])
*/
class ShortLinkRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'code',
        'link'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ShortLink::class;
    }
}
