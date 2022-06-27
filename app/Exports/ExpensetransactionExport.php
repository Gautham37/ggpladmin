<?php

namespace App\Exports;

use App\Models\Expenses;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class ExpensetransactionExport implements FromCollection,WithHeadings
{

   protected $start_date,$end_date,$expense_category;

 function __construct($start_date,$end_date,$expense_category) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->expense_category = $expense_category;
 }

    
    public function collection()
    {

            $data = Expenses::select('expenses.expense_date','expenses.id','expenses_categories.name','payment_mode.name as payment_mode','expenses.expense_total_amount')
                ->leftJoin('expenses_categories', 'expenses.expense_category', '=', 'expenses_categories.id')
                ->leftJoin('payment_mode', 'expenses.expense_payment_mode', '=', 'payment_mode.id');

            if($this->start_date!='' & $this->end_date!='') {
                $data->whereBetween('expenses.expense_date', [$this->start_date, $this->end_date]);
            }
            if($this->expense_category > 0) {
                $data->where('expenses.expense_category', $this->expense_category);
            }
            $datas = $data->get();
            
        return $datas;
    }

    public function headings(): array
    {
         $start_date = date("d-M-Y", strtotime($this->start_date));  
          $end_date = date("d-M-Y", strtotime($this->end_date));  
          
       $data = Expenses::select('expenses.expense_date','expenses.id','expenses_categories.name','payment_mode.name as payment_mode','expenses.expense_total_amount')
                ->leftJoin('expenses_categories', 'expenses.expense_category', '=', 'expenses_categories.id')
                ->leftJoin('payment_mode', 'expenses.expense_payment_mode', '=', 'payment_mode.id');

            if($this->start_date!='' & $this->end_date!='') {
                $data->whereBetween('expenses.expense_date', [$this->start_date, $this->end_date]);
            }
            if($this->expense_category > 0) {
                $data->where('expenses.expense_category', $this->expense_category);
            }
            $datas = $data->get();

      if(count($datas) > 0) {
              foreach ($datas as $key => $value) {
                $datas[$key]->expense_total_amount = number_format($value->expense_total_amount,2,'.','');
                $total[] = $datas[$key]->expense_total_amount;
              }
            }
            $total_expense = array_sum($total);
     
         return [
           ['#Expense Transaction Report'],
           ['Dated: '.$start_date.' - '.$end_date],
           ['Total Amount: '.$total_expense],
           [],
           [],
           ['DATE','EXPENSE NUMBER','CATEGORY','PAYMENT MODE','TOTAL AMOUNT'],
        ];
    }
}
