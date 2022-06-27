@extends('layouts.app')
@push('css_lib')
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
<!-- select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
{{--dropzone--}}
<link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Email Alerts <small>Email Alerts Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{!! route('emailnotifications.index') !!}">Email Alerts</a>
          </li>
          <li class="breadcrumb-item active"> Create Email Alert </li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  @include('adminlte-templates::common.errors')
  <div class="clearfix"></div>
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        @can('emailnotifications.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('emailnotifications.index') !!}">
            <i class="fa fa-list mr-2"></i> Email Alerts List
          </a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}">
            <i class="fa fa-plus mr-2"></i> Create Email Alert
          </a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      {!! Form::open(['route' => 'emailnotifications.store']) !!}
      <div class="row">
        @include('emailnotifications.fields')
      </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
  </div>
</div>


<!-- Modal -->
<div id="ScheduleModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    {!! Form::open([ 'id' => 'scheduleForm']) !!}  
    <!-- Modal content-->
    <div class="modal-content schedule-modal">

    </div>
    {!! Form::close() !!}
  </div>
</div> 

@include('layouts.media_modal')
@endsection
@push('scripts_lib')
<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- select2 -->
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
{{--dropzone--}}
<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    var dropzoneFields = [];
</script>

<script>

  function addSchedule() {

     var name = $('#name').val();
      var description = $('#description').val();
       var customer_groups = $('.customer_groups').val();

      var customers = [];
        $.each($(".customers option:selected"), function(){            
            customers.push($(this).val());
        });

      var customers_count = $(".customers :selected").length;
          

      if((customers_count>0) && (name!='') && (description!='')&& (customer_groups!='') ) {

         $('#ScheduleModal').modal('show');
      $.ajax({
          url: "{{ url('/emailnotifications/loadScheduledetails') }}",
          type: 'post',
          data: {
             'subject'  : name,
              'description'  : description,
               'customers'  : customers,
               'customer_groups'  : customer_groups,
            '_token' : "{{ csrf_token() }}"
          }
      })
      .done(function(response) {


          $('.schedule-modal').html(response);
      })
      .fail(function(response) {
          console.log(response);
      });
    
      
      }else{
    iziToast.warning({title: 'Warning', position: 'topRight', message: 'Please fill the notification details'});
      $('#productModal').hide();  

      }
   
  }


  $("document").ready(function(){
      $("#scheduleForm").submit(function(e){
          e.preventDefault();
          $.ajax({
              url: "{{ url('/emailnotifications/save_schedule_notifications') }}",
              type: 'post',
              dataType: 'JSON',
              data: $('#scheduleForm').serialize()
          })
          .done(function(results) {

              $('#ScheduleModal').modal('hide');
              $('.schedule-modal').html('');

              window.location = '{{ route('emailnotifications.index') }}'

          })
          .fail(function(results) {
              console.log(results);
          });
      });
  }); 


</script>

@endpush