<?php
/**
 * File name: ExpensesDataTable.php
 * Last modified: 2020.05.14 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\CustomerLevels;
use App\Models\CustomField;
use App\Models\PartyStreams;
use App\Models\StaffDepartment;
use App\Models\StaffDesignation;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class StaffDesignationDataTable extends DataTable
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
            // ->editColumn('image', function ($designation) {
            //     return getMediaColumn($category, 'image');
            // })
            ->editColumn('created_at', function ($staffdesignation) {
                return getDateColumn($staffdesignation, 'created_at');
            })
            ->editColumn('updated_at', function ($staffdesignation) {
                return getDateColumn($staffdesignation, 'updated_at');
            })

            ->addColumn('action', 'staffdesignation.datatables_actions')
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
                'data' => 'name',
                'title' => trans('lang.staffdesignation_name'),

            ],
            [
                'data' => 'created_at',
                'title' => trans('lang.staffdesignation_created_at'),
                'searchable' => false,
            ]
        ];

        $hasCustomField = in_array(StaffDesignation::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', StaffDesignation::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.staffdesignation_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(StaffDesignation $model)
    {
        $query = $model->newQuery()->with("staffdepartments");
         if($this->department_id) {
                return $query->where('department_id',$this->department_id); 
            } else {
                return $query;
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
        return 'StaffDesignationDatatable_' . time();
    }
}