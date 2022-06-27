<?php

namespace App\Http\Controllers;

use App\DataTables\EmailnotificationsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateEmailnotificationsRequest;
use App\Http\Requests\UpdateEmailnotificationsRequest;
use App\Repositories\EmailnotificationsRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\MarketRepository;
use App\Repositories\PartyTypesRepository;
use App\Repositories\PartySubTypesRepository;
use App\Models\Market;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Mail\AdhocCustomersMail;
use DB;

use Notification;
use App\Notifications\EmailAlertNotification;

class EmailnotificationsController extends Controller
{
    /** @var  OptionGroupRepository */
    private $emailnotificationsRepository;

    /** @var  MarketRepository */
    private $marketRepository;

    /** @var  PartyTypesRepository */
    private $partyTypesRepository;

    /** @var  PartySubTypesRepository */
    private $partySubTypesRepository;


    public function __construct(EmailnotificationsRepository $emailnotiRepo, MarketRepository $marketRepo, PartyTypesRepository $partyTypesRepo, PartySubTypesRepository $partySubTypesRepo)
    {
        parent::__construct();
        $this->emailnotificationsRepository = $emailnotiRepo;
        $this->marketRepository             = $marketRepo;
        $this->partyTypesRepository         = $partyTypesRepo;
        $this->partySubTypesRepository      = $partySubTypesRepo;
        
    }

    /**
     * Display a listing of the OptionGroup.
     *
     * @param OptionGroupDataTable $optionGroupDataTable
     * @return Response
     */
    public function index(EmailnotificationsDataTable $EmailnotificationsDataTable)
    {
        return $EmailnotificationsDataTable->render('emailnotifications.index');
    }

    /**
     * Show the form for creating a new OptionGroup.
     *
     * @return Response
     */
    public function create()
    { 
        $markets     = $this->marketRepository->pluck('name','id');
        $party_types = $this->partyTypesRepository->pluck('name','id');
        $party_types->prepend('Please Select',null);
        return view('emailnotifications.create',compact('markets','party_types'));
    }

    /**
     * Store a newly created OptionGroup in storage.
     *
     * @param CreateOptionGroupRequest $request
     *
     * @return Response
     */
    public function store(CreateEmailnotificationsRequest $request)
    {
        $input          = $request->all();
        try {
            $input['created_by']  = auth()->user()->id;
            $email_notification   = $this->emailnotificationsRepository->create($input);
            if($email_notification) {
                if($email_notification->type == 'send') {
                    if($email_notification->party_sub_type_id > 0) {
                        $markets = $this->marketRepository->where('sub_type',$email_notification->party_sub_type_id)->get();
                    } else {
                        $markets = $this->marketRepository->where('type',$email_notification->party_type_id)->get();    
                    }
                    
                    $notification_data = [
                        'greeting'    => $email_notification->subject,
                        'body'        => $email_notification->message,
                        'thanks'      => 'Thank you'
                    ];
                    $i = 0;
                    if(count($markets) > 0) {
                        foreach($markets as $market) {
                            $notify = $market->user->notify(new EmailAlertNotification($notification_data));
                            $i++;
                        }
                    }

                    if($i > 0) {
                        $this->emailnotificationsRepository->update(['status'=>'sent'],$email_notification->id);
                    }

                }
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.email_notifications')]));
        return redirect(route('emailnotifications.index'));
    }

    /**
     * Display the specified OptionGroup.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $emailnotifications = $this->emailnotificationsRepository->findWithoutFail($id);

        if (empty($emailnotifications)) {
            Flash::error('Notifications not found');

            return redirect(route('emailnotifications.index'));
        }
    
        return view('emailnotifications.show')->with('emailnotifications', $emailnotifications);
    }

    /**
     * Show the form for editing the specified OptionGroup.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        
        $emailnotification = $this->emailnotificationsRepository->findWithoutFail($id);
        if(empty($emailnotification)) {
            Flash::error('Notifications not found');
            return redirect(route('emailnotifications.index'));
        }
        $markets     = $this->marketRepository->pluck('name','id');
        $party_types = $this->partyTypesRepository->pluck('name','id');
        $party_types->prepend('Please Select',null);
        return view('emailnotifications.edit',compact('markets','party_types','emailnotification'));
    }

    /**
     * Update the specified OptionGroup in storage.
     *
     * @param  int              $id
     * @param UpdateOptionGroupRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmailnotificationsRequest $request)
    {
        $emailnotification_old = $this->emailnotificationsRepository->findWithoutFail($id);
        if(empty($emailnotification_old)) {
            Flash::error('Notifications not found');
            return redirect(route('emailnotifications.index'));
        }
        try {
            $input['created_by']  = auth()->user()->id;
            $email_notification   = $this->emailnotificationsRepository->update($input,$id);
            
            if($emailnotification_old->status=='draft') {
                if($email_notification) {
                    if($email_notification->type == 'send') {
                        if($email_notification->party_sub_type_id > 0) {
                            $markets = $this->marketRepository->where('sub_type',$email_notification->party_sub_type_id)->get();
                        } else {
                            $markets = $this->marketRepository->where('type',$email_notification->party_type_id)->get();    
                        }
                        
                        $notification_data = [
                            'greeting'    => $email_notification->subject,
                            'body'        => $email_notification->message,
                            'thanks'      => 'Thank you'
                        ];
                        $i = 0;
                        if(count($markets) > 0) {
                            foreach($markets as $market) {
                                $notify = $market->user->notify(new EmailAlertNotification($notification_data));
                                $i++;
                            }
                        }

                        if($i > 0) {
                            $this->emailnotificationsRepository->update(['status'=>'sent'],$email_notification->id);
                        }

                    }
                }
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully',['operator' => __('lang.email_notifications')]));
        return redirect(route('emailnotifications.index'));
    }

    /**
     * Remove the specified OptionGroup from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $emailnotifications = $this->emailnotificationsRepository->findWithoutFail($id);

        if (empty($emailnotifications)) {
            Flash::error('Notifications not found');

            return redirect(route('emailnotifications.index'));
        }

        $this->emailnotificationsRepository->delete($id);

        Flash::success(__('lang.deleted_successfully',['operator' => __('lang.option_gremail_notificationsoup')]));

        return redirect(route('emailnotifications.index'));
    }

     public function loadScheduledetails(Request $request) {

        $subject          = $request->subject;
        $description = $request->description;
        $customer_groups = $request->customer_groups;
        $customers = implode(',',$request->customers);
        $customers = trim($customers,',');

       if(isset($request->notification_id))
       {

               $get_vals =  DB::table('email_notifications')->select('*')->where('id', $request->notification_id)->get();

               foreach ($get_vals as $key => $value) {

            return view('layouts.schedule_modal')->with('subject',$subject)->with('description',$description)->with('customers',$customers)->with('customer_groups',$customer_groups)->with('schedule_date',$value->schedule_date)->with('notification_id',$request->notification_id);
               }

       }else{

            return view('layouts.schedule_modal')->with('subject',$subject)->with('description',$description)->with('customers',$customers)->with('customer_groups',$customer_groups);
       }

    }

  public function save_schedule_notifications(Request $request) {
        $subject    = $request->subject;
        $description = $request->description;
        $customers = $request->customers;
        $customer_groups = $request->customer_groups;
        $schedule_date = $request->schedule_date;

      if(isset($request->notification_id))
       {

           $emailnotifications = DB::table('email_notifications')->where('id', $request->notification_id)->update(array('status' => 3,'schedule_date'=>$schedule_date,'customers'=>$customers,'customer_groups'=>$customer_groups,'description'=>$description,'name'=>$subject,'updated_by'=>auth()->user()->id)); 

       }else{

        $data['name'] = $subject;
         $data['description'] = $description;
          $data['customers'] = $customers;
           $data['customer_groups'] = $customer_groups;
           $data['schedule_date'] = $schedule_date;
           $data['status'] = 3;

          $emailnotifications = $this->emailnotificationsRepository->create($data);

       }

           echo json_encode($emailnotifications);
    }

    public function schedulenotifications()
    {
        
        // $emailnotifications = DB::table('email_notifications')->where('id', 5)->update(array('status' => 1)); 

     date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
     $curr_date_time =  date('Y-m-d H:i').":00";

      $data =   DB::table('email_notifications')->get();
      
      foreach ($data as $key => $value) {
        $schedule_date = $value->schedule_date;
        $subject = $value->name;
        $description = $value->description;
        $customers = $value->customers;
        $customer_groups = $value->customer_groups;
        
        $selected_customers = explode(',',$customers);


      if($schedule_date==$curr_date_time)
      {
          $id = $value->id;

           if (in_array("0", $selected_customers))
          {
             if($customer_groups==0)
             {

              $get_vals =  DB::table('markets')->select('*')->get();

             }else{
               $get_vals =  DB::table('markets')->select('*')->where('customer_group', $customer_groups)->get();

             }

            foreach ($get_vals as $key => $value1) {
       
               $email = $value1->email;
               $name = $value1->name;

                $details = ['subject' => $subject,'body_content' => $description,'customer_name'=>$name];
                \Mail::to($email)->send(new AdhocCustomersMail($details));
             }

          }else
          {

             foreach ($selected_customers as $key => $value) {

          $get_vals =  DB::table('markets')->select('*')->where('id', $value)->get();

          foreach ($get_vals as $key => $value1) {
       
           $email = $value1->email;
           $name = $value1->name;


           $details = ['subject' => $subject,'body_content' => $description,'customer_name'=>$name];
    
           \Mail::to($email)->send(new AdhocCustomersMail($details));
          
                    }
               } 

          }

           $emailnotifications = DB::table('email_notifications')->where('id', $id)->update(array('status' => 1)); 

        }
            
      }
}

    public function previewEmailTemplate() {
        return view('emailnotifications.template');
    }
}