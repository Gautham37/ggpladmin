<<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>{{$payment_in->code.'-'.$payment_in->market->name}}</title>
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
                  <td style="border: 0px solid #000; padding: 3px;"><b>Payment In</b></td>
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
                        <img src="{{$app_logo}}" alt="{{setting('app_name')}}" style="width:150px;height:150px;" >
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
                     <b>Payment In No : </b> 
                     {{$payment_in->code}}
                  </td>
                  <td width="33%">
                     <b>Date : </b> 
                     {{$payment_in->date->format('d M Y')}}
                  </td>
                  <td width="33%">
                     <b>Payment Method : </b> 
                     {{$payment_in->paymentmethod->name}}
                  </td>
               </tr>
            </table>
            <div class="pdf_whole" style="padding:10px;background-color:#f4f4f533;">
               
               <table style="width: 100%; line-height:15px;">
                  <tr>
                     <td width="33.33333%">  
                        <p><b>Bill To :</b></p>
                        <p><b>{{$payment_in->market->name}}</b></p>
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
                     <td width="33.33333%"></td>
                     <td width="33.33333%">
                        <p><b>Ship To :</b></p>
                        <p><b>{{$payment_in->market->name}}</b></p>
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

               @if(count($payment_in->paymentinsettle) > 0)
               <table class="table table-bordered view-table-custom-product-item" style="width: 100%; border-collapse: collapse;">
                  <tbody>      
                     
                      <tr>
                        <th class="text-left">INVOICE NO</th>
                        <th class="text-center">DATE</th>
                        <th class="text-right">SETTLED AMOUNT</th>
                      </tr>

                      @foreach($payment_in->paymentinsettle as $invoice)
                        <tr>
                          <td width="60%">
                            @if($invoice->settle_type=='sales')
                              {{$invoice->salesinvoice->code}}
                            @elseif($invoice->settle_type=='purchase')
                              {{$invoice->purchasereturn->code}}
                            @endif  
                          </td>
                          <td class="text-center" width="20%">{{$invoice->created_at->format('d M Y')}}</td>
                          <td class="text-right" width="20%">{!!$currency!!} {{number_format($invoice->amount,2,'.','')}}</td>
                        </tr>
                      @endforeach

                  </tbody>
               </table>
               @endif 

               <br><br><br>

               <table class="table table-bordered view-table-custom-product-item" style="width: 100%; border-collapse: collapse;">
                  <tr>
                     <td width="50%">
                        <hr>
                        <p>
                           <b>Notes :</b>
                           {!!$payment_in->notes!!}
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

</body>
</html>

