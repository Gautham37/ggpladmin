<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class CustomersExport implements FromCollection,WithHeadings
{
     protected $start_date,$end_date;
     
     function __construct($start_date,$end_date) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
 }

    
    public function collection()
    {
            
            $data =  DB::table('markets')
                                  ->leftJoin('transaction_track','markets.id','=','transaction_track.transaction_track_market_id')
                                  ->leftJoin('customer_groups','markets.customer_group','=','customer_groups.id')
                                  ->leftJoin('user_markets', 'markets.id','=','user_markets.market_id')
                                  ->leftJoin('users', 'user_markets.user_id','=','users.id')
                                  ->leftJoin('customer_levels', 'users.level','=','customer_levels.id')
                                  ->select('markets.name',DB::raw("CONCAT(markets.address_line_1,' ',markets.address_line_2) as address"),'markets.city','markets.state','markets.pincode','markets.email','markets.phone','markets.mobile','customer_groups.name as party_group',\DB::raw('count(transaction_track.id) as total_no_transactions'),'markets.created_at','customer_levels.name as levels','customer_levels.monthly_spend as reward_points')
                                  ->groupBy('markets.id')->where('markets.type',1);

               if($this->start_date!='' & $this->end_date!='') {
         
            $data->whereDate('markets.created_at','>=',$this->start_date)->whereDate('markets.created_at','<=',$this->end_date);
        }   
            $stock_data = $data->get();

            foreach ($stock_data as $key => $value) {
                if($stock_data[$key]->levels!=''){
                 $stock_data[$key]->reward_levels = $stock_data[$key]->levels.' - '.$stock_data[$key]->reward_points;
                }
             
             unset($stock_data[$key]->levels);
             unset($stock_data[$key]->reward_points);
            }
             
        return $stock_data;
    }

    public function headings(): array
    {
         $start_date = date("d-M-Y", strtotime($this->start_date));  
         $end_date = date("d-M-Y", strtotime($this->end_date)); 
     
         return [
           ['#Customers Report'],
           ['Dated: '.$start_date.' - '.$end_date],
           [],
           [],
           ['NAME','ADDRESS','CITY','STATE','PINCODE','EMAIL','PHONE NUMBER','MOBILE NUMBER','PARTY GROUP','NUMBER OF TRANSACTIONS','CREATED AT','REWARD LEVELS'],
        ];
    }
}
