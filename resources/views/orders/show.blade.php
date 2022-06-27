@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Orders<small class="ml-3 mr-3">|</small><small>Orders Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{!! route('orders.index') !!}">Orders List </a>
          </li>
          <li class="breadcrumb-item active">Orders</li>
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
          <a class="nav-link" href="{!! route('orders.index') !!}"><i class="fa fa-list mr-2"></i>Sales Invoice List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>Sales Invoice</a>
        </li>
      </ul>
      
        <div class="dropdown float-right top-action-btn">


          <div class="dropdown float-right top-action-btn">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
              <i class="fa fa-gear"></i> Options <span class="caret"></span>
            </button>
            
            <ul class="dropdown-menu">
              
              @can('orders.edit')
                  <a href="{{ route('orders.edit', $order->id) }}" class='dropdown-item'> 
                    <i class="fa fa-edit"></i> Edit
                  </a>
              @endcan

              @can('orders.destroy')
                  <a href="#" data-id="{{$order->id}}" data-url="{{route('orders.destroy', $order->id)}}" onclick="deleteItemLoad(this)" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
              @endcan

            </ul>

          </div>


        @can('orders.print')
        <div class="dropdown float-right top-action-btn">
          <button style="background: #0078d7;border: 1px solid #0078d7;" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Download PDF
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li>
              <a target="_blank" id="print-invoice" href='{{route("orders.print",[$order->id,"1","download"])}}'>
                Download Original
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("orders.print",[$order->id,"2","download"])}}'>
                Download Duplicate
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("orders.print",[$order->id,"2","download"])}}'>
                Download Triplicate
              </a>
            </li>
          </ul>
        </div>
        @endcan

        @can('orders.print')
        <div class="dropdown float-right top-action-btn">
          <button style="background: #881798;border: 1px solid #881798;" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Print PDF
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li>
              <a target="_blank" id="print-invoice" href='{{route("orders.print",[$order->id,"1","print"])}}'>
                Print Original
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("orders.print",[$order->id,"2","print"])}}'>
                Print Duplicate
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("orders.print",[$order->id,"2","print"])}}'>
                Print Triplicate
              </a>
            </li>
          </ul>
        </div>
        @endcan

        

       <div class="float-right top-action-btn">
            <button style="background: #c30052;border: 1px solid #c30052;" id="thermal-print-invoice" class="btn btn-primary thermal_prints" href='{{route("orders.print",[$order->id,"1","thermalprint"])}}'>
              Thermal Print
            </button>
        </div>

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

                    <h6><a href="{{route('markets.view',$order->user->market->id)}}"><b>{{$order->user->market->name}}</b></a></h6>
                    <p>
                    <b>Address : </b> 
                    {!! ($order->user->market->street_no) ? $order->user->market->street_no.', ' : '' !!}
                    {!! ($order->user->market->street_name) ? $order->user->market->street_name.', ' : '' !!}
                    {!! ($order->user->market->street_type) ? $order->user->market->street_type.', ' : '' !!}
                    {!! ($order->user->market->address_line_1) ? $order->user->market->address_line_1.', ' : '' !!}
                    {!! ($order->user->market->address_line_2) ? $order->user->market->address_line_2.', ' : '' !!} 
                    {!! ($order->user->market->town) ? $order->user->market->town.', ' : '' !!} 
                    {!! ($order->user->market->city) ? $order->user->market->city.', ' : '' !!} 
                    {!! ($order->user->market->state) ? $order->user->market->state.', ' : '' !!} 
                    {!! ($order->user->market->pincode) ? $order->user->market->pincode.', ' : '' !!} 
                    <br>
                    <b>Landmark : </b>
                    {!! ($order->user->market->landmark_1) ? $order->user->market->landmark_1.', ' : '' !!}
                    {!! ($order->user->market->landmark_2) ? $order->user->market->landmark_2.', ' : '' !!}
                    </p>
                    <p>{!! '<b>Phone  : </b>'.$order->user->market->phone !!}</p>
                    <p>{!! '<b>Mobile : </b>'.$order->user->market->mobile !!}</p>
                    <p>{!! '<b>Email  : </b>'.$order->user->market->email !!}</p>
                  </td>
                  <td width="20%" colspan="2">
                    <p><b>Order Code</b></p>
                    <p>{{$order->order_code}}</p>  
                  </td>
                  <td width="20%" colspan="2">
                    <p><b>Date</b></p>
                    <p>{{$order->created_at->format('d-m-Y')}}</p>
                  </td>
                </tr>

                <tr>
                  <td width="20%" colspan="2">
                    <p><b>Payment Status</b></p>
                    {!!getPayment($order->payment,'status')!!}  
                  </td>
                  <td width="20%" colspan="2">
                    <p><b>Status</b></p>
                    @if($order->deliverytrack)
                      <b class="btn btn-sm btn-{{str_replace('_','-',$order->deliverytrack->status)}}">
                        {{str_replace('_',' ',strtoupper($order->deliverytrack->status))}}
                      </b>
                    @else
                      <b class="btn btn-sm btn-{{str_replace('_','-',$order->status)}}">
                        {{str_replace('_',' ',strtoupper($order->status))}}
                      </b>
                    @endif
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
                  <th class="text-right">QTY</th>
                  <th class="text-right">PRICE / ITEM</th>
                  <th class="text-right">DISCOUNT</th>
                  <th class="text-right">TAX</th>
                  <th class="text-right">AMOUNT</th>
                </tr>
                @if(count($order->productOrders) > 0)
                  @foreach($order->productOrders as $item)
                    <tr>
                      <!-- <td></td> -->
                      <td class="text-left">{{$item->product_name}}</td>
                      <td class="text-center">{{$item->product_code}}</td>
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
                    <p>{!!$order->notes!!}</p>
                    <hr>
                    <h6><b>Terms & Conditions</b></h6>
                    <p>{!!$order->terms_and_conditions!!}</p>

                  </td>
                  <td colspan="3" class="font-weight-600 text-right"> 
                    <!-- Sub Total -->
                  </td>
                  <td class="text-right">
                   <!-- {{setting('default_currency')}} {{number_format($order->sub_total,2,'.','')}} -->
                  </td>
                </tr>
                
                @if($order->tax > 0)
                  <tr>
                    <td colspan="3" class="font-weight-600 text-right"> GST </td>
                    <td class="text-right">
                     {{setting('default_currency')}} {{number_format($order->tax,2,'.','')}}
                    </td>
                  </tr>
                @endif

                @if($order->coupon_amount > 0)
                  <tr>
                    <td colspan="3" class="font-weight-600 text-right"> Discount </td>
                    <td class="text-right">
                     {{setting('default_currency')}} {{number_format($order->coupon_amount,2,'.','')}}
                    </td>
                  </tr>
                @endif

                
                <tr>
                  <td colspan="3" class="font-weight-600 text-right"> Delivery ({{$order->delivery_distance}} Km) </td>
                  <td class="text-right">
                    @if($order->delivery_fee > 0)
                      {{setting('default_currency')}} {{number_format($order->delivery_fee,2,'.','')}}
                    @else
                      <span>Free Delivery</span>
                    @endif
                  </td>
                </tr>
                
                @if($order->redeempoints)
                  <tr>
                    <td colspan="3" class="font-weight-600 text-right"> Redeem {{$order->redeempoints->points}} Points </td>
                    <td class="text-right">
                     {{setting('default_currency')}} {{number_format($order->redeempoints->amount,2,'.','')}}
                    </td>
                  </tr>
                @endif


                @if($order->contribution_amount > 0)
                  <tr>
                    <td colspan="3" class="font-weight-600 text-right"> Contribution </td>
                    <td class="text-right">
                     {{setting('default_currency')}} {{number_format($order->contribution_amount,2,'.','')}}
                    </td>
                  </tr>
                @endif
                
                <tr>
                  <td colspan="3" class="font-weight-600 text-right"> <h5><b>Total</b></h5> </td>
                  <td class="text-right">
                    <h5><b>{{setting('default_currency')}} {{number_format($order->order_amount,2,'.','')}}</b></h5>
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
          <a href="{!! route('orders.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Back </a>
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
