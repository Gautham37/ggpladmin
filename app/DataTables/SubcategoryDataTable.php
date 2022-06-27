<?php
/**
 * File name: CategoryDataTable.php
 * Last modified: 2020.04.30 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\Subcategory;
use App\Models\CustomField;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class SubcategoryDataTable extends DataTable
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
            ->editColumn('image', function ($subcategory) {
                return getMediaColumn($subcategory, 'image');
            })
            ->editColumn('updated_at', function ($subcategory) {
                return getDateColumn($subcategory, 'updated_at');
            })
            ->editColumn('active', function ($subcategory) {
                return getBooleanColumn($subcategory, 'active');
            })
            ->editColumn('products', function ($subcategory) {
                return '<a href="'.url('subcategory-products').'/'.$subcategory->id.'">'.count($subcategory->products).' </a>';
            })
            ->addColumn('action', 'subcategory.datatables_actions')
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
                'data' => 'name',
                'title' => trans('lang.subcategory_name'),
                'class'=> 'text-center'

            ],
            [
                'data' => 'departments.name',
                'title' => trans('lang.product_department_id'),
                'class'=> 'text-center'

            ],
             [
                'data' => 'category.name',
                'title' => trans('lang.product_category_id'),
                'class'=> 'text-center'

            ],
            [
                'data' => 'image',
                'title' => trans('lang.subcategory_image'),
                'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false,
                'class'=> 'text-center'
            ],
            [
                'data' => 'products',
                'title' => trans('lang.subcategory_products'),
                'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false,
                'class'=> 'text-center'
            ],
            [
                'data' => 'active',
                'title' => trans('lang.subcategory_active'),
                'class'=> 'text-center'

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.subcategory_updated_at'),
                'searchable' => false,
                'class'=> 'text-center'
            ]
        ];

        $hasCustomField = in_array(Subcategory::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', Subcategory::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.subcategory_' . $field->name),
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
    public function query(Subcategory $model)
    {   
         $query = $model->newQuery()->with('products')->with("category")->with("departments");
          if($this->category_id) {
                return $query->where('category_id',$this->category_id); 
            } else if($this->department_id) {
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
        return 'subcategorydatatable_' . time();
    }
}