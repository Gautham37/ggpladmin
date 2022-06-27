<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class StockPurchaseExport implements FromCollection,WithHeadings
{
  

    public function collection()
    {
          $data = DB::select('SELECT id, name,bar_code,hsn_code,purchase_price,price,stock,unit,stockvalue, SUM(total_quantity) AS ordered_quantity FROM(SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.sales_detail_quantity) AS total_quantity
FROM products a JOIN sales_invoice_detail c ON c.sales_detail_product_id = a.id GROUP BY a.id, c.sales_detail_product_id
) temp GROUP BY id order by total_quantity asc');

       $arr=[];
       foreach ($data as $key => $value) {
          $items[] = $value;
   
           $arr[]=$value->id;
            unset($value->id);
       }

          $data1    = DB::table('products')->select('id','name','bar_code','hsn_code','purchase_price','price','stock','unit',DB::raw('(stock * purchase_price) as stockvalue'))->whereNotIn('id', $arr)->get();
     if(count($data1)>0){
      foreach ($data1 as $key => $value1) {
           $item[] = $value1;
           
           unset($value1->id);
      }
      
     $data2 =  array_merge($item,$items);
      }else
      {
        $data2 =   $items;
      }
     $datas = $data2;//array_slice($data2, 0, 10);
     
   
     
      
        return collect($datas);
    }

    public function headings(): array
    {

         $date = date("d-M-Y"); 
      
         return [
           ['#Stock Purchase Report'],
           ['Dated: '.$date],
           [],
           [],
           ['ITEM NAME','ITEM CODE','HSN CODE','PURCHASE PRICE','SELLING PRICE','STOCK QUANTITY','UNIT','STOCK VALUE','ORDERED QUANTITY'],
        ];
    }
}
