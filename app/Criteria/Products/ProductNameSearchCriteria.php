<?php

namespace App\Criteria\Products;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ProductNameSearchCriteria.
 *
 * @package namespace App\Criteria\Products;
 */
class ProductNameSearchCriteria implements CriteriaInterface
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
        if (!$this->request->has('search')) {
            return $model;
        } else {
            $search = $this->request->get('search');
            return $model->where('name', 'like', "%$search%");
        }
    }
}
