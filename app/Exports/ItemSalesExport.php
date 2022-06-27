<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class ItemSalesExport implements FromCollection,WithHeadings
{
   
   protected $start_date,$end_date;

 function __construct($start_date,$end_date) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
 }


    public function collection()
    {
               
            $datas = DB::table('sales_invoice_detail')
                      ->select('sales_detail_product_id','sales_detail_product_name')
                      ->distinct()
                      ->whereBetween('sales_invoice_detail.created_at', [$this->start_date, $this->end_date])
                      ->get();
            foreach ($datas as $key => $value) {
              $product            = DB::table('products')
                                        ->select('*')
                                        ->where('id',$datas[$key]->sales_detail_product_id)->get();
              $datas[$key]->unit  = $product[0]->unit;             
              $datas[$key]->total_sales = DB::table('sales_invoice_detail')
                                            ->where('sales_detail_product_id',$datas[$key]->sales_detail_product_id)
                                            ->whereBetween('sales_invoice_detail.created_at', [$this->start_date, $this->end_date])
                                            ->sum('sales_detail_quantity');
                                            
                                            
              unset($datas[$key]->sales_detail_product_id);      
            }
             
        return $datas;
    }

    public function headings(): array
    {
       $start_date = date("d-M-Y", strtotime($this->start_date));  
          $end_date = date("d-M-Y", strtotime($this->end_date));  
          
         $datas = DB::table('sales_invoice_detail')
                      ->select('sales_detail_product_id')
                      ->distinct()
                      ->whereBetween('sales_invoice_detail.created_at', [$this->start_date, $this->end_date])
                      ->get();
            $total_sales=0;
            foreach ($datas as $key => $value) {
              $product            = DB::table('products')
                                        ->select('*')
                                        ->where('id',$datas[$key]->sales_detail_product_id)->get();
              $total_sales += DB::table('sales_invoice_detail')
                                            ->where('sales_detail_product_id',$datas[$key]->sales_detail_product_id)
                                            ->whereBetween('sales_invoice_detail.created_at', [$this->start_date, $this->end_date])
                                            ->sum('sales_detail_quantity');
              
            }
       
         return [
           ['#Item Sales Report'],
           ['Dated: '.$start_date.' - '.$end_date],
           ['Total Item Quantity: '.$total_sales],
           [],
           [],
           ['ITEM NAME','UNIT','QUANTITY'],
        ];
    }
}
