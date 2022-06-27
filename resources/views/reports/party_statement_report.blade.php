@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.reports_plural')}}<small class="ml-3 mr-3">|</small><small>{{ ucfirst(str_replace('-',' ', $report_type)) }}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('reports.index') !!}">{{trans('lang.reports_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{ ucfirst(str_replace('-',' ', $report_type)) }}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link active" href="{!! route('reports.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.reports_plural')}}</a>
        </li>
      </ul>
    </div>
    
    <div class="card-body">

          <div class="row">
            
            <!-- Party Field -->
            <div class="col-md-3 form-group">
              {!! Form::select('market', $markets, null, ['class' => 'select2 form-control', 'id' => 'market', 'onchange' => 'cb(start, end)']) !!}
            </div>
            
            <div class="col-md-3 form-group">
                <div id="reportrange"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> 
                </div>  
            </div>

            <div class="col-md-6 text-right">
              
              <!-- <button id="report" value="export" class="btn export-buttons btn-primary btn-sm">Excel Download <span><i class="fa fa-file-excel-o"></i></span> </button>
              
              &nbsp;&nbsp;&nbsp; -->
              
              <button id="report" value="download" class="btn export-buttons btn-primary btn-sm">Download PDF <span><i class="fa fa-download"></i></span> </button>
              
              &nbsp;&nbsp;&nbsp;
              
              <button id="report" value="print" class="btn export-buttons btn-primary btn-sm">Print PDF <span><i class="fa fa-print"></i></span> </button>

            </div>

          </div>

          @push('css_lib')
          @include('layouts.datatables_css')
          @endpush

              <table class="table table-bordered dataTable no-footer dtr-inline ledger-table" width="100%">
              <thead>
                <tr>
                  <th>DATE</th>
                  <th>TRANSACTION TYPE</th>
                  <th>TRANSACTION NUMBER</th>
                  <th>CREDIT</th>
                  <th>DEBIT</th>
                  <th>STATUS</th>
                  <!-- <th>BALANCE</th> -->
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>

          @push('scripts_lib')
          @include('layouts.datatables_js')
          @endpush

      <div class="clearfix"></div>
    </div>

  </div>
</div>
@endsection

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

@push('scripts')
<script type="text/javascript">
    
    var ledger_table = $('.ledger-table').DataTable({
       processing: true,
       serverSide: true,
       pageLength:20,
       ajax: "{{ route('reports.show') }}?report_type=party-statement&market_id=&start_date=&end_date=",
       columns: [
           {data: 'date', name: 'date', orderable: false, searchable: true, class: "text-left"},
           {data: 'category', name: 'category', orderable: false, searchable: true, class: "text-center"},
           {data: 'transaction_no', name: 'transaction_no', orderable: false, searchable: true, class: "text-center"},
           {data: 'credit', name: 'credit', orderable: false, searchable: true, class: "text-center"},
           {data: 'debit', name: 'debit', orderable: false, searchable: true, class: "text-center"},
           {data: 'status', name: 'status', orderable: false, searchable: true, class: "text-center"},
           // {data: 'balance', name: 'balance', orderable: false, searchable: true, class: "text-center"},
       ]
    });

    function cb2(start, end) {
        $('#reportrange2 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');

        ledger_table.ajax.url("{{ route('reports.show') }}?report_type=party-statement&market_id=&start_date="+start_date+"&end_date="+end_date).load(function(result) {
            var total = 0;
            $.each(result.data, function (key,value) {
                total += parseFloat(value.total);
            });

            $('.total-l-transaction').html(result.data.length);
            //$('.total-amount').html("{{setting('default_currency')}}"+(total).toFixed(2));
        });
    }

    $(document).on("click","#report-t",function() {

        var type        = this.value;
        var start_date  = localStorage.getItem("start_date_t");
        var end_date    = localStorage.getItem("end_date_t");

        window.open("{{url('')}}/reports/exportReport?type="+type+"&report_type=party-statement&start_date="+start_date+"&end_date="+end_date+"&market_id=",'_blank');  

    });

</script>
@endpush



