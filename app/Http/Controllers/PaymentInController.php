<?php

namespace App\Http\Controllers;

use App\Models\PaymentIn;
use App\Criteria\Markets\MarketsOfManagerCriteria;
use App\DataTables\PaymentInDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePaymentInRequest;
use App\Http\Requests\UpdatePaymentInRequest;
use App\Repositories\PaymentModeRepository;
use App\Repositories\PaymentInRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\InvoiceSettleRepository;
use App\Repositories\PaymentInSettleRepository;
use App\Repositories\MarketRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DB;
use PDF;
use Carbon\Carbon;
use CustomHelper;
use App\Mail\PaymentInMail;
use Illuminate\Support\Facades\Input;

use Notification;
use App\Notifications\PaymentInNotification;

class PaymentInController extends Controller
{   
    /** @var  PaymentRepository */
    private $paymentInRepository;

    /** @var  PaymentInSettleRepository */
    private $paymentInSettleRepository;

    /* @var PaymentModeRepository */
    private $paymentModeRepository;

    /** @var  TransactionRepository */
    private $transactionRepository;

    /** @var  InvoiceSettleRepository */
    private $invoiceSettleRepository;

    /**
  * @var UserRepository
  */
    private $marketRepository;

    public function __construct(PaymentInRepository $paymentInRepo, MarketRepository $marketRepo, PaymentModeRepository $paymentMRepo, TransactionRepository $transactionRepo, InvoiceSettleRepository $invoiceSettleRepo, PaymentInSettleRepository $paymentInSettleRepo)
    {
        parent::__construct();
        $this->paymentInRepository          = $paymentInRepo;
        $this->paymentInSettleRepository    = $paymentInSettleRepo; 
        $this->marketRepository             = $marketRepo;
        $this->transactionRepository        = $transactionRepo;
        $this->invoiceSettleRepository      = $invoiceSettleRepo;
        $this->paymentModeRepository        = $paymentMRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaymentInDataTable $paymentInDataTable)
    {
        $payment_in = $paymentInDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $payment_in->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        return $payment_in->render('payment_in.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users  = $this->marketRepository->select('id', DB::raw("concat(name, ' ', mobile, ' ', code) as name"))->pluck('name', 'id');
        $users->prepend("Please Select",null);
        $payment_methods = $this->paymentModeRepository->pluck('name','id');

        $payment_in_no = setting('app_invoice_prefix').'-PAI-'.(autoIncrementId('payment_in'));

        return view('payment_in.create',compact('users','payment_methods','payment_in_no'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePaymentInRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by']  = auth()->user()->id;
            $payment_in           = $this->paymentInRepository->create($input);

            $payment_in->market->activity()->create([
                'market_id'  => $payment_in->market->id,
                'action'     => 'Payment In Created',
                'notes'      => 'Payment In '.$payment_in->code.' for '.number_format($payment_in->total,'2','.','').' Created',
                'status'     => 'completed',
                'created_by' => auth()->user()->id
            ]);

            //Transaction
                $this->addTransaction(
                    'payment_in',
                    'credit',
                    date('Y-m-d'),
                    $payment_in->market->id,
                    $payment_in->total,
                    'payment_in_id',
                    $payment_in->id
                );     
            //Transaction

            //Update Balance
                $this->partyBalanceUpdate($payment_in->market->id);
            //Update Balance  


            //Setttle Invoice Amount
                if(isset($input['amount']) && count(array_filter($input['amount'])) > 0) {
                    for($i=0; $i<count($input['amount']); $i++) {
                        if($input['amount'][$i] != null) {

                            $payment_in_settle = $this->paymentInSettleRepository->create([
                                'payment_in_id'             => $payment_in->id,
                                'settle_type'               => $input['settle_type'][$i],
                                'amount'                    => $input['amount'][$i],
                                $input['column_name'][$i]   => $input['invoice_id'][$i],
                                'created_by'                => auth()->user()->id
                            ]);

                            if($input['settle_type'][$i] == 'sales') {
                                $payment_in_settle->salesinvoice->update([
                                    'amount_due' => $payment_in_settle->salesinvoice->amount_due - $payment_in_settle->amount
                                ]);
                            } elseif($input['settle_type'][$i] == 'purchase') {
                                $payment_in_settle->purchasereturn->update([
                                    'amount_due' => $payment_in_settle->purchasereturn->amount_due - $payment_in_settle->amount
                                ]);
                            }    

                        }
                    }
                }    
            //Setttle Invoice Amount  

            $this->emailtoparty($payment_in->id);    

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('Save successfully',['operator' => __('Payment In')]));
        return redirect(route('paymentIn.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\paymentIn  $paymentIn
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {   
        $payment_in = $this->paymentInRepository
                           ->with('paymentmethod')
                           ->with('paymentinsettle')
                           ->with('paymentinsettle.salesinvoice')
                           ->with('paymentinsettle.purchasereturn')
                           ->findWithoutFail($id);
        if (empty($payment_in)) {
            Flash::error(__('Not Found',['operator' => __('Payment In')]));
            return redirect(route('paymentIn.index'));
        }
        if($request->ajax()) {
            return ['status' => 'success', 'data' => $payment_in];   
        }
        return view('payment_in.show',compact('payment_in'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\paymentIn  $paymentIn
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   

        $payment_in = $this->paymentInRepository->with('paymentinsettle')->with('paymentmethod')->findWithoutFail($id);
        if (empty($payment_in)) {
            Flash::error(__('Not Found',['operator' => __('Payment In')]));
            return redirect(route('paymentIn.index'));
        }

        $users  = $this->marketRepository->select('id', DB::raw("concat(name, ' ', mobile, ' ', code) as name"))->pluck('name', 'id');
        $users->prepend("Please Select",null);
        $payment_methods = $this->paymentModeRepository->pluck('name','id');

        return view('payment_in.edit',compact('users','payment_methods','payment_in'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\paymentIn  $paymentIn
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdatepaymentInRequest $request)
    {
        $payment_in_old = $this->paymentInRepository->with('paymentinsettle')->with('paymentmethod')->findWithoutFail($id);
        if (empty($payment_in_old)) {
            Flash::error(__('Not Found',['operator' => __('Payment In')]));
            return redirect(route('paymentIn.index'));
        }

        $input = $request->all();
        try {
           
            $input['created_by']  = auth()->user()->id;
            $payment_in           = $this->paymentInRepository->update($input,$id);

            $payment_in->market->activity()->create([
                'market_id'  => $payment_in->market->id,
                'action'     => 'Payment In Updated',
                'notes'      => 'Payment In '.$payment_in->code.' total updated from '.number_format($payment_in_old->total,'2','.','').' to '.number_format($payment_in->total,'2','.',''),
                'status'     => 'completed',
                'created_by' => auth()->user()->id
            ]);

            //Transaction
                $this->updateTransaction(
                    'payment_in',
                    'credit',
                    date('Y-m-d'),
                    $payment_in->market->id,
                    $payment_in->total,
                    'payment_in_id',
                    $payment_in->id
                );     
            //Transaction

            //Update Balance
                $this->partyBalanceUpdate($payment_in->market->id);
            //Update Balance  


            //Setttle Invoice Amount
                if(isset($input['amount']) && count(array_filter($input['amount'])) > 0) {
                    for($i=0; $i<count($input['amount']); $i++) {
                        if($input['amount'][$i] != null) {

                            if($input['payment_in_settle_id'][$i] > 0) {

                                $payment_in_settle_old = $this->paymentInSettleRepository->findWithoutFail($input['payment_in_settle_id'][$i]);
                                $payment_in_settle     = $this->paymentInSettleRepository->update([
                                    'payment_in_id'             => $payment_in->id,
                                    'settle_type'               => $input['settle_type'][$i],
                                    'amount'                    => $input['amount'][$i],
                                    $input['column_name'][$i]   => $input['invoice_id'][$i],
                                    'updated_by'                => auth()->user()->id
                                ],$input['payment_in_settle_id'][$i]);

                                
                                if($input['settle_type'][$i] == 'sales') {
                                    $payment_in_settle->salesinvoice->update([
                                        'amount_due' => ($payment_in_settle->salesinvoice->amount_due + $payment_in_settle_old->amount) - $payment_in_settle->amount
                                    ]);
                                } elseif($input['settle_type'][$i] == 'purchase') {
                                    $payment_in_settle->purchasereturn->update([
                                        'amount_due' => ($payment_in_settle->purchasereturn->amount_due + $payment_in_settle_old->amount) - $payment_in_settle->amount
                                    ]);
                                }

                            }    

                        }
                    }
                }    
            //Setttle Invoice Amount

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('Save successfully',['operator' => __('Payment In')]));
        return redirect(route('paymentIn.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\paymentIn  $paymentIn
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment_in = $this->paymentInRepository->with('paymentinsettle')->with('paymentmethod')->findWithoutFail($id);
        if (empty($payment_in)) {
            Flash::error(__('Not Found',['operator' => __('Payment In')]));
            return redirect(route('paymentIn.index'));
        }

        $this->paymentInRepository->delete($id);

        if(count($payment_in->paymentinsettle) > 0) {
            foreach($payment_in->paymentinsettle as $settle) {
                if($settle->settle_type == 'sales') {
                    $settle->salesinvoice->update([
                        'amount_due' => $settle->salesinvoice->amount_due + $settle->amount
                    ]);
                } elseif($settle->settle_type == 'purchase') {
                    $settle->purchasereturn->update([
                        'amount_due' => $settle->purchasereturn->amount_due + $settle->amount
                    ]);
                }
            }
        }
        $this->partyBalanceUpdate($payment_in->market->id);

        $payment_in->market->activity()->create([
            'market_id'  => $payment_in->market->id,
            'action'     => 'Payment In Deleted',
            'notes'      => 'Payment In '.$payment_in->code.' Deleted',
            'status'     => 'completed',
            'created_by' => auth()->user()->id
        ]);

        Flash::success(__('Deleted successfully',['operator' => __('Payment In')]));
        return redirect(route('paymentIn.index'));
    }

    public function print($id,$type,$view_type,Request $requestt)
    {  
        $payment_in = $this->paymentInRepository->with('paymentinsettle')->with('paymentmethod')->findWithoutFail($id);
        if (empty($payment_in)) {
            Flash::error(__('Not Found',['operator' => __('Payment In')]));
            return redirect(route('paymentIn.index'));
        }

        $words    = $this->amounttoWords($payment_in->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('payment_in.print', compact('payment_in','type','currency','words'));
        $filename = $payment_in->code.'-'.$payment_in->market->name.'.pdf';

        if($view_type=='print') {
            return $pdf->stream($filename);
        } elseif($view_type=='download') {
            return $pdf->download($filename);
        }
    }


    public function emailtoparty($id) {

        $payment_in = $this->paymentInRepository->findWithoutFail($id);
        if (empty($payment_in)) {
            Flash::error('Payment In not found');
            return redirect(route('paymentIn.index'));
        }
        $type       = 1;
        $words      = $this->amounttoWords($payment_in->total);
        $currency   = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf        =   PDF::setOptions([
                            'isHtml5ParserEnabled' => true, 
                            'isRemoteEnabled' => true, 
                            'dpi' => 100
                        ])->loadView('payment_in.print',compact('payment_in','type','words','currency'));

        $invoice_url = url(base64_encode($payment_in->id).'/PaymentIn'); 
        $message     = "Hi ".$payment_in->market->name.",<br>

                        Here's Payment In #".$payment_in->code." for ".number_format($payment_in->total,2,'.','').".<br>

                        View your bill online: ".url(base64_encode($payment_in->id).'/PaymentIn')."<br>

                        From your online bill you can print a PDF or create a free login and view your outstanding bills <br>.

                        If you have any questions, please let us know. <br>";

        //Notification
            $notification_data = [
                'greeting'    => "New Payment In Invoice Generated!",
                'body'        => $message,
                'thanks'      => 'Thank you',
                'pdf_file'    => $pdf,
                'filename'    => 'Payment In Invoice #'.$payment_in->code.'.pdf',
                'datas'       => array(
                    'title'   => 'Payment In Invoice '.$payment_in->code.' from '.setting('app_name').' for '.$payment_in->market->name, //"New Payment In Generated!",
                    'message' => $message
                )
            ];
            $notify = $payment_in->market->user->notify(new PaymentInNotification($notification_data));
            $payment_in->market->activity()->create([
                'market_id'  => $payment_in->market->id,
                'action'     => 'Payment In Invoice Sent',
                'notes'      => 'Payment In Invoice '.$payment_in->code.' sent to '.$payment_in->market->user->email,
                'status'     => 'completed',
                'created_by' => auth()->user()->id
            ]);
        //Notification
            
    }


    public function frontView(Request $request, $id) {

        $id               = base64_decode($id);
        $payment_in       = $this->paymentInRepository
                            ->with('paymentmethod')
                            ->with('paymentinsettle')
                            ->with('paymentinsettle.salesinvoice')
                            ->with('paymentinsettle.purchasereturn')
                            ->findWithoutFail($id);
        if (empty($payment_in)) {
            Flash::error('Payment In not found');
            return redirect(route('paymentIn.index'));
        }
        $words    = $this->amounttoWords($payment_in->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        return view('payment_in.front_end_invoice', compact('payment_in','currency','words'));
    }


    public function DownloadPDF(Request $request, $id) {
        $id            = base64_decode($id);
        $type          = 1;
        $payment_in    = $this->paymentInRepository
                            ->with('paymentmethod')
                            ->with('paymentinsettle')
                            ->with('paymentinsettle.salesinvoice')
                            ->with('paymentinsettle.purchasereturn')
                            ->findWithoutFail($id); 
        if (empty($payment_in)) {
            Flash::error('Payment In not found');
            return redirect(route('paymentIn.index'));
        }
        $words    = $this->amounttoWords($payment_in->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('payment_in.print', compact('payment_in','type','currency','words'));
        $filename = $payment_in->code.'-'.$payment_in->market->name.'.pdf';
        return $pdf->download($filename);
    }

}
