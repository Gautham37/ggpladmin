<?php

namespace App\Criteria\Products;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ProductsOfPriceCriteria.
 *
 * @package namespace App\Criteria\Products;
 */
class ProductsOfPriceCriteria implements CriteriaInterface
{   
    /**
     * @var array
     */
    private $request;

    /**
     * ProductsOfFieldsCriteria constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (!$this->request->has('price_from') || !$this->request->has('price_to')) {
            return $model;
        } else {
            $price_from = $this->request->get('price_from');
            $price_to   = $this->request->get('price_to');
            return $model->whereBetween('price', [$price_from,$price_to]);
        }
    }
}
