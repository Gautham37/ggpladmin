<?php
/**
 * File name: StockDetailReportDataTable.php
 * Last modified: 2020.05.04 at 09:04:18
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\InventoryTrack;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\Repositories\ProductRepository;
use DataTables;

class StockDetailReportDataTable extends DataTable
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
            /*->editColumn('purchase_price', function ($product) {
                return getPriceColumn($product);
            })
            ->editColumn('price', function ($product) {
                return getPriceColumn($product);
            })
            ->editColumn('discount_price', function ($product) {
                return getPriceColumn($product,'discount_price');
            })
            ->editColumn('stock', function ($product) {
                return $product['stock']." ".$product['unit'];
            })
            ->editColumn('stock_value', function ($product) {
                return setting('default_currency').' '.number_format($product['stock'] * $product['price'],2);
            })*/
            // ->addColumn('action', 'products.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InventoryTrack $model)
    {

        return $model->select(
                'inventory_track.id',
                'inventory_track.inventory_track_date',
                'inventory_track.inventory_track_category',
                'inventory_track.inventory_track_type',
                'inventory_track.inventory_track_product_quantity'
            )->where('inventory_track_product_id',44);
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
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            [
                'data' => 'inventory_track_date',
                'title' => strtoupper(trans('lang.pro_stock_date')),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,
            ],
            [
                'data' => 'inventory_track_category',
                'title' => strtoupper(trans('lang.pro_stock_transaction_type')),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,
            ],
            [
                'data' => 'quantity',
                'title' => strtoupper(trans('lang.pro_stock_quantity')),
                'searchable' => true, 'orderable' => true, 'exportable' => true, 'printable' => true,
            ],
            [
                'data' => 'inventory_closing_stock',
                'title' => strtoupper(trans('lang.pro_stock_closing_stock')),

            ]
        ];

        $hasCustomField = in_array(InventoryTrack::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', InventoryTrack::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.product_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'stockdetailreportdatatable_' . time();
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