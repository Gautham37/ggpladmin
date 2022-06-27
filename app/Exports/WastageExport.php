<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use App\Models\InventoryTrack;

class WastageExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
   protected $start_date,$end_date,$product_id;

 function __construct($start_date,$end_date,$product_id) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->product_id = $product_id;
 }


    public function collection()
    {
               
             $data = InventoryTrack::select(
                'inventory_track.id',
                'inventory_track.inventory_track_date',
                'inventory_track.inventory_track_category',
                'inventory_track.inventory_track_type',
                'inventory_track.inventory_track_product_quantity',
                'inventory_track.inventory_track_product_id',
                'inventory_track.inventory_track_description',
                'inventory_track.inventory_track_product_uom'
            )->where('inventory_track_usage',2);
            
            if($this->product_id!='' && $this->product_id!=0) {
                $data->where('inventory_track_product_id',$this->product_id);
            }

               if($this->start_date!='' & $this->end_date!='') {
            $data->whereBetween('inventory_track.inventory_track_date', [$this->start_date, $this->end_date]);
        } 

            $datas = $data->get();
            
            $stocks[] = 0;
            for($i=0; $i<count($datas); $i++) {
                
                $product = DB::table('products')->select('*')->where('id',$datas[$i]->inventory_track_product_id)->first();
               
               $datas[$i]->product_name  = $product->name; 
                $datas[$i]->item_code  = $product->bar_code; 
                $datas[$i]->hsn_code  = $product->hsn_code; 
               if($datas[$i]->inventory_track_type=='add') {
                    $stock = array_sum($stocks) + $datas[$i]->inventory_track_product_quantity;
               } else if($datas[$i]->inventory_track_type=='reduce') {
                    $stock = array_sum($stocks) - $datas[$i]->inventory_track_product_quantity;
               }
               $datas[$i]->inventory_track_category = ucfirst(str_replace("_"," ",$datas[$i]->inventory_track_category));
               //($datas[$i]->inventory_track_type=='add') ? $operator='' : $operator='-';
               $datas[$i]->quantity                 = $datas[$i]->inventory_track_product_quantity.' '.$datas[$i]->inventory_track_product_uom;
                //$datas[$i]->inventory_closing_stock  = $stock.' '.$product->unit; 
                 $datas[$i]->inventory_description  = $datas[$i]->inventory_track_description; 
               
               unset($stocks);
               unset($datas[$i]->id);
                unset($datas[$i]->inventory_track_type);
                 unset($datas[$i]->inventory_track_product_quantity);
                 unset($datas[$i]->inventory_track_product_id);
                  unset($datas[$i]->inventory_track_description);
                  unset($datas[$i]->inventory_track_product_uom);
               $stocks[] = $stock;
            }
             
        return $datas;
    }

    public function headings(): array
    {
          $start_date = date("d-M-Y", strtotime($this->start_date));  
          $end_date = date("d-M-Y", strtotime($this->end_date));  

         return [
           ['#Wastage Report'],
           ['Dated: '.$start_date.' - '.$end_date],
           [],
           [],
           ['Date','Transaction Type','Item Name','Item Code','HSN Code','Quantity','Reason'],
        ];
    }
}
