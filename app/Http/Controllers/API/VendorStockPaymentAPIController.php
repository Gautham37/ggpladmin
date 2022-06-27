<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\VendorStock;
use App\Repositories\VendorStockRepository;
use App\Repositories\VendorStockItemsRepository;
use App\Repositories\TransactionRepository;
use Flash;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserRepository;

/**
 * Class VendorStockPaymentAPIController
 * @package App\Http\Controllers\API
 */
class VendorStockPaymentAPIController extends Controller
{
    /** @var  VendorStockRepository */
    private $vendorStockRepository;
    private $vendorStockItemsRepository;
    private $transactionRepository;
    private $userRepository;

    public function __construct(VendorStockRepository $vendorStockRepo, VendorStockItemsRepository $vendorStockItemsRepo, TransactionRepository $transactionRepo, UserRepository $userRepository)
    {
        $this->vendorStockRepository        = $vendorStockRepo;
        $this->vendorStockItemsRepository   = $vendorStockItemsRepo;
        $this->transactionRepository        = $transactionRepo;
        $this->userRepository               = $userRepository;
    }

    /**
     * Display a listing of the DeliveryAddress.
     * GET|HEAD /vendor_stocks
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->transactionRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $transactions = $this->transactionRepository
                             ->where('category','purchase')
                             ->where('type','debit')   
                             ->where('market_id',auth()->user()->market->id)
                             ->get();

        return $this->sendResponse($transactions->toArray(), 'Transactions retrieved successfully');
    }

    /**
     * Display the specified DeliveryAddress.
     * GET|HEAD /vendor_stocks/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (!empty($this->transactionRepository)) {
            $transaction = $this->transactionRepository
                                ->findWithoutFail($id);
        }

        if (empty($transaction)) {
            return $this->sendError('Transaction not found');
        }

        return $this->sendResponse($transaction->toArray(), 'Transaction retrieved successfully');
    }

    
    
}