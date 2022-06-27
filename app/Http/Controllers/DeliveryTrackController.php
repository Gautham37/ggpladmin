<?php

namespace App\Http\Controllers;

use App\Models\DeliveryTrack;
use App\Helper\Reply;
use App\Http\Requests\CreateDeliveryTrackRequest;
use App\Http\Requests\UpdateDeliveryTrackRequest;
use App\Repositories\DeliveryTrackRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DataTables;

use Notification;
use App\Notifications\DeliveryTrackAssign;

class DeliveryTrackController extends Controller
{   
    /**
     * @var DeliveryTrackRepository
     */
    private $deliveryTrackRepository;
    /**
     * @var UploadRepository
     */
    private $uploadRepository;
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(DeliveryTrackRepository $deliveryTrackRepo, UploadRepository $uploadRepo, OrderRepository $orderRepo, UserRepository $userRepo) {
        $this->deliveryTrackRepository   = $deliveryTrackRepo;
        $this->orderRepository           = $orderRepo;
        $this->userRepository            = $userRepo;
        $this->uploadRepository          = $uploadRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            if($request->type=='summarydata') {

                $data = $this->orderRepository->orderBy('created_at','desc')->get();
                $view =  view('delivery_track.summary_data', compact('data'))->render();
                return ['status' => 'success', 'data' => $view];

            } elseif($request->type=='delivery_assign') {
                
                $order = $this->orderRepository->findWithoutFail($request->order_id);
                $users = $this->userRepository->with("roles")->whereHas('roles', function ($q) { 
                            $q->where('name', 'driver'); 
                         })->pluck('name', 'id');
                $users->prepend("Please Select",null);
                $type       = 'deliver';
                $category   = 'online_order';
                $view =  view('delivery_track.delivery_assign', compact('order','users','type','category'))->render();
                return ['status' => 'success', 'data' => $view];

            }

        }

        return view('delivery_track.index');
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
    public function store(CreateDeliveryTrackRequest $request)
    {
        $order = $this->orderRepository->findWithoutFail($request->order_id);
        $user  = $this->userRepository->findWithoutFail($request->user_id);
        if (empty($order)) {
            Flash::error(__('Not Found',['operator' => __('Delivery Track')]));
            return redirect(route('deliveryTracker.index'));
        }
        $input = $request->all();
        try {
            $input['created_by'] = auth()->user()->id;
            $delivery_track     = $this->deliveryTrackRepository->create($input);
            
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($delivery_track, 'image');
            }

            $order->activity()->create([
                'order_id'  => $order->id,
                'action'    => 'Delivery Person Assigned',
                'notes'     => $user->market->name.' Assigned to this order'
            ]);
            
            //Notification
                $notification_data = [
                    'greeting'    => "Order Assigned",
                    'body'        => 'Order #'.$order->order_code.' was assigned to you by'. auth()->user()->name,
                    'thanks'      => 'Thank you',
                    'datas'       => array(
                        'title'   => 'Order Assigned',
                        'message' => 'Order #'.$order->order_code.' was assigned to you by'. auth()->user()->name
                    )
                ];
                $notify = $order->user->notify(new DeliveryTrackAssign($notification_data));
            //Notification

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Delivery Track')]));
        return redirect(route('deliveryTracker.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(MarketActivity $marketActivity)
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
        $delivery_track_old = $this->deliveryTrackRepository->findWithoutFail($id);
        if (empty($delivery_track_old)) {
            Flash::error(__('Not Found',['operator' => __('Delivery Track')]));
            return redirect(route('deliveryTracker.index'));
        }
        return view('deliveryTracker.index')->with('delivery_track',$delivery_track_old);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {   
        if ($request->ajax()) { 
            if($request->type=='driver-update') {

                $delivery_track_old = $this->deliveryTrackRepository->findWithoutFail($id);
                if (empty($delivery_track_old)) {
                    return ['status' => 'failiure', 'data' => 'Delievry Track Not Found'];
                }
                $input = $request->all();

                $active_order = $this->deliveryTrackRepository->where('user_id',auth()->user()->id)->where('active','1')->count();
                if($active_order == 0) {
                    try {
                        
                        $input['active'] = ($input['status']=='rejected') ? 0 : 1 ;
                        $delivery_track  = $this->deliveryTrackRepository->update($input, $id);
                        
                        $$delivery_track->order->activity()->create([
                            'order_id'  => $$delivery_track->order->id,
                            'action'    => 'Delivery Accepted',
                            'notes'     => 'Delivery Person Accepted the order'
                        ]);

                        return ['status' => 'success', 'data' => 'Delivery Accepted Successfully'];

                    } catch (ValidatorException $e) {
                        Flash::error($e->getMessage());
                    }
                } else {
                    return ['status' => 'failiure', 'data' => 'Already have an ongoing order'];
                }
            }
        }

        $delivery_track_old = $this->deliveryTrackRepository->findWithoutFail($id);
        if (empty($delivery_track_old)) {
            Flash::error(__('Not Found',['operator' => __('Delivery Track')]));
            return redirect(route('deliveryTracker.index'));
        }
        $input = $request->all();
        try {
            $delivery_track = $this->deliveryTrackRepository->update($input, $id);
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($delivery_track, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('Updated successfully',['operator' => __('Delivery Track')]));
        return redirect(route('deliveryTracker.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delivery_track = $this->deliveryTrackRepository->findWithoutFail($id);
        if (empty($delivery_track)) {
            Flash::error('Delivery Track not found');
            return redirect(route('deliveryTracker.index'));
        }
        $this->deliveryTrackRepository->delete($id);

        Flash::success(__('Deleted successfully',['operator' => __('Delivery Track')]));
        return redirect(route('deliveryTracker.index'));
    }
}
