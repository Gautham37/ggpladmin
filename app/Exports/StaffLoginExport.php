<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\User;
use DB;

class StaffLoginExport implements FromCollection,WithHeadings
{
   protected $start_date,$end_date,$user_id,$user_role;

 function __construct($start_date,$end_date,$user_id,$user_role) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->user_id = $user_id;
        $this->user_role = $user_role;
 }


    public function collection()
    {
          if($this->user_role!='')
            {

               if($this->user_role=="admin")
                {
                  $data    = User::whereHas('roles', function($q){$q->where('name','=','admin');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.name','users.email','user_log.created_at as created','user_log.updated_at as updated');
                }
                else if($this->user_role=='manager')
                {
                  $data    = User::whereHas('roles', function($q){$q->where('name','=','manager');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.name','users.email','user_log.created_at as created','user_log.updated_at as updated');  
                }
                else
                {
                    $data    = User::whereHas('roles', function($q){$q->where('name','=','driver');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.name','users.email','user_log.created_at as created','user_log.updated_at as updated');
                }
                
            }else{
                $data    = User::whereHas('roles', function($q){$q->where('name','!=','client');})->join('user_log', 'users.id','=','user_log.user_id')->select('users.name','users.email','user_log.created_at as created','user_log.updated_at as updated');
            }

        if($this->start_date!='' & $this->end_date!='') {
             $data->whereDate('user_log.created_at','>=',$this->start_date)->whereDate('user_log.created_at','<=',$this->end_date);
            //$data->whereBetween('user_log.created_at', [$this->start_date, $this->end_date]);
        } 
        if($this->user_id!=0) {
         
            $data->where('users.id',$this->user_id);
        }  
        $datas = $data->get();
        
      
        return $datas;
    }

    public function headings(): array
    {

         $start_date = date("d-M-Y", strtotime($this->start_date));  
         $end_date = date("d-M-Y", strtotime($this->end_date)); 
      
         return [
           ['#Staff Login Report'],
           ['Dated: '.$start_date.' - '.$end_date],
           [],
           [],
           ['NAME','EMAIL','LOGIN','LOGOUT'],
        ];
    }
}
