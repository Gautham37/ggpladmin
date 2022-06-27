<?php

namespace App\Exports;

use App\Models\Expenses;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class ExpensecategoryExport implements FromCollection,WithHeadings
{

   protected $start_date,$end_date;

 function __construct($start_date,$end_date) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
 }

    
    public function collection()
    {
           
           $selectExpenses = Expenses::join('expenses_categories','expenses.expense_category','expenses_categories.id')->distinct()->select('expense_category')
                                ->whereBetween('expenses.expense_date', [$this->start_date,$this->end_date])
                                ->get();


            $data           = Expenses::join('expenses_categories','expenses.expense_category','expenses_categories.id')->select('name',\DB::raw('SUM(expense_total_amount) as expense_total_amount'))
                                ->whereBetween('expenses.expense_date', [$this->start_date, $this->end_date])
                                ->whereIn('expense_category',$selectExpenses);
            $datas = $data->get();
            if(count($datas) > 0) {
              foreach ($datas as $key => $value) {
                $datas[$key]->expense_total_amount = number_format($value->expense_total_amount,2,'.','');
                $total[] = $datas[$key]->expense_total_amount;
              }
            }
            $total_expense = number_format(array_sum($total),2,'.','');

         
            //$total_expense = number_format(array_sum($total),2,'.','');
             
        return $datas;
    }

    public function headings(): array
    {
          $start_date = date("d-M-Y", strtotime($this->start_date));  
          $end_date = date("d-M-Y", strtotime($this->end_date));  
          
           $selectExpenses = Expenses::join('expenses_categories','expenses.expense_category','expenses_categories.id')->distinct()->select('expense_category')
                                ->whereBetween('expenses.expense_date', [$this->start_date,$this->end_date])
                                ->get();


            $data           = Expenses::join('expenses_categories','expenses.expense_category','expenses_categories.id')->select('name',\DB::raw('SUM(expense_total_amount) as expense_total_amount'))
                                ->whereBetween('expenses.expense_date', [$this->start_date, $this->end_date])
                                ->whereIn('expense_category',$selectExpenses);
            $datas = $data->get();
            if(count($datas) > 0) {
              foreach ($datas as $key => $value) {
                $datas[$key]->expense_total_amount = number_format($value->expense_total_amount,2,'.','');
                $total[] = $datas[$key]->expense_total_amount;
              }
            }
            $total_expense = array_sum($total);
     
         return [
           ['#Expense Category Report'],
           ['Dated: '.$start_date.' - '.$end_date],
           ['Total Expense Amount: '.$total_expense],
           [],
           [],
           ['CATEGORY','TOTAL AMOUNT'],
        ];
    }
}
