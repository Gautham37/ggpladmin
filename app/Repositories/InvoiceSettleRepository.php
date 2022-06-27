<?php
/**
 * File name: InvoiceSettleRepository.php
 * Last modified: 2020.06.07 at 07:02:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\Repositories;

use App\Models\InvoiceSettle;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class ProductRepository
 * @package App\Repositories
 * @version August 29, 2019, 9:38 pm UTC
 *
 * @method Product findWithoutFail($id, $columns = ['*'])
 * @method Product find($id, $columns = ['*'])
 * @method Product first($columns = ['*'])
 */
class InvoiceSettleRepository extends BaseRepository implements CacheableInterface
{

    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'payment_out_id',
        'invoice_settle_type',
        'invoice_settle_invoice_id',
        'invoice_settle_date',
        'invoice_settle_amount'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return InvoiceSettle::class;
    }

    /**
     * get my products
     **/
    public function myProducts()
    {
        return Product::join("user_markets", "user_markets.market_id", "=", "products.market_id")
            ->where('user_markets.user_id', auth()->id())->get();
    }

    public function groupedByMarkets()
    {
        $products = [];
        foreach ($this->all() as $model) {
            if(!empty($model->market)){
            $products[$model->market->name][$model->id] = $model->name;
        }
        }
        return $products;
    }
}
