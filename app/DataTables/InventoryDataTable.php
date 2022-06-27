<?php
/**
 * File name: ProductDataTable.php
 * Last modified: 2020.05.04 at 09:04:18
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\Product;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class InventoryDataTable extends DataTable
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
            ->editColumn('image', function ($product) {
                return getMediaColumn($product, 'image');
            })
            ->editColumn('price', function ($product) {
                return getPriceColumn($product);
            })
            ->editColumn('name', function ($product) {
                $html = '';
                $html .= '<div class="table-avatar">';
                $html .= '<a class="inpro-img">'.getMediaColumn($product, 'image').'</a>';
                $html .= '<a>';
                $html .= '<b>'.$product->name.'</b><br/>';
                $html .= 'Category : <b>'.$product->category->name.'</b><br>';
                $html .= 'Subcategory : <b>'.$product->subcategory->name.'</b>';
                $html .= '</a>';
                $html .= '</div>';
                return $html;
                //return '<a href="'.route('products.view', $product->id).'">'.$product->name.'</a>';
            })
            ->editColumn('discount_price', function ($product) {
                return getPriceColumn($product,'discount_price');
            })
            ->editColumn('stock', function ($product) {
                return number_format($product['stock'],3)." ".$product->primaryunit->name;
            })
            ->editColumn('updated_at', function ($product) {
                return getDateColumn($product, 'updated_at');
            })
            ->editColumn('margin', function ($product) {
                $gross_profit = $product['price'] - $product['purchase_price'];
                $revenue = $gross_profit / $product['price'];
                $margin = $revenue * 100;
                return number_format($margin,'2','.','').'%';
            })
            ->editColumn('status', function ($product) {
                return ucfirst($product->product_status);
            })
            ->addColumn('action', 'inventory.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
    {
        /*if (auth()->user()->hasRole('admin')) {
            return $model->newQuery()->with("market")->with("category")->select('products.*')->orderBy('products.updated_at','desc');
        } else if (auth()->user()->hasRole('manager')) {
            return $model->newQuery()->with("market")->with("category")
                ->join("user_markets", "user_markets.market_id", "=", "products.market_id")
                ->where('user_markets.user_id', auth()->id())
                ->groupBy('products.id')
                ->select('products.*')->orderBy('products.updated_at', 'desc');
        } else if (auth()->user()->hasRole('driver')) {
            return $model->newQuery()->with("market")->with("category")
                ->join("driver_markets", "driver_markets.market_id", "=", "products.market_id")
                ->where('driver_markets.user_id', auth()->id())
                ->groupBy('products.id')
                ->select('products.*')->orderBy('products.updated_at', 'desc');
        } else if (auth()->user()->hasRole('client')) {
            return $model->newQuery()->with("market")->with("category")
                ->join("product_orders", "product_orders.product_id", "=", "products.id")
                ->join("orders", "product_orders.order_id", "=", "orders.id")
                ->where('orders.user_id', auth()->id())
                ->groupBy('products.id')
                ->select('products.*')->orderBy('products.updated_at', 'desc');
        }*/

        return $model->newQuery()->with("market")->with("category")->select('products.*')->orderBy('products.updated_at','desc');

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
			[ 'data'=> 'DT_Row_Index', 'title' => 'Sno','searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'className' => "text-center",'width'=> '7%' ],
            [
                'data' => 'name',
                'title' => trans('lang.product_name'),

            ],
            [
                'data' => 'product_code',
                'title' => 'Code', 'className' => "text-center"

            ],
            [
                'data' => 'price',
                'title' => 'Sale Price', 'className' => "text-center"

            ],
            [
                'data' => 'stock',
                'title' => 'Current Stock', 'className' => "text-center"

            ],
            [
                'data' => 'margin',
                'title' => 'Margin', 'className' => "text-center"

            ],
            [
                'data' => 'status',
                'title' => 'Status', 'className' => "text-center"

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
        return 'inventorydatatable_' . time();
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