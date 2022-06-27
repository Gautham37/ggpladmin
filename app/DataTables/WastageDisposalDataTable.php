<?php

namespace App\DataTables;

use App\Models\InventoryTrack;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class WastageDisposalDataTable extends DataTable
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
            ->editColumn('name', function ($wastage) {
                $html = '';
                $html .= '<div class="table-avatar">';
                $html .= '<a class="inpro-img">'.getMediaColumn($wastage->product, 'image').'</a>';
                $html .= '<a>';
                $html .= '<b>'.$wastage->product->name.'</b><br/>';
                $html .= 'Category : <b>'.$wastage->product->category->name.'</b><br>';
                $html .= 'Subcategory : <b>'.$wastage->product->subcategory->name.'</b>';
                $html .= '</a>';
                $html .= '</div>';
                return $html;
            })
            ->editColumn('product_image', function ($wastage) {
                return getMediaColumn($wastage, 'image');
            })
            ->editColumn('quantity', function ($wastage) {
                return number_format($wastage->quantity,3)." ".$wastage->uom->name;
            })
            ->editColumn('updated_at', function ($wastage) {
                return getDateColumn($wastage, 'updated_at');
            })
            ->editColumn('status', function ($wastage) {
                if($wastage->disposal) {
                    if($wastage->quantity == $wastage->disposal->quantity) {
                       return '<span class="btn btn-sm btn-success"> Disposed </span>'; 
                    } else {
                       return '<span class="btn btn-sm btn-warning"> Partially Disposed '.number_format($wastage->disposal->quantity,'3','.','').' '.$wastage->disposal->uom->name.'</span>'; 
                    }
                } else {
                    return '<span class="btn btn-sm btn-danger"> Pending </span>';
                }
            })
            ->addColumn('action', 'wastageDisposal.datatables_actions')
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
        return $model->newQuery()
                     ->with('createdby')
                     ->with("product")
                     ->with("disposal")
                     ->with("product.category")
                     ->where('usage','wastage')
                     ->orderBy('created_at','desc');
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
                'data' => 'name',
                'title' => 'Name',

            ],
            [
                'data' => 'product.product_code',
                'title' => 'Code', 
                'className' => "text-center"
            ],
            [
                'data' => 'quantity',
                'title' => 'Wastage Quantity', 
                'className' => "text-center"
            ],
            [
                'data' => 'product_image',
                'title' => 'Image Proof', 
                'className' => "text-center"
            ],
            [
                'data' => 'createdby.name',
                'title' => 'Created By', 
                'className' => "text-center"
            ],
            [
                'data' => 'status',
                'title' => 'Status', 
                'className' => "text-center"
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
        return 'wastagedisposaldatatable_' . time();
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