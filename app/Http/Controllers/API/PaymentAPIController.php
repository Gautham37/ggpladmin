<?php

namespace App\Http\Controllers\API;


use App\Models\Payment;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Response;
use Prettus\Repository\Exceptions\RepositoryException;
use App\Repositories\TransactionRepository;
use Flash;
use DB;

/**
 * Class PaymentController
 * @package App\Http\Controllers\API
 */
class PaymentAPIController extends Controller
{
    /** @var  PaymentRepository */
    private $paymentRepository;

    /** @var  TransactionRepository */
    private $transactionRepository;

    public function __construct(PaymentRepository $paymentRepo, TransactionRepository $transactionRepo)
    {
        $this->paymentRepository = $paymentRepo;
        $this->transactionRepository = $transactionRepo;
    }

    /**
     * Display a listing of the Payment.
     * GET|HEAD /payments
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->paymentRepository->pushCriteria(new RequestCriteria($request));
            $this->paymentRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $payments = $this->paymentRepository->all();

        return $this->sendResponse($payments->toArray(), 'Payments retrieved successfully');
    }

    /**
     * Display the specified Payment.
     * GET|HEAD /payments/{id}
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var Payment $payment */
        if (!empty($this->paymentRepository)) {
            $payment = $this->paymentRepository->findWithoutFail($id);
        }

        if (empty($payment)) {
            return $this->sendError('Payment not found');
        }

        return $this->sendResponse($payment->toArray(), 'Payment retrieved successfully');
    }

    /*public function byMonth(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $payments = [];
        if (!empty($this->paymentRepository)) {
            $payments = $this->paymentRepository
                            ->whereDate('created_at', '>=', $start_date)
                            ->whereDate('created_at', '<=', $end_date)
                            ->orderBy("created_at",'asc')->get()->map(function ($row) {
                $row['month'] = $row['created_at']->format('M');
                return $row;
            })->groupBy('month')->map(function ($row) {
                return $row->sum('price');
            });
        }
        return $this->sendResponse([array_values($payments->toArray()),array_keys($payments->toArray())], 'Payment retrieved successfully');
    }*/

    public function byMonth(Request $request)
    {   
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $payments = [];
        if (!empty($this->paymentRepository)) {
            $payments = $this->paymentRepository
                            ->whereDate('created_at', '>=', $start_date)
                            ->whereDate('created_at', '<=', $end_date)
                            ->orderBy("created_at",'asc')->get()->map(function ($row) {
                $row['month'] = $row['created_at']->format('M');
                return $row;
            })->groupBy('month')->map(function ($row) {
                return $row->sum('price');
            });
        }

        
        $sales = [];
        $sales = $this->transactionRepository
                        ->where('transaction_track_category','sales')
                        ->where('transaction_track_type','debit')
                        ->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date)
                        ->get()->map(function ($row) {
                            $row['month'] = $row['created_at']->format('M');
                            return $row;
                        })->groupBy('month')->map(function ($row) {
                            return $row->sum('transaction_track_amount');
                        });                    

        return response()->json([
            'success' => true,
            'data'    => [array_values($payments->toArray()),array_keys($payments->toArray()),array_values($sales->toArray())],
            'message' =>'Payment retrieved successfully'
        ]);
    }
    
}
