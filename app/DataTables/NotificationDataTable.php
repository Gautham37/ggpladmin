<?php

namespace App\DataTables;

use App\Models\Notification;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class NotificationDataTable extends DataTable
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
            ->editColumn('type', function ($notification) {
                return  ('lang.' . preg_replace(['/App\\\/', '/\\\/'], ['', '_'], $notification->type));
            })
            ->editColumn('updated_at', function ($notification) {
                return getDateColumn($notification, 'updated_at');
            })
            ->editColumn('created_at', function ($notification) {
                return getDateColumn($notification, 'created_at');
            })
            ->editColumn('read_at', function ($notification) {
                return getBooleanColumn($notification, 'read_at');
            })
            ->addColumn('action', 'notifications.datatables_actions')
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
                'data' => 'type',
                'title' => trans('lang.notification_title'),

            ],
            [
                'data' => 'read_at',
                'title' => trans('lang.notification_read'), 'className' => "text-center"

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.notification_read_at'),
                'searchable' => false,
            ],
            [
                'data' => 'created_at',
                'title' => trans('lang.notification_created_at'),
                'searchable' => false,
            ]
        ];
        $columns = array_filter($columns);
        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Notification $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Notification $model)
    {
        return $model->newQuery()->where('notifications.notifiable_id', auth()->id())->select('notifications.*')->orderBy('notifications.updated_at', 'desc');
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
        return 'notificationsdatatable_' . time();
    }
}