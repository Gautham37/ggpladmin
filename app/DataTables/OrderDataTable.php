<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\Order;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use DB;

class OrderDataTable extends DataTable
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
            ->editColumn('created_at', function ($order) {
                return date('d M Y', strtotime($order->created_at));
            })
            ->editColumn('updated_at', function ($order) {
                return getDateColumn($order, 'updated_at');
            })
            ->editColumn('delivery_fee', function ($order) {
                return getPriceColumn($order, 'delivery_fee');
            })
            ->editColumn('payment.status', function ($order) {
                return getPayment($order->payment,'status');
            })
            ->editColumn('status', function ($order) {
                if($order->deliverytrack) {
                    return '<b class="btn btn-sm btn-'.str_replace('_','-',$order->deliverytrack->status).'">'.str_replace('_',' ',strtoupper($order->deliverytrack->status)).'</b>';
                } else {
                    return '<b class="btn btn-sm btn-'.str_replace('_','-',$order->status).'">'.str_replace('_',' ',strtoupper($order->status)).'</b>';
                }

            })
            ->editColumn('active', function ($product) {
                return getBooleanColumn($product, 'active');
            })
            ->addColumn('action', 'orders.datatables_actions')
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
                'data' => 'order_code',
                'title' => 'Order ID', 'className' => "text-center"

            ],
            [
                'data' => 'created_at',
                'title' => 'Date', 'className' => "text-center"

            ],
            [
                'data' => 'user.name',
                'name' => 'user.name',
                'title' => 'Party',

            ],
            [
                'data' => 'status',
                'name' => 'status',
                'title' => 'Status', 'className' => "text-center"

            ],
            [
                'data' => 'delivery_fee',
                'title' => 'Delivery Fee',
                'searchable' => false, 'className' => "text-center"

            ],
            [
                'data' => 'payment.status',
                'name' => 'payment.status',
                'title' => 'Payment Status', 'className' => "text-center"

            ],
            [
                'data' => 'payment.method',
                'name' => 'payment.method',
                'title' => 'Payment Method', 'className' => "text-center"

            ],
            /*[
                'data' => 'active',
                'title' => 'Active', 'className' => "text-center"

            ],*/
            [
                'data' => 'updated_at',
                'title' => 'Updated At',
                'searchable' => false,
                'orderable' => true,

            ]
        ];

        $hasCustomField = in_array(Order::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', Order::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.order_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model)
    {
        if($this->start_date && $this->end_date && $this->start_date!='' && $this->end_date!='') {
            $result = $model->newQuery()->whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date)->where('is_deleted',0);
       
        } else  {
            $result = $model->newQuery()->where('is_deleted',0);
        }
        /*if($this->payment_method && $this->payment_method!='') {
            $result = $result->where('method',$this->payment_method);
        }*/
        return $result->with("user")->with("orderStatus")->with('payment');
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
                [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true),
                    'order' => [ [0, 'desc'] ],
                ],
                config('datatables-buttons.parameters')
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
        return 'ordersdatatable_' . time();
    }
}