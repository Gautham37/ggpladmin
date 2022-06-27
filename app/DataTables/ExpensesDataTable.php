<?php

namespace App\DataTables;

use App\Models\Expenses;
use App\Models\CustomField;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use DB;

class ExpensesDataTable extends DataTable
{

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable = $dataTable
			->addIndexColumn()
            ->editColumn('image', function ($expenses) {
                return getMediaColumn($expenses, 'image');
            })
            ->editColumn('created_by', function ($expenses) {
                return $expenses->createdby->name;
            })
            ->editColumn('expense_category_id', function ($expenses) {
                return $expenses->expensecategory->name;
            })
            ->editColumn('total_amount', function ($expenses) {
                return getPriceColumn($expenses,'total_amount');
            })
            ->editColumn('date', function ($expenses) {
                return $expenses->date->format('d M Y');
            })
            ->editColumn('updated_at', function ($expenses) {
                return getDateColumn($expenses, 'updated_at');
            })
            ->editColumn('created_at', function ($expenses) {
                return getDateColumn($expenses, 'created_at');
            })
            ->addColumn('action', 'expenses.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
			[ 'data'=> 'DT_Row_Index', 'title' => 'Sno','searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'className' => "text-center",'width'=> '7%' ],
            [
                'data' => 'date',
                'title' => 'Date',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"
            ],
            [
                'data' => 'expense_category_id',
                'title' => 'Category',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,
            ],
            [
                'data' => 'total_amount',
                'title' => 'Amount',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"
            ],
            [
                'data' => 'created_by',
                'title' => 'Created By',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"
            ],
            [
                'data' => 'created_at',
                'title' => 'Created At',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"
            ],
            [
                'data' => 'updated_at',
                'title' => 'Updated At',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"
            ]
        ];

        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Expenses $model)
    {
        if($this->start_date && $this->end_date && $this->start_date!='' && $this->end_date!='') {
            $result = $model->newQuery()->whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date);
        } else  {
            $result = $model->newQuery();
        }
        if($this->category && $this->category!='') {
            $result = $result->where('expense_category_id',$this->category);
        }
        return $result->with('expensecategory');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['title'=>trans('lang.actions'),'width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true)
                ]
            ));
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename() . '.pdf');
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'expensesdatatable_' . time();
    }
}