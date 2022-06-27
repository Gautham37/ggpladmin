<?php
/**
 * File name: DeliveryZonesDataTable.php
 * Last modified: 2020.04.30 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\DeliveryZones;
use App\Models\CustomField;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class DeliveryZonesDataTable extends DataTable
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
            ->editColumn('delivery_charge', function ($deliveryzones) {
                return getPriceColumn($deliveryzones, 'delivery_charge');
            })
            ->editColumn('updated_at', function ($deliveryzones) {
                return getDateColumn($deliveryzones, 'updated_at');
            })
            ->addColumn('action', 'delivery_zones.datatables_actions')
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
                'data' => 'zone',
                'title' => trans('lang.delivery_zones_zone'), 'className' => "text-center"

            ],
            [
                'data' => 'distance',
                'title' => trans('lang.delivery_zones_km'), 'className' => "text-center"

            ],
            [
                'data' => 'delivery_charge',
                'title' => trans('lang.delivery_zones_delivery_charge'), 'className' => "text-center"

            ],
            [
                'data' => 'minimum_order',
                'title' => trans('lang.delivery_zones_minimum_order'), 'className' => "text-center"

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.delivery_zones_updated_at'),
                'searchable' => false,
            ]
        ];

        $hasCustomField = in_array(DeliveryZones::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', DeliveryZones::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.delivery_zones_' . $field->name),
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
    public function query(DeliveryZones $model)
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
        return 'delivertzonesdatatable_' . time();
    }
}