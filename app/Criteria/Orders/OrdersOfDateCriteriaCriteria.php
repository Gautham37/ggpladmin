<?php

namespace App\Criteria\Orders;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class OrdersOfDateCriteriaCriteria.
 *
 * @package namespace App\Criteria\Orders;
 */
class OrdersOfDateCriteriaCriteria implements CriteriaInterface
{   
    /**
     * @var array
     */
    private $request;

    /**
     * OrdersOfStatusesCriteria constructor.
     * @param array $request
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
        if (!$this->request->has('date')) {
            return $model;
        } else {
            return $model->whereDate('created_at', $this->request->get('date'));
        }
    }
}
