@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.sales_return_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.sales_return_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('salesReturn.index') !!}">{{trans('lang.sales_return_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.sales_return')}}</li>
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
          <a class="nav-link" href="{!! route('salesReturn.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.sales_return_table')}}</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.sales_return')}}</a>
        </li>


      </ul>
      

        <div class="dropdown float-right top-action-btn">
          <button style="background: #0078d7;border: 1px solid #0078d7;" class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">{{trans('lang.sales_download')}} {{trans('lang.sales_pdf')}}
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a target="_blank" id="download-invoice" href='{{url("/salesReturn/downloadsalesReturn/$sales_return->id/1")}}'>{{trans('lang.sales_download')}} {{trans('lang.sales_original')}}</a></li>
            <li><a target="_blank" href='{{url("/salesReturn/downloadsalesReturn/$sales_return->id/2")}}'>{{trans('lang.sales_download')}} {{trans('lang.sales_duplicate')}}</a></li>
            <li><a target="_blank" href='{{url("/salesReturn/downloadsalesReturn/$sales_return->id/3")}}'>{{trans('lang.sales_download')}} {{trans('lang.sales_triplicate')}}</a></li>
          </ul>
        </div>


        <div class="dropdown float-right top-action-btn">
          <button style="background: #881798;border: 1px solid #881798;" class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Print PDF
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a target="_blank" id="print-invoice" href='{{url("/salesReturn/printsalesReturn/$sales_return->id/1")}}'>{{trans('lang.sales_print')}} {{trans('lang.sales_original')}}</a></li>
            <li><a target="_blank" href='{{url("/salesReturn/printsalesReturn/$sales_return->id/2")}}'>{{trans('lang.sales_print')}} {{trans('lang.sales_duplicate')}}</a></li>
            <li><a target="_blank" href='{{url("/salesReturn/printsalesReturn/$sales_return->id/3")}}'>{{trans('lang.sales_print')}} {{trans('lang.sales_triplicate')}}</a></li>
          </ul>
        </div>

       <div class="float-right top-action-btn">
            <button style="background: #c30052;border: 1px solid #c30052;" id="thermal-print-invoice" class="btn btn-primary btn-sm thermal_prints" href='{{url("/salesReturn/thermalprintsalesReturn/$sales_return->id")}}'>
              {{ trans('lang.app_thermal_print') }}
            </button> 
        </div>
        
        <div class="float-right top-action-btn">
            <a style="background: #107c10;border: 1px solid #107c10;" class="btn btn-primary btn-sm" href="https://web.whatsapp.com/send?text=<?=$wp?>" data-action="share/whatsapp/share" target="_blank">Whatsapp  <i class="fa fa-whatsapp"></i></a>
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
                  <td width="60%" colspan="6">
                    <p style="margin-bottom:0px;"><b>{{trans('lang.bill_to')}}</b></p>
                  </td>
                  <td width="20%" colspan="2">
                    <p style="margin-bottom:0px;"><b>{{trans('lang.sales_return_no')}}</b></p>
                    <p>{{$sales_return->sales_code}}</p>  
                  </td>
                  <td width="20%" colspan="2">
                    <p style="margin-bottom:0px;"><b>{{trans('lang.sales_return_date')}}</b></p>
                    <p>{{date('d-m-Y',strtotime($sales_return->sales_date))}}</p>
                  </td>
                </tr>
                <tr>
                  <td width="60%" colspan="6">
                      <b>{{$sales_party->name}}</b></h6>
                      <p>
                       <b>{{trans('lang.address')}} : </b> {{$sales_party->address_line_1}}, {{$sales_party->address_line_2}}
                       <br>
                       {{$sales_party->city}} - {{$sales_party->pincode}}, {{$sales_party->state}}.
                       <br>
                       <b>{{trans('lang.market_mobile')}} : </b> {{$sales_party->mobile}} , <b>{{trans('lang.market_phone')}} : </b> {{$sales_party->phone}}
                       <br> 
                       <b>{{trans('lang.gstin')}} : </b> {{$sales_party->gstin}}
                     </p>
                  </td>
                  <td width="40%" colspan="4">
                    <p style="margin-bottom:0px;"><b>{{trans('lang.sales_return_valid_date')}}</b></p>
                    <p>{{date('d-m-Y',strtotime($sales_return->sales_valid_date))}}</p>  
                  </td>
                </tr>

          </tbody>
          </table>

          <table class="table table-bordered view-table-custom-product-item">
            <tbody>      

                <tr class="bg-secondary" style="background-color: #28a745 !important;">
                  <th>{{trans('lang.po_table_no')}}</th>
                  <th colspan="2">{{trans('lang.po_table_items')}}</th>
                  <th>{{trans('lang.po_table_hsn')}}</th>
                  <th>{{trans('lang.po_table_mrp')}}</th>
                  <th>{{trans('lang.po_table_qty')}}</th>
                  <th>{{trans('lang.po_table_price')}}</th>
                  <th>{{trans('lang.po_table_discount')}}</th>
                  <th>{{trans('lang.po_table_tax')}}</th>
                  <th>{{trans('lang.po_table_amount')}}</th>
                </tr>

                @foreach($sales_detail as $sales_item)
                
                <tr>
                  <td>1</td>
                  <td colspan="2">{{$sales_item->sales_detail_product_name}}</td>
                  <td>{{$sales_item->sales_detail_product_hsn_code}}</td>
                  <td>{{number_format($sales_item->sales_detail_mrp,2)}}</td>
                  <td>{{$sales_item->sales_detail_quantity}} {{$sales_item->sales_detail_unit}}</td>
                  <td>{{number_format($sales_item->sales_detail_price,2)}}</td>
                  <td>{{$sales_item->sales_detail_discount_amount}} ({{$sales_item->sales_detail_discount_percent}} %)</td>
                  <td>{{$sales_item->sales_detail_igst}} ({{$sales_item->sales_detail_tax_percent}}%)</td>
                  <td>{{$sales_item->sales_detail_amount}}</td>  
                </tr>

                @endforeach

              
                <tr>
                  <th class="text-right" colspan="7">{{trans('lang.po_table_sub_total')}}</th>
                  <td>₹ {{$sales_return->sales_discount_amount}}</td>
                  <td>₹ {{$sales_return->sales_igst_amount}}</td>
                  <td>₹ {{($sales_return->sales_total_amount - $sales_return->sales_additional_charge)}}</td>  
                </tr>

          </tbody>
          </table>

          <table class="table table-bordered view-table-custom">      
            <tbody>
                <tr>
                  <td rowspan="5" width="50%">
                    <p style="margin-bottom:5px;"><b>{{trans('lang.po_table_notes')}}</b></p>
                    {!!$sales_return->sales_notes!!}

                    <p style="margin-bottom:5px;"><b>{{trans('lang.po_table_terms_and_conditions')}}</b></p>
                    {!!$sales_return->sales_terms_and_conditions!!}
                  </td>
                  <td class="font-weight-600">
                    {{trans('lang.po_taxable_amount')}}
                  </td>
                  <td class="text-right" >
                    {{$sales_return->sales_taxable_amount}}
                  </td>
                </tr>
                <tr>
                  <td class="font-weight-600">
                    {{trans('lang.po_sgst_amount')}}
                  </td>
                  <td class="text-right">
                    {{$sales_return->sales_sgst_amount}}
                  </td>
                </tr>
                <tr>
                  <td class="font-weight-600" >
                    {{trans('lang.po_cgst_amount')}}
                  </td>
                  <td class="text-right">
                    {{$sales_return->sales_cgst_amount}}
                  </td>
                </tr>
                <tr>
                  <td class="font-weight-600">
                    {{ trans('lang.po_additional_charge')}}<br>
                    {{$sales_return->sales_additional_charge_description}}
                  </td>
                  <td  class="text-right">
                    {{$sales_return->sales_additional_charge}}
                  </td>
                </tr>
                <tr>
                  <td class="font-weight-600">
                    {{trans('lang.po_round_off')}}
                  </td>
                  <td class="text-right">
                    {{$sales_return->sales_round_off}}
                  </td>
                </tr>
                <tr>
                  <td rowspan="4" width="50%"></td>
                  <td class="font-weight-600">
                    {{trans('lang.po_total_amount')}}
                  </td>
                  <td class="text-right">
                    {{$sales_return->sales_total_amount}}
                  </td>
                </tr>
                <tr>
                  <td class="font-weight-600">
                    {{trans('lang.po_cash_paid')}}
                  </td>
                  <td class="text-right">
                    {{$sales_return->sales_cash_paid}}
                  </td>
                </tr>
                <tr>
                  <td class="font-weight-600">
                    {{trans('lang.po_balance_amount')}}
                  </td>
                  <td class="text-right">
                    {{$sales_return->sales_balance_amount}}
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <p class="text-right mt-2">{{trans('lang.po_table_signature')}} <b>{{setting('app_name')}}</b></p>
                    <img style="width: 10%; float: right;" src="{{$app_invoice_signature}}">
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
          <a href="{!! route('salesReturn.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.back')}}</a>
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
