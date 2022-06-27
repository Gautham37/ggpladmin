<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class ProductsExport implements FromCollection,WithHeadings
{
   protected $start_date,$end_date,$product;

 function __construct($start_date,$end_date,$product) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
         $this->product = $product;
 }


    public function collection()
    {
           $data    = DB::table('products')
                            ->leftJoin('product_orders', 'product_orders.product_id','=','products.id')
                            ->select('products.name','products.bar_code','products.hsn_code','products.purchase_price','products.price','products.stock','products.unit',DB::raw('(products.stock * products.purchase_price) as stockvalue'),'product_orders.quantity as order_quantity',\DB::raw('Date(product_orders.created_at) AS ordered_date'))
                            ->where('order_id','!=','NULL');
             if($this->product!=0) {
           
            $data->where('products.id',$this->product);
        }   
        
        if($this->start_date!='' & $this->end_date!='') {
            $data->whereDate('product_orders.created_at','>=',$this->start_date)->whereDate('product_orders.created_at','<=',$this->end_date);
        } 
        
         $items = $data->get();

        $data1    = DB::table('products')
                            ->leftJoin('sales_invoice_detail', 'sales_invoice_detail.sales_detail_product_id','=','products.id')
                            ->select('products.name','products.bar_code','products.hsn_code','products.purchase_price','products.price','products.stock','products.unit',DB::raw('(products.stock * products.purchase_price) as stockvalue'),'sales_invoice_detail.sales_detail_quantity as order_quantity',\DB::raw('Date(sales_invoice_detail.created_at) AS ordered_date'))
                            ->where('sales_invoice_id','!=','NULL');
        
           if($this->product!=0) {
           
            $data1->where('products.id',$this->product);
        } 
        
         if($this->start_date!='' & $this->end_date!='') {
            $data1->whereDate('sales_invoice_detail.created_at','>=',$this->start_date)->whereDate('sales_invoice_detail.created_at','<=',$this->end_date);
        }   
        
        $items1 = $data1->get();
                          
        $datas = array_merge($items->toArray(), $items1->toArray());
      
        return collect($datas);
         
       
    }

    public function headings(): array
    {

         $start_date = date("d-M-Y", strtotime($this->start_date));  
          $end_date = date("d-M-Y", strtotime($this->end_date));  

         return [
           ['#Products Report'],
            ['Dated: '.$start_date.' - '.$end_date],
           [],
           [],
           ['ITEM NAME','ITEM CODE','HSN CODE','PURCHASE PRICE','SELLING PRICE','STOCK QUANTITY','UNIT','STOCK VALUE','ORDERED QUANTITY','ORDERED DATE'],
        ];
    }
}
