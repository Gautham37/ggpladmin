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
        <h1 class="m-0 text-dark">{{trans('lang.payment_out_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.payment_out_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('paymentOut.index') !!}">{{trans('lang.payment_out_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.payment_out_create')}}</li>
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
        @can('paymentOut.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('paymentOut.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.payment_out_table')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.payment_out_create')}}</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      {!! Form::open(['route' => 'paymentOut.store']) !!}
      <div class="row">
        @include('payment_out.fields')
      </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
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
    var invoices       = [];

    localStorage.setItem("payment_total", 0);

    $(document).on("change", ".payment_out_party", function() { 

        $('#payment_out_amount').val('0');

        $.ajax({
            url: "{{ url('/paymentOut/getPartyBalance') }}",
            type: 'post',
            dataType: "json",
            data: {'market_id':$('.payment_out_party').val(),'_token': "{{ csrf_token() }}" },
        })
        .done(function(results) {
            if(results.status=='success') {
                $('.balance_amount').html(results.result_data.balance_amount);
            } else {
                $('.balance_amount').html('0.00');
            }
        })
        .fail(function(results) {
            alert('failed');
        });


        $.ajax({
            url: "{{ url('/paymentOut/getPartyPendingInvoice') }}",
            type: 'post',
            dataType: "json",
            data: {'market_id':$('.payment_out_party').val(),'_token': "{{ csrf_token() }}" },
        })
        .done(function(response) {
            invoices.length = 0;
            $.each(response, function (key,val) {
              val.settle_amount = '';
              invoices.push(val);
            });
            payoutInvoices();
        })
        .fail(function(response) {
            alert('failed');
        });

    });

    function payoutInvoices() {
        $("#payoutInvoices").find("tr:gt(0)").remove();
        var count = 0;
        $.each(invoices, function (key,val) {
          count++;
          var id = key;
          if(val.settle_amount > 0) { var check='checked="checked"'; } else { var check=''; }
          var settle_box  = '<input '+check+' type="checkbox" onclick="settleInvoice(this);" name="settle_invoices[]" id="'+id+'" class="mark_as_paid'+id+'" value="'+val.id+'">';
          var date        = val.purchase_date;
          var invoice     = val.id;
          var balance     = val.purchase_balance_amount - val.settle_amount;
          var amount      = "<?= setting('default_currency') ?>" + val.purchase_total_amount + ' <span class="text-danger pending'+id+'"> ('+ balance +' pending) <span>';
          var pending     = '<input type="hidden" class="balance" name="pending[]" id="pending'+id+'" value="'+val.purchase_balance_amount+'" />';
          var settle      = '<input type="text" readonly="readonly" class="form-control settle_amount" name="settle_amount[]" id="settle_amount'+id+'" value="'+val.settle_amount+'" />';
          $('#payoutInvoices tr:last').after('<tr><td>'+settle_box+'</td><td>'+date+'</td><td>'+invoice+'</td><td>'+amount+'</td><td>'+settle+'</td>'+pending+'</tr>');
        });

    }


    function settleInvoice(elem) {

        var id      = elem.id;
        var payment = $('#payment_out_amount').val() == "" || isNaN($('#payment_out_amount').val()) ? 0 : $('#payment_out_amount').val(); 

        if($(elem).is(':checked')){
            /*if(payment <= 0) { 
              payments = invoices[id].settle_amount; 
              total = parseFloat($('#payment_out_amount').val())  + parseFloat(payments);
              $('#payment_out_amount').val(total); 
              payment = 0;
            } else {*/
              $.each(invoices, function (key,val) {
                if(val.purchase_balance_amount < payment) {
                  invoices[key].settle_amount = val.purchase_balance_amount; 
                  payment = payment - val.purchase_balance_amount;
                } else {
                  invoices[key].settle_amount = payment;
                  payment = 0;
                }
                payoutInvoices();
              });
           // }
        } else {
            //total = parseFloat($('#payment_out_amount').val()) - parseFloat(invoices[id].settle_amount);
            //$('#payment_out_amount').val(total); 
            invoices[id].settle_amount = 0;
        }
        payoutInvoices();

    }

    $(document).on("keyup", "#payment_out_amount", function() {

        var payment  = this.value; 
        $.each(invoices, function (key,val) {
          
          if(val.purchase_balance_amount < payment) {
            invoices[key].settle_amount = val.purchase_balance_amount; 
            payment = payment - val.purchase_balance_amount;
          } else {
            invoices[key].settle_amount = payment;
            payment = 0;
          }
          payoutInvoices();
        });

    });

    function cb() {} function cb1() {}
    
    $(document).ready(function() {
         var select = jQuery("select[id='payment_out_party']");
         select.change();
    });
    
</script>
@endpush