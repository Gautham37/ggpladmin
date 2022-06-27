@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Purchase Return<small class="ml-3 mr-3">|</small><small>Purchase Return Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{!! route('purchaseReturn.index') !!}">Purchase Return List </a>
          </li>
          <li class="breadcrumb-item active">Purchase Return</li>
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
          <a class="nav-link" href="{!! route('purchaseReturn.index') !!}"><i class="fa fa-list mr-2"></i>Purchase Return List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>Purchase Return</a>
        </li>
      </ul>
      
        <div class="dropdown float-right top-action-btn">

        @can('purchaseReturn.print')
        <div class="dropdown float-right top-action-btn">
          <button style="background: #0078d7;border: 1px solid #0078d7;" class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Download PDF
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li>
              <a target="_blank" id="print-invoice" href='{{route("purchaseReturn.print",[$purchase_return->id,"1","download"])}}'>
                Download Original
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("purchaseReturn.print",[$purchase_return->id,"2","download"])}}'>
                Download Duplicate
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("purchaseReturn.print",[$purchase_return->id,"2","download"])}}'>
                Download Triplicate
              </a>
            </li>
          </ul>
        </div>
        @endcan

        @can('purchaseReturn.print')
        <div class="dropdown float-right top-action-btn">
          <button style="background: #881798;border: 1px solid #881798;" class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Print PDF
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li>
              <a target="_blank" id="print-invoice" href='{{route("purchaseReturn.print",[$purchase_return->id,"1","print"])}}'>
                Print Original
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("purchaseReturn.print",[$purchase_return->id,"2","print"])}}'>
                Print Duplicate
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("purchaseReturn.print",[$purchase_return->id,"2","print"])}}'>
                Print Triplicate
              </a>
            </li>
          </ul>
        </div>
        @endcan

        
        @can('purchaseReturn.print')
        <div class="float-right top-action-btn">
            <button style="background: #c30052;border: 1px solid #c30052;" id="thermal-print-invoice" class="btn btn-primary btn-sm thermal_prints" href='{{route("purchaseReturn.print",[$purchase_return->id,"1","thermalprint"])}}'>
              Thermal Print
            </button>
        </div>
        @endcan

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

                    <h6><a href="{{route('markets.view',$purchase_return->market->id)}}"><b>{{$purchase_return->market->name}}</b></a></h6>
                    <p>
                    <b>Address : </b> 
                    {!! ($purchase_return->market->street_no) ? $purchase_return->market->street_no.', ' : '' !!}
                    {!! ($purchase_return->market->street_name) ? $purchase_return->market->street_name.', ' : '' !!}
                    {!! ($purchase_return->market->street_type) ? $purchase_return->market->street_type.', ' : '' !!}
                    {!! ($purchase_return->market->address_line_1) ? $purchase_return->market->address_line_1.', ' : '' !!}
                    {!! ($purchase_return->market->address_line_2) ? $purchase_return->market->address_line_2.', ' : '' !!} 
                    {!! ($purchase_return->market->town) ? $purchase_return->market->town.', ' : '' !!} 
                    {!! ($purchase_return->market->city) ? $purchase_return->market->city.', ' : '' !!} 
                    {!! ($purchase_return->market->state) ? $purchase_return->market->state.', ' : '' !!} 
                    {!! ($purchase_return->market->pincode) ? $purchase_return->market->pincode.', ' : '' !!} 
                    <br>
                    <b>Landmark : </b>
                    {!! ($purchase_return->market->landmark_1) ? $purchase_return->market->landmark_1.', ' : '' !!}
                    {!! ($purchase_return->market->landmark_2) ? $purchase_return->market->landmark_2.', ' : '' !!}
                    </p>
                    <p>{!! '<b>Phone  : </b>'.$purchase_return->market->phone !!}</p>
                    <p>{!! '<b>Mobile : </b>'.$purchase_return->market->mobile !!}</p>
                    <p>{!! '<b>Email  : </b>'.$purchase_return->market->email !!}</p>
                  </td>
                  <td width="20%" colspan="2">
                    <p><b>Purchase Return No</b></p>
                    <p>{{$purchase_return->code}}</p>  
                  </td>
                  <td width="20%" colspan="2">
                    <p><b>Date</b></p>
                    <p>{{$purchase_return->date->format('d-m-Y')}}</p>
                  </td>
                </tr>

                <tr>
                  <td width="20%" colspan="2">
                    <p><b>Due Date</b></p>
                    <p>{{$purchase_return->valid_date->format('d-m-Y')}}</p>  
                  </td>
                  <td width="20%" colspan="2">
                    <p><b>Created By</b></p>
                    <p>{{$purchase_return->createdby->name}}</p>  
                  </td>
                </tr>

            </tbody>
          </table>

          <table class="table table-bordered view-table-custom-product-item">
            <tbody>      

                <tr class="bg-secondary" style="background-color: #28a745 !important;">
                  <!-- <th>No</th> -->
                  <th>ITEM</th>
                  <th class="text-center">HSN CODE</th>
                  <th class="text-right">MRP</th>
                  <th class="text-right">QTY</th>
                  <th class="text-right">PRICE / ITEM</th>
                  <th class="text-right">DISCOUNT</th>
                  <th class="text-right">TAX</th>
                  <th class="text-right">AMOUNT</th>
                </tr>
                @if(count($purchase_return->items) > 0)
                  @foreach($purchase_return->items as $item)
                    <tr>
                      <!-- <td></td> -->
                      <td class="text-left">{{$item->product_name}}</td>
                      <td class="text-center">{{$item->product_hsn_code}}</td>
                      <td class="text-right">{{number_format($item->mrp,2,'.','')}}</td>
                      <td class="text-right">{{number_format($item->quantity,3,'.','')}} {{$item->uom->name}}</td>
                      <td class="text-right">{{number_format($item->unit_price,2,'.','')}}</td>
                      <td class="text-right">{{number_format($item->discount_amount,2,'.','')}}</td>
                      <td class="text-right">{{number_format($item->tax_amount,2,'.','')}}</td>
                      <td class="text-right">{{number_format($item->amount,2,'.','')}}</td>  
                    </tr>
                  @endforeach
                @endif
                
                <tr>
                  <td colspan="3" rowspan="7">
                    
                    <h6><b>Notes</b></h6>
                    <p>{!!$purchase_return->notes!!}</p>
                    <hr>
                    <h6><b>Terms & Conditions</b></h6>
                    <p>{!!$purchase_return->terms_and_conditions!!}</p>

                  </td>
                  <td colspan="4" class="font-weight-600 text-right"> Sub Total </td>
                  <td class="text-right">
                   {{setting('default_currency')}} {{number_format($purchase_return->sub_total,2,'.','')}}
                  </td>
                </tr>
                
                @if($purchase_return->tax_total > 0)
                  <tr>
                    <td colspan="4" class="font-weight-600 text-right"> GST </td>
                    <td class="text-right">
                     {{setting('default_currency')}} {{number_format($purchase_return->tax_total,2,'.','')}}
                    </td>
                  </tr>
                @endif

                @if($purchase_return->discount_total > 0)
                  <tr>
                    <td colspan="4" class="font-weight-600 text-right"> Discount </td>
                    <td class="text-right">
                     {{setting('default_currency')}} {{number_format($purchase_return->discount_total,2,'.','')}}
                    </td>
                  </tr>
                @endif

                @if($purchase_return->additional_charge > 0)
                  <tr>
                    <td colspan="4" class="font-weight-600 text-right"> {{$purchase_return->additional_charge_description}} </td>
                    <td class="text-right">
                     {{setting('default_currency')}} {{number_format($purchase_return->additional_charge,2,'.','')}}
                    </td>
                  </tr>
                @endif

                @if($purchase_return->delivery_charge > 0)
                  <tr>
                    <td colspan="4" class="font-weight-600 text-right"> Delivery Charge </td>
                    <td class="text-right">
                     {{setting('default_currency')}} {{number_format($purchase_return->delivery_charge,2,'.','')}}
                    </td>
                  </tr>
                @endif
                
                <tr>
                  <td colspan="4" class="font-weight-600 text-right"> <h5><b>Total</b></h5> </td>
                  <td class="text-right">
                    <h5><b>{{setting('default_currency')}} {{number_format($purchase_return->total,2,'.','')}}</b></h5>
                  </td>
                </tr>

                @if(count($purchase_return->amountsettle) > 0)
                  @foreach($purchase_return->amountsettle as $settle)
                    <tr>
                      <td colspan="4" class="font-weight-600 text-right"> 
                        <h6>
                          <b>Payment In</b>
                          <a href="{{route('paymentIn.show',$settle->paymentin->id)}}">{{$settle->paymentin->code}}</a>
                        </h6>
                      </td>
                      <td class="text-right">
                        <h6><b>{{setting('default_currency')}} {{number_format($settle->amount,2,'.','')}}</b></h6>
                      </td>
                    </tr>
                  @endforeach
                @endif
                
                @if($purchase_return->amount_due > 0)
                  <tr>
                    <td colspan="4" class="font-weight-600 text-right"> <h5><b>Amout Due</b></h5> </td>
                    <td class="text-right">
                      <h5><b>{{setting('default_currency')}} {{number_format($purchase_return->amount_due,2,'.','')}}</b></h5>
                    </td>
                  </tr>
                @endif

            </tbody>
          </table>


        </div>
      </div>



      <div class="clearfix"></div>

      <div class="row d-print-none">
        <!-- Back Field -->
        <div class="form-group col-12 text-right">
          <a href="{!! route('purchaseReturn.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Back </a>
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
