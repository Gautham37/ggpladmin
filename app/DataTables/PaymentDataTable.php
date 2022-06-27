<?php
/**
 * File name: PaymentDataTable.php
 * Last modified: 2020.05.04 at 09:04:19
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class PaymentDataTable extends DataTable
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
            ->editColumn('created_at', function ($payment) {
                return date('d M Y',strtotime($payment->created_at));
            })
            ->editColumn('updated_at', function ($payment) {
                return getDateColumn($payment, 'updated_at');
            })
            ->editColumn('price', function ($payment) {
                return getPriceColumn($payment);
            })
            ->editColumn('status', function ($payment) {
                return getPayment($payment,'status');
            })
            ->editColumn('order.order_code', function ($payment) {
                return '<b><a href="'.route('orders.show',$payment->order->id).'">'.$payment->order->order_code.'</a></b>';
            })
            //->addColumn('action', 'payments.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Payment $model)
    {
        return $model->newQuery()->with("user")->with("order")->orderBy('id', 'desc');
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
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
			[ 'data'=> 'DT_Row_Index', 'title' => 'Sno','searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'className' => "text-center",'width'=> '7%' ],
            [
                'data' => 'created_at',
                'title' => 'Transaction Date', 'className' => "text-center"

            ],
            [
                'data' => 'order.order_code',
                'title' => 'Order Code', 'className' => "text-center"

            ],
            [
                'data' => 'price',
                'title' => 'Amount', 'className' => "text-center"

            ],
            [
                'data' => 'description',
                'title' => 'Description',

            ],
            /*(auth()->check() && auth()->user()->hasAnyRole(['admin','manager'])) ? [
                'data' => 'user.name',
                'title' => trans('lang.payment_user_id'),

            ] : null,*/
            [
                'data' => 'method',
                'title' => 'Payment Method', 'className' => "text-center"

            ],
            [
                'data' => 'status',
                'title' => 'Payment Status', 'className' => "text-center"

            ],
            [
                'data' => 'updated_at',
                'title' => 'Updated At',
                'searchable' => false,
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
        return 'paymentsdatatable_' . time();
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