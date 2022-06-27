<?php
namespace App\Repositories;

use App\Models\TransactionTrack;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class TransactionRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method TransactionTrack findWithoutFail($id, $columns = ['*'])
 * @method TransactionTrack find($id, $columns = ['*'])
 * @method TransactionTrack first($columns = ['*'])
 */
class TransactionRepository extends BaseRepository implements CacheableInterface
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'sales_invoice_id',
        'sales_return_id',
        'purchase_invoice_id',
        'purchase_return_id',
        'payment_in_id',
        'payment_out_id',
        'order_id',
        'category',
        'type',
        'date',
        'market_id',
        'amount',
        'created_by',
        'updated_by'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TransactionTrack::class;
    }
    
}
