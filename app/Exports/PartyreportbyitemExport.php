<?php

namespace App\Exports;
use App\Models\TransactionTrack;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class PartyreportbyitemExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
   protected $start_date,$end_date,$product;

 function __construct($start_date,$end_date,$product) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->product = $product;
 }


    public function collection()
    {

            $datas = DB::table('markets')->select('id','name')->get();

            foreach ($datas as $key => $value) {

             
                $sales_quantity = DB::table('sales_invoice')
                                  ->join('sales_invoice_detail','sales_invoice.id','=','sales_invoice_detail.sales_invoice_id')
                                  ->where('sales_invoice.market_id',$value->id)
                                  ->where('sales_invoice_detail.sales_detail_product_id',$this->product)
                                  ->whereBetween('sales_invoice.sales_date', [$this->start_date, $this->end_date])
                                  ->sum('sales_detail_quantity');

                $sales_invoice   = DB::table('sales_invoice')
                                  ->join('sales_invoice_detail','sales_invoice.id','=','sales_invoice_detail.sales_invoice_id')
                                  ->where('sales_invoice.market_id',$value->id)
                                  ->where('sales_invoice_detail.sales_detail_product_id',$this->product)
                                  ->whereBetween('sales_invoice.sales_date', [$this->start_date, $this->end_date])
                                  ->get();

                $sales_invoice_amount[] = 0;                                 
                if(count($sales_invoice) > 0) {
                  foreach ($sales_invoice as $key1 => $value1) {
                    $sales_invoice_amount[]= $value1->sales_detail_quantity * $value1->sales_detail_price;
                  }
                }

                $purchase_quantity = DB::table('purchase_invoice')
                                    ->join('purchase_invoice_detail','purchase_invoice.id','=','purchase_invoice_detail.purchase_invoice_id')
                                    ->where('purchase_invoice.market_id',$value->id)
                                    ->where('purchase_invoice_detail.purchase_detail_product_id',$this->product)
                                    ->whereBetween('purchase_invoice.purchase_date', [$this->start_date, $this->end_date])
                                    ->sum('purchase_detail_quantity');

                $purchase_invoice   = DB::table('purchase_invoice')
                                    ->join('purchase_invoice_detail','purchase_invoice.id','=','purchase_invoice_detail.purchase_invoice_id')
                                    ->where('purchase_invoice.market_id',$value->id)
                                    ->where('purchase_invoice_detail.purchase_detail_product_id',$this->product)
                                    ->whereBetween('purchase_invoice.purchase_date', [$this->start_date, $this->end_date])
                                    ->get();
                                  
                $purchase_invoice_amount[] = 0;                                 
                if(count($purchase_invoice) > 0) {
                  foreach ($purchase_invoice as $key1 => $value1) {
                    $purchase_invoice_amount[]= $value1->purchase_detail_quantity * $value1->purchase_detail_price;
                  }
                }                  

                $datas[$key]->sales_quantity    = $sales_quantity;
                $datas[$key]->sales_amount      = number_format(array_sum($sales_invoice_amount),2,'.','');
                $datas[$key]->purchase_quantity = $purchase_quantity;
                $datas[$key]->purchase_amount   = number_format(array_sum($purchase_invoice_amount),2,'.','');
                unset($sales_invoice_amount);
                unset($purchase_invoice_amount);
                 unset($datas[$key]->id);
            }

        return $datas;
            
            
        //     $sales_data     = DB::table('sales_invoice')
        //                                 ->join('markets', 'sales_invoice.market_id','=','markets.id')
        //                                 ->select('sales_invoice.sales_date','sales_invoice.id','markets.name as market_id','sales_total_amount')
        //                                 ->whereBetween('sales_invoice.sales_date', [$this->start_date, $this->end_date])->get();
             
        // return $sales_data;
    }

    public function headings(): array
    {
        
        $start_date = date("d-M-Y", strtotime($this->start_date));  
          $end_date = date("d-M-Y", strtotime($this->end_date)); 

        $product = DB::table('products')->select('name')->where('id',$this->product)->get();

         return [
           ['#Party report by item'],
           ['Dated: '.$start_date.' - '.$end_date],
           ['Item: '.$product[0]->name],
           [],
           [],
           ['PARTY NAME','SALES QUANTITY',' SALES AMOUNT','PURCHASE QUANTITY','PURCHASE AMOUNT'],
        ];
    }
}
