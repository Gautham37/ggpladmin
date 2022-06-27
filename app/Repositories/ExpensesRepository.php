<?php

namespace App\Repositories;

use App\Models\Expenses;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ExpensesRepository
 * @package App\Repositories
 * @version April 11, 2020, 1:57 pm UTC
 *
 * @method Expenses findWithoutFail($id, $columns = ['*'])
 * @method Expenses find($id, $columns = ['*'])
 * @method Expenses first($columns = ['*'])
*/
class ExpensesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'expense_category_id',
        'date',
        'payment_mode',
        'total_amount',
        'notes',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Expenses::class;
    }
}
