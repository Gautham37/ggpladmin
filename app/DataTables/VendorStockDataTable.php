<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\VendorStock;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use DB;

class VendorStockDataTable extends DataTable
{
    /**
     * custom fields columns
     * @var array
     */
    public static $customFields = [];

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
            
            ->editColumn('date', function ($vendor_stock) {
                return $vendor_stock->date->format('d M Y');
            })
            ->editColumn('party', function ($vendor_stock) {
                return '<a target="_blank" href="'.route('markets.view', $vendor_stock->market->id).'">'.$vendor_stock->market->name.'</a>';    
            })
            ->editColumn('total', function ($vendor_stock) {
                return number_format($vendor_stock->total,2,'.','');
            })
            ->editColumn('valid_date', function ($vendor_stock) {
                return $vendor_stock->valid_date->format('d M Y');
            })
            ->editColumn('updated_at', function ($vendor_stock) {
                return getDateColumn($vendor_stock, 'updated_at');
            })
            ->editColumn('status', function ($vendor_stock) {
                return '<button class="btn btn-sm btn-'.$vendor_stock->status.'">'.ucfirst($vendor_stock->status).'</button>';
            })
            ->addColumn('action', 'vendor_stock.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(VendorStock $model)
    {
        if($this->start_date && $this->end_date && $this->start_date!='' && $this->end_date!='') {
            return $model->newQuery()->whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date);
        } else  {
            return $model->newQuery();
        }
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
            ->addAction(['title'=>trans('lang.actions'), 'width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
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
			[ 'data'=> 'DT_Row_Index', 'title' => 'Sno','searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'className' => "text-center",'width'=> '8%' ],
            [
                'data' => 'date',
                'title' => 'Date',
                'searchable' => true, 'orderable' => false, 'sortable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"

            ],
            [
                'data' => 'code',
                'title' => 'Code',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-left"

            ],  
            [
                'data' => 'party',
                'title' => 'Party',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-left",

            ],
            [
                'data' => 'total',
                'title' => 'Total',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-right"

            ],
            [
                'data' => 'valid_date',
                'title' => 'Valid Date',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"

            ],
            [
                'data' => 'status',
                'title' => 'Status',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center",
            ],
            [
                'data' => 'updated_at',
                'title' => 'Updated at',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center",
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
        return 'vendorstockdatatable_' . time();
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