<?php

namespace App\DataTables;

use App\Models\Market;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use DB;

class DriverDataTable extends DataTable
{
    
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $i = 0;
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable = $dataTable
            ->addIndexColumn()
            ->editColumn('image', function ($market) {
                return getMediaColumn($market, 'image');
            })
            ->editColumn('name', function ($market) {
                return ucfirst($market->name);
            })
            ->editColumn('code', function ($market) {
                return '<a target="_blank" href="'.route('markets.view',$market->id).'">'.$market->code.'</a>';
            })
            ->editColumn('created_at', function ($market) {
                return getDateColumnwithCreatedDays($market, 'created_at');
            })
            ->editColumn('last_transaction', function ($market) {
                return ($market->transaction) ? getDateColumnwithCreated($market->transaction, 'created_at') : '' ;
            })
            ->editColumn('active', function ($market) {
                return getBooleanActiveColumn($market, 'active');
            })
            ->editColumn('balance', function ($market) {
                if(getBalanceStatus($market, 'balance')!='')
                {
                    $balance = getBalanceStatus($market, 'balance');
                }
                else
                {
                    $balance = '0 | Up to date';
                }
                return $balance;
            })
            ->addColumn('action', 'markets.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Market $model)
    {
        $query = $model->newQuery()
                       ->with('party_sub_type')
                       ->with('party_stream')
                       ->with('user')
                       ->with('designation')
                       ->where('type','4')
                       ->where('sub_type','10')
                       ->orWhere('sub_type','11');
        return $query;
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
            ->addAction(['title'=>'Actions','width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
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
            ],
            [
                'data' => 'code',
                'title' => 'Driver Code',
                'className' => "text-left",
                'searchable' => true
            ],
            [
                'data' => 'name',
                'title' => 'Name',
                'searchable' => true
            ],
            [
                'data' => 'mobile',
                'title' => 'Mobile',
                'className' => "text-left",
                'searchable' => true
            ],
            [
                'data' => 'designation.name',
                'title' => 'Designation',
                'className' => "text-left",
                'searchable' => true
            ],
            [
                'data' => 'total_orders',
                'title' => 'Total Orders',
                'className' => "text-left",
                'searchable' => true
            ],
            [
                'data' => 'total_order_amount',
                'title' => 'Total Order Amount',
                'className' => "text-left",
                'searchable' => true
            ],
            [
                'data' => 'total_order_tips',
                'title' => 'Total Tips',
                'className' => "text-left",
                'searchable' => true
            ],
            [
                'data' => 'total_distance',
                'title' => 'Total Distance',
                'className' => "text-left",
                'searchable' => true
            ],
            [
                'data' => 'active',
                'title' => 'Status',
                'className' => "text-center",
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
        return 'staffsdatatable_' . time();
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