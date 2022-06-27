<?php

namespace App\DataTables;

use App\Models\Emailnotifications;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class EmailnotificationsDataTable extends DataTable
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
            ->editColumn('message', function ($emailnotifications) {
                $strip  = strip_tags(str_replace('nbsp', '', $emailnotifications->message));
                $string = implode(' ', array_slice(str_word_count($strip, 2), 0, 10));                         
                return $string;
            })
            ->editColumn('created_at', function ($emailnotifications) {
                return getDateColumn($emailnotifications, 'created_at');
            })
            ->editColumn('status', function ($emailnotifications) {
                return ucfirst($emailnotifications->status);
            })
            ->addColumn('action', 'emailnotifications.datatables_actions')
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
                'data' => 'subject',
                'title' => 'Subject',
            ],
            [
                'data' => 'partytype.name',
                'title' => 'Party Type',
                'class' => 'text-center'
            ],
            [
                'data' => 'partysubtype.name',
                'title' => 'Party Subtype',
                'class' => 'text-center'
            ],
            [
                'data' => 'status',
                'title' => 'Status',
                'class' => 'text-center'
            ],
            [
                'data' => 'created_at',
                'title' => 'Created At',
                'class' => 'text-center',
            ],
        ];
        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Emailnotifications $model)
    {
        return $model->newQuery()->with('partytype')->with('partysubtype');
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
        return 'emailnotificationsdatatable_' . time();
    }
}