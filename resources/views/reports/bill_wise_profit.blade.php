@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"> Reports <small class="ml-3 mr-3">|</small><small>{{ ucfirst(str_replace('-',' ', $report_type)) }}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{!! route('reports.index') !!}"> Reports </a>
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
          <a class="nav-link active" href="{!! route('reports.index') !!}"><i class="fa fa-list mr-2"></i> Reports </a>
        </li>
      </ul>
    </div>
    
    <div class="card-body">

          <div class="row">
            
            <div class="col-md-3 form-group">
                <div id="reportrange"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> 
                </div>  
            </div>

            <div class="col-md-9 text-right">
              
              <!-- <button id="report" value="export" class="btn export-buttons btn-primary btn-sm">Excel Download <span><i class="fa fa-file-excel-o"></i></span> </button>
              
              &nbsp;&nbsp;&nbsp; -->
              
                <button id="report" value="download" class="btn btn-primary btn-sm">
                    Download PDF <span><i class="fa fa-download"></i></span> 
                </button>
              
                &nbsp;&nbsp;&nbsp;
              
                <button id="report" value="print" class="btn btn-primary btn-sm">
                    Print PDF <span><i class="fa fa-print"></i></span> 
                </button>

            </div>

          </div>

          @push('css_lib')
          @include('layouts.datatables_css')
          @endpush

            <table class="table dataTable table-bordered no-footer dtr-inline" id="dataTableBuilder" width="100%">
              <thead>
                <!-- <tr>
                  <th colspan="5">Total Sales : 100.00</th>
                </tr> -->  
                <tr>
                  <th>DATE</th>
                  <th>TRANSACTION NO</th>
                  <th>PARTY</th>
                  <th>TOTAL AMOUNT</th>
                  <th>SALES AMOUNT</th>
                  <th>PURCHASE AMOUNT</th>
                  <th>PROFIT</th>
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
@push('scripts_lib')
<script type="text/javascript">

  var table = $('.dataTable').DataTable({
       processing: true,
       serverSide: true,
       pageLength:20,
       ajax: "{{ route('reports.show') }}?report_type=bill-wise-profit&start_date=&end_date=",
       columns: [
           {data: 'date', name: 'date', orderable: false, searchable: true, class: "text-left"},
           {data: 'transaction_no', name: 'transaction_no', orderable: false, searchable: true, class: "text-left"},
           {data: 'market_id', name: 'market_id', orderable: false, searchable: true, class: "text-left"},
           {data: 'amount', name: 'amount', orderable: false, searchable: true, class: "text-right"},
           {data: 'sales_amount', name: 'sales_amount', orderable: false, searchable: true, class: "text-right"},
           {data: 'purchase_amount', name: 'purchase_amount', orderable: false, searchable: true, class: "text-right"},
           {data: 'profit', name: 'profit', orderable: false, searchable: true, class: "text-right"},
       ]
    });

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');
        localStorage.setItem("bwp_start_date",start_date);
        localStorage.setItem("bwp_end_date",end_date);

        table.ajax.url("{{route('reports.show')}}?report_type=bill-wise-profit&start_date="+start_date+"&end_date="+end_date).load(function(result) {});
    }

    function cb1(){ }

    function cb2(){ }

    $(document).on("click","#report",function() {

        var type        = this.value;
        
        var start_date  = localStorage.getItem("bwp_start_date");
        var end_date    = localStorage.getItem("bwp_end_date");

        window.open("{{route('reports.show')}}?type="+type+"&report_type=bill-wise-profit&start_date="+start_date+"&end_date="+end_date,'_blank');  
    });

</script>
@endpush



