<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class BitwiseprofitExport implements FromCollection,WithHeadings
{
    
      protected $start_date,$end_date;

 function __construct($start_date,$end_date) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
 }

    
    public function collection()
    {
            
           $sales_invoice = DB::table('sales_invoice')->select('sales_date','market_id','sales_code','id','sales_total_amount')
                             ->whereBetween('sales_invoice.sales_date', [$this->start_date, $this->end_date]);
            $datas         = $sales_invoice->get();
            foreach ($datas as $key => $value) {
               $get_markets = DB::table('markets')->select('name')->where('id',$value->market_id)->get();
                $datas[$key]->market_id = $get_markets[0]->name;

                 $datas[$key]->invoice_amount = $value->sales_total_amount;

                $sales_detail     = DB::table('sales_invoice_detail')->where('sales_invoice_id',$value->id)->get();
                $total_purchase[] = 0;
                if(count($sales_detail) > 0) {
                    foreach ($sales_detail as $key1 => $value1) {
                        $product          = DB::table('products')->select('purchase_price')->where('id',$value1->sales_detail_product_id)->get();
                        $total_purchase[] = $product[0]->purchase_price * $value1->sales_detail_quantity;
                    }
                }
                $datas[$key]->purchase_amount    = number_format(array_sum($total_purchase),2,'.','');
                $datas[$key]->profit_amount      = number_format($value->sales_total_amount - array_sum($total_purchase),2,'.','');
                $overall_profit[] = $datas[$key]->profit_amount;
                $datas[$key]->sales_total_amount = number_format($value->sales_total_amount,2,'.','');
                
                unset($total_purchase);
                unset($datas[$key]->id);
            }
            $profit_amount = number_format(array_sum($overall_profit),2,'.','');
        return $datas;
    }

    public function headings(): array
    {
          $start_date = date("d-M-Y", strtotime($this->start_date));  
         $end_date = date("d-M-Y", strtotime($this->end_date));  
         
          $sales_invoice = DB::table('sales_invoice')->select('sales_date','market_id','id','sales_total_amount')
                             ->whereBetween('sales_invoice.sales_date', [$this->start_date, $this->end_date]);
            $datas         = $sales_invoice->get();
            foreach ($datas as $key => $value) {
               $get_markets = DB::table('markets')->select('name')->where('id',$value->market_id)->get();
                $datas[$key]->market_id = $get_markets[0]->name;
                
                 $datas[$key]->invoice_amount = $value->sales_total_amount;

                $sales_detail     = DB::table('sales_invoice_detail')->where('sales_invoice_id',$value->id)->get();
                $total_purchase[] = 0;
                if(count($sales_detail) > 0) {
                    foreach ($sales_detail as $key1 => $value1) {
                        $product          = DB::table('products')->select('purchase_price')->where('id',$value1->sales_detail_product_id)->get();
                        $total_purchase[] = $product[0]->purchase_price * $value1->sales_detail_quantity;
                    }
                }
                $datas[$key]->purchase_amount    = number_format(array_sum($total_purchase),2,'.','');
                $datas[$key]->profit_amount      = number_format($value->sales_total_amount - array_sum($total_purchase),2,'.','');
                $overall_profit[] = $datas[$key]->profit_amount;
                $datas[$key]->sales_total_amount = number_format($value->sales_total_amount,2,'.','');
                unset($total_purchase);
            }
            $profit_amount = number_format(array_sum($overall_profit),2,'.','');
     
         return [
           ['#Bit Wise Profit Report'],
            ['Dated: '.$start_date.' - '.$end_date],
            ['Profit Amount: '.$profit_amount],
           [],
           [],
           ['DATE','PARTY NAME','INVOICE NO','INVOICE AMOUNT','SALES AMOUNT','PURCHASE AMOUNT','PROFIT'],
        ];
    }
}
