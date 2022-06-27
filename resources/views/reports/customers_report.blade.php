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
                <tr>
                  <th>{{strtoupper(trans('lang.customers_name'))}}</th>
                  <th>{{strtoupper(trans('lang.customers_name_date'))}}</th>
                  <th>{{strtoupper(trans('lang.customers_name_email'))}}</th>
                  <th>{{strtoupper(trans('lang.customers_name_phone_number'))}}</th>
                  <th>{{strtoupper(trans('lang.customers_name_contact_number'))}}</th>
                  <th>{{strtoupper(trans('lang.customers_name_reward_level'))}}</th>
                  <th>{{strtoupper(trans('lang.customers_name_transactions'))}}</th>
                  <th>{{strtoupper(trans('lang.customers_name_party_group'))}}</th>
                  <th>{{strtoupper(trans('lang.customers_name_address'))}}</th>
                  <th>{{strtoupper(trans('lang.customers_name_city'))}}</th>
                  <th>{{strtoupper(trans('lang.customers_name_state'))}}</th>
                  <th>{{strtoupper(trans('lang.customers_name_pincode'))}}</th>
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
       ajax: "{{ route('reports.show') }}?report_type=customers-report&start_date=&end_date=",
       columns: [
           {data: 'name', name: 'name', orderable: false, searchable: true, class: "text-left"},
           {data: 'created_at', name: 'created_at', orderable: false, searchable: true, class: "text-left"},
           {data: 'email', name: 'email', orderable: false, searchable: true, class: "text-center"},
           {data: 'phone', name: 'phone', orderable: false, searchable: true, class: "text-right"},
           {data: 'mobile', name: 'mobile', orderable: false, searchable: true, class: "text-center"},
           {data: 'reward_levels', name: 'reward_levels', orderable: false, searchable: true, class: "text-center"},
           {data: 'total_no_transactions', name: 'total_no_transactions', orderable: false, searchable: true, class: "text-center"},
           {data: 'party_group', name: 'party_group', orderable: false, searchable: true, class: "text-center"},
           {data: 'address', name: 'address', orderable: false, searchable: true, class: "text-right"},
           {data: 'city', name: 'city', orderable: false, searchable: true, class: "text-center"},
           {data: 'state', name: 'state', orderable: false, searchable: true, class: "text-center"},
           {data: 'pincode', name: 'pincode', orderable: false, searchable: true, class: "text-center"},
       ]
    });

    function cb(start, end) {
        $('#reportrange1 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');
        //var product         = $('#product').val();

        localStorage.setItem("start_date",start_date);
        localStorage.setItem("end_date",end_date);

        table.ajax.url("{{route('reports.show')}}?report_type=customers-report&start_date="+start_date+"&end_date="+end_date).load(function(result) {});
    }

    function cb1(){ }

    function cb2(){ }

    $(document).on("click","#report",function() {

        var type        = this.value;
        //var product  = $('#product').val();
        
        var start_date  = localStorage.getItem("start_date");
        var end_date    = localStorage.getItem("end_date");

        window.open("{{url('')}}/reports/exportReport?type="+type+"&report_type=customers-report&start_date="+start_date+"&end_date="+end_date,'_blank');  
    });

</script>
@endpush



