@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Payment In<small class="ml-3 mr-3">|</small><small>Payment In Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{!! route('paymentIn.index') !!}">Payment In List </a>
          </li>
          <li class="breadcrumb-item active">Payment In</li>
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
          <a class="nav-link" href="{!! route('paymentIn.index') !!}"><i class="fa fa-list mr-2"></i>Payment In List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>Payment In</a>
        </li>
      </ul>
      
        <div class="dropdown float-right top-action-btn">

        @can('paymentIn.print')
        <div class="dropdown float-right top-action-btn">
          <button style="background: #0078d7;border: 1px solid #0078d7;" class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Download PDF
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li>
              <a target="_blank" id="print-invoice" href='{{route("paymentIn.print",[$payment_in->id,"1","download"])}}'>
                Download Original
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("paymentIn.print",[$payment_in->id,"2","download"])}}'>
                Download Duplicate
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("paymentIn.print",[$payment_in->id,"2","download"])}}'>
                Download Triplicate
              </a>
            </li>
          </ul>
        </div>
        @endcan

        @can('paymentIn.print')
        <div class="dropdown float-right top-action-btn">
          <button style="background: #881798;border: 1px solid #881798;" class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Print PDF
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li>
              <a target="_blank" id="print-invoice" href='{{route("paymentIn.print",[$payment_in->id,"1","print"])}}'>
                Print Original
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("paymentIn.print",[$payment_in->id,"2","print"])}}'>
                Print Duplicate
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("paymentIn.print",[$payment_in->id,"2","print"])}}'>
                Print Triplicate
              </a>
            </li>
          </ul>
        </div>
        @endcan

        

        <!-- <div class="float-right top-action-btn">
            <button style="background: #c30052;border: 1px solid #c30052;" id="thermal-print-invoice" class="btn btn-primary btn-sm thermal_prints" href='{{route("paymentIn.print",[$payment_in->id,"1","thermalprint"])}}'>
              Thermal Print
            </button>
        </div> -->

        <!-- <div class="float-right top-action-btn">
            <a style="background: #107c10;border: 1px solid #107c10;" class="btn btn-primary btn-sm" href="https://web.whatsapp.com/send?text=" data-action="share/whatsapp/share" target="_blank">Whatsapp  <i class="fa fa-whatsapp"></i></a>
        </div> -->
       
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
            p {
              margin-bottom: 0rem;
            }
          </style>

          <table class="table table-bordered view-table-custom-product-item">
            <tbody>
                
                <tr>
                  <td width="10%" colspan="2"><img src="{{$app_logo}}" alt="{{setting('app_name')}}" style="width: 100%;" class="sales_image"></td>
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
                  <td width="60%" colspan="6" rowspan="2">
                    <h6><b>Bill To :</b></h6>

                    <h6><a href="{{route('markets.view',$payment_in->market->id)}}"><b>{{$payment_in->market->name}}</b></a></h6>
                    <p>
                    <b>Address : </b> 
                    {!! ($payment_in->market->street_no) ? $payment_in->market->street_no.', ' : '' !!}
                    {!! ($payment_in->market->street_name) ? $payment_in->market->street_name.', ' : '' !!}
                    {!! ($payment_in->market->street_type) ? $payment_in->market->street_type.', ' : '' !!}
                    {!! ($payment_in->market->address_line_1) ? $payment_in->market->address_line_1.', ' : '' !!}
                    {!! ($payment_in->market->address_line_2) ? $payment_in->market->address_line_2.', ' : '' !!} 
                    {!! ($payment_in->market->town) ? $payment_in->market->town.', ' : '' !!} 
                    {!! ($payment_in->market->city) ? $payment_in->market->city.', ' : '' !!} 
                    {!! ($payment_in->market->state) ? $payment_in->market->state.', ' : '' !!} 
                    {!! ($payment_in->market->pincode) ? $payment_in->market->pincode.', ' : '' !!} 
                    <br>
                    <b>Landmark : </b>
                    {!! ($payment_in->market->landmark_1) ? $payment_in->market->landmark_1.', ' : '' !!}
                    {!! ($payment_in->market->landmark_2) ? $payment_in->market->landmark_2.', ' : '' !!}
                    </p>
                    <p>{!! '<b>Phone  : </b>'.$payment_in->market->phone !!}</p>
                    <p>{!! '<b>Mobile : </b>'.$payment_in->market->mobile !!}</p>
                    <p>{!! '<b>Email  : </b>'.$payment_in->market->email !!}</p>
                  </td>
                  <td width="20%" colspan="2">
                    <p><b>Payment In No</b></p>
                    <p>{{$payment_in->code}}</p>  
                  </td>
                  <td width="20%" colspan="2">
                    <p><b>Date</b></p>
                    <p>{{$payment_in->date->format('d-m-Y')}}</p>
                  </td>
                </tr>

                <tr>
                  <td width="20%" colspan="2">
                    <p><b>Created By</b></p>
                    <p>{{$payment_in->createdby->name}}</p>  
                  </td>
                  <td width="20%" colspan="2">
                    <p><b>Payment Method</b></p>
                    <p>{{$payment_in->paymentmethod->name}}</p>  
                  </td>
                </tr>

            </tbody>
          </table>

          @if(count($payment_in->paymentinsettle) > 0) 
            <h6><b>Settled invoice with payment</b></h6>
          @endif

          <table class="table table-bordered view-table-custom-product-item">
            <tbody> 

              @if(count($payment_in->paymentinsettle) > 0)
                <tr class="bg-secondary" style="background-color: #28a745 !important;">
                  <th class="text-left">INVOICE NO</th>
                  <th class="text-center">DATE</th>
                  <th class="text-right">SETTLED AMOUNT</th>
                </tr>

                @foreach($payment_in->paymentinsettle as $invoice)
                  <tr>
                    <td width="60%">
                      @if($invoice->settle_type=='sales')
                        <a href="{{route('salesInvoice.show',$invoice->salesinvoice->id)}}">{{$invoice->salesinvoice->code}}</a>
                      @elseif($invoice->settle_type=='purchase')
                        <a href="{{route('purchaseReturn.show',$invoice->purchasereturn->id)}}">{{$invoice->purchasereturn->code}}</a>
                      @endif  
                    </td>
                    <td class="text-center" width="20%">{{$invoice->created_at->format('d M Y')}}</td>
                    <td class="text-right" width="20%">{{setting('default_currency')}} {{number_format($invoice->amount,2,'.','')}}</td>
                  </tr>
                @endforeach
              @endif     
                
                <tr>
                  <td>
                    
                    <h6><b>Notes</b></h6>
                    <p>{!!$payment_in->notes!!}</p>

                  </td>
                  <td class="font-weight-600 text-right"> <h6>Total</h6> </td>
                  <td class="text-right">
                    <h6>{{setting('default_currency')}} {{number_format($payment_in->total,2,'.','')}}</h6>
                  </td>
                </tr>
            </tbody>
          </table>


        </div>
      </div>



      <div class="clearfix"></div>

      <div class="row d-print-none">
        <!-- Back Field -->
        <div class="form-group col-12 text-right">
          <a href="{!! route('paymentIn.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Back </a>
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
