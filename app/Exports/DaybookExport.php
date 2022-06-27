<?php

namespace App\Exports;
use App\Models\TransactionTrack;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class DaybookExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
   protected $start_date,$end_date;

 function __construct($start_date,$end_date) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
 }


    public function collection()
    {

            $data = TransactionTrack::select(
                'transaction_track.transaction_number',
                'transaction_track.transaction_track_category',
                'transaction_track.transaction_track_market_id'
            )->distinct();

            if($this->start_date!='' & $this->end_date!='') {
                $data->whereBetween('transaction_track.transaction_track_date', [$this->start_date, $this->end_date]);
            }
            $datas = $data->get();

             for($i=0; $i<count($datas); $i++) {

               if($datas[$i]->transaction_track_category=='purchase') {

               $invoice     = DB::table('purchase_invoice')
                                        ->select('purchase_date','purchase_total_amount')
                                        ->where('id',$datas[$i]->transaction_number)->get();
           
               $datas[$i]->transaction_track_date = $invoice[0]->purchase_date;  

                $datas[$i]->credit   = number_format($invoice[0]->purchase_total_amount,2); 
                   $purchase_debit      = DB::table('transaction_track')
                                                ->where('transaction_number',$datas[$i]->transaction_number)
                                                ->where('transaction_track_category','purchase')
                                                ->where('transaction_track_type','debit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$this->start_date,$this->end_date])
                                                ->sum('transaction_track_amount');
                   $datas[$i]->debit     = ($purchase_debit > 0) ? number_format($purchase_debit,2):'';

             }

             if($datas[$i]->transaction_track_category=='sales') {
                   $invoice = DB::table('sales_invoice')
                                        ->select('sales_date','sales_total_amount')
                                        ->where('id',$datas[$i]->transaction_number)->get();
                   $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice[0]->sales_date));

                   $sales_credit            = DB::table('transaction_track')
                                                    ->where('transaction_number',$datas[$i]->transaction_number)
                                                    ->where('transaction_track_category','sales')
                                                    ->where('transaction_track_type','credit')
                                                    ->whereBetween('transaction_track.transaction_track_date', [$this->start_date, $this->end_date])
                                                    ->sum('transaction_track_amount'); 
                   $datas[$i]->credit       = ($sales_credit > 0) ? number_format($sales_credit,2) :  '' ; 
                   $datas[$i]->debit        = number_format($invoice[0]->sales_total_amount,2);
               }

                if($datas[$i]->transaction_track_category=='payment_in') {
                   $invoice = DB::table('payment_in')
                                        ->select('payment_in_date','payment_in_amount')
                                        ->where('id',$datas[$i]->transaction_number)->get();
                   $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice[0]->payment_in_date)); 
                   $datas[$i]->credit                  = number_format($invoice[0]->payment_in_amount,2); 
                   $datas[$i]->debit                   = '';
               }

               if($datas[$i]->transaction_track_category=='payment_out') {
                   $invoice = DB::table('payment_out')
                                        ->select('payment_out_date','payment_out_amount')
                                        ->where('id',$datas[$i]->transaction_number)->get();
                   $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice[0]->payment_out_date));
                   $datas[$i]->credit                  = '';
                   $datas[$i]->debit                   = number_format($invoice[0]->payment_out_amount,2); 
                   
               }

               $datas[$i]->transaction_track_category = ucfirst( str_replace('_', ' ', $datas[$i]->transaction_track_category) );
               $market_name = DB::table('markets')->where('id',$datas[$i]->transaction_track_market_id)->get();
              $datas[$i]->party = $market_name[0]->name;
              
              
              unset($datas[$i]->transaction_track_market_id);

                 
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

        $total_debit = DB::table('transaction_track')
                                                ->where('transaction_track_type','debit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$this->start_date, $this->end_date])
                                                ->sum('transaction_track_amount');
         $total_credit = DB::table('transaction_track')
                                                ->where('transaction_track_type','credit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$this->start_date, $this->end_date])
                                                ->sum('transaction_track_amount');
         $total_amount = DB::table('transaction_track')
                                                ->whereBetween('transaction_track.transaction_track_date', [$this->start_date, $this->end_date])
                                                ->sum('transaction_track_amount');
      
         return [
           ['#Daybook Report'],
           ['Dated: '.$start_date.' - '.$end_date],
           ['Total Debit: '.$total_debit],
           ['Total Credit: '.$total_credit],
           [],
           [],
           ['TRANSACTION NO','TRANSACTION TYPE','DATE','CREDIT','DEBIT','PARTY'],
        ];
    }
}
