<?php
/**
 * File name: DeliveryChallanDataTable.php
 * Last modified: 2020.05.04 at 09:04:18
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\DeliveryChallan;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class DeliveryChallanDataTable extends DataTable
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
            ->editColumn('delivery_valid_date', function ($category) {
                return getDateColumn($category, 'delivery_valid_date');
            })

            ->editColumn('delivery_date', function ($delivery_challan) {
                return date('d M Y',strtotime($delivery_challan->delivery_date));
            })
            
            ->editColumn('market.name', function ($delivery_challan) {
                return '<a target="_blank" href="'.route('markets.view',$delivery_challan->market->id).'">'.$delivery_challan->market->name.'</a>';
            })

            ->editColumn('delivery_total_amount', function ($delivery_challan) {
                return getPriceColumn($delivery_challan,'delivery_total_amount');
            })
            
            ->editColumn('due', function ($delivery_challan) {
                return getDue($delivery_challan->delivery_date,$delivery_challan->delivery_valid_date);
            })

            ->editColumn('delivery_balance_amount', function ($delivery_challan) {
                if($delivery_challan->delivery_cash_paid >= $delivery_challan->delivery_total_amount) {
                    $payment_status  = '<span class="btn btn-sm btn-success">Paid</span>';
                } else {
                    $payment_status  = '<span class="btn btn-sm btn-danger">Unpaid</span>';
                }
                return $payment_status;
            })

            ->editColumn('updated_at', function ($delivery_challan) {
                return getDateColumn($delivery_challan, 'updated_at');
            })

            ->addColumn('action', 'delivery_challan.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DeliveryChallan $model)
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
                'data' => 'delivery_date',
                'title' => trans('lang.dc_date'),
                'searchable' => true, 'orderable' => false, 'sortable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"

            ],
            [
                'data' => 'delivery_code',
                'title' => trans('lang.dc_no'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"

            ],  
            [
                'data' => 'market.name',
                'title' => trans('lang.dc_party'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,

            ],
            [
                'data' => 'delivery_total_amount',
                'title' => trans('lang.dc_total_amount'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"

            ],
            [
                'data' => 'due',
                'title' => trans('lang.dc_due'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"

            ],
            /*[
                'data' => 'delivery_balance_amount',
                'title' => trans('lang.dc_payment_status'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,
            ],*/
            [
                'data' => 'updated_at',
                'title' => trans('lang.dc_updated_at'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,
            ]
        ];

        $hasCustomField = in_array(DeliveryChallan::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', DeliveryChallan::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.delivery_challan_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'deliveryinvoicedatatable_' . time();
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