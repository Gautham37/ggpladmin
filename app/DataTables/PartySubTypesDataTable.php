<?php
/**
 * File name: CategoryDataTable.php
 * Last modified: 2020.04.30 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\PartySubTypes;
use App\Models\CustomField;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class PartySubTypesDataTable extends DataTable
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
            ->editColumn('updated_at', function ($partySubTypes) {
                return getDateColumn($partySubTypes, 'updated_at');
            })
            ->editColumn('active', function ($partySubTypes) {
                return getBooleanColumn($partySubTypes, 'active');
            })
            ->addColumn('action', 'party_sub_types.datatables_actions')
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
			[ 'data'=> 'DT_Row_Index', 'title' => 'Sno','searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'className' => "text-center sorting_disabled",'width'=> '8%' ],
            [
                'data' => 'name',
                'title' => trans('lang.party_sub_type_name'),
                'class' => 'text-center'
            ],
             [
                'data' => 'party_types.name',
                'title' => trans('lang.party_type_id'),
                'class' => 'text-center'
            ],
             [
                'data' => 'prefix_value',
                'title' => trans('lang.party_sub_type_prefix'), 'className' => "text-center",
                'class' => 'text-center'
            ],
            [
                'data' => 'active',
                'title' => trans('lang.party_sub_type_active'), 'className' => "text-center",'width'=> '10%',
                'class' => 'text-center'
            ],
            [
                'data' => 'role.name',
                'title' => 'Role', 'className' => "text-center",'width'=> '10%',
                'class' => 'text-center'
            ]
            /*[
                'data' => 'updated_at',
                'title' => trans('lang.party_sub_type_updated_at'),
                'searchable' => false,'width'=> '15%',
                'class' => 'text-center'
            ]*/
        ];

        $hasCustomField = in_array(PartySubTypes::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', PartySubTypes::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.party_sub_type_' . $field->name),
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
    public function query(PartySubTypes $model)
    {   
         $query = $model->newQuery()->with('party_types')->with('role');
          if($this->party_type_id) {
                return $query->where('party_type_id',$this->party_type_id); 
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
        return 'party_sub_type_datatable_' . time();
    }
}