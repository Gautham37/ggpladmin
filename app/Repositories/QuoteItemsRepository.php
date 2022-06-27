<?php

namespace App\Repositories;

use App\Models\QuoteItems;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class QuoteItemsRepository
 * @package App\Repositories
 *
 * @method QuoteItems findWithoutFail($id, $columns = ['*'])
 * @method QuoteItems find($id, $columns = ['*'])
 * @method QuoteItems first($columns = ['*'])
*/
class QuoteItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'quote_id',
        'product_id',
        'product_name',
        'product_hsn_code',
        'mrp',
        'quantity',
        'unit',
        'unit_price',
        'discount',
        'discount_amount',
        'tax',
        'tax_amount',
        'amount',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QuoteItems::class;
    }
}
