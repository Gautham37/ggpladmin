<?php

namespace App\DataTables;

use App\Models\Charity;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class CharityDataTable extends DataTable
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
            ->editColumn('image', function ($category) {
                return getMediaColumn($category, 'image');
            })
            ->editColumn('updated_at', function ($category) {
                return getDateColumn($category, 'updated_at');
            })
            ->editColumn('active', function ($category) {
                return getBooleanColumn($category, 'active');
            })
            ->addColumn('action', 'charity.datatables_actions')
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
                'className' => "text-center sorting_disabled",
                'width'=> '8%' 
            ],
            [
                'data' => 'name',
                'title' => 'Charity Name',
                'class' => 'text-left'

            ],
            [
                'data' => 'email',
                'title' => 'Email',
                'class' => 'text-center'

            ],
            [
                'data' => 'mobile',
                'title' => 'Mobile',
                'class' => 'text-center'

            ],
            [
                'data' => 'address_line_1',
                'title' => 'Address',
                'class' => 'text-center'

            ],
            [
                'data' => 'town',
                'title' => 'Town',
                'class' => 'text-center'

            ],
            [
                'data' => 'state',
                'title' => 'State',
                'class' => 'text-center'

            ],
            [
                'data' => 'pincode',
                'title' => 'Pincode',
                'class' => 'text-center'

            ],
            [
                'data' => 'updated_at',
                'title' => 'Updated at',
                'searchable' => false,'width'=> '15%',
                'class' => 'text-center'
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
    public function query(Charity $model)
    {   
        $query = $model->newQuery();
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
            ->addAction(['title'=>trans('lang.actions'),'width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
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
        return 'charitydatatable_' . time();
    }
}