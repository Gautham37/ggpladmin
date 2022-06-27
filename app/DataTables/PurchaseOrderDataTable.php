<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\PurchaseOrder;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class PurchaseOrderDataTable extends DataTable
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
            ->editColumn('image', function ($category) {
                return getMediaColumn($category, 'image');
            })
            ->editColumn('date', function ($purchase_order) {
                return $purchase_order->date->format('d M Y');
            })
            ->editColumn('valid_date', function ($purchase_order) {
                return $purchase_order->valid_date->format('d M Y');
            })
            ->editColumn('market_id', function ($purchase_order) {
                return '<a target="_blank" href="'.route('markets.view',$purchase_order->market->id).'">'.$purchase_order->market->name.'</a>';
            })
            ->editColumn('total', function ($purchase_order) {
                return getPriceColumn($purchase_order,'total');
            })
            ->editColumn('created_at', function ($purchase_order) {
                return getDateColumn($purchase_order, 'created_at');
            })
            ->editColumn('status', function ($purchase_order) {
                return ucfirst($purchase_order->status);
            })
            ->editColumn('updated_at', function ($purchase_order) {
                return getDateColumn($purchase_order, 'updated_at');
            })
            ->addColumn('action', 'purchase_order.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PurchaseOrder $model)
    {
        if($this->start_date && $this->end_date && $this->start_date!='' && $this->end_date!='') {
            return $model->newQuery()->whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date)->with('market');
        } else  {
            return $model->newQuery()->with('market');
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
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            [ 'data'=> 'DT_Row_Index', 'title' => 'Sno','searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'className' => "text-center",'width'=> '7%' ],
            [
                'data' => 'code',
                'title' => 'Code',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'class' => 'text-center',

            ],
            [
                'data' => 'date',
                'title' => 'Date',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'class' => 'text-center',

            ],
            [
                'data' => 'market_id',
                'title' => 'Party',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'class' => 'text-left',

            ],
            [
                'data' => 'total',
                'title' => 'Total',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'class' => 'text-right',

            ],
            [
                'data' => 'valid_date',
                'title' => 'Valid Date',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'class' => 'text-right',

            ],
            [
                'data' => 'status',
                'title' => 'Status',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'class' => 'text-right',
            ],
            [
                'data' => 'updated_at',
                'title' => 'Updated At',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'class' => 'text-right',
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
        return 'purchaseorderdatatable_' . time();
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