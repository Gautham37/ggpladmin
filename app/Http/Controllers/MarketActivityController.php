<?php

namespace App\Http\Controllers;

use App\Models\MarketActivity;
use App\Helper\Reply;
use App\Http\Requests\CreateMarketActivityRequest;
use App\Http\Requests\UpdateMarketActivityRequest;
use App\Repositories\MarketActivityRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DataTables;
use Carbon\Carbon;

use Notification;
use App\Notifications\MarketActivityNotification;

class MarketActivityController extends Controller
{   
    /**
     * @var MarketActivityRepository
     */
    private $marketActivityRepository;
    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(MarketActivityRepository $marketActivityRepo, UploadRepository $uploadRepo) {
        $this->marketActivityRepository  = $marketActivityRepo;
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
            if($request->type=='grid') {
            
                $id   = $request->market_id;
                $data = $this->marketActivityRepository->where('market_id',$id)->orderBy('created_at','desc')->get();
                $view =  view('market_activity.summary_data', compact('data'))->render();
                return ['status' => 'success', 'data' => $view];
            
            } elseif($request->type=='list') {

                $id    = $request->market_id;
                $query = $this->marketActivityRepository->where('market_id',$id);
                if(isset($request->date) && $request->date!='') {
                    $query->whereDate('created_at',Carbon::parse($request->date)->format('Y-m-d'));
                }
                if(isset($request->status) && $request->status!='') {
                    $query->where('status',$request->status);
                }
                if(isset($request->priority) && $request->priority!='') {
                    $query->where('priority',$request->priority);
                }
                $data  = $query->orderBy('created_at','desc')->get();

                $dataTable = Datatables::of($data);
                $dataTable->addIndexColumn();

                $table= $dataTable
                        ->addColumn('date', function($activity){
                            return $activity->created_at->format('M d, Y h:i A');
                        })
                        ->addColumn('assigned_by', function($activity){
                            return $activity->createdby->name;
                        })
                        ->addColumn('assign_to', function($activity){
                            return ($activity->assignto) ?  $activity->assignto->market->name : '' ;
                        })
                        ->addColumn('action_summary', function($activity){
                            return '<b>'.$activity->action.'</b>'.' : '. $activity->notes;
                        })
                        ->addColumn('updated_at', function($activity) {
                            return ($activity->updated_at) ? $activity->updated_at->format('M d, Y h:i: A') : '' ;
                        })  
                        ->addColumn('status', function($activity) {
                            $html ='<div class="dropdown"><button class="btn btn-sm btn-'.$activity->status.' dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.ucfirst($activity->status).'</button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'; 
                            $html.='<a onclick="activityStatusupdate(this);" data-id="'.$activity->id.'" data-status="pending" class="dropdown-item" href="#">Mark as Pending</a>';
                            $html.='<a onclick="activityStatusupdate(this);" data-id="'.$activity->id.'" data-status="completed" class="dropdown-item" href="#">Mark as Complete</a>';
                            $html.='<a onclick="activityStatusupdate(this);" data-id="'.$activity->id.'" data-status="cancelled" class="dropdown-item" href="#">Mark as Cancel</a>';
                            $html.='</div></div>';
                            return $html;
                        })
                        ->addColumn('action', function($activity){
                            return view('market_activity.datatables_actions',compact('activity'));
                        })        
                        ->rawColumns(['action_summary','status','action'])
                        ->make(true);
                return $table;        
            }

        }
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
    public function store(CreateMarketActivityRequest $request)
    {
        $input = $request->all();
        try {
            
            $input['created_by'] = auth()->user()->id;
            $market_activity     = $this->marketActivityRepository->create($input);
            
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($market_activity, 'image');
            }

            if($market_activity->assignto) :
                //Notification
                    $notification_data = [
                        'greeting'    => "New Party activity assigned by".$market_activity->createdby->name,
                        'body'        => $market_activity->action.' : '.$market_activity->notes,
                        'thanks'      => 'Thank you',
                        'datas'       => array(
                            'title'   => "New Party activity assigned by".$market_activity->createdby->name,
                            'message' => $market_activity->action.' : '.$market_activity->notes
                        )
                    ];
                    $market_activity->assignto->notify(new MarketActivityNotification($notification_data));
                //Notification
            endif;

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Market Activity')]));
        return redirect(route('markets.index'));
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
        $market_activity = $this->marketActivityRepository->findWithoutFail($id);
        if (empty($market_activity)) {
            Flash::error(__('Not Found',['operator' => __('Market Activity')]));
            return redirect(route('marketActivity.index'));
        }
        return view('marketActivity.index')->with('marketActivity',$market_activity);
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
            if($request->type=='status-update') {

                $market_activity_old = $this->marketActivityRepository->findWithoutFail($id);
                if (empty($market_activity_old)) {
                    return ['status' => 'failiure', 'data' => 'Activity Not Found'];
                }
                $input = $request->all();

                try {
                        
                    $input['updated_by']  = auth()->user()->id ;
                    $market_activity      = $this->marketActivityRepository->update($input, $id);
                    
                    return ['status' => 'success', 'data' => 'Activity Status Updated Successfully'];

                } catch (ValidatorException $e) {
                    Flash::error($e->getMessage());
                }
            }
        }

        $market_activity = $this->marketActivityRepository->findWithoutFail($id);
        if (empty($market_activity)) {
            Flash::error('Market Activity not found');
            return redirect(route('marketActivity.index'));
        }
        $input = $request->all();
        try {
            $market_activity = $this->marketActivityRepository->update($input, $id);
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($market_activity, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Updated successfully',['operator' => __('Market Activity')]));
        return redirect(route('marketActivity.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $market_activity = $this->marketActivityRepository->findWithoutFail($id);
        if (empty($market_activity)) {
            Flash::error('Market Activity not found');
            return redirect(route('marketActivity.index'));
        }
        $this->marketActivityRepository->delete($id);

        Flash::success(__('Deleted successfully',['operator' => __('Market Activity')]));
        return redirect(route('marketActivity.index'));
    }
}
