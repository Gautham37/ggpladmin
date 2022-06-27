<?php

namespace App\DataTables;

use App\Models\InventoryTrack;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class InventoryListDataTable extends DataTable
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
            ->editColumn('image', function ($inventory) {
                return getMediaColumn($inventory, 'image');
            })
            ->editColumn('updated_at', function ($inventory) {
                return getDateColumn($inventory, 'updated_at');
            })
            ->editColumn('date', function ($inventory) {
                return $inventory->date->format('d M Y');
            })
            ->editColumn('category', function ($inventory) {
                return strtoupper($inventory->category);
            })
            ->editColumn('usage', function ($inventory) {
                return strtoupper($inventory->usage);
            })
            ->editColumn('type', function ($inventory) {
                return strtoupper($inventory->type);
            })
            ->editColumn('quantity', function ($inventory) {
                return number_format($inventory->quantity,'3','.','');
            })
            ->addColumn('action', 'inventory.list_datatables_actions')
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
        return $model->newQuery()->with("product")->with("market")->with("uom")->with("createdby")->orderBy('created_at','desc');
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
            ->addAction(['title'=>trans('lang.actions'),'width' => '80px','className' => "text-center", 'printable' => false, 'responsivePriority' => '100'])
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
				'width'=> '7%' 
			],
            [
                'data' => 'product.name',
                'title' => 'Product', 'className' => "text-left"
            ],
            [
                'data' => 'product.product_code',
                'title' => 'Code', 'className' => "text-left"
            ],
            [
                'data' => 'date',
                'title' => 'Date', 'className' => "text-left"
            ],
            [
                'data' => 'category',
                'title' => 'Category', 'className' => "text-left"

            ],
            [
                'data' => 'type',
                'title' => 'Stock Type', 'className' => "text-left"

            ],
            [
                'data' => 'usage',
                'title' => 'Stock Usage', 'className' => "text-left"

            ],
            [
                'data' => 'quantity',
                'title' => 'Quantity', 'className' => "text-right"
            ],
            [
                'data' => 'uom.name',
                'title' => 'Unit', 'className' => "text-left"
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
        return 'inventorylistdatatable_' . time();
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