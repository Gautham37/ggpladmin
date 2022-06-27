<?php

namespace App\Http\Controllers;

use App\Models\PaymentOut;
use App\Criteria\Markets\MarketsOfManagerCriteria;
use App\DataTables\PaymentOutDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePaymentOutRequest;
use App\Http\Requests\UpdatePaymentOutRequest;
use App\Repositories\PaymentModeRepository;
use App\Repositories\PaymentOutRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\InvoiceSettleRepository;
use App\Repositories\PaymentOutSettleRepository;
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
use App\Notifications\PaymentOutNotification;

class PaymentOutController extends Controller
{   
    /** @var  PaymentOutRepository */
    private $paymentOutRepository;

    /** @var  PaymentOutSettleRepository */
    private $paymentOutSettleRepository;

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

    public function __construct(PaymentOutRepository $paymentOutRepo, MarketRepository $marketRepo, PaymentModeRepository $paymentMRepo, TransactionRepository $transactionRepo, InvoiceSettleRepository $invoiceSettleRepo, PaymentOutSettleRepository $paymentOutSettleRepo)
    {
        parent::__construct();
        $this->paymentOutRepository         = $paymentOutRepo;
        $this->paymentOutSettleRepository   = $paymentOutSettleRepo; 
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
    public function index(PaymentOutDataTable $paymentOutDataTable)
    {
        $payment_out = $paymentOutDataTable;
        if(Request('start_date')!='' && Request('end_date')!='' ) {
           $payment_out->with('start_date',Request('start_date'))->with('end_date',Request('end_date'));  
        }
        return $payment_out->render('payment_out.index');
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

        $payment_out_no  = setting('app_invoice_prefix').'-PAO-'.(autoIncrementId('payment_out'));

        return view('payment_out.create',compact('users','payment_methods','payment_out_no'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePaymentOutRequest $request)
    {
        $input = $request->all();
        try {
            $input['created_by']  = auth()->user()->id;
            $payment_out          = $this->paymentOutRepository->create($input);

            //Transaction
                $this->addTransaction(
                    'payment_out',
                    'debit',
                    date('Y-m-d'),
                    $payment_out->market->id,
                    $payment_out->total,
                    'payment_out_id',
                    $payment_out->id
                );     
            //Transaction

            //Update Balance
                $this->partyBalanceUpdate($payment_out->market->id);
            //Update Balance  


            //Setttle Invoice Amount
                if(isset($input['amount']) && count(array_filter($input['amount'])) > 0) {
                    for($i=0; $i<count($input['amount']); $i++) {
                        if($input['amount'][$i] != null) {

                            $payment_out_settle = $this->paymentOutSettleRepository->create([
                                'payment_out_id'            => $payment_out->id,
                                'settle_type'               => $input['settle_type'][$i],
                                'amount'                    => $input['amount'][$i],
                                $input['column_name'][$i]   => $input['invoice_id'][$i],
                                'created_by'                => auth()->user()->id
                            ]);

                            if($input['settle_type'][$i] == 'sales') {
                                $payment_out_settle->salesreturn->update([
                                    'amount_due' => $payment_out_settle->salesreturn->amount_due - $payment_out_settle->amount
                                ]);
                            } elseif($input['settle_type'][$i] == 'purchase') {
                                $payment_out_settle->purchaseinvoice->update([
                                    'amount_due' => $payment_out_settle->purchaseinvoice->amount_due - $payment_out_settle->amount
                                ]);
                            }    

                        }
                    }
                }    
            //Setttle Invoice Amount  

            $this->emailtoparty($payment_out->id);    

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('Save successfully',['operator' => __('Payment Out')]));
        return redirect(route('paymentOut.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentOut  $PaymentOut
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {   
        $payment_out = $this->paymentOutRepository
                           ->with('paymentmethod')
                           ->with('paymentoutsettle')
                           ->with('paymentoutsettle.salesreturn')
                           ->with('paymentoutsettle.purchaseinvoice')
                           ->findWithoutFail($id);
        if (empty($payment_out)) {
            Flash::error(__('Not Found',['operator' => __('Payment Out')]));
            return redirect(route('paymentOut.index'));
        }
        if($request->ajax()) {
            return ['status' => 'success', 'data' => $payment_out];   
        }
        return view('payment_out.show',compact('payment_out'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentOut  $PaymentOut
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   

        $payment_out = $this->paymentOutRepository->with('paymentoutsettle')->with('paymentmethod')->findWithoutFail($id);
        if (empty($payment_out)) {
            Flash::error(__('Not Found',['operator' => __('Payment Out')]));
            return redirect(route('paymentOut.index'));
        }

        $users  = $this->marketRepository->select('id', DB::raw("concat(name, ' ', mobile, ' ', code) as name"))->pluck('name', 'id');
        $users->prepend("Please Select",null);
        $payment_methods = $this->paymentModeRepository->pluck('name','id');

        return view('payment_out.edit',compact('users','payment_methods','payment_out'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentOut  $PaymentOut
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdatePaymentOutRequest $request)
    {
        $payment_out = $this->paymentOutRepository->with('paymentoutsettle')->with('paymentmethod')->findWithoutFail($id);
        if (empty($payment_out)) {
            Flash::error(__('Not Found',['operator' => __('Payment Out')]));
            return redirect(route('paymentOut.index'));
        }

        $input = $request->all();
        try {
           
            $input['created_by']  = auth()->user()->id;
            $payment_out          = $this->paymentOutRepository->update($input,$id);

            //Transaction
                $this->updateTransaction(
                    'payment_out',
                    'debit',
                    date('Y-m-d'),
                    $payment_out->market->id,
                    $payment_out->total,
                    'payment_out_id',
                    $payment_out->id
                );     
            //Transaction

            //Update Balance
                $this->partyBalanceUpdate($payment_out->market->id);
            //Update Balance  


            //Setttle Invoice Amount
                if(isset($input['amount']) && count(array_filter($input['amount'])) > 0) {
                    for($i=0; $i<count($input['amount']); $i++) {
                        if($input['amount'][$i] != null) {

                            if($input['payment_out_settle_id'][$i] > 0) {

                                $payment_out_settle_old = $this->paymentOutSettleRepository->findWithoutFail($input['payment_out_settle_id'][$i]);
                                $payment_out_settle     = $this->paymentOutSettleRepository->update([
                                    'payment_out_id'            => $payment_out->id,
                                    'settle_type'               => $input['settle_type'][$i],
                                    'amount'                    => $input['amount'][$i],
                                    $input['column_name'][$i]   => $input['invoice_id'][$i],
                                    'updated_by'                => auth()->user()->id
                                ],$input['payment_out_settle_id'][$i]);

                                
                                if($input['settle_type'][$i] == 'sales') {
                                    $payment_out_settle->salesreturn->update([
                                        'amount_due' => ($payment_out_settle->salesreturn->amount_due + $payment_out_settle_old->amount) - $payment_out_settle->amount
                                    ]);
                                } elseif($input['settle_type'][$i] == 'purchase') {
                                    $payment_out_settle->purchaseinvoice->update([
                                        'amount_due' => ($payment_out_settle->purchaseinvoice->amount_due + $payment_out_settle_old->amount) - $payment_out_settle->amount
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

        Flash::success(__('Save successfully',['operator' => __('Payment Out')]));
        return redirect(route('paymentOut.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentOut  $PaymentOut
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment_out = $this->paymentOutRepository->with('paymentoutsettle')->with('paymentmethod')->findWithoutFail($id);
        if (empty($payment_out)) {
            Flash::error(__('Not Found',['operator' => __('Payment Out')]));
            return redirect(route('paymentOut.index'));
        }

        $this->paymentOutRepository->delete($id);

        if(count($payment_out->paymentoutsettle) > 0) {
            foreach($payment_out->paymentoutsettle as $settle) {
                
                if($settle->settle_type == 'sales') {
                    $settle->salesreturn->update([
                        'amount_due' => $settle->salesreturn->amount_due + $settle->amount
                    ]);
                } elseif($settle->settle_type == 'purchase') {
                    $settle->purchaseinvoice->update([
                        'amount_due' => $settle->purchaseinvoice->amount_due + $settle->amount
                    ]);
                }

            }
        }
        $this->partyBalanceUpdate($payment_out->market->id);

        Flash::success(__('Deleted successfully',['operator' => __('Payment Out')]));
        return redirect(route('paymentOut.index'));
    }

    public function print($id,$type,$view_type,Request $requestt)
    {  
        $payment_out = $this->paymentOutRepository->with('paymentoutsettle')->with('paymentmethod')->findWithoutFail($id);
        if (empty($payment_out)) {
            Flash::error(__('Not Found',['operator' => __('Payment Out')]));
            return redirect(route('paymentOut.index'));
        }

        $words    = $this->amounttoWords($payment_out->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('payment_out.print', compact('payment_out','type','currency','words'));
        $filename = $payment_out->code.'-'.$payment_out->market->name.'.pdf';

        if($view_type=='print') {
            return $pdf->stream($filename);
        } elseif($view_type=='download') {
            return $pdf->download($filename);
        }
    }

    public function emailtoparty($id) {

        $payment_out = $this->paymentOutRepository->findWithoutFail($id);
        if (empty($payment_out)) {
            Flash::error('Payment Out not found');
            return redirect(route('paymentOut.index'));
        }
        $type       = 1;
        $words      = $this->amounttoWords($payment_out->total);
        $currency   = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf        =   PDF::setOptions([
                            'isHtml5ParserEnabled' => true, 
                            'isRemoteEnabled' => true, 
                            'dpi' => 100
                        ])->loadView('payment_out.print',compact('payment_out','type','words','currency'));

        $invoice_url = url(base64_encode($payment_out->id).'/PaymentOut'); 
        $message     = "Hi ".$payment_out->market->name.",<br>

                        Here's Payment Out #".$payment_out->code." for ".number_format($payment_out->total,2,'.','').".<br>

                        View your bill online: ".url(base64_encode($payment_out->id).'/PaymentOut')."<br>

                        From your online bill you can print a PDF or create a free login and view your outstanding bills <br>.

                        If you have any questions, please let us know. <br>";

        //Notification
            $notification_data = [
                'greeting'    => "New Payment Out Invoice Generated!",
                'body'        => $message,
                'thanks'      => 'Thank you',
                'pdf_file'    => $pdf,
                'filename'    => 'Payment Out Invoice #'.$payment_out->code.'.pdf',
                'datas'       => array(
                    'title'   => 'Payment Out Invoice '.$payment_out->code.' from '.setting('app_name').' for '.$payment_out->market->name, //"New Payment Out Generated!",
                    'message' => $message
                )
            ];
            $notify = $payment_out->market->user->notify(new PaymentOutNotification($notification_data));
            $payment_out->market->activity()->create([
                'market_id'  => $payment_out->market->id,
                'action'     => 'Payment Out Invoice Sent',
                'notes'      => 'Payment Out Invoice '.$payment_out->code.' sent to '.$payment_out->market->user->email,
                'created_by' => auth()->user()->id
            ]);
        //Notification
            
    }


    public function frontView(Request $request, $id) {

        $id               = base64_decode($id);
        $payment_out      = $this->paymentOutRepository
                               ->with('paymentmethod')
                               ->with('paymentoutsettle')
                               ->with('paymentoutsettle.salesreturn')
                               ->with('paymentoutsettle.purchaseinvoice')
                               ->findWithoutFail($id);
        if (empty($payment_out)) {
            Flash::error('Payment Out not found');
            return redirect(route('paymentOut.index'));
        }
        $words    = $this->amounttoWords($payment_out->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        return view('payment_out.front_end_invoice', compact('payment_out','currency','words'));
    }


    public function DownloadPDF(Request $request, $id) {
        $id            = base64_decode($id);
        $type          = 1;
        $payment_out   = $this->paymentOutRepository
                               ->with('paymentmethod')
                               ->with('paymentoutsettle')
                               ->with('paymentoutsettle.salesreturn')
                               ->with('paymentoutsettle.purchaseinvoice')
                               ->findWithoutFail($id); 
        if (empty($payment_out)) {
            Flash::error('Payment Out not found');
            return redirect(route('paymentOut.index'));
        }
        $words    = $this->amounttoWords($payment_out->total);
        $currency = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
        $pdf      = PDF::loadView('payment_out.print', compact('payment_out','type','currency','words'));
        $filename = $payment_out->code.'-'.$payment_out->market->name.'.pdf';
        return $pdf->download($filename);
    }

}
