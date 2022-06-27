<?php

namespace App\Repositories;

use App\Models\Quotes;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class QuotesRepository
 * @package App\Repositories
 *
 * @method Quotes findWithoutFail($id, $columns = ['*'])
 * @method Quotes find($id, $columns = ['*'])
 * @method Quotes first($columns = ['*'])
*/
class QuotesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'market_id',
        'code',
        'date',
        'valid_date',
        'sub_total',
        'additional_charge_description',
        'additional_charge',
        'delivery_charge',
        'discount_total',
        'tax_total',
        'round_off',
        'total',
        'notes',
        'terms_and_conditions',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Quotes::class;
    }
}
