<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class PopularProductsExport implements FromCollection,WithHeadings
{
   protected $start_date,$end_date;

 function __construct($start_date,$end_date) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
 }


    public function collection()
    {
           
           $sales_data = DB::select('SELECT name,bar_code,purchase_price,price,stock,unit,stockvalue FROM(SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(b.quantity) AS total_quantity
FROM products a JOIN product_orders b ON b.product_id = a.id GROUP BY a.id, b.product_id UNION SELECT a.id,a.name,a.bar_code,a.hsn_code,a.purchase_price,a.price,a.stock,a.unit,a.stock*a.purchase_price as stockvalue, SUM(c.sales_detail_quantity) AS total_quantity
FROM products a JOIN sales_invoice_detail c ON c.sales_detail_product_id = a.id GROUP BY a.id, c.sales_detail_product_id
) temp GROUP BY id order by total_quantity desc');

      
        return collect($sales_data);
    }

    public function headings(): array
    {

        $date = date("d-M-Y");  
      
         return [
           ['#Popular Products Report'],
           ['Dated: '.$date],
           [],
           [],
           ['ITEM NAME','ITEM CODE','PURCHASE PRICE','SELLING PRICE','STOCK QUANTITY','UNIT','STOCK VALUE'],
        ];
    }
}
