<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class PartywiseExport implements FromCollection,WithHeadings
{
    
    public function collection()
    {
            
            $stock_data = DB::table('markets')
                                  ->leftJoin('transaction_track','markets.id','=','transaction_track.transaction_track_market_id')
                                  ->leftJoin('customer_groups','markets.customer_group','=','customer_groups.id')
                                  ->select('markets.name','customer_groups.name as party_group',\DB::raw('count(transaction_track.id) as total_no_transactions'),'balance',\DB::raw('max(transaction_track.transaction_track_date) as last_transaction'))
                                  ->groupBy('markets.id')->get();
         
             
        return $stock_data;
    }

    public function headings(): array
    {
         $dated = date('d-M-Y');
     
         return [
           ['#Party Wise Outstanding Report'],
           ['Dated: '.$dated],
           [],
           [],
           ['NAME','PARTY GROUP','TOTAL NUMBER OF TRANSACTIONS','CLOSING BALANCE','DATE OF LAST TRANSACTION'],
        ];
    }
}
