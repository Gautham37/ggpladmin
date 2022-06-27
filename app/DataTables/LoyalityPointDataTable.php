<?php

namespace App\DataTables;

use App\Models\LoyalityPoints;
use App\Models\CustomField;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class LoyalityPointDataTable extends DataTable
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
            ->editColumn('name', function ($loyality) {
                return $loyality->affiliateuser->name;
            })
            ->editColumn('date', function ($loyality) {
                return $loyality->created_at->format('d M Y');
            })
            ->editColumn('category', function ($loyality) {
                return strtoupper($loyality->category);
            })
            ->editColumn('transaction_no', function ($loyality) {
                if($loyality->category=='referral') {
                    return '';
                } elseif($loyality->category=='sales_invoice') {
                    return '<a href="'.route('salesInvoice.show',$loyality->salesinvoice->id).'">'.$loyality->salesinvoice->code.'</a>';
                } elseif($loyality->category=='online_order') {
                    return '<a href="'.route('orders.show',$loyality->onlineorder->id).'">'.$loyality->onlineorder->order_code.'</a>';
                }
            })
            ->editColumn('type', function ($loyality) {
                return strtoupper($loyality->type);
            })
            ->editColumn('amount', function ($loyality) {
                return setting('default_currency').number_format($loyality->amount,'2','.','');
            })
            ->editColumn('updated_at', function ($category) {
                return getDateColumn($category, 'updated_at');
            })
            
            ->editColumn('created_at', function ($category) {
                return date('d M Y',strtotime($category->created_at));
            })
            //->addColumn('action', 'categories.datatables_actions')
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
                'data' => 'name',
                'title' => 'Party',
                'searchable' => true,

            ],
            [
                'data' => 'date',
                'title' => 'Date', 'className' => "text-center", 'orderable' => false,

            ],
            [
                'data' => 'category',
                'title' => 'Transaction Type', 'className' => "text-center", 'orderable' => false,
            ],
            [
                'data' => 'transaction_no',
                'title' => 'Transaction No', 'className' => "text-center", 'orderable' => false,
            ],
            [
                'data' => 'points',
                'title' => 'Points', 'className' => "text-right", 'orderable' => false,

            ],
            [
                'data' => 'type',
                'title' => 'Type', 'className' => "text-right", 'orderable' => false,

            ],
            [
                'data' => 'amount',
                'title' => 'Point Worth', 'className' => "text-right", 'orderable' => false,
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
    public function query(LoyalityPoints $model)
    {
        return $model->newQuery();
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
        return 'loyalitypointsdatatable_' . time();
    }
}