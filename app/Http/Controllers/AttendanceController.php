<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Http\Requests\CreateAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Repositories\AttendanceRepository;
use App\Repositories\AttendancePunchinRepository;
//use App\Repositories\EmployeesRepository;
use App\Repositories\HolidaysRepository;
use App\Repositories\UserRepository;
use App\Repositories\UploadRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use DataTables;
use Carbon\Carbon;
use DB;
use DateTime;
use Config;

class AttendanceController extends Controller
{   
    /**
     * @var AttendanceRepository
     */
    private $attendanceRepository;
    /**
     * @var AttendancePunchinRepository
     */
    private $attendancePunchinRepository;
    /**
     * @var EmployeesRepository
     */
    private $employeesRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var HolidaysRepository
     */
    private $holidaysRepository;
    /**
     * @var UploadRepository
     */
    private $uploadRepository;

    public function __construct(AttendanceRepository $attendanceRepo, UserRepository $userRepo, UploadRepository $uploadRepo, AttendancePunchinRepository $attendancePunchinRepo, HolidaysRepository $holidaysRepo) {
        $this->attendanceRepository         = $attendanceRepo;
        //$this->employeesRepository          = $employeesRepo;
        $this->userRepository               = $userRepo; 
        $this->uploadRepository             = $uploadRepo;
        $this->holidaysRepository           = $holidaysRepo;
        $this->attendancePunchinRepository  = $attendancePunchinRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        
        if ($request->ajax()) {
            if(auth()->user()->hasAllPermissions('attendance.summary')) {
                
                $query     = $this->attendanceRepository->with('user');
                if($request->userId!='' && $request->userId > 0) {
                    $query->where('user_id',$request->userId);
                }
                $data      = $query->whereRaw('MONTH(attendances.clock_in_time) = ?', [$request->month])
                                   ->whereRaw('YEAR(attendances.clock_in_time) = ?', [$request->year])
                                   ->orderBy('clock_in_time','desc')->get();

            } else {
                $data      = $this->attendanceRepository->orderBy('clock_in_time','desc')->where('user_id',auth()->user()->id)->get();
            }
            
            $dataTable = Datatables::of($data);
            $dataTable->addIndexColumn();                     
            return $dataTable
                    ->addColumn('date',function($attendance) {
                        return date('d-m-Y',strtotime($attendance->clock_in_time));
                    })
                    ->addColumn('clock_in_time',function($attendance) {
                        return date('h:i A',strtotime($attendance->clock_in_time));
                    })
                    ->addColumn('clock_out_time',function($attendance) {
                        if($attendance->clock_out_time != null) {
                            return date('h:i A',strtotime($attendance->clock_out_time));
                        } else {
                            return '-';
                        }
                    })
                    ->addColumn('production',function($attendance) {
                        
                        $breakPunches = $attendance->punches->toArray();
                        if(count($breakPunches) > 0) {
                            $breakTime = [];
                            foreach(array_chunk($breakPunches,2) as $punch) {
                                if(isset($punch[0])) {
                                    isset($punch[1]) ? '' : $punch[1]['punch_time'] = date('H:i:s') ;
                                    $time1 = new DateTime(date('H:i:s',strtotime($punch[0]['punch_time'])));
                                    $time2 = new DateTime(date('H:i:s',strtotime($punch[1]['punch_time'])));
                                    $interval = $time1->diff($time2);
                                    $breakTime[] = strtotime($interval->format('%H:%I:%S')) - strtotime("00:00:00");    
                                }
                            }
                            return $working_hours = date('H:i:s',strtotime('00:00:00') + array_sum($breakTime));
                        } else {
                            if($attendance->clock_in_time != null && $attendance->clock_out_time != null) {
                                $time1 = new DateTime(date('H:i:s',strtotime($attendance->clock_in_time)));
                                $time2 = new DateTime(date('H:i:s',strtotime($attendance->clock_out_time)));
                                $interval = $time1->diff($time2); 
                                return $interval->format('%H:%I:%S');
                            } else {
                                return '-';
                            }
                        }

                    })
                    ->addColumn('break',function($attendance) {
                        
                        if($attendance->clock_in_time != null && $attendance->clock_out_time != null) {
                            if($attendance->clock_in_time != null && $attendance->clock_out_time != null) {
                                $time1 = new DateTime(date('H:i:s',strtotime($attendance->clock_in_time)));
                                $time2 = new DateTime(date('H:i:s',strtotime($attendance->clock_out_time)));
                                $interval = $time1->diff($time2); 
                                $total_hours = strtotime($interval->format('%H:%I:%S')) - strtotime('00:00:00');
                            } else {
                                $total_hours = 0;
                            }

                            $breakPunches = $attendance->punches->toArray();
                            if(count($breakPunches) > 0) {
                                $breakTime = [];
                                foreach(array_chunk($breakPunches,2) as $punch) {
                                    if(isset($punch[0])) {
                                        isset($punch[1]) ? '' : $punch[1]['punch_time'] = date('H:i:s') ;
                                        $time1 = new DateTime(date('H:i:s',strtotime($punch[0]['punch_time'])));
                                        $time2 = new DateTime(date('H:i:s',strtotime($punch[1]['punch_time'])));
                                        $interval = $time1->diff($time2);
                                        $breakTime[] = strtotime($interval->format('%H:%I:%S')) - strtotime("00:00:00");    
                                    }
                                }
                                $working_hours = array_sum($breakTime);
                            } else {
                                $working_hours = 0;
                            }
                            
                            return date('H:i:s',strtotime('00:00:00') + ($total_hours - $working_hours));    
                        } else {
                            return '-';
                        }       

                    })
                    ->addColumn('name', function($attendance) { 
                        if(count($attendance->user->getMedia('image')) > 0) {
                            $image = $attendance->user->getMedia('image')[0]->getUrl();
                        } else {
                            $image = asset('assets/images/user-icon.png');    
                        }
                        isset($attendance->designation->name) ? $designation = $attendance->designation->name :  $designation = '' ;
                        return '
                        <h2 class="table-avatar">
                            <a href="'.route('attendance.show',$attendance->id).'" class="avatar"><img alt="" src="'.$image.'"></a>
                            <a href="'.route('attendance.show',$attendance->id).'">'.$attendance->user->name.'<br> <span>'.$designation.'</span></a>
                        </h2>
                        ';
                    })
                    ->addColumn('overtime',function($attendance) {
                        return '-';
                    })        
                    ->rawColumns(['name','action','active'])
                    ->make(true);
        }

        $year      = date('Y');
        $month     = date('m');
        $totaldays = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
        $users     = $this->userRepository->get();
        $staffs    = $this->userRepository->whereHas('roles', function ($q) { $q->where('name', 'manager')->orWhere('name', 'supervisor')->orWhere('name', 'worker')->orWhere('name', 'driver'); })->pluck('name','id');
        $staffs->prepend('Please select',null);
        return view('attendance.index',compact('totaldays','staffs','users','year','month'));

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
    public function store(CreateAttendanceRequest $request)
    {
        $input = $request->all();
        try {
            $input['clock_in_time']  = $request->attendance_date.' '.$request->clock_in_time;
            $input['clock_out_time'] = ($request->clock_out_time) ? $request->attendance_date.' '.$request->clock_out_time : null ;

            $attendance = $this->attendanceRepository->create($input);
            if($attendance) {
                $punch_in_data = array(
                    'attendance_id' => $attendance->id,
                    'punch_time'    => $attendance->clock_in_time,
                    'punch_type'    => 'punch_in'
                );
                $punch = $this->attendancePunchinRepository->create($punch_in_data);
                if($attendance->clock_out_time!='' && $attendance->clock_out_time!=null) {
                    $punch_out_data = array(
                        'attendance_id' => $attendance->id,
                        'punch_time'    => $attendance->clock_out_time,
                        'punch_type'    => 'punch_out'
                    );
                    $punch = $this->attendancePunchinRepository->create($punch_out_data);
                }
            }

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('Save successfully',['operator' => __('Attendance')]));
        return redirect(route('attendance.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendance         = $this->attendanceRepository->findWithoutFail($id);
        $attendanceActivity = $attendance->punches;

        $firstClockIn       = $this->attendanceRepository
                                   ->where(DB::raw('DATE(attendances.clock_in_time)'), $attendance->clock_in_time->format('Y-m-d'))
                                   ->where('user_id', $attendance->user_id)
                                   ->orderBy('id', 'asc')
                                   ->first();
        $lastClockOut       = $this->attendanceRepository
                                   ->where(DB::raw('DATE(attendances.clock_in_time)'), $attendance->clock_in_time->format('Y-m-d'))
                                   ->where('user_id', $attendance->user_id)
                                   ->orderBy('id', 'desc')
                                   ->first();
        $startTime          = Carbon::parse($firstClockIn->clock_in_time)->timezone(Config::get('app.timezone'));

        if (!is_null($lastClockOut->clock_out_time)) {
            $endTime = Carbon::parse($lastClockOut->clock_out_time)->timezone(Config::get('app.timezone'));
             $notClockedOut = false;
        } elseif (($lastClockOut->clock_in_time->timezone(Config::get('app.timezone'))->format('Y-m-d') != Carbon::now()->timezone(Config::get('app.timezone'))->format('Y-m-d')) && is_null($lastClockOut->clock_out_time)) {
            $endTime = Carbon::parse($startTime->format('Y-m-d') . ' 15:00:00', Config::get('app.timezone'));
            $notClockedOut = true;
        } else {
            $notClockedOut = true;
            $endTime       = Carbon::now()->timezone(Config::get('app.timezone'));
        }

        $totalTime = $endTime->diff($startTime, true)->format('%h.%i');


            $time1 = new DateTime(date('H:i:s',strtotime($attendance->clock_in_time)));
            $time2 = new DateTime(date('H:i:s',($attendance->clock_out_time!='') ? strtotime($attendance->clock_out_time) : strtotime(date('H:i:s')) ));
            $interval = $time1->diff($time2); 
            $total_hours = strtotime($interval->format('%H:%I:%S')) - strtotime('00:00:00');

            
            $breakPunches = $attendance->punches->toArray();
            if(count($breakPunches) > 0) {
                $breakTime = [];
                foreach(array_chunk($breakPunches,2) as $punch) {
                    if(isset($punch[0])) {
                        isset($punch[1]) ? '' : $punch[1]['punch_time'] = date('H:i:s') ;
                        $time1 = new DateTime(date('H:i:s',strtotime($punch[0]['punch_time'])));
                        $time2 = new DateTime(date('H:i:s',strtotime($punch[1]['punch_time'])));
                        $interval = $time1->diff($time2);
                        $breakTime[] = strtotime($interval->format('%H:%I:%S')) - strtotime("00:00:00");    
                    }
                }
                $working_hours = array_sum($breakTime);
            } else {
                $working_hours = 0;
            }
            
            $break =  date('H:i:s',strtotime('00:00:00') + ($total_hours - $working_hours));    
        

        return view('attendance.attendance_info', 
            compact(
                'attendance',
                'attendanceActivity',
                'firstClockIn',
                'lastClockOut',
                'startTime',
                'endTime',
                'notClockedOut',
                'totalTime',
                'break',
                'working_hours'
            ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $department = $this->departmentsRepository->findWithoutFail($id);
        if (empty($department)) {
            Flash::error(__('Not Found',['operator' => __('Department')]));
            return redirect(route('departments.index'));
        }
        return view('department.index')->with('department',$department);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateDepartmentRequest $request)
    {
        $department = $this->departmentsRepository->findWithoutFail($id);
        if (empty($department)) {
            Flash::error('Department not found');
            return redirect(route('departments.index'));
        }
        $input = $request->all();
        try {
            $department = $this->departmentsRepository->update($input, $id);
            if(isset($input['image'][0]) && $input['image'][0]){
                $cacheUpload = $this->uploadRepository->getByUuid($input['image'][0]);
                $mediaItem = $cacheUpload->getMedia('image')->first();
                $mediaItem->copy($department, 'image');
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('Updated successfully',['operator' => __('Department')]));
        return redirect(route('departments.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendance = $this->attendanceRepository->findWithoutFail($id);
        if (empty($attendance)) {
            Flash::error('Department not found');
            return redirect(route('attendance.index'));
        }
        $this->attendancePunchinRepository->where('attendance_id',$attendance->id)->delete();
        $this->attendanceRepository->delete($id);

        Flash::success(__('Deleted successfully',['operator' => __('Attendance')]));
        return redirect(route('attendance.index'));
    }





    public function summaryData(Request $request)
    {
        $employees = $this->userRepository->with(
            ['attendance' => function ($query) use ($request) {
                $query->whereRaw('MONTH(attendances.clock_in_time) = ?', [$request->month])
                    ->whereRaw('YEAR(attendances.clock_in_time) = ?', [$request->year]);
            }]
        )->with("roles")->whereHas('roles', function ($q) { $q->where('name', 'manager')->orWhere('name', 'supervisor')->orWhere('name', 'worker')->orWhere('name', 'driver'); });

        if ($request->userId == '') {
            $employees = $employees->get();
        } else {
            $employees = $employees->where('users.id', $request->userId)->get();
        }
        $holidays = $this->holidaysRepository
                               ->whereRaw('MONTH(holidays.date) = ?', [$request->month])
                               ->whereRaw('YEAR(holidays.date) = ?', [$request->year])
                               ->get();
        $final = [];

        $daysInMonth = Carbon::parse('01-' . $request->month . '-' . $request->year)->daysInMonth;
        $month             = Carbon::parse('01-' . $request->month . '-' . $request->year)->lastOfMonth();
        $now               = Carbon::now();
        $requestedDate     = Carbon::parse(Carbon::parse('01-' . $request->month . '-' . $request->year))->endOfMonth();

        foreach ($employees as $employee) {

            if($requestedDate->isPast()){
                $dataTillToday = array_fill(1, $daysInMonth, 'Absent');
            }
            else{
                $dataTillToday = array_fill(1, $now->copy()->format('d'), 'Absent');
            }

            $dataFromTomorrow = [];
            if (($now->copy()->addDay()->format('d') != $daysInMonth) && !$requestedDate->isPast()) {
                $dataFromTomorrow = array_fill($now->copy()->addDay()->format('d'), ($daysInMonth - $now->copy()->format('d')), '-');
            } else {
                if($daysInMonth < $now->copy()->format('d')){
                    $dataFromTomorrow = array_fill($month->copy()->addDay()->format('d'), (0), 'Absent');
                }
                else{
                    $dataFromTomorrow = array_fill($month->copy()->addDay()->format('d'), ($daysInMonth - $now->copy()->format('d')), 'Absent');
                }
            }
            $final[$employee->id . '#' . $employee->name] = array_replace($dataTillToday, $dataFromTomorrow);

            foreach ($employee->attendance as $attendance) {
                ($attendance->attendance_type=='regular') ? $icon = '<i class="fa fa-check text-success"></i>' : $icon = '<i class="fa fa-star text-success"></i>' ;
                $final[$employee->id.'#'.$employee->name][Carbon::parse($attendance->clock_in_time)->timezone(Config::get('app.timezone'))->day] = '
                    <a href="javascript:;" class="view-attendance" data-attendance-id="'.$attendance->id.'">
                        '.$icon.'
                    </a>
                    ';
            }
            
            /*foreach ($employee->leaves as $leave) {
                $final[$employee->id.'#'.$employee->name][Carbon::parse($leave->leave_date)->timezone(Config::get('app.timezone'))->day] = '
                    <a href="javascript:;" class="view-attendance" data-attendance-id="'.$attendance->id.'">
                        <i class="fa fa-star text-success"></i>
                    </a>
                    ';
            }*/

            if(count($employee->getMedia('image')) > 0) {
                $image = $employee->getMedia('image')[0]->getUrl();
            } else {
                $image = asset('images/avatar_default.png');    
            }

            $final[$employee->id.'#'.$employee->name][] = '
                <h2 class="table-avatar">
                    <a style="margin-right:15px;" id="userID'.$employee->id.'" data-employee-id="'.$employee->id.'" class="avatar userData">
                        <img style="width:30px; border-radius:20px;" class="avatar" alt="user" src="'.$image.'">
                    </a>
                    <a>'.$employee->name.'</a>
                </h2>
            ';    

            foreach ($holidays as $holiday) {
                if ($final[$employee->id . '#' . $employee->name][(int)date('d',strtotime($holiday->date))] == 'Absent' || $final[$employee->id . '#' . $employee->name][(int)date('d',strtotime($holiday->date))] == '-') {
                    $final[$employee->id . '#' . $employee->name][(int)date('d',strtotime($holiday->date))] = 'Holiday';
                }
            }
        }

        $employeeAttendence = $final;
        $view = view('attendance.summary_data', compact('employeeAttendence','holidays','daysInMonth'))->render();
        return ['status' => 'success', 'data' => $view];
    }    


    public function mark(Request $request)
    {   
        $userid = $request->userid; 
        $day    = $request->day; 
        $month  = $request->month;
        $year   = $request->year;

        $date           = Carbon::createFromFormat('d-m-Y', $day . '-' . $month . '-' . $year)->format('Y-m-d');
        $row            = $this->attendanceRepository->attendanceByUserDate($userid, $date);
        $clock_in       = 0;
        $total_clock_in = $this->attendanceRepository->where('user_id', $userid)
                                    ->where(DB::raw('DATE(attendances.clock_in_time)'), '=', $date)
                                    ->whereNull('attendances.clock_out_time')
                                    ->count();
        $userid         = $userid;
        $type           = 'add';
        $maxAttandenceInDay = $this->attendanceRepository->getTotalClockIn($date);
        return view('attendance.attendance_mark', compact('date','row','clock_in','total_clock_in','userid','type','maxAttandenceInDay'));
    }

    public function punch(Request $request) {
        $type       = $request->punchtype;
        $break_type = isset($request->break_type) ? $request->break_type : '' ;
        if($type=='punch-in') {
            if($break_type=='start') {    
                $data = array(
                    'user_id'        => auth()->user()->id,
                    'clock_in_time'  => date('H:i:s'),
                    'clock_in_ip'    => $request->ip(),
                    'working_from'   => '',
                    'late'           => 'no',
                    'half_day'       => 'no'
                );
                $attendance = $this->attendanceRepository->create($data);
                $punchin    = $this->attendancePunchinRepository->create([
                    'attendance_id' => $attendance->id,
                    'punch_time'    => date('Y-m-d H:i:s'),
                    'punch_type'    => 'punch_in'
                ]);
            } else {
                $attendance = $this->attendanceRepository
                               ->where('user_id',auth()->user()->id)
                               ->whereDate('clock_in_time',date('Y-m-d'))
                               ->first();
                if($attendance) {
                    $last_punch  = $attendance->lastPunch;
                    ($last_punch->punch_type=='punch_in') ? $ptype='punch_out' : $ptype='punch_in' ;
                    $punch    = $this->attendancePunchinRepository->create([
                        'attendance_id' => $attendance->id,
                        'punch_time'    => date('Y-m-d H:i:s'),
                        'punch_type'    => $ptype
                    ]);
                }
            }

        } elseif($type=='punch-out') {
            
            if($break_type=='eod') {
                $attendance = $this->attendanceRepository
                               ->where('user_id',auth()->user()->id)
                               ->whereDate('clock_in_time',date('Y-m-d'))
                               ->first();
                if($attendance) {
                    $data  = array(
                        'clock_out_time' => date('H:i:s'),
                        'clock_out_ip'   => $request->ip()
                    );
                    $attendanceUpdate = $this->attendanceRepository->update($data, $attendance->id);
                    $punchout         = $this->attendancePunchinRepository->create([
                        'attendance_id' => $attendance->id,
                        'punch_time'    => date('Y-m-d H:i:s'),
                        'punch_type'    => 'punch_out'
                    ]);
                }
            } else {
                $attendance = $this->attendanceRepository
                               ->where('user_id',auth()->user()->id)
                               ->whereDate('clock_in_time',date('Y-m-d'))
                               ->first();
                if($attendance) {
                    $last_punch  = $attendance->lastPunch;
                    ($last_punch->punch_type=='punch_in') ? $ptype='punch_out' : $ptype='punch_in' ;
                    $punch    = $this->attendancePunchinRepository->create([
                        'attendance_id' => $attendance->id,
                        'punch_time'    => date('Y-m-d H:i:s'),
                        'punch_type'    => $ptype
                    ]);
                }
            } 

        }    

    }

    public function enable(Request $request) {
        $attendance = $this->attendanceRepository->findWithoutFail($request->id);
        if (empty($attendance)) {
            Flash::error('Attendance not found');
            return redirect(route('attendance.index'));
        }
        try {
            $data  = array(
                'clock_out_time' => null,
                'clock_out_ip'   => null
            );
            $attendanceUpdate = $this->attendanceRepository->update($data, $attendance->id);
            $last_punch       = $attendance->lastPunch;
            $this->attendancePunchinRepository->delete($last_punch->id);

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
    }    

}
