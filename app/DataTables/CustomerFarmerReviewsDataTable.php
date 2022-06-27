<?php

namespace App\DataTables;

use App\Models\CustomerFarmerReviews;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use DB;

class CustomerFarmerReviewsDataTable  extends DataTable
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
            ->editColumn('user.name', function ($customerFarmerReviews) {
                return $customerFarmerReviews->user->name;
            })
            ->editColumn('driver.name', function ($customerFarmerReviews) {
                return $customerFarmerReviews->user->name;
            })
            ->editColumn('updated_at', function ($customerFarmerReviews) {
                return getDateColumn($customerFarmerReviews, 'updated_at');
            })
            ->editColumn('created_at', function ($customerFarmerReviews) {
                return date('d M Y',strtotime($customerFarmerReviews->created_at));
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
                'data' => 'user.name',
                'title' => 'Customer',
                'searchable' => true,

            ],
            [
                'data' => 'review',
                'title' => 'Review',
                'searchable' => true,

            ],
            [
                'data' => 'rate',
                'title' => 'Rating',
                'searchable' => true,

            ],
            [
                'data' => 'driver.name',
                'title' => 'Review By', 'className' => "text-center"

            ],
            [
                'data' => 'created_at',
                'title' => 'Created At', 'className' => "text-center"

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
    public function query(CustomerFarmerReviews $model)
    {
        return $model->newQuery()->with('user')->with('driver');
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
        return 'customerfarmerreviewdatatable_' . time();
    }
}