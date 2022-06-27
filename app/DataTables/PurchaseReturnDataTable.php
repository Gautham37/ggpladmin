<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\PurchaseReturn;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use DB;

class PurchaseReturnDataTable extends DataTable
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
            
            ->editColumn('date', function ($purchase_return) {
                return $purchase_return->date->format('d M Y');
            })
            ->editColumn('party', function ($purchase_return) {
                return '<a target="_blank" href="'.route('markets.view', $purchase_return->market->id).'">'.$purchase_return->market->name.'</a>';    
            })
            ->editColumn('total', function ($purchase_return) {
                return number_format($purchase_return->total,2,'.','');
            })
            ->editColumn('valid_date', function ($purchase_return) {
                return $purchase_return->valid_date->format('d M Y');
            })
            ->editColumn('payment_status', function ($purchase_return) {
                $paid = $purchase_return->cash_paid + $purchase_return->totalsettle('purchase','purchase_return_id',$purchase_return->id);
                if($purchase_return->amount_due > 0 && $purchase_return->amount_due < $purchase_return->total) {
                    return '<span class="btn btn-sm btn-warning">Partially Paid ('.setting('default_currency').' '.$paid.') </span>';
                } elseif($purchase_return->amount_due == 0) {
                    return '<span class="btn btn-sm btn-success">Paid</span>';
                } elseif($purchase_return->amount_due == $purchase_return->total) {
                    return '<span class="btn btn-sm btn-danger">Unpaid</span>';
                }
            })
            ->editColumn('payment_mode', function ($purchase_return) {
                return ($purchase_return->paymentmethod) ? $purchase_return->paymentmethod->name : '' ;
            })
            ->editColumn('updated_at', function ($purchase_return) {
                return getDateColumn($purchase_return, 'updated_at');
            })

            ->addColumn('action', 'purchase_return.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PurchaseReturn $model)
    {
        if($this->start_date && $this->end_date && $this->start_date!='' && $this->end_date!='') {
            return $model->newQuery()->whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date)->with('paymentmethod');
        } else  {
            return $model->newQuery()->with('paymentmethod');
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
                'data' => 'updated_at',
                'title' => 'Updated at',
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,
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
        return 'purchasereturndatatable_' . time();
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