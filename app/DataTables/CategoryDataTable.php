<?php

namespace App\DataTables;

use App\Models\Category;
use App\Models\CustomField;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
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
            ->editColumn('image', function ($category) {
                return getMediaColumn($category, 'image');
            })
            ->editColumn('updated_at', function ($category) {
                return getDateColumn($category, 'updated_at');
            })
            ->editColumn('active', function ($category) {
                return getBooleanColumn($category, 'active');
            })
            ->editColumn('products', function ($category) {
                return '<a href="'.url('category-products').'/'.$category->id.'">'.count($category->products).' </a>';
            })
            ->addColumn('action', 'categories.datatables_actions')
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
                'title' => trans('lang.category_name'),
                'class' => 'text-left'

            ],
             [
                'data' => 'departments.name',
                'title' => trans('lang.product_department_id'),
                'class' => 'text-center'

            ]/*,
            [
                'data' => 'image',
                'title' => trans('lang.category_image'),
                'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false,
            ]*/,
            [
                'data' => 'products',
                'title' => trans('lang.category_products'),
                'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'className' => "text-center",'width'=> '15%'
            ],
            [
                'data' => 'active',
                'title' => trans('lang.category_active'), 'className' => "text-center",'width'=> '10%',
                'class' => 'text-center'

            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.category_updated_at'),
                'searchable' => false,'width'=> '15%',
                'class' => 'text-center'
            ]
        ];

        $hasCustomField = in_array(Category::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', Category::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.category_' . $field->name),
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
    public function query(Category $model)
    {   
         $query = $model->newQuery()->with("departments")->with('products');
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
        return 'categoriesdatatable_' . time();
    }
}