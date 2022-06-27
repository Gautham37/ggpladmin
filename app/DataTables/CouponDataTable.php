<?php

namespace App\DataTables;

use App\Models\Coupon;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

class CouponDataTable extends DataTable
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
            ->editColumn('updated_at', function ($coupon) {
                return getDateColumn($coupon, 'updated_at');
            })
            ->editColumn('expires_at', function ($coupon) {
                return getDateColumn($coupon, 'expires_at');
            })
            ->editColumn('enabled', function ($coupon) {
                return getBooleanColumn($coupon, 'enabled');
            })
            ->editColumn('discount', function ($coupon) {
                if($coupon['discount_type'] == 'percent'){
                    return $coupon['discount'] . "%";
                }
                return getPriceColumn($coupon, 'discount');
            })
            ->addColumn('action', 'coupons.datatables_actions')
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
                'data' => 'code',
                'title' => 'Code', 'className' => "text-center"

            ],
            [
                'data' => 'discount',
                'title' => 'Coupon Discount', 'className' => "text-center"

            ],
            [
                'data' => 'description',
                'title' => 'Description',

            ],
            [
                'data' => 'expires_at',
                'title' => 'Expires At', 'className' => "text-center"

            ],
            [
                'data' => 'enabled',
                'title' => 'Enabled', 'className' => "text-center"

            ],
            [
                'data' => 'updated_at',
                'title' => 'Updated At',
                'searchable' => false,
            ]
        ];

        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Coupon $model)
    {
        return $model->newQuery();
        /*if (auth()->user()->hasRole('admin')) {
            return $model->newQuery();
        }elseif (auth()->user()->hasRole('manager')){
            $markets = $model->join("discountables", "discountables.coupon_id", "=", "coupons.id")
                ->join("user_markets", "user_markets.market_id", "=", "discountables.discountable_id")
                ->where('discountable_type','App\\Models\\Market')
                ->where("user_markets.user_id",auth()->id())->select("coupons.*");

            $products = $model->join("discountables", "discountables.coupon_id", "=", "coupons.id")
                ->join("products", "products.id", "=", "discountables.discountable_id")
                ->where('discountable_type','App\\Models\\Product')
                ->join("user_markets", "user_markets.market_id", "=", "products.market_id")
                ->where("user_markets.user_id",auth()->id())
                ->select("coupons.*")
                ->union($markets);
            return $products;
        }else{
            $model->newQuery();
        }*/

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
                        ), true),
                    'order' => [ [5, 'desc'] ],
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
        return 'couponsdatatable_' . time();
    }
}