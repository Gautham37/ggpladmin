<?php

namespace App\DataTables;

use App\Models\DeliveryAddress;
use App\Models\CustomField;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

class DeliveryAddressDataTable extends DataTable
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
            ->editColumn('updated_at',function($delivery_address){
    return getDateColumn($delivery_address,'updated_at');
})
            ->editColumn('address',function($delivery_address){
                return $delivery_address->address_line_1.', '.$delivery_address->address_line_2.', '.$delivery_address->city.' - '.$delivery_address->pincode.', '.$delivery_address->state;
            })
            
            ->editColumn('is_default',function($delivery_address){
    return getBooleanColumn($delivery_address,'is_default');
})
            ->addColumn('action', 'delivery_addresses.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DeliveryAddress $model)
    {
        return $model->newQuery()->with("user");
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
            ->addAction(['title'=>trans('lang.actions'),'width' => '80px', 'printable' => false ,'responsivePriority'=>'100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/'.app()->getLocale().'/datatable.json')
                        ),true)
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
  'data' => 'user.name',
  'title' => trans('lang.delivery_address_user_id'), 'className' => "text-center"
  
],
            [
  'data' => 'description',
  'title' => trans('lang.delivery_address_description'),
  
],
            [
  'data' => 'address',
  'title' => trans('lang.delivery_address_address'),
  
],
            [
  'data' => 'is_default',
  'title' => trans('lang.delivery_address_is_default'), 'className' => "text-center"
  
],
           
            [
  'data' => 'updated_at',
  'title' => trans('lang.delivery_address_updated_at'),
  'searchable'=>false,
]
            ];

        $hasCustomField = in_array(DeliveryAddress::class, setting('custom_field_models',[]));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', DeliveryAddress::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.delivery_address_' . $field->name),
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
        return 'delivery_addressesdatatable_' . time();
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename().'.pdf');
    }
}