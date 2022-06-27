<?php
/**
 * File name: SupplierRequestDataTable.php
 * Last modified: 2020.05.04 at 09:04:18
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\SupplierRequest;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class SupplierRequestDataTable extends DataTable
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
            ->editColumn('image', function ($supplier_request) {
                return getMediaColumn($supplier_request, 'image');
            })
            ->editColumn('sr_valid_date', function ($supplier_request) {
                return getDateColumn($supplier_request, 'sr_valid_date');
            })

            ->editColumn('sr_date', function ($supplier_request) {
                return date('d M Y',strtotime($supplier_request->sr_date));
            })
            
            ->editColumn('market.name', function ($supplier_request) {
                return '<a target="_blank" href="'.route('markets.view',$supplier_request->market->id).'">'.$supplier_request->market->name.'</a>';
            })
            
            ->editColumn('sr_taxable_amount', function ($supplier_request) {
                return getPriceColumn($supplier_request,'sr_taxable_amount');
            })
            
            ->editColumn('sr_status', function ($supplier_request) {
                if($supplier_request->sr_status==0) {
                    $status = '<span class="btn btn-sm btn-danger"> Rejected </span>';
                } elseif($supplier_request->sr_status==1) {
                    $status = '<span class="btn btn-sm btn-warning"> Pending </span>';
                } elseif($supplier_request->sr_status==2) {
                    $status = '<span class="btn btn-sm btn-success"> Approved </span>';
                } elseif($supplier_request->sr_status==3) {
                    $status = '<span class="btn btn-sm btn-info"> Purchased </span>';
                } elseif($supplier_request->sr_status==4) {
                    $status = '<span class="btn btn-sm btn-info"> Picked up </span>';
                }
                return $status;
            })

            ->editColumn('updated_at', function ($supplier_request) {
                return getDateColumn($supplier_request, 'updated_at');
            })

            ->addColumn('action', 'supplier_request.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SupplierRequest $model)
    {
        if($this->start_date && $this->end_date && $this->start_date!='' && $this->end_date!='') {
            return $model->newQuery()->where('is_deleted',0)->whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date)->with('market');
        } else  {
            return $model->newQuery()->where('is_deleted',0)->with('market');
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
            [ 'data'=> 'DT_Row_Index', 'title' => 'Sno','searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'className' => "text-center",'width'=> '7%' ],
			[
                'data' => 'sr_date',
                'title' => trans('lang.supplier_request_date'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"

            ],
            [
                'data' => 'sr_code',
                'title' => trans('lang.supplier_request_no'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"

            ],  
            [
                'data' => 'market.name',
                'title' => trans('lang.supplier_request_customer'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"	

            ],
            /*[
                'data' => 'sr_taxable_amount',
                'title' => trans('lang.supplier_request_total_amount'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,

            ],*/
            [
                'data' => 'updated_at',
                'title' => trans('lang.supplier_request_updated_at'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,
            ],
            [
                'data' => 'sr_valid_date',
                'title' => trans('lang.supplier_request_valid_date'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,
            ],
            [
                'data' => 'sr_status',
                'title' => trans('lang.supplier_request_status'),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true, 'className' => "text-center"
            ]
        ];

        $hasCustomField = in_array(SupplierRequest::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', SupplierRequest::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.supplier_request_' . $field->name),
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
        return 'supplierrequestdatatable_' . time();
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