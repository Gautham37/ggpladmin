<?php

namespace App\Http\Controllers;

use App\Models\PaymentFor;
use App\Http\Requests\CreatePaymentForRequest;
use App\Http\Requests\UpdatePaymentForRequest;
use App\Repositories\PaymentForRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DataTables;

class PaymentForController extends Controller
{   
    /**
     * @var PaymentForRepository
     */
    private $paymentForRepository;
    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(PaymentForRepository $paymentForRepo, UploadRepository $uploadRepo) {
        $this->paymentForRepository  = $paymentForRepo;
        $this->uploadRepository      = $uploadRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('paymentFor.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePaymentForRequest $request)
    {
        $input = $request->all();
        try {
            $paymentFor = $this->paymentForRepository->create($input);
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Payment For')]));
        return redirect(route('paymentFor.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Notes $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $paymentFor = $this->paymentForRepository->findWithoutFail($id);
        if (empty($paymentFor)) {
            Flash::error(__('Not Found',['operator' => __('Payment For')]));
            return redirect(route('paymentFor.index'));
        }
        return view('paymentFor.index')->with('paymentFor',$paymentFor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdatePaymentForRequest $request)
    {
        $paymentFor = $this->paymentForRepository->findWithoutFail($id);
        if (empty($paymentFor)) {
            Flash::error('Payment For not found');
            return redirect(route('paymentFor.index'));
        }
        $input = $request->all();
        try {
            $paymentFor = $this->paymentForRepository->update($input, $id);
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($paymentFor, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('Updated successfully',['operator' => __('Payment For')]));
        return redirect(route('paymentFor.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paymentFor = $this->paymentForRepository->findWithoutFail($id);
        if (empty($paymentFor)) {
            Flash::error('Payment For not found');
            return redirect(route('paymentFor.index'));
        }
        $this->paymentForRepository->delete($id);

        Flash::success(__('Deleted successfully',['operator' => __('Payment For')]));
        return redirect(route('paymentFor.index'));
    }
}
