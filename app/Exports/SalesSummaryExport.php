<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class SalesSummaryExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
   protected $start_date,$end_date;

 function __construct($start_date,$end_date) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
 }


    public function collection()
    {
               
            
            $sales_data     = DB::table('sales_invoice')
                            ->join('markets', 'sales_invoice.market_id','=','markets.id')
                            ->leftJoin('customer_groups', 'markets.customer_group','=','customer_groups.id')
                            ->leftJoin('user_markets', 'markets.id','=','user_markets.market_id')
                            ->leftJoin('users', 'user_markets.user_id','=','users.id')
                            ->leftJoin('customer_levels', 'users.level','=','customer_levels.id')
                            ->leftJoin('sales_invoice_detail', 'sales_invoice.id','=','sales_invoice_detail.sales_invoice_id')
                            ->leftJoin('payment_mode', 'sales_invoice.sales_payment_method','=','payment_mode.id')
                            ->select('sales_invoice.sales_cash_paid','sales_invoice.sales_date','sales_invoice.sales_code','markets.name as market_id','customer_groups.name as party_group','customer_levels.name as reward_level',DB::raw('COUNT(sales_invoice_detail.sales_invoice_id) as no_of_items'),'sales_total_amount','payment_mode.name as payment_type')
                            ->whereBetween('sales_invoice.sales_date', [$this->start_date, $this->end_date])
                            ->groupBy('sales_invoice_detail.sales_invoice_id')->get();
                            
             foreach ($sales_data as $key => $value) {
              if($value->sales_cash_paid >= $value->sales_total_amount) {
                     $sales_data[$key]->payment_status  = 'Paid';
                } elseif($value->sales_cash_paid > 0) {
                     $sales_data[$key]->payment_status  = 'Partially Paid';
                } else {
                     $sales_data[$key]->payment_status  = 'Unpaid';
                }
                
                unset($sales_data[$key]->sales_cash_paid);
        }
        
        

        return $sales_data;
    }

    public function headings(): array
    {
          $start_date = date("d-M-Y", strtotime($this->start_date));  
          $end_date = date("d-M-Y", strtotime($this->end_date));  

    	  $total_transaction = DB::table('sales_invoice')->whereBetween('sales_date', [$this->start_date, $this->end_date])->sum('sales_total_amount');
      
         return [
           ['#Sales Summary Report'],
           ['Dated: '.$start_date.' - '.$end_date],
           ['Total Sales: '.$total_transaction],
           [],
           [],
           ['DATE','INVOICE NO','PARTY NAME','PARTY GROUP','PARTY REWARD LEVEL','NUMBER OF ITEMS','TRANSACTION AMOUNT','PAYMENT TYPE','PAYMENT STATUS'],
        ];
    }
}
