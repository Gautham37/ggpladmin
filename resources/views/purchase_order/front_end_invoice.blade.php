@extends('layouts.front_app')

@section('content')
    
<div class="dwn-button-div" style="float: right;margin-right: 20%;">
   <table>
      <tr>
         <td>
            <div class="float-left" style="margin-right:5px;">
               @if($purchase_order->status=='sent' || $purchase_order->status=='viewed')
                  <div class="float-left top-action-btn">
                    <button data-status="accepted" class="btn btn-success purchase-status-btn float-left bg-secondary" style="background-color:#28a745;color:#fff;border: 1px solid #28a745;padding:4px 10px;"> Accept </button>
                    &nbsp;
                    <button data-status="declined" class="btn btn-info purchase-status-btn float-left" style="background-color:#c30052;color:#fff;border: 1px solid #c30052;padding:4px 10px;"> Decline </button>
                  </div>
               @else    
                    <button class="btn btn-info float-left" style="background-color:#0078d7;color:#fff;border:#0078d7;padding:4px 10px;"> Quote {{ucfirst($purchase_order->status)}} </button>
               @endif
            </div>
         </td>
         <td>
            <div class="float-right top-action-btn">
               <a href="{{url(base64_encode($purchase_order->id).'/PurchaseOrder/DownloadPDF')}}"><button style="background: #881798;border: 1px solid #881798;" class="btn btn-primary btn-sm thermal_prints">
                 Download PDF
               </button></a>
            </div>
         </td>
      </tr>
   </table>
</div>

<div class="pdf_all" style="font-size:12px;margin: -25px;" cellpadding="5">
   <div class="stylish_original">
      <div class="invoice_page_original" style="font-size:12px;background-color:#f4f4f533;">
         <div class="invoice_page1">
            <table style="width: auto; background-color:#f4f4f533;">
               <tr>
                  <td style="border: 0px solid #000; padding: 3px;"><b>Purchase Order</b></td>
               </tr>
            </table>
            <table class="table"  cellpadding="5" style="width: 100%;background-color:#f4f4f533;">
               <tbody>
                  <tr>
                     <td style="width: 20%; text-align:center;">
                        <!-- {{$app_logo}} -->
                        <img src="{{$app_logo}}" alt="{{setting('app_name')}}" style="width:100px;height:100px;" >
                     </td>
                     <td style="width: 80%;">
                        <span class="pdf_head" style="font-size:25px;line-height: 25px;"> {{ setting('app_name') }}</span><br>
                        <span style="font-size:12px;line-height: 25px;">
                          {{setting('app_store_address_line_1')}}
                          {{setting('app_store_address_line_2')}} 
                          {{setting('app_store_city')}}, 
                          {{setting('app_store_pincode')}}.
                          {{setting('app_store_state')}}, {{setting('app_store_country')}},
                          <br>
                          <b>{{ trans('lang.market_mobile') }}:</b> 
                          {{setting('app_store_phone_no')}} &nbsp;&nbsp;
                          <b>{{ trans('lang.gstin') }} : </b> {{setting('app_store_gstin')}}
                        </span>
                     </td>
                  </tr>
               </tbody>
            </table>

            <div class="pdf_div" @if(setting('app_invoice_color')!='') style="background-color:{!! setting('app_invoice_color') !!};" @endif ></div>

            <table style="width: 100%; line-height:15px;" class="pdf_div1" cellpadding="15">
               <tr>
                  <td width="33%">
                     <b>Purchase Order No : </b> 
                     {{$purchase_order->code}}
                  </td>
                  <td width="33%">
                     <b>Date : </b> 
                     {{$purchase_order->date->format('d M Y')}}
                  </td>
                  <td width="33%">
                     <b>Valid Date : </b> 
                     {{$purchase_order->valid_date->format('d M Y')}}
                  </td>
               </tr>
            </table>
            <div class="pdf_whole" style="padding:10px;background-color:#f4f4f533;">
               
               <table style="width: 100%; line-height:15px;">
                  <tr>
                     <td width="33.33333%">  
                        <p><b>Bill To :</b></p>
                        <p><b>{{$purchase_order->market->name}}</b></p>
                        <p>
                        <b>Address : </b> 
                        {!! ($purchase_order->market->street_no) ? $purchase_order->market->street_no.', ' : '' !!}
                        {!! ($purchase_order->market->street_name) ? $purchase_order->market->street_name.', ' : '' !!}
                        {!! ($purchase_order->market->street_type) ? $purchase_order->market->street_type.', ' : '' !!}
                        {!! ($purchase_order->market->address_line_1) ? $purchase_order->market->address_line_1.', ' : '' !!}
                        {!! ($purchase_order->market->address_line_2) ? $purchase_order->market->address_line_2.', ' : '' !!} 
                        {!! ($purchase_order->market->town) ? $purchase_order->market->town.', ' : '' !!} 
                        {!! ($purchase_order->market->city) ? $purchase_order->market->city.', ' : '' !!} 
                        {!! ($purchase_order->market->state) ? $purchase_order->market->state.', ' : '' !!} 
                        {!! ($purchase_order->market->pincode) ? $purchase_order->market->pincode.', ' : '' !!} 
                        <br>
                        <b>Landmark : </b>
                        {!! ($purchase_order->market->landmark_1) ? $purchase_order->market->landmark_1.', ' : '' !!}
                        {!! ($purchase_order->market->landmark_2) ? $purchase_order->market->landmark_2.', ' : '' !!}
                        </p>
                        <p>{!! '<b>Phone  : </b>'.$purchase_order->market->phone !!}</p>
                        <p>{!! '<b>Mobile : </b>'.$purchase_order->market->mobile !!}</p>
                        <p>{!! '<b>Email  : </b>'.$purchase_order->market->email !!}</p>
                     </td>
                     <td width="33.33333%"></td>
                     <td width="33.33333%">
                        <p><b>Ship To :</b></p>
                        <p><b>{{$purchase_order->market->name}}</b></p>
                        <p>
                        <b>Address : </b> 
                        {!! ($purchase_order->market->street_no) ? $purchase_order->market->street_no.', ' : '' !!}
                        {!! ($purchase_order->market->street_name) ? $purchase_order->market->street_name.', ' : '' !!}
                        {!! ($purchase_order->market->street_type) ? $purchase_order->market->street_type.', ' : '' !!}
                        {!! ($purchase_order->market->address_line_1) ? $purchase_order->market->address_line_1.', ' : '' !!}
                        {!! ($purchase_order->market->address_line_2) ? $purchase_order->market->address_line_2.', ' : '' !!} 
                        {!! ($purchase_order->market->town) ? $purchase_order->market->town.', ' : '' !!} 
                        {!! ($purchase_order->market->city) ? $purchase_order->market->city.', ' : '' !!} 
                        {!! ($purchase_order->market->state) ? $purchase_order->market->state.', ' : '' !!} 
                        {!! ($purchase_order->market->pincode) ? $purchase_order->market->pincode.', ' : '' !!} 
                        <br>
                        <b>Landmark : </b>
                        {!! ($purchase_order->market->landmark_1) ? $purchase_order->market->landmark_1.', ' : '' !!}
                        {!! ($purchase_order->market->landmark_2) ? $purchase_order->market->landmark_2.', ' : '' !!}
                        </p>
                        <p>{!! '<b>Phone  : </b>'.$purchase_order->market->phone !!}</p>
                        <p>{!! '<b>Mobile : </b>'.$purchase_order->market->mobile !!}</p>
                        <p>{!! '<b>Email  : </b>'.$purchase_order->market->email !!}</p>
                     </td>
                  </tr>
               </table>

               <style>
                  .view-table-custom-product-item tbody tr th { 
                     padding: 8px 10px !important;font-size: 13px; 
                     border-top:3px solid {!! (setting('app_invoice_color')!='') ? setting('app_invoice_color') : '#000' !!} ;
                     border-bottom:3px solid {!! (setting('app_invoice_color')!='') ? setting('app_invoice_color') : '#000' !!} ;
                     font-size: 12px;
                  }

                  .view-table-custom-product-item tbody tr td { 
                     padding: 8px 10px !important;font-size: 13px; 
                     border-bottom:1px solid #e9ecef;
                     font-size: 12px;
                  }
               </style>

               <table class="table table-bordered view-table-custom-product-item" style="width: 100%; border-collapse: collapse;">
                  <tbody>      
                   <tr>
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
                   @if(count($purchase_order->items) > 0)
                     @foreach($purchase_order->items as $item)
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
                     <td colspan="3" rowspan="7" style="vertical-align: top;">
                        <table>
                           <tbody>                       
                              <tr>
                                 <td><b>Bank Detail</b></td>
                                 <td></td>
                              </tr>
                              <tr>
                                 <td>Account Number :</td>
                                 <td>{{setting('app_bank_account_number')}}</td>
                              </tr>
                              <tr>
                                 <td>IFSC Code :</td>
                                 <td>{{setting('app_bank_ifsc_code')}}</td>
                              </tr>
                              <tr>
                                 <td>Bank Name :</td>
                                 <td>{{setting('app_bank_bankname')}}</td>
                              </tr>
                              <tr>
                                 <td>Branch Name :</td>
                                 <td>{{setting('app_bank_bankbranch')}}</td>
                              </tr>
                              <?php /* ?>
                              <tr>
                                 <td>PAYMENT QR CODE</td>
                                 <td rowspan="3">
                                    <!-- {{$app_upi_code}} -->
                                    <img src="https://www.investopedia.com/thmb/ZG1jKEKttKbiHi0EkM8yJCJp6TU=/1148x1148/filters:no_upscale():max_bytes(150000):strip_icc()/qr-code-bc94057f452f4806af70fd34540f72ad.png" style="width:100px;height:100px;">
                                 </td>
                              </tr>
                              <?php */ ?>
                              <tr>
                                 <td>UPI ID : </td>
                                 <td>{{setting('app_upi_id')}}</td>
                              </tr>
                           </tbody>
                        </table>

                     </td>
                     <td colspan="4" class="font-weight-600 text-right"> Sub Total </td>
                     <td class="text-right">
                      {!! $currency !!} {{number_format($purchase_order->sub_total,2,'.','')}}
                     </td>
                   </tr>
                   
                   @if($purchase_order->tax_total > 0)
                     <tr>
                       <td colspan="4" class="font-weight-600 text-right"> GST </td>
                       <td class="text-right">
                        {!! $currency !!} {{number_format($purchase_order->tax_total,2,'.','')}}
                       </td>
                     </tr>
                   @endif

                   @if($purchase_order->discount_total > 0)
                     <tr>
                       <td colspan="4" class="font-weight-600 text-right"> Discount </td>
                       <td class="text-right">
                        {!! $currency !!} {{number_format($purchase_order->discount_total,2,'.','')}}
                       </td>
                     </tr>
                   @endif

                   @if($purchase_order->additional_charge > 0)
                     <tr>
                       <td colspan="4" class="font-weight-600 text-right"> {{$purchase_order->additional_charge_description}} </td>
                       <td class="text-right">
                        {!! $currency !!} {{number_format($purchase_order->additional_charge,2,'.','')}}
                       </td>
                     </tr>
                   @endif

                   @if($purchase_order->delivery_charge > 0)
                     <tr>
                       <td colspan="4" class="font-weight-600 text-right"> Delivery Charge </td>
                       <td class="text-right">
                        {!! $currency !!} {{number_format($purchase_order->delivery_charge,2,'.','')}}
                       </td>
                     </tr>
                   @endif
                   
                   <tr>
                     <td colspan="4" class="font-weight-600 text-right"> <b>Total</b> </td>
                     <td class="text-right">
                       <b>{!! $currency !!} {{number_format($purchase_order->total,2,'.','')}}</b>
                     </td>
                   </tr>

                   <tr>
                     <td colspan="8" class="font-weight-600 text-right"> 
                        <b>Amount in Words</b> 
                        <br>
                        {!! $words !!}
                     </td>
                   </tr>



                  </tbody>
               </table>

               <br><br><br>

               <table class="table table-bordered view-table-custom-product-item" style="width: 100%; border-collapse: collapse;">
                  <tr>
                     <td width="50%">
                        <p>
                           <b>Notes :</b>
                           {!!$purchase_order->notes!!}
                        </p>
                        <hr>
                        <p>
                           <b>Terms & Conditions :</b>
                           {!!$purchase_order->terms_and_conditions!!}
                        </p>
                     </td>
                     <td width="50%" style="vertical-align:bottom; float:right; text-align: right;">
                          <!-- {{$app_invoice_signature}} --> 
                          <img src="{{$app_invoice_signature}}" style="width:30%;">
                          <br>
                          Authorized signatory for {{setting('app_name')}}
                     </td>
                  </tr>
               </table>

            </div>
         </div>
      </div>
   </div>
</div>

@endsection    

@push('scripts')

<script>
      $('.purchase-status-btn').click(function() {
         var status = $(this).data('status');
         var url   = "{!!url('purchaseOrder/statusUpdate')!!}";
         var token = "{{ csrf_token() }}";
         if(status=='accepted' || status=='declined') {
            $.ajax({
                 type: 'POST',
                 data: {
                     '_token': token,
                     'status': status,
                     'id': "{{$purchase_order->id}}",
                     'notes': "Purchase Order {{$purchase_order->code}} to {{$purchase_order->market->name}} for {{number_format($purchase_order->total,setting('app_price_format'),'.','')}}."
                 },
                 url: url,
                 success: function (response) {      
                    iziToast.success({
                        backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                        messageColor: '#fff',
                        timeout: 4000, 
                        icon: 'fa fa-check', 
                        position: "topCenter", 
                        iconColor:'#fff',
                        message: 'Purchase Order '+status+''
                    });
                    setTimeout(function () { location.reload(); }, 1000);
                 }
            });
         } /*else if(status=='declined') {
            $('#quoteDeclineModal').modal('show');
         }*/
      }); 

      @if($purchase_order->status=='sent')

        $(document).ready(function() {
             var url   = "{!!url('purchaseOrder/statusUpdate')!!}";
             var token = "{{ csrf_token() }}";
             $.ajax({
                 type: 'POST',
                 data: {
                     '_token': token,
                     'status': 'viewed',
                     'id': "{{$purchase_order->id}}",
                     'notes': "This Purchase Order has been viewed online"
                 },
                 url: url,
                 success: function (response) {      
                    console.log(response);
                 }
             });
        });

      @endif

</script>

@endpush