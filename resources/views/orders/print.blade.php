<<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>{{$order->order_code.'-'.$order->user->market->name}}</title>
</head>
<body>

<style>
/*pdf_duplicate*/
.pdf_head {
   font-size:36px;
   color:<?= (setting('app_invoice_color')!='') ? setting('app_invoice_color')  : 'green'  ; ?>;
   font-weight:700;
   line-height: 18px;
}
.pdf_div {
   height:10px;
   background-color: green;
}
.pdf_div1 {
   background-color: #e9ecef;
   padding:5px;
}

.pdf_all {
   /*font-family: 'Poppins' !important; */
   font-family: Arial, Helvetica, sans-serif;
   font-size:12px;
}
.text-right {
   text-align: right;   
}
.text-left {
   text-align: left;   
}
.text-center {
   text-align: center;   
}

</style>

<div class="pdf_all" style="font-size:12px;margin: -25px;" cellpadding="5">
   <div class="stylish_original">
      <div class="invoice_page_original" style="font-size:12px;background-color:#f4f4f533;">
         <div class="invoice_page1">
            <table style="width: auto; background-color:#f4f4f533;">
               <tr>
                  <td style="border: 0px solid #000; padding: 3px;"><b>Order Invoice</b></td>
                  <td style="border: 1px solid #000; padding: 3px;">
                    @if($type=='1')
                      <b>Original</b>
                    @elseif($type=='2')
                      <b>Duplicate</b>
                    @elseif($type=='3')
                      <b>Triplicate</b>
                    @endif
                  </td>
               </tr>
            </table>
            <table class="table"  cellpadding="5" style="width: 100%;background-color:#f4f4f533;">
               <tbody>
                  <tr>
                     <td style="width: 4%;">
                        <!-- {{$app_logo}} -->
                        <img src="https://www.ggpl.s22alpha.com.au/images/logo_default.png" alt="{{setting('app_name')}}" style="width:150px;height:150px;" >
                     </td>
                     <td style="width: 96%;">
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
                     <b>Order No : </b> 
                     {{$order->order_code}}
                  </td>
                  <td width="33%">
                     <b>Date : </b> 
                     {{$order->created_at->format('d M Y')}}
                  </td>
                  <td width="33%">
                  </td>
               </tr>
            </table>
            <div class="pdf_whole" style="padding:10px;background-color:#f4f4f533;">
               
               <table style="width: 100%; line-height:15px;">
                  <tr>
                     <td width="33.33333%">  
                        <p><b>Bill To :</b></p>
                        <p><b>{{$order->user->market->name}}</b></p>
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
                     <td width="33.33333%"></td>
                     <td width="33.33333%">
                        <p><b>Ship To :</b></p>
                        <p><b>{{$order->user->market->name}}</b></p>
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
                     <th class="text-center">CODE</th>
                     <th class="text-right">PRICE / ITEM</th>
                     <th class="text-right">QTY</th>
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
                         <td class="text-right">{{number_format($item->unit_price,2,'.','')}}</td>
                         <td class="text-right">{{number_format($item->quantity,3,'.','')}} {{$item->uom->name}}</td>
                         <td class="text-right">{{number_format($item->discount_amount,2,'.','')}}</td>
                         <td class="text-right">{{number_format($item->tax_amount,2,'.','')}}</td>
                         <td class="text-right">{{number_format($item->amount,2,'.','')}}</td>  
                       </tr>
                     @endforeach
                   @endif

                   <tr>
                     <td colspan="2" rowspan="7" style="vertical-align: top;">
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
                      {!! $currency !!} {{number_format($order->sub_total,2,'.','')}}
                     </td>
                   </tr>
                   
                   @if($order->tax_total > 0)
                     <tr>
                       <td colspan="4" class="font-weight-600 text-right"> GST </td>
                       <td class="text-right">
                        {!! $currency !!} {{number_format($order->tax_total,2,'.','')}}
                       </td>
                     </tr>
                   @endif

                   @if($order->discount_total > 0)
                     <tr>
                       <td colspan="4" class="font-weight-600 text-right"> Discount </td>
                       <td class="text-right">
                        {!! $currency !!} {{number_format($order->discount_total,2,'.','')}}
                       </td>
                     </tr>
                   @endif

                   @if($order->additional_charge > 0)
                     <tr>
                       <td colspan="4" class="font-weight-600 text-right"> {{$order->additional_charge_description}} </td>
                       <td class="text-right">
                        {!! $currency !!} {{number_format($order->additional_charge,2,'.','')}}
                       </td>
                     </tr>
                   @endif

                   @if($order->delivery_fee > 0)
                     <tr>
                       <td colspan="4" class="font-weight-600 text-right"> Delivery Charge </td>
                       <td class="text-right">
                        {!! $currency !!} {{number_format($order->delivery_fee,2,'.','')}}
                       </td>
                     </tr>
                   @endif

                   @if($order->redeempoints)
                     <tr>
                       <td colspan="4" class="font-weight-600 text-right"> Redeem {{$order->redeempoints->points}} Points </td>
                       <td class="text-right">
                        {!! $currency !!} {{number_format($order->redeempoints->amount,2,'.','')}}
                       </td>
                     </tr>
                   @endif
                   
                   <tr>
                     <td colspan="4" class="font-weight-600 text-right"> <b>Total</b> </td>
                     <td class="text-right">
                       <b>{!! $currency !!} {{number_format($order->order_amount,2,'.','')}}</b>
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
                           {!!$order->notes!!}
                        </p>
                        <hr>
                        <p>
                           <b>Terms & Conditions :</b>
                           {!!$order->terms_and_conditions!!}
                        </p>
                     </td>
                     <td width="50%" style="vertical-align:bottom; float:right; text-align: right;">
                          <!-- {{$app_invoice_signature}} --> 
                          <img src="https://www.pngitem.com/pimgs/m/28-285296_mike-pence-signature-hd-png-download.png" style="width:30%;">
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

</body>
</html>

