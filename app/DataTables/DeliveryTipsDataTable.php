<?php

namespace App\DataTables;

use App\Models\DeliveryTips;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use DB;

class DeliveryTipsDataTable  extends DataTable
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
            ->editColumn('user_id', function ($drivertips) {
                return $drivertips->user->name;
            })
            ->editColumn('order_id', function ($drivertips) {
                return $drivertips->order->order_code;
            })
            ->editColumn('updated_at', function ($drivertips) {
                return getDateColumn($drivertips, 'updated_at');
            })
            ->editColumn('created_at', function ($drivertips) {
                return date('d M Y',strtotime($drivertips->created_at));
            })
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
                'data' => 'user.name',
                'title' => 'Driver',
                'searchable' => true,

            ],
            [
                'data' => 'order.order_code',
                'title' => 'Order',
                'searchable' => true,

            ],
            [
                'data' => 'amount',
                'title' => 'Delivery Tip Amount',
                'searchable' => true,

            ],
            [
                'data' => 'created_at',
                'title' => 'Created At',

            ],
            [
                'data' => 'updated_at',
                'title' => 'Updated At',
                'searchable' => true,
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
    public function query(DeliveryTips $model)
    {
        return $model->newQuery()->with('user')->with('order');
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
            //->addAction(['title'=>trans('lang.actions'),'width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
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
        return 'deliverytipsdatatable_' . time();
    }
}