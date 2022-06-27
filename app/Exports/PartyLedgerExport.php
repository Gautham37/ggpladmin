<?php

namespace App\Exports;
use App\Models\TransactionTrack;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class PartyLedgerExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
   protected $start_date,$end_date,$market_id;

 function __construct($start_date,$end_date,$market_id) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->market_id = $market_id;
 }


    public function collection()
    {

            $data = TransactionTrack::select(
            'transaction_track.transaction_number',
            'transaction_track.transaction_track_category'
        )->where('transaction_track_market_id',$this->market_id)->distinct();
        if($this->start_date!='' & $this->end_date!='') {
            $data->whereBetween('transaction_track.transaction_track_date', [$this->start_date, $this->end_date]);
        }
        $datas = $data->get();

        for($i=0; $i<count($datas); $i++) {

           if($datas[$i]->transaction_track_category=='purchase') {
               $invoice =  DB::table('purchase_invoice')
                                        ->select('purchase_date','purchase_total_amount','purchase_code')
                                        ->where('id',$datas[$i]->transaction_number)->get();
               $datas[$i]->transaction_code        = $invoice[0]->purchase_code;       
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice[0]->purchase_date));

               $datas[$i]->credit   = number_format($invoice[0]->purchase_total_amount,2); 
               $purchase_debit      = DB::table('transaction_track')
                                            ->where('transaction_number',$datas[$i]->transaction_number)
                                            ->where('transaction_track_category','purchase')
                                            ->where('transaction_track_type','debit')
                                            ->whereBetween('transaction_track.transaction_track_date', [$this->start_date, $this->end_date])
                                            ->sum('transaction_track_amount');
               $datas[$i]->debit     = ($purchase_debit > 0) ? number_format($purchase_debit,2):'';

           }

           if($datas[$i]->transaction_track_category=='purchase_return') {
               $invoice = DB::table('purchase_return')
                                        ->select('purchase_date','purchase_total_amount','purchase_code')
                                        ->where('id',$datas[$i]->transaction_number)->get();
                                        
               $datas[$i]->transaction_code        = $invoice[0]->purchase_code;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice[0]->purchase_date));

               $purchase_credit      = DB::table('transaction_track')
                                            ->where('transaction_number',$datas[$i]->transaction_number)
                                            ->where('transaction_track_category','purchase_return')
                                            ->where('transaction_track_type','credit')
                                            ->whereBetween('transaction_track.transaction_track_date', [$this->start_date, $this->end_date])
                                            ->sum('transaction_track_amount');
               $datas[$i]->credit     = ($purchase_credit > 0) ? number_format($purchase_credit,2):'';
               $datas[$i]->debit   = number_format($invoice[0]->purchase_total_amount,2);
           }

           if($datas[$i]->transaction_track_category=='sales') {
               $invoice =  DB::table('sales_invoice')
                                        ->select('sales_date','sales_total_amount','sales_code')
                                        ->where('id',$datas[$i]->transaction_number)->get();
               $datas[$i]->transaction_code        = $invoice[0]->sales_code;
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


           if($datas[$i]->transaction_track_category=='sales_return') {
               $invoice = DB::table('sales_return')
                                        ->select('sales_date','sales_total_amount','sales_code')
                                        ->where('id',$datas[$i]->transaction_number)->get();
               $datas[$i]->transaction_code        = $invoice[0]->sales_code;                         
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice[0]->sales_date));
               $datas[$i]->credit      = number_format($invoice[0]->sales_total_amount,2);
               $sales_debit            = DB::table('transaction_track')
                                                ->where('transaction_number',$datas[$i]->transaction_number)
                                                ->where('transaction_track_category','sales_return')
                                                ->where('transaction_track_type','debit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$this->start_date, $this->end_date])
                                                ->sum('transaction_track_amount'); 
               $datas[$i]->debit       = ($sales_debit > 0) ? number_format($sales_debit,2) :  '' ; 
               
           }

           if($datas[$i]->transaction_track_category=='payment_in') {
               $invoice = DB::table('payment_in')
                                        ->select('payment_in_date','payment_in_amount','payment_in_no')
                                        ->where('id',$datas[$i]->transaction_number)->get();
                $datas[$i]->transaction_code        = $invoice[0]->payment_in_no;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice[0]->payment_in_date)); 
               $datas[$i]->credit                  = number_format($invoice[0]->payment_in_amount,2); 
               $datas[$i]->debit                   = '';
              
           }

           if($datas[$i]->transaction_track_category=='payment_out') {
               $invoice = DB::table('payment_out')
                                        ->select('payment_out_date','payment_out_amount','payment_out_no')
                                        ->where('id',$datas[$i]->transaction_number)->get();
               $datas[$i]->transaction_code        = $invoice[0]->payment_out_no;
               $datas[$i]->transaction_track_date  = date('Y-m-d',strtotime($invoice[0]->payment_out_date));
               $datas[$i]->credit                  = '';
               $datas[$i]->debit                   = number_format($invoice[0]->payment_out_amount,2);
               
               
           }

           $datas[$i]->transaction_track_category = ucfirst( str_replace('_', ' ', $datas[$i]->transaction_track_category) );
           
           unset($datas[$i]->transaction_number);

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

        $data = TransactionTrack::select(
                        'transaction_track.transaction_number',
                        'transaction_track.transaction_track_category'
                    )->where('transaction_track_market_id',$this->market_id)->distinct();

            if($this->start_date!='' & $this->end_date!='') {
                $data->whereBetween('transaction_track.transaction_track_date', [$this->start_date, $this->end_date]);
            }
            $datas = $data->get();
            
            $total_credit = $total_debit = 0;
            
          

             for($i=0; $i<count($datas); $i++) {

               if($datas[$i]->transaction_track_category=='purchase') {

               $invoice     = DB::table('purchase_invoice')
                                        ->select('purchase_date','purchase_total_amount')
                                        ->where('id',$datas[$i]->transaction_number)->get();

                $total_credit   += $invoice[0]->purchase_total_amount; 
                   $purchase_debit      = DB::table('transaction_track')
                                                ->where('transaction_number',$datas[$i]->transaction_number)
                                                ->where('transaction_track_category','purchase')
                                                ->where('transaction_track_type','debit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$this->start_date,$this->end_date])
                                                ->sum('transaction_track_amount');
                   $total_debit   += $purchase_debit;

             }

              if($datas[$i]->transaction_track_category=='purchase_return') {

               $invoice     = DB::table('purchase_return')
                                        ->select('purchase_date','purchase_total_amount')
                                        ->where('id',$datas[$i]->transaction_number)->get();

                $total_debit   += $invoice[0]->purchase_total_amount; 
                   $purchase_credit      = DB::table('transaction_track')
                                                ->where('transaction_number',$datas[$i]->transaction_number)
                                                ->where('transaction_track_category','purchase_return')
                                                ->where('transaction_track_type','credit')
                                                ->whereBetween('transaction_track.transaction_track_date', [$this->start_date,$this->end_date])
                                                ->sum('transaction_track_amount');
                   $total_credit   += $purchase_credit;

             }
  
             if($datas[$i]->transaction_track_category=='sales') {
                   $invoice = DB::table('sales_invoice')
                                        ->select('sales_date','sales_total_amount')
                                        ->where('id',$datas[$i]->transaction_number)->get();

                   $sales_credit            = DB::table('transaction_track')
                                                    ->where('transaction_number',$datas[$i]->transaction_number)
                                                    ->where('transaction_track_category','sales')
                                                    ->where('transaction_track_type','credit')
                                                    ->whereBetween('transaction_track.transaction_track_date', [$this->start_date, $this->end_date])
                                                    ->sum('transaction_track_amount'); 
                    $total_credit   += $sales_credit; 
                   $total_debit   += $invoice[0]->sales_total_amount;
               }

                if($datas[$i]->transaction_track_category=='sales_return') {
                   $invoice = DB::table('sales_return')
                                        ->select('sales_date','sales_total_amount')
                                        ->where('id',$datas[$i]->transaction_number)->get();

                   $sales_debit            = DB::table('transaction_track')
                                                    ->where('transaction_number',$datas[$i]->transaction_number)
                                                    ->where('transaction_track_category','sales_return')
                                                    ->where('transaction_track_type','debit')
                                                    ->whereBetween('transaction_track.transaction_track_date', [$this->start_date, $this->end_date])
                                                    ->sum('transaction_track_amount'); 
                    $total_debit   += $sales_debit; 
                   $total_credit   += $invoice[0]->sales_total_amount;
               }

                if($datas[$i]->transaction_track_category=='payment_in') {
                   $invoice = DB::table('payment_in')
                                        ->select('payment_in_date','payment_in_amount')
                                        ->where('id',$datas[$i]->transaction_number)->get();
                   $total_credit   += $invoice[0]->payment_in_amount; 
                   $total_debit   += 0;
               }

               if($datas[$i]->transaction_track_category=='payment_out') {
                   $invoice = DB::table('payment_out')
                                        ->select('payment_out_date','payment_out_amount')
                                        ->where('id',$datas[$i]->transaction_number)->get();
                    $total_credit   += 0;
                    $total_debit     += $invoice[0]->payment_out_amount; 
                   
               }


                 
           }

            $party_name = DB::table('markets')->select('name','balance')->where('id',$this->market_id)->get();

          if($party_name[0]->balance>0){ 
              $heading='Total Transferable';
              $balance = $party_name[0]->balance;
              
          }else{ 
              $heading='Total Receivable'; 
              $balance = str_replace('-','',$party_name[0]->balance);
          }
      
         return [
           ['#Party Ledger Report'],
           ['Dated: '.$start_date.' - '.$end_date],
           ['Party Name: '.$party_name[0]->name],
           [$heading.': '.$balance],
           ['Total Debit: '.$total_debit],
           ['Total Credit: '.$total_credit],
           [],
           [],
           ['TRANSACTION TYPE','TRANSACTION NO','DATE','CREDIT','DEBIT'],
        ];
    }
}
