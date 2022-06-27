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

class ProductDataTable extends DataTable
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
            ->editColumn('discount_price', function ($product) {
                return getPriceColumn($product,'discount_price');
            })
            /*->editColumn('capacity', function ($product) {
                return $product['capacity']." ".$product['unit'];
            })*/
            ->editColumn('stock', function ($product) {
                return number_format($product['stock'],2)." ".$product->primaryunit->name;
            })
            ->editColumn('updated_at', function ($product) {
                return getDateColumn($product, 'updated_at');
            })
            ->editColumn('purchase_price', function ($product) {
                $gross_profit = $product['price'] - $product['purchase_price'];
                $revenue = $gross_profit / $product['price'];
                $margin = $revenue * 100;
                return number_format($margin,'2','.','').'%';
            })
            ->editColumn('featured', function ($product) {
                return getBooleanColumn($product, 'featured');
            })
            ->addColumn('action', 'products.datatables_actions')
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
        $query = $model->newQuery()
                     ->with("market")
                     ->with("category")
                     ->with("subcategory")
                     ->with("department")
                     ->select('products.*')
                     ->orderBy('products.updated_at','desc'); 
        if($this->category_id) {
            return $query->where('category_id',$this->category_id); 
        }else if($this->subcategory_id) {
            return $query->where('subcategory_id',$this->subcategory_id); 
        }else if($this->department_id) {
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
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
			[ 'data'=> 'DT_Row_Index', 'title' => 'Sno','searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'className' => "text-center",'width'=> '6%' ],
			[
                'data' => 'image',
                'title' => trans('lang.product_image'),
                'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'className' => "text-center",
                'class' => 'text-center'
            ],
            [
                'data' => 'name',
                'title' => trans('lang.product_name'),
                'class' => 'text-center'

            ],
            [
                'data' => 'product_code',
                'title' => 'Code', 'className' => "text-center",
                'class' => 'text-center'

            ],
             [
                'data' => 'department.name',
                'title' => trans('lang.department'),
                'class' => 'text-center'

            ],
            [
                'data' => 'category.name',
                'title' => trans('lang.product_category_id'),
                'class' => 'text-center'

            ],
             [
                'data' => 'subcategory.name',
                'title' => trans('lang.subcategory'),
                'class' => 'text-center'

            ],
            [
                'data' => 'price',
                'title' => trans('lang.product_price'), 'className' => "text-center",'width'=> '10%',
                'class' => 'text-center'

            ],
            /*[
                'data' => 'discount_price',
                'title' => trans('lang.product_discount_price'),

            ],*/
            [
                'data' => 'stock',
                'title' => trans('lang.stock'), 'className' => "text-center",'width'=> '7%'

            ],
            /*[
                'data' => 'capacity',
                'title' => trans('lang.product_unit'),

            ],*/
            [
                'data' => 'featured',
                'title' => trans('lang.product_featured'), 'className' => "text-center",'width'=> '8%'

            ],
            /*[
                'data' => 'market.name',
                'title' => trans('lang.product_market_id'),

            ],*/
            
            [
                'data' => 'purchase_price',
                'title' => trans('lang.product_margin'), 'className' => "text-center",'width'=> '8%'

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.product_updated_at'),
                'searchable' => false,
            ]
        ];

        $hasCustomField = in_array(Product::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', Product::class)->where('in_table', '=', true)->get();
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
        return 'productsdatatable_' . time();
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