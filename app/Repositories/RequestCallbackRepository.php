<?php

namespace App\Repositories;

use App\Models\RequestCallback;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class RequestCallbackRepository
 * @package App\Repositories
 * @version December 07, 2021, 07:50 am UTC
 *
 * @method Role findWithoutFail($id, $columns = ['*'])
 * @method Role find($id, $columns = ['*'])
 * @method Role first($columns = ['*'])
*/
class RequestCallbackRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'name',
        'mobile',
        'email',
        'message',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RequestCallback::class;
    }
}