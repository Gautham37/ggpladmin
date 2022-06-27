<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class ProfitableProductsExport implements FromCollection,WithHeadings
{
   

    public function collection()
    {
           
          $datas = DB::select('SELECT  name,bar_code,hsn_code,purchase_price,price,stock,unit FROM(SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.sales_detail_quantity) AS total_quantity
FROM products a JOIN sales_invoice_detail c ON c.sales_detail_product_id = a.id GROUP BY a.id, c.sales_detail_product_id
) temp where purchase_price<price GROUP BY id order by total_quantity desc');

     if(count($datas)>0){
            foreach ($datas as $key => $value) {

                  $profit = $value->price - $value->purchase_price;
                 if($value->purchase_price>0){
                 $profit_percentage = ($profit*100)/$value->purchase_price;
                 }else{ $profit_percentage =100; }
                 if($profit_percentage>0){ $profit_percent = $profit_percentage; } else{ $profit_percent = 0; }
                  $datas[$key]->profit_percentage   = number_format($profit_percent,2).'%';
            }
        }
      
        return collect($datas);
    }

    public function headings(): array
    {

         $date = date("d-M-Y"); 
      
         return [
           ['#Profitable Products Report'],
           ['Dated: '.$date],
           [],
           [],
           ['ITEM NAME','ITEM CODE','HSN CODE','PURCHASE PRICE','SELLING PRICE','STOCK QUANTITY','UNIT','PROFIT %'],
        ];
    }
}
