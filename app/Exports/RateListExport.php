<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class RateListExport implements FromCollection,WithHeadings
{
   


    public function collection()
    {
               
            
            $datas = DB::table('products')->select('id','name','bar_code','purchase_price','price')->get();
            $customer_groups = DB::table('customer_groups')->get();
            foreach ($datas as $key => $value) {

                foreach($customer_groups as $customer_group) {
                  $price_variation = DB::table('product_group_price')->where('customer_group_id',$customer_group->id)->where('product_id',$datas[$key]->id)->get();
                  if(count($price_variation) > 0) {
                    $datas[$key]->{strtolower($customer_group->name)} = number_format($price_variation[0]->product_price,2, '.', '');  
                  } else {
                    $datas[$key]->{strtolower($customer_group->name)} = '';
                  }
                }
                
                unset($datas[$key]->id);
            }
             
        return $datas;
    }

    public function headings(): array
    {
       $dated = date('d-M-Y');
       $datas = DB::table('products')->select('id','name','bar_code','purchase_price','price')->get();
       $count = $datas->count();
       
         return [
           ['#Rate List Report'],
           ['Dated: '.$dated],
           ['Total Items: '.$count],
           [],
           [],
           ['NAME','ITEM CODE','PURCHASE PRICE','SELLING PRICE','RETAIL','WHOLESALE'],
        ];
    }
}
