<?php

namespace App\Repositories;

use App\Models\ExpenseItems;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ExpenseItemsRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method ExpenseItems findWithoutFail($id, $columns = ['*'])
 * @method ExpenseItems find($id, $columns = ['*'])
 * @method ExpenseItems first($columns = ['*'])
*/
class ExpenseItemsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'expense_id',
        'name',
        'quantity',
        'rate',
        'amount',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ExpenseItems::class;
    }
}
