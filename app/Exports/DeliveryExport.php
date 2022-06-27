<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class DeliveryExport implements FromCollection,WithHeadings
{
   

    public function collection()
    {
              $datas = DB::table('orders')
                ->leftJoin('delivery_addresses', 'delivery_addresses.id','=','orders.delivery_address_id')
                 ->leftJoin('users', 'users.id','=','orders.user_id')
                 ->leftJoin('payments', 'payments.id','=','orders.payment_id')
                 ->leftJoin('order_statuses', 'order_statuses.id','=','orders.order_status_id')
                ->select('users.name','address_line_1','address_line_2','city','state','pincode','delivery_addresses.description','orders.delivery_distance','orders.driver_id','orders.id as order_id','order_statuses.status as order_status','payments.method','payments.status as payment_status')
                ->get();

              foreach ($datas as $key => $value) {
               
               if($value->address_line_1!=''){
                $datas[$key]->address = $value->address_line_1.', '.$value->address_line_2.', '.$value->city.', '.$value->state.'-'.$value->pincode;
               }else
               {
                $datas[$key]->address = '';
               }
               
               if($value->driver_id!=''){
                    $get_driver = DB::table('users')->select('name')->where('id',$value->driver_id)->first();
                    $datas[$key]->delivered_by = $get_driver->name;
               }else
               {
                    $datas[$key]->delivered_by ='';
               }
               
               $datas[$key]->transaction_number ='';
               
               unset($datas[$key]->address_line_1);
                unset($datas[$key]->address_line_2);
                 unset($datas[$key]->city);
                  unset($datas[$key]->state);
                   unset($datas[$key]->pincode);
                   unset($datas[$key]->driver_id);
               }
         
       
        return $datas;
    }

    public function headings(): array
    {

         $date = date("d-M-Y");  
      
         return [
           ['#Delivery Report'],
           ['Dated: '.$date],
           [],
           [],
           ['NAME','DESCRIPTION','DISTANCE','ORDER ID','ORDER STATUS','PAYMENT TYPE','PAYMENT STATUS','ADDRESS','DELIVERED BY','TRANSACTION NUMBER'],
        ];
    }
}
