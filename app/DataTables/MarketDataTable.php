<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\Market;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use DB;

class MarketDataTable extends DataTable
{
    
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
		$i = 0;
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        $dataTable = $dataTable
			->addIndexColumn()
            ->editColumn('image', function ($market) {
                return getMediaColumn($market, 'image');
            })
            ->editColumn('name', function ($market) {
                return ucfirst($market->name);
            })
            ->editColumn('code', function ($market) {
                return '<a target="_blank" href="'.route('markets.view',$market->id).'">'.$market->code.'</a>';
            })
            ->editColumn('created_at', function ($market) {
                return getDateColumnwithCreatedDays($market, 'created_at');
            })
            ->editColumn('last_transaction', function ($market) {
                return ($market->transaction) ? getDateColumnwithCreated($market->transaction, 'created_at') : '' ;
            })
            ->editColumn('active', function ($market) {
                return getBooleanActiveColumn($market, 'active');
            })
            ->editColumn('balance', function ($market) {
                if(getBalanceStatus($market, 'balance')!='')
                {
                    $balance = getBalanceStatus($market, 'balance');
                }
                else
                {
                    $balance = '0 | Up to date';
                }
                return $balance;
            })
            ->addColumn('action', 'markets.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Market $model)
    {
        $query = $model->newQuery()->with('party_sub_type')->with('party_stream')->with('user');
        if($this->sub_type) {
            return $query->where('sub_type',$this->sub_type); 
        } else if($this->stream) {
            return $query->where('stream',$this->stream); 
        }
        else if(($this->p_name && $this->p_name!='') ||($this->p_code && $this->p_code!='') ||($this->p_town && $this->p_town!='') ||($this->p_mobile && $this->p_mobile!='') ||($this->p_phone && $this->p_phone!=''))  {
            
            if($this->p_name && $this->p_name!='') {
                $query = $query->where('name','LIKE','%'.$this->p_name.'%'); 
            }
            
            if($this->p_code && $this->p_code!='') {
                $query = $query->where('code','LIKE','%'.$this->p_code.'%'); 
            }
            if($this->p_town && $this->p_town!='') {
                $query = $query->where('city','LIKE','%'.$this->p_town.'%'); 
            }
            if($this->p_mobile && $this->p_mobile!='') {
                $query = $query->where('mobile','LIKE','%'.$this->p_mobile.'%'); 
            }
            if($this->p_phone && $this->p_phone!='') {
                $query = $query->where('phone','LIKE','%'.$this->p_phone.'%'); 
            } 
            return $query;

        }  else {
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
            ->addAction(['title'=>'Actions','width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
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
            ],
			[
                'data' => 'code',
                'title' => 'Party Code',
				'className' => "text-center",
                'searchable' => true
            ],
			[
                'data' => 'name',
                'title' => 'Party Name',
                'searchable' => true
            ],
            [
                'data' => 'city',
                'title' => trans('lang.market_town'),
				'className' => "text-center",
                'searchable' => true
            ],
            [
                'data' => 'phone',
                'title' => trans('lang.market_phone'),
				'className' => "text-center",
                'searchable' => true
            ],
            [
                'data' => 'mobile',
                'title' => trans('lang.market_mobile'),
				'className' => "text-center",
                'searchable' => true
            ],
            [
                'data' => 'party_sub_type.prefix_value',
                'title' => trans('lang.market_party_sub_type'),
				'className' => "text-center",
                'searchable' => true
            ],
            [
                'data' => 'balance',
                'title' => trans('lang.market_party_balance'),
				'className' => "text-center",
                'searchable' => false
            ],
            [
                'data' => 'active',
                'title' => trans('lang.market_status'),
				'className' => "text-center",
                'searchable' => false,
            ],
            [
                'data' => 'party_stream.name',
                'title' => trans('lang.partystream'),
				'className' => "text-center",
                'searchable' => true
            ],
            [
                'data' => 'user.points',
                'title' => 'Reward Balance',
				'className' => "text-center",
                'searchable' => false
            ],
            [
                'data' => 'created_at',
                'title' => trans('lang.market_status_days'),
                'className' => "text-center",
                'searchable' => false,
            ],
            [
                'data' => 'last_transaction',
                'title' => 'Last Transaction Days',
                'className' => "text-center",
                'searchable' => false,
            ],
    
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
        return 'partiesdatatable_' . time();
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