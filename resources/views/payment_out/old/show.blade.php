@extends('layouts.app')

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
          <li class="breadcrumb-item active">{{trans('lang.payment_out_table')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="card">
    <div class="card-header d-print-none">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link" href="{!! route('paymentOut.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.payment_out_table')}}</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.payment_out_table')}}</a>
        </li>

      </ul>
      

        <div class="dropdown float-right top-action-btn">
          <button style="background: #0078d7;border: 1px solid #0078d7;" class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">{{trans('lang.paymentout_download')}} {{trans('lang.paymentout_pdf')}}
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a target="_blank" id="download-invoice" href='{{url("/paymentOut/downloadPaymentOut/$payment_out->id/1")}}'>{{trans('lang.paymentout_download')}} {{trans('lang.paymentout_original')}}</a></li>
            <li><a target="_blank" href='{{url("/paymentOut/downloadPaymentOut/$payment_out->id/2")}}'>{{trans('lang.paymentout_download')}} {{trans('lang.paymentout_duplicate')}}</a></li>
            <li><a target="_blank" href='{{url("/paymentOut/downloadPaymentOut/$payment_out->id/3")}}'>{{trans('lang.paymentout_download')}} {{trans('lang.paymentout_triplicate')}}</a></li>
          </ul>
        </div>

        <div class="dropdown float-right top-action-btn">
          <button style="background: #881798;border: 1px solid #881798;" class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Print PDF
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a target="_blank" id="print-invoice" href='{{url("/paymentOut/printPaymentOut/$payment_out->id/1")}}'>{{trans('lang.paymentout_print')}} {{trans('lang.paymentout_original')}}</a></li>
            <li><a target="_blank" href='{{url("/paymentOut/printPaymentOut/$payment_out->id/2")}}'>{{trans('lang.paymentout_print')}} {{trans('lang.paymentout_duplicate')}}</a></li>
            <li><a target="_blank" href='{{url("/paymentOut/printPaymentOut/$payment_out->id/3")}}'>{{trans('lang.paymentout_print')}} {{trans('lang.paymentout_triplicate')}}</a></li>
          </ul>
        </div>
        
    </div>
    <div class="card-body">
      


      <div class="row">
        <div class="col-md-12">

          <style>
            table {
              border-collapse:collapse;
              border-spacing: 0;
              font-size: 12px !important;
            }

            tr{
              border: 1px solid #000;
            }
            td {
              border: 1px solid #000;
              padding: 7px;
            }
          </style>
           
          <table class="table table-bordered view-table-custom-product-item">
            <tbody>
                
                <tr>
                  <td width="13%" colspan="2"><img src="{{$app_logo}}" alt="{{setting('app_name')}}" style="width: 100%;" class="sales_image"></td>
                  <td width="90%" colspan="8" style="vertical-align:middle;">
                    <h6>
                      <b>{{setting('app_name')}}</b></h6>
                      <p>
                       <b>{{trans('lang.address')}} : </b> {{setting('app_store_address_line_1')}} {{setting('app_store_address_line_2')}} 
                          {{setting('app_store_city')}}, {{setting('app_store_pincode')}}.
                          {{setting('app_store_state')}}, {{setting('app_store_country')}},
                       <br>
                       <b>{{ trans('lang.market_mobile') }}:</b> {{setting('app_store_phone_no')}} &nbsp;&nbsp;
                       <b>{{ trans('lang.gstin') }} : </b> {{setting('app_store_gstin')}}
                      </p>
                  </td>
                </tr>
                
                <tr>
                  <td width="30%" colspan="6">
                    <p style="margin-bottom:0px;"><b>{{trans('lang.paymentout_party_name')}}</b></p>
                    <p>{{$paymentout_party->name}}</p>
                  </td>
                  <td width="30%" colspan="2">
                    <p style="margin-bottom:0px;"><b>{{trans('lang.paymentout_payment_date')}}</b></p>
                    <p>{{date('d-m-Y',strtotime($payment_out->payment_out_date))}}</p>
                  </td>
                  <td width="30%" colspan="2">
                    <p style="margin-bottom:0px;">{{trans('lang.paymentout_payment_amount')}}</b></p>
                   <p>₹ {{$payment_out->payment_out_amount}}</p> 
                  </td>
                </tr>

                 <tr>
                  <td width="30%" colspan="6">
                    <p style="margin-bottom:0px;"><b>{{trans('lang.paymentout_payment_type')}}</b></p>
                    <p>{{$paymentout_mode->name}}</p>
                  </td>
                  <td width="30%" colspan="2">
                    <p style="margin-bottom:0px;"><b>{{trans('lang.paymentout_payment_notes')}}</b></p>
                    <p>{!! $payment_out->payment_out_notes !!}</p>
                  </td>
                   <td width="30%" colspan="2">
                    <p style="margin-bottom:0px;"><b>{{trans('lang.payment_out_no')}}</b></p>
                    <p>{{ $payment_out->payment_out_no }}</p>
                  </td>
                </tr>

          </tbody>
          </table>
          <h6>{{trans('lang.Invoices settled')}}</h6><br>

          <table class="table table-bordered  view-table-custom-product-item" >
            <tbody>      

                <tr class="bg-secondary" style="background-color: #28a745 !important;">
                  <th>{{trans('lang.payment_out_invoice_date')}}</th>
                  <th>{{trans('lang.payment_out_invoice_number')}}</th>
                  <th>{{trans('lang.payment_out_invoice_amount')}}</th>
                  <th>{{trans('lang.payment_out_invoice_amount_paid')}}</th>
                </tr>

                  @foreach($paymentout_detail as $paymentout_item)
                
                <tr>
                  <td>{{$paymentout_item->transaction_track_date}}</td>
                  <td>{{$paymentout_item->transaction_number}}</td>
                  <td>{{number_format($purchase_invoice->purchase_total_amount,2)}}</td>
                  <td>{{number_format($paymentout_item->transaction_track_amount,2)}}</td> 
                </tr>

                @endforeach
              

          </tbody>
          </table>

         


        </div>
      </div>



      <div class="clearfix"></div>
      <div class="row d-print-none">
        <!-- Back Field -->
        <div class="form-group col-12 text-right">
          <a href="{!! route('paymentOut.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.back')}}</a>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <script type="text/javascript">
    $("#printOrder").on("click",function () {
      window.print();
    });
  </script>
@endpush
