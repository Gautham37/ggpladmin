<?php

namespace App\DataTables;

use App\Models\StockTake;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class StockTakeDataTable extends DataTable
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
            ->editColumn('date', function ($stock_take) {
                return $stock_take->date->format('d M Y');
            })
            ->editColumn('created_at', function ($stock_take) {
                return getDateColumn($stock_take, 'updated_at');
            })
            ->editColumn('status', function ($stock_take) {
                return '<span class="btn btn-sm btn-'.$stock_take->status.'">'.ucfirst($stock_take->status).'</span>';
            })
            ->addColumn('action', 'stock_take.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(StockTake $model)
    {
        return $model->newQuery();
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
            ->addAction(['title'=>trans('lang.actions'),'width' => '80px','className' => "text-center", 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true)
                ]
            ));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
			[ 
                'data'=> 'DT_Row_Index', 
                'title' => 'Sno',
                'searchable' => false, 
                'orderable' => false, 
                'exportable' => false, 
                'printable' => false, 
                'className' => "text-center",
                'width'=> '7%' 
            ],
            [
                'data' => 'code',
                'title' => 'Stock Take No', 
                'className' => "text-center"
            ],
            [
                'data' => 'date',
                'title' => 'Date',
                'className' => "text-center"
            ],
            [
                'data' => 'status',
                'title' => 'Status', 
                'className' => "text-center"
            ],
            [
                'data' => 'createdby.name',
                'title' => 'Created By', 
                'className' => "text-center"
            ],
            [
                'data' => 'created_at',
                'title' => 'Created At', 
                'className' => "text-center"
            ]
        ];

        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'stocktakedatatable_' . time();
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
}