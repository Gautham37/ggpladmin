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
            
            <div class="col-md-3 form-group">
                <div id="reportrange"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> 
                </div>  
            </div>

            <div class="col-md-9 text-right">
              
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
                  <th>{{strtoupper(trans('lang.profitable_products_item_name'))}}</th>
                  <th>{{strtoupper(trans('lang.profitable_products_item_code'))}}</th>
                  <th>{{strtoupper(trans('lang.profitable_products_hsn_code'))}}</th>
                  <th>{{strtoupper(trans('lang.profitable_products_purchase_price'))}}</th>
                  <th>{{strtoupper(trans('lang.profitable_products_selling_price'))}}</th>
                  <th>{{strtoupper(trans('lang.profitable_products_stock_quantity'))}}</th>
                  <!--<th>{{strtoupper(trans('lang.profitable_products_stock_value'))}}</th>-->
                   <th>{{strtoupper(trans('lang.stock_summary_profit_percentage'))}}</th>
                   
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
       ajax: "{{ route('reports.show') }}?report_type=profitable-products-report&start_date=&end_date=",
       columns: [
           {data: 'name', name: 'name', orderable: false, searchable: true, class: "text-left"},
           {data: 'product_code', name: 'product_code', orderable: false, searchable: true, class: "text-left"},
           {data: 'hsn_code', name: 'hsn_code', orderable: false, searchable: true, class: "text-center"},
           {data: 'purchasing_price', name: 'purchasing_price', orderable: false, searchable: true, class: "text-right"},
           {data: 'selling_price', name: 'selling_price', orderable: false, searchable: true, class: "text-center"},
           {data: 'stock_quantity', name: 'stock_quantity', orderable: false, searchable: true, class: "text-center"},
           {data: 'profit_percentage', name: 'profit_percentage', orderable: false, searchable: true, class: "text-center"},
       ]
    });

    function cb(start, end) {
        $('#reportrange1 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');
        //var product         = $('#product').val();

        localStorage.setItem("start_date",start_date);
        localStorage.setItem("end_date",end_date);

        table.ajax.url("{{route('reports.show')}}?report_type=profitable-products-report&start_date="+start_date+"&end_date="+end_date).load(function(result) {});
    }

    function cb1(){ }

    function cb2(){ }

    $(document).on("click","#report",function() {

        var type        = this.value;
        //var product  = $('#product').val();
        
        var start_date  = localStorage.getItem("start_date");
        var end_date    = localStorage.getItem("end_date");

        window.open("{{url('')}}/reports/exportReport?type="+type+"&report_type=profitable-products-report&start_date="+start_date+"&end_date="+end_date,'_blank');  
    });

</script>
@endpush



