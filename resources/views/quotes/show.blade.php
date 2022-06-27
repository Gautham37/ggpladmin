@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Quotes<small class="ml-3 mr-3">|</small><small>Quotes Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{!! route('quotes.index') !!}">Quotes List </a>
          </li>
          <li class="breadcrumb-item active">Quotes</li>
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
          <a class="nav-link" href="{!! route('quotes.index') !!}"><i class="fa fa-list mr-2"></i>Quotes List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>Quote</a>
        </li>
      </ul>
      
        <div class="dropdown float-right top-action-btn">

          <div class="dropdown float-right top-action-btn">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
              <i class="fa fa-gear"></i> Options <span class="caret"></span>
            </button>
            
            <ul class="dropdown-menu">
              
              @if($quote->status!='invoiced')
              <a href="#" data-id="{{$quote->id}}" data-status="accepted" class="dropdown-item quote-status"> 
                <i class="fa fa-check"></i> Mark as Accepted
              </a>
              @endif

              @if($quote->status!='invoiced')
              <a href="#" data-id="{{$quote->id}}" data-status="declined" class='dropdown-item quote-status'> 
                <i class="fa fa-ban"></i> Mark as Declined
              </a>
              @endif

              @can('quotes.edit')
                  <a href="{{ route('quotes.edit', $quote->id) }}" class='dropdown-item'> 
                    <i class="fa fa-edit"></i> Edit
                  </a>
              @endcan

              @can('quotes.destroy')
                  <a href="#" data-id="{{$quote->id}}" data-url="{{route('quotes.destroy', $quote->id)}}" onclick="deleteItemLoad(this)" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
              @endcan

            </ul>

          </div>

        @can('quotes.print')
        <div class="dropdown float-right top-action-btn">
          <button style="background: #0078d7;border: 1px solid #0078d7;" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Download PDF
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li>
              <a target="_blank" id="print-invoice" href='{{route("quotes.print",[$quote->id,"1","download"])}}'>
                Download Original
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("quotes.print",[$quote->id,"2","download"])}}'>
                Download Duplicate
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("quotes.print",[$quote->id,"2","download"])}}'>
                Download Triplicate
              </a>
            </li>
          </ul>
        </div>
        @endcan

        @can('quotes.print')
        <div class="dropdown float-right top-action-btn">
          <button style="background: #881798;border: 1px solid #881798;" class="btn btn-primary  dropdown-toggle" type="button" data-toggle="dropdown">Print PDF
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li>
              <a target="_blank" id="print-invoice" href='{{route("quotes.print",[$quote->id,"1","print"])}}'>
                Print Original
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("quotes.print",[$quote->id,"2","print"])}}'>
                Print Duplicate
              </a>
            </li>
            <li>
              <a target="_blank" id="print-invoice" href='{{route("quotes.print",[$quote->id,"2","print"])}}'>
                Print Triplicate
              </a>
            </li>
          </ul>
        </div>
        @endcan

        

       <div class="float-right top-action-btn">
            <button style="background: #c30052;border: 1px solid #c30052;" id="thermal-print-invoice" class="btn btn-primary thermal_prints" href='{{route("quotes.print",[$quote->id,"1","thermalprint"])}}'>
              Thermal Print
            </button>
        </div>

        @if($quote->status=='accepted')

          @can('salesInvoice.create')
          <div class="float-right top-action-btn">
            <a class="btn btn-success" href='{{route("salesInvoice.create")}}?quote_id={{$quote->id}}'>
              <i class="fa fa-exchange"></i> &nbsp; Convert to Invoice
            </a>
          </div>
          @endcan

        @endif
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

                    <h6><a href="{{route('markets.view',$quote->market->id)}}"><b>{{$quote->market->name}}</b></a></h6>
                    <p>
                    <b>Address : </b> 
                    {!! ($quote->market->street_no) ? $quote->market->street_no.', ' : '' !!}
                    {!! ($quote->market->street_name) ? $quote->market->street_name.', ' : '' !!}
                    {!! ($quote->market->street_type) ? $quote->market->street_type.', ' : '' !!}
                    {!! ($quote->market->address_line_1) ? $quote->market->address_line_1.', ' : '' !!}
                    {!! ($quote->market->address_line_2) ? $quote->market->address_line_2.', ' : '' !!} 
                    {!! ($quote->market->town) ? $quote->market->town.', ' : '' !!} 
                    {!! ($quote->market->city) ? $quote->market->city.', ' : '' !!} 
                    {!! ($quote->market->state) ? $quote->market->state.', ' : '' !!} 
                    {!! ($quote->market->pincode) ? $quote->market->pincode.', ' : '' !!} 
                    <br>
                    <b>Landmark : </b>
                    {!! ($quote->market->landmark_1) ? $quote->market->landmark_1.', ' : '' !!}
                    {!! ($quote->market->landmark_2) ? $quote->market->landmark_2.', ' : '' !!}
                    </p>
                    <p>{!! '<b>Phone  : </b>'.$quote->market->phone !!}</p>
                    <p>{!! '<b>Mobile : </b>'.$quote->market->mobile !!}</p>
                    <p>{!! '<b>Email  : </b>'.$quote->market->email !!}</p>
                  </td>
                  <td width="20%" colspan="2">
                    <p><b>Quotes No</b></p>
                    <p>{{$quote->code}}</p>  
                  </td>
                  <td width="20%" colspan="2">
                    <p><b>Date</b></p>
                    <p>{{$quote->date->format('d-m-Y')}}</p>
                  </td>
                </tr>

                <tr>
                  <td width="10%" colspan="1">
                    <p><b>Due Date</b></p>
                    <p>{{$quote->valid_date->format('d-m-Y')}}</p>  
                  </td>
                  <td width="10%" colspan="1">
                    <p><b>Created By</b></p>
                    <p>{{$quote->createdby->name}}</p>  
                  </td>
                  <td width="20%" colspan="2">
                    <p><b>Status</b></p>
                    <p class="btn btn-{{$quote->status}} btn-sm">{{ucfirst($quote->status)}}</p>  
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
                @if(count($quote->items) > 0)
                  @foreach($quote->items as $item)
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
                    <p>{!!$quote->notes!!}</p>
                    <hr>
                    <h6><b>Terms & Conditions</b></h6>
                    <p>{!!$quote->terms_and_conditions!!}</p>

                  </td>
                  <td colspan="4" class="font-weight-600 text-right"> Sub Total </td>
                  <td class="text-right">
                   {{setting('default_currency')}} {{number_format($quote->sub_total,2,'.','')}}
                  </td>
                </tr>
                
                @if($quote->tax_total > 0)
                  <tr>
                    <td colspan="4" class="font-weight-600 text-right"> GST </td>
                    <td class="text-right">
                     {{setting('default_currency')}} {{number_format($quote->tax_total,2,'.','')}}
                    </td>
                  </tr>
                @endif

                @if($quote->discount_total > 0)
                  <tr>
                    <td colspan="4" class="font-weight-600 text-right"> Discount </td>
                    <td class="text-right">
                     {{setting('default_currency')}} {{number_format($quote->discount_total,2,'.','')}}
                    </td>
                  </tr>
                @endif

                @if($quote->additional_charge > 0)
                  <tr>
                    <td colspan="4" class="font-weight-600 text-right"> {{$quote->additional_charge_description}} </td>
                    <td class="text-right">
                     {{setting('default_currency')}} {{number_format($quote->additional_charge,2,'.','')}}
                    </td>
                  </tr>
                @endif

                @if($quote->delivery_charge > 0)
                  <tr>
                    <td colspan="4" class="font-weight-600 text-right"> Delivery Charge </td>
                    <td class="text-right">
                     {{setting('default_currency')}} {{number_format($quote->delivery_charge,2,'.','')}}
                    </td>
                  </tr>
                @endif
                
                <tr>
                  <td colspan="4" class="font-weight-600 text-right"> <h5><b>Total</b></h5> </td>
                  <td class="text-right">
                    <h5><b>{{setting('default_currency')}} {{number_format($quote->total,2,'.','')}}</b></h5>
                  </td>
                </tr>

                <?php /*/ ?>
                @if(count($quote->amountsettle) > 0)
                  @foreach($quote->amountsettle as $settle)
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
                
                @if($quote->amount_due > 0)
                  <tr>
                    <td colspan="4" class="font-weight-600 text-right"> <h5><b>Amout Due</b></h5> </td>
                    <td class="text-right">
                      <h5><b>{{setting('default_currency')}} {{number_format($quote->amount_due,2,'.','')}}</b></h5>
                    </td>
                  </tr>
                @endif
                <?php /*/ ?>

            </tbody>
          </table>


        </div>
      </div>



      <div class="clearfix"></div>

      <div class="row d-print-none">
        <!-- Back Field -->
        <div class="form-group col-12 text-right">
          <a href="{!! route('quotes.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Back </a>
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

    $('.quote-status').click(function() {
      var status = $(this).data('status');
      var id     = $(this).data('id');

      iziToast.show({
          theme: 'dark',
          icon: 'fa fa-trash',
          overlay: true,
          message: 'Are you sure?',
          position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          progressBarColor: 'yellow',
          backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
          messageColor: '#fff', 
          buttons: [
              ['<button>Yes</button>', function (instance, toast) {
                 
                  var url       = '{!!  route('quotes.show', [':quotesID']) !!}';
                  url           = url.replace(':quotesID', id);
                  var token     = "{{ csrf_token() }}";
                  $.ajax({
                      type: 'POST',
                      url: url,
                      data: {
                        '_token': token,
                        '_method': 'PATCH', 
                        'id': id,
                        'status': status,
                        'type': 'status-update'
                      },
                      success: function (response) {
                          iziToast.success({
                              backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                              messageColor: '#fff',
                              timeout: 3000, 
                              icon: 'fa fa-check', 
                              position: "topRight", 
                              iconColor:'#fff',
                              message: status.toUpperCase() + ' Sucessfully'
                          });
                          instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName');  
                          setTimeout(function () { location.reload(); }, 1000);
                      }
                  }); 

              }, true], // true to focus
              ['<button>No</button>', function (instance, toast) {
                  instance.hide({
                      transitionOut: 'fadeOutUp',
                      onClosing: function(instance, toast, closedBy){
                          console.info('closedBy: ' + closedBy); // The return will be: 'closedBy: buttonName'
                      }
                  }, toast, 'buttonName');
              }]
          ]
      });

    });
  </script>
@endpush
