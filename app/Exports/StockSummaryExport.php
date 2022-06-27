<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class StockSummaryExport implements FromCollection,WithHeadings
{
   


    public function collection()
    {
               
            
            $datas = DB::table('products')
                                        ->select(DB::raw('DATE(created_at)'),'name','bar_code','hsn_code','purchase_price','price','unit','stock',DB::raw('(stock * purchase_price) as stockvalue'))
                                        ->get();
            foreach ($datas as $key => $value) {
                
                 $profit = $value->price - $value->purchase_price;
                 if($value->purchase_price>0){
                 $profit_percentage = ($profit*100)/$value->purchase_price;
                 }else{ $profit_percentage =100; }
                 if($profit_percentage>0){ $profit_percent = $profit_percentage; } else{ $profit_percent = 0; }
                  $datas[$key]->profit_percentage   = number_format($profit_percent,2).'%';
            }
             
        return $datas;
    }

    public function headings(): array
    {

      $dated = date('d-M-Y');
       
        $total_value =  DB::table('products')
                                        ->select(DB::raw('(sum(stock * purchase_price)) as stockvalue'))
                                        ->get();
     
         return [
           ['#Stock Summary Report'],
           ['Dated: '.$dated],
           ['Total Stock Value: '.$total_value[0]->stockvalue],
           [],
           [],
           ['DATE','ITEM NAME','ITEM CODE','HSN CODE','PURCHASE PRICE','SELLING PRICE','UNIT','STOCK QUANTITY','STOCK VALUE','PROFIT %'],
        ];
    }
}
