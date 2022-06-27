<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\PurchaseInvoice;
use App\Repositories\PurchaseInvoiceRepository;
use Flash;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserRepository;

/**
 * Class PurchaseInvoiceAPIController
 * @package App\Http\Controllers\API
 */
class PurchaseInvoiceAPIController extends Controller
{
    /** @var  PurchaseInvoiceRepository */
    private $purchaseInvoiceRepository;
    private $userRepository;

    public function __construct(PurchaseInvoiceRepository $purchaseInvoiceRepo, UserRepository $userRepository)
    {
        $this->purchaseInvoiceRepository = $purchaseInvoiceRepo;
        $this->userRepository            = $userRepository;
    }

    /**
     * Display a listing of the PurchaseInvoice.
     * GET|HEAD /purchase_invoice
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->purchaseInvoiceRepository->pushCriteria(new RequestCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $purchase_invoice = $this->purchaseInvoiceRepository
                                 ->with('market')
                                 ->with('paymentmethod')
                                 ->with('items')
                                 ->with('amountsettle')
                                 ->with('purchasereturn')
                                 ->where('market_id',auth()->user()->market->id)
                                 ->get();

        return $this->sendResponse($purchase_invoice->toArray(), 'Purchase Invoice retrieved successfully');
    }

    /**
     * Display the specified PurchaseInvoice.
     * GET|HEAD /purchase_invoice/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (!empty($this->purchaseInvoiceRepository)) {
            $purchase_invoice = $this->purchaseInvoiceRepository
                                     ->with('market')
                                     ->with('paymentmethod')
                                     ->with('items')
                                     ->with('amountsettle')
                                     ->with('purchasereturn')
                                     ->findWithoutFail($id);
        }

        if (empty($purchase_invoice)) {
            return $this->sendError('Purchase Invoice not found');
        }

        return $this->sendResponse($purchase_invoice->toArray(), 'Purchase Invoice retrieved successfully');
    }
    
   
}