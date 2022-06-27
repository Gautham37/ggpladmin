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

            <!-- Products Field -->
            <div class="col-md-3 form-group">
              {!! Form::select('product', $products, null, ['class' => 'select2 form-control', 'id' => 'product','onchange' => 'cb(start, end)']) !!}
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

            <table class="table dataTable no-footer dtr-inline" id="dataTableBuilder" width="100%">
              <thead>
                <!-- <tr>
                  <th colspan="5">Total Sales : 100.00</th>
                </tr> -->  
                <tr>
                  <th>{{strtoupper(trans('lang.stock_detail_report_date'))}}</th>
                  <th>{{strtoupper(trans('lang.stock_detail_report_transaction_type'))}}</th>
                  <th>{{strtoupper(trans('lang.stock_detail_report_quantity'))}}</th>
                  <th>{{strtoupper(trans('lang.stock_detail_report_closing_stock'))}}</th>
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
<script type="text/javascript">
     
    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

        var start_date = start.format('YYYY-MM-DD');
        var end_date   = end.format('YYYY-MM-DD');
        var product    = $('#product').val();

        localStorage.setItem("start_date", start_date);
        localStorage.setItem("end_date", end_date);

        var table = $('#dataTableBuilder').DataTable({
              columns: [
               { data: "inventory_track_date"},
               { data: "inventory_track_category"},
               { data: "quantity"},
               { data: "inventory_closing_stock"}
            ],
            "aaSorting": [],
            "bSort": false,
        });

        table.ajax.url("{{ url('reports/stockDetailReport') }}?start_date="+start_date+"&end_date="+end_date+"&product="+product);
        table.ajax.reload();
    }
    function cb1() { }
    function cb2() { }

    $(document).on("click","#report",function() {

        var type = this.value;
        var start_date = localStorage.getItem("start_date");
        var end_date   = localStorage.getItem("end_date");
        var product    = $('#product').val();
        location.href="exportReport?type="+type+"&report_type=sales-summary&start_date="+start_date+"&end_date="+end_date+"&product="+product;

    });

</script>



