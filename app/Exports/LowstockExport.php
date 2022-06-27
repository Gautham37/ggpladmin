<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class LowstockExport implements FromCollection,WithHeadings
{
   
    protected $start_date,$end_date;

 function __construct($start_date,$end_date) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        
 }


    public function collection()
    {
            
            $stock_data = DB::table('products')
                                        ->select('name','bar_code','stock','unit','low_stock_unit',DB::raw('(stock * purchase_price) as stockvalue'))
                                        ->whereRaw('low_stock_unit >= stock')
                                        ->get();
             
        return $stock_data;
    }

    public function headings(): array
    {
         $dated = date("d-M-Y");  

        $total_value =  DB::table('products')
                                        ->select(DB::raw('(sum(stock * purchase_price)) as stockvalue'))
                                        ->whereRaw('low_stock_unit >= stock')
                                        ->get();
     
         return [
           ['#Low Summary Report'],
           ['Dated: '.$dated],
           ['Total Stock Value: '.$total_value[0]->stockvalue],
           [],
           [],
           ['ITEM NAME','ITEM CODE','STOCK QUANTITY','UNIT','LOW STOCK LEVEL','STOCK VALUE'],
        ];
    }
}
