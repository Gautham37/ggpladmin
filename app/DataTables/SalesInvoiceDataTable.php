<?php
/**
 * File name: SalesInvoiceDataTable.php
 * Last modified: 2020.05.04 at 09:04:18
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\SalesInvoice;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use DB;

class SalesInvoiceDataTable extends DataTable
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
            
            ->editColumn('date', function ($sales_invoice) {
                return $sales_invoice->date->format('d M Y');
            })
            ->editColumn('party', function ($sales_invoice) {
                return '<a target="_blank" href="'.route('markets.view', $sales_invoice->market->id).'">'.$sales_invoice->market->name.'</a>';    
            })
            ->editColumn('total', function ($sales_invoice) {
                return number_format($sales_invoice->total,2,'.','');
            })
            ->editColumn('valid_date', function ($sales_invoice) {
                return $sales_invoice->valid_date->format('d M Y');
            })
            ->editColumn('payment_status', function ($sales_invoice) {
                $paid = $sales_invoice->cash_paid + $sales_invoice->totalsettle('sales','sales_invoice_id',$sales_invoice->id);
                if($sales_invoice->amount_due > 0 && $sales_invoice->amount_due < $sales_invoice->total) {
                    return '<span class="btn btn-sm btn-warning">Partially Paid ('.setting('default_currency').' '.$paid.') </span>';
                } elseif($sales_invoice->amount_due == 0) {
                    return '<span class="btn btn-sm btn-success">Paid</span>';
                } elseif($sales_invoice->amount_due == $sales_invoice->total) {
                    return '<span class="btn btn-sm btn-danger">Unpaid</span>';
                }
            })
            ->editColumn('payment_mode', function ($sales_invoice) {
                return $sales_invoice->paymentmethod->name;
            })
            ->editColumn('reward_earned', function ($sales_invoice) {
                return ($sales_invoice->loyalitypoints) ? $sales_invoice->loyalitypoints->points : '0' ;
            })

            ->editColumn('updated_at', function ($sales_invoice) {
                return getDateColumn($sales_invoice, 'updated_at');
            })

            ->addColumn('action', 'sales_invoice.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SalesInvoice $model)
    {
        if($this->start_date && $this->end_date && $this->start_date!='' && $this->end_date!='') {
            return $model->newQuery()->whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date)->with('paymentmethod')->with('loyalitypoints');
        } else  {
            return $model->newQuery()->with('paymentmethod')->with('loyalitypoints');
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
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"

            ],  
            [
                'data' => 'party',
                'title' => 'Party',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,

            ],
            [
                'data' => 'total',
                'title' => 'Total',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"

            ],
            [
                'data' => 'valid_date',
                'title' => 'Valid Date',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"

            ],
            [
                'data' => 'payment_status',
                'title' => 'Payment Status',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"
            ],
            [
                'data' => 'payment_mode',
                'title' => 'Payment Mode',
                'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'className' => "text-center"
            ],
            [
                'data' => 'reward_earned',
                'title' => 'Reward Earned',
                'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'className' => "text-center"
            ],
            [
                'data' => 'updated_at',
                'title' => 'Updated at',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,
            ]
        ];

        /*$hasCustomField = in_array(SalesInvoice::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', SalesInvoice::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.sales_invoice_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }*/
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'salesinvoicedatatable_' . time();
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