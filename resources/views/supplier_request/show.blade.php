@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.supplier_request_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.supplier_request_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('supplierRequest.index') !!}">{{trans('lang.supplier_request_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.supplier_request')}}</li>
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
          <a class="nav-link" href="{!! route('supplierRequest.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.supplier_request_table')}}</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.supplier_request')}}</a>
        </li>

        <?php /* ?>  
        &nbsp;&nbsp;
        <button id="thermal-print-invoice" class="btn btn-primary btn-sm thermal_prints" href='{{url("/supplierRequest/thermalprintsupplierRequest/$supplier_request->id")}}'>
          {{ trans('lang.app_thermal_print') }}
        </button>
        <?php /*/ ?> 
        

      </ul>
      

        <div class="dropdown float-right top-action-btn">
          <button style="background: #0078d7;border: 1px solid #0078d7;" class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">{{trans('lang.sales_download')}} {{trans('lang.sales_pdf')}}
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a target="_blank" id="download-invoice" href='{{url("/supplierRequest/downloadSupplierRequest/$supplier_request->id/1")}}'>{{trans('lang.sales_download')}} {{trans('lang.sales_original')}}</a></li>
          </ul>
        </div>


        <div class="dropdown float-right top-action-btn">
          <button style="background: #881798;border: 1px solid #881798;" class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Print PDF
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a target="_blank" id="print-invoice" href='{{url("/supplierRequest/printSupplierRequest/$supplier_request->id/1")}}'>{{trans('lang.sales_print')}} {{trans('lang.sales_original')}}</a></li>
          </ul>
        </div>
        
        @can('supplierRequest.edit')
        &nbsp;&nbsp; <div class="float-right top-action-btn">
            @if($supplier_request->sr_status!=3)
              <a  data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.supplier_request_edit')}}" href="{{ route('supplierRequest.edit', $supplier_request->id) }}" class="btn btn-sm btn-warning float-right">
                {{trans('lang.pro_edit_item').' '.trans('lang.supplier_request') }} 
              </a>
            @endif
            </div>
        @endcan
        &nbsp;&nbsp; <div class="float-right top-action-btn">
        @can('supplierRequest.destroy')
            {!! Form::open(['route' => ['supplierRequest.destroy', $supplier_request->id], 'method' => 'delete']) !!}
              {!! Form::button( trans('lang.delete').' '.trans('lang.supplier_request'), [
              'type' => 'submit',
              'class' => 'btn btn-sm btn-danger',
              'onclick' => "return confirm('Are you sure?')"
              ]) !!}
            {!! Form::close() !!}
        @endcan
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
                    <p style="margin-bottom:0px;"><b>{{trans('lang.supplied_by')}}</b></p>
                  </td>
                  <td width="20%" colspan="2">
                    <p style="margin-bottom:0px;"><b>{{trans('lang.supplier_request_no')}}</b></p>
                    <p>{{$supplier_request->sr_code}}</p>  
                  </td>
                  <td width="20%" colspan="2">
                    <p style="margin-bottom:0px;"><b>{{trans('lang.supplier_request_date')}}</b></p>
                    <p>{{date('d-m-Y',strtotime($supplier_request->sr_date))}}</p>
                  </td>
                </tr>
                <tr>
                  <td width="60%" colspan="6">
                      <b>{{$requestParty->name}}</b></h6>
                      <p>
                       <b>{{trans('lang.address')}} : </b> {{$requestParty->address_line_1}}, {{$requestParty->address_line_2}}
                       <br>
                       {{$requestParty->city}} - {{$requestParty->pincode}}, {{$requestParty->state}}.
                       <br> 
                       <b>{{trans('lang.market_mobile')}} : </b> {{$requestParty->mobile}} , <b>{{trans('lang.market_phone')}} : </b> {{$requestParty->phone}}
                       <br> 
                       <b>{{trans('lang.gstin')}} : </b> {{$requestParty->gstin}}
                     </p>
                  </td>
                  <td width="40%" colspan="4">
                    <p style="margin-bottom:0px;"><b>{{trans('lang.supplier_request_valid_date')}}</b></p>
                    <p>{{date('d-m-Y',strtotime($supplier_request->sr_valid_date))}}</p>  
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
                  <?php /* ?><th>{{trans('lang.po_table_price')}}</th>*/?>
                  <th>{{trans('lang.po_table_amount')}}</th>
                </tr>
                <?php $count = 0 ?>
                @foreach($requestDetails as $request_item)
                <?php $count++ ?>
                <tr>
                  <td><?= $count ?></td>
                  <td colspan="2">{{$request_item->sr_detail_product_name}}</td>
                  <td>{{$request_item->sr_detail_product_hsn_code}}</td>
                  <td>{{number_format($request_item->sr_detail_mrp,2)}}</td>
                  <td>{{$request_item->sr_detail_quantity}}</td>
                  <?php /* ?><td>{{number_format($request_item->sr_detail_price,2)}}</td><?php /*/ ?>
                  <td>{{number_format($request_item->sr_amount,2)}}</td>
                </tr>

                @endforeach
          </tbody>
          </table>

          <table class="table table-bordered">      
            <tbody>
                <?php /* ?>
                <tr>
                  <td rowspan="5" width="50%">
                    <p><b>{{trans('lang.po_table_notes')}}</b></p>
                    {!!$supplier_request->sr_notes!!}
                  </td>
                  <td>
                    {{trans('lang.po_taxable_amount')}}
                  </td>
                  <td class="text-right" >
                    {{$supplier_request->sr_taxable_amount}}
                  </td>
                </tr>
                <?php /*/ ?>
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
          <a href="{!! route('supplierRequest.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.back')}}</a>
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
