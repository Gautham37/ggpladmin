<style>
 .sales_app_name
{
font-weight:600;
}
.app_addr
{
color:rgb(0 0 0 / 40%);
}
.sales_image
{
width: 120px;
height: 120px;
}
.sales_table td
{
border:none;
vertical-align:top;
}
.sales_head
{
font-size:15px;
line-height: 25px;
}
.sales_list_table, .sales_list_table td
{
border:1px solid rgba(0, 0, 0, 0.1);
font-size:14px;
}
.invoice_button
{
width: 215px;
background-color: #fff;
text-align: left;
font-size: 16px;
}
.invoice_color_picker
{
border: none;
color: white;
padding: 25px;
text-align: center;
text-decoration: none;
display: inline-block;
font-size: 16px;
margin: 2px 26px;
cursor: pointer;
border-radius: 50%;
}
.invoice_button1 {background-color: #212529a1;}
.invoice_button2 {background-color: #28a745;}
.invoice_button3 {background-color: #007bffb5;}
.invoice_button4 {background-color: #c235dc;}
.invoice_button5 {background-color: #dc3545;}
.invoice_button6 {background-color: #4c6682;}
.invoice_button7 {background-color: #ffc107ab;}
.invoice_button8 {background-color: #ca5816;}
.invoice_page
{
background-color: #fff;
height: auto;
border: 1px solid #212529;
}
.invoice_page_original
{
background-color: #fff;
height: auto;
/*border: 1px solid #212529;*/
}
.bill_ship
{
background-color:#007bff91;
font-weight:600;
font-size:14px;
}
.invoice_button .active
{
color:#ffffff;
background-color:#28a745;
border-color:#28a745;
}
.invoice_color_picker .active
{
border:3px solid #007bff;
}
/*pdf_duplicate*/
.pdf_head
{
font-size:36px;
color:<?= (setting('app_invoice_color')!='') ? setting('app_invoice_color')  : 'green'  ; ?>;
font-weight:700;
line-height: 18px;
}
.pdf_div
{
height:10px;
background-color: green;
}
.pdf_div1
{
height:50px;
background-color: #e9ecef;
padding:5px;
}
.pdf_list
{
width: 100%;
border-top:1px solid green;
border-bottom:1px solid green;
}
.pdf_list_original
{
width: 100%;
border-spacing: 0px;
}
.pdf_all {
/*font-family: 'Poppins' !important; */
font-family: Arial, Helvetica, sans-serif;
font-size:12px;
}
.theme_head
{
font-size:20px;
color:green;
font-weight:700;
line-height: 32px;
}
.bank_info td
{
padding: 0.35rem !important;  
}
.pdf_simple_div
{
height:30px;
background-color: green;
}
.theme_invoice_head
{
font-size:20px;
font-weight:700;
line-height: 32px;
}

.top_border_simple
{
  border-top:1px solid #212529!important;
}

.right_border_simple
{
  border-right:1px solid #212529!important;
}

.border_none
{
  border:none!important;
} 
</style>

<!-- <link rel="stylesheet" href="{{asset('css/style.css')}}"> -->
<div class="pdf_all" style="font-size:12px;margin: -25px;" cellpadding="5">
   <div class="stylish_original">
      <div class="invoice_page_original" style="font-size:12px;background-color:#f4f4f533;">
         <div class="invoice_page1">
            <table style="width: auto; background-color:#f4f4f533;">
               <tr>
                  <td style="border: 0px solid #000; padding: 3px;"><b>{{ trans('lang.order_plural') }}</b></td>
                  <td style="border: 1px solid #000; padding: 3px;">
                    @if($type=='1')
                      <b>{{ trans('lang.sales_original') }}</b>
                    @elseif($type=='2')
                      <b>{{ trans('lang.sales_duplicate') }}</b>
                    @elseif($type=='3')
                      <b>{{ trans('lang.sales_triplicate') }}</b>
                    @endif
                  </td>
               </tr>
            </table>
            <table class="table"  cellpadding="5" style="width: 100%;background-color:#f4f4f533;">
               <tbody>
                  <tr>
                     <td style="width: 4%;">
                        <img src="{{$app_logo}}" alt="{{setting('app_name')}}" style="width:150px;height:150px;" >
                     </td>
                     <td style="width: 96%;">
                        <span class="pdf_head"  style="font-size:30px;line-height: 25px;"> {{ setting('app_name') }}</span><br>
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
            <?php
               if(setting('app_invoice_color')!='')
               {
                $col_val = setting('app_invoice_color');
                $choose_col = 'style="background-color:'.$col_val.';"';
               }else{
                  $choose_col = "style='';";
               }
               ?>
            <div class="pdf_div" <?=$choose_col?>></div>
            <table style="width: 100%; line-height:3px;" class="pdf_div1" cellpadding="15">
               <tr>
                  <td width="33%">
                     <b>{{ trans('lang.order_id') }} : </b> {{$order->id}}
                  </td>
                  <td width="33%">
                     <b>{{ trans('lang.order_date') }} : </b> {{date('d-m-Y',strtotime($order->created_at))}}
                  </td>
                  <td width="33%">
                     <b>{{ trans('lang.driver') }} : </b> @if($drivers!='') {{$drivers->name}} @else {{trans('lang.order_driver_not_assigned')}}
                     @endif
                  </td>
               </tr>
               <tr>
                  <td width="33%">
                     <b>{{ trans('lang.payment_method') }} : </b> {{$order->method}}
                  </td>
                  <td width="33%">
                     <b>{{ trans('lang.order_status_status') }} : </b> {{$order->order_status}}
                  </td>
                  <td width="33%">
                     <b>{{trans('lang.payment_status')}} : </b> {{$order->payment_status}}
                  </td>
               </tr>
               <tr>
                  <td width="33%">
                     <b>{{ trans('lang.order_active') }} : </b> @if($order->active) {{trans('lang.yes')}} @else {{trans('lang.order_canceled')}}
                     @endif
                  </td>
                   @if($order->hint!='')
                  <td width="33%">
                     <b>{{trans('lang.order_hint')}} : </b> {!! $order->hint !!}
                  </td>     
                  @endif               
               </tr>
            </table>
            <div class="pdf_whole" style="padding:10px;background-color:#f4f4f533;">
               
               <table style="width: 100%; line-height:15px;">
                  <tr>
                     <td width="33%">  
                        <b>{{ trans('lang.sales_bill_to') }}</b><br>
                        
                        <b>{!! $order_user->name !!}</b><br>

                        @if($order_party!='') 
                        <span>{{$order_party->address_line_1}} {{$order_party->address_line_2}} {{$order_party->city}}, {{$order_party->state}} - {{$order_party->pincode}}</span><br>
                        @endif

                        @if($order_party!='' && $order_party->mobile!='')
                            <span>{{trans('lang.market_mobile')}} :  {{$order_party->mobile}}</span><br> 
                        @endif      
                        <!--@if($order_party!='' && $order_party->gstin!='')         
                            <span>{{trans('lang.gstin')}} :  {{$order_party->gstin}}</span>  
                        @endif-->
                     </td>
                     <td width="33%"></td>
                     <td width="33%">
                        <b>{{ trans('lang.sales_ship_to') }}</b><br>

                         <b>{!! $order_user->name !!}</b><br>

                        @if($order_delivery_address!='') 
                        <span>{{$order_delivery_address->address_line_1}} {{$order_delivery_address->address_line_2}} {{$order_delivery_address->city}}, {{$order_delivery_address->state}} - {{$order_delivery_address->pincode}}</span><br>
                        @endif

                        @if($order_party!='' && $order_party->mobile!='')
                            <span>{{trans('lang.market_mobile')}} :  {{$order_party->mobile}}</span><br> 
                        @endif      
                        <!--@if($order_party!='' && $order_party->gstin!='')         
                            <span>{{trans('lang.gstin')}} :  {{$order_party->gstin}}</span>  
                        @endif-->
                     </td>
                  </tr>
               </table>

               <br>
               <table class="pdf_list_original"  cellpadding="5">
                  <?php
                  if(setting('app_invoice_color')!='')
                  {
                      $col_val = setting('app_invoice_color');
                  } else {
                      $col_val = '#000';
                  }
                  $sty_pdf = 'style="border-top:3px solid '.$col_val.';border-bottom:3px solid '.$col_val.'"';
                  $money_symbol = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
                  ?>
                  <tr>
                     <td width="18%" align="left" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_items') }}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_hsn') }}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_qty') }}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_price') }} {!!$money_symbol!!}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_tax') }}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_amount') }} {!!$money_symbol!!}</b></td>
                  </tr>
               </table>
               <table cellpadding="5" style="width: 100%;border-spacing: 0px;">
                  <?php $sty_con = 'style="border-top:2px solid #e9ecef;"'; ?>
                    
                    @php
                     $total_price = 0;
                     $total_tax = 0;
                     $total_subtotal = 0;
                     $total_quantity = 0;
                     @endphp
                     
                    @foreach($product_orders as $order_item)
                    
                    @php
                    $subtotal = $order_item->price * $order_item->quantity;
                    $taxAmount = $order_item->tax_amount;
                    $total = $order->order_amount;
                    
                    $total_price += $order_item->price;
                    $total_tax += $taxAmount;
                    $total_subtotal += $subtotal;
                    $total_quantity += $order_item->quantity;
                    @endphp
                  <tr>
                     <td width="18%" align="left" {!!$sty_con!!} >{{$order_item->name}}</td>
                     <td width="12%" align="center" {!!$sty_con!!} >{{$order_item->hsn_code}}</td>
                     <td width="12%" align="center" {!!$sty_con!!} >{{$order_item->quantity}} {{$order_item->unit}}</td>
                     <td width="12%" align="center" {!!$sty_con!!} >{{$order_item->price}}</td>
                     <td width="12%" align="center" {!!$sty_con!!} >{{$order_item->tax_amount}} ({!!$order_item->tax_percent!!}%)</td>
                     <td width="12%" align="center" {!!$sty_con!!} >{{$subtotal}}</td>
                  </tr>

                 @endforeach 

               </table>
               <br> <br>
               <table class="pdf_list_original"  cellpadding="5">
                  <tr>
                     <td width="18%" align="left" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_sub_total') }}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{$total_quantity}}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} >{!!$money_symbol!!}<b>{{$total_price}}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} >{!!$money_symbol!!}<b>{{$total_tax}}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} >{!!$money_symbol!!}<b>{{$total_subtotal}}</b></td>
                  </tr>
                </table>

                <table style="width: 100%; line-height: 10px;">

                  <tr>
                     <td style="width: 50%; border:none;" rowspan="9">   

                      <table class="bank_info" style="width: 100%;">
                        <tr>
                           <td style="width: 50%;border:none;"><b>{{ trans('lang.app_bank_detail') }}</b></td>
                           <td style="width: 50%;border:none;"></td>
                        </tr>
                        <tr>
                           <td style="width: 50%;border:none;">{{ trans('lang.app_bank_account_number') }}:</td>
                           <td style="width: 50%;border:none;">{{setting('app_bank_account_number')}}</td>
                        </tr>
                        <tr>
                           <td style="width: 50%;border:none;">{{ trans('lang.app_bank_ifsc_code') }}:</td>
                           <td style="width: 50%;border:none;">{{setting('app_bank_ifsc_code')}}</td>
                        </tr>
                        <tr>
                           <td style="width: 50%;border:none;">{{ trans('lang.app_bank_bankname') }}:</td>
                           <td style="width: 50%;border:none;">{{setting('app_bank_bankname')}}</td>
                        </tr>
                        <tr>
                           <td style="width: 50%;border:none;">{{ trans('lang.app_bank_bankbranch') }}:</td>
                           <td style="width: 50%;border:none;">{{setting('app_bank_bankbranch')}}</td>
                        </tr>
                        <tr>
                           <td style="width: 50%;border:none;">{{ trans('lang.payment_qr_code') }}</td>
                           <td style="width: 50%;border:none;" rowspan="3">
                              <img src="{{$app_upi_code}}" style="width:100px;height:100px;">
                           </td>
                        </tr>
                        <tr>
                           <td style="border:none;">{{ trans('lang.upi_id') }}</td>
                        </tr>
                        <tr>
                           <td style="border:none;">{{setting('app_upi_id')}}</td>
                        </tr>
                      </table>

                     </td>
                     <td style="width: 35%;border:none;" align="right">{{ trans('lang.po_taxable_amount') }}</td>
                     <td style="width: 15%;border:none;" align="right">
                       <b>{!!$money_symbol!!} {{number_format($total_subtotal,2)}}</b>
                     </td>
                  </tr>
                  <tr>
                     <td style="width: 35%;" align="right">{{ trans('lang.order_tax')}} ({!!$order->tax!!}%)</td>
                     <td style="width: 15%;" align="right">
                          <b>{!!$money_symbol!!} {{number_format($total_tax,2)}}</b>
                     </td>
                  </tr>
                  @if($order->delivery_fee>0)
                   <tr>
                     <td style="width: 35%;" align="right">{{ trans('lang.order_delivery_fee')}}</td>
                     <td style="width: 15%;" align="right">
                          <b>{!!$money_symbol!!} {{number_format($order->delivery_fee,2)}}</b>
                     </td>
                  </tr>
                  @endif
                  @if($order->redeem_amount>0)
                   <tr>
                     <td style="width: 35%;" align="right">{{ trans('lang.redeem_amount')}}</td>
                     <td style="width: 15%;" align="right">
                          <b>{!!$money_symbol!!} {{number_format($order->redeem_amount,2)}}</b>
                     </td>
                  </tr>
                  @endif
                  @if($order->coupon_amount>0)
                   <tr>
                     <td style="width: 35%;" align="right">{{ trans('lang.coupon_discount')}}</td>
                     <td style="width: 15%;" align="right">
                          <b>{!!$money_symbol!!} {{number_format($order->coupon_amount,2)}}</b>
                     </td>
                  </tr>
                  @endif
                  <tr>
                     <td style="width: 35%;border-top:2px solid #e9ecef;border-bottom:2px solid #e9ecef;" align="right">
                        {{ trans('lang.po_table_grand_total')}}
                     </td>
                     <td style="width: 15%;border-top:2px solid #e9ecef;border-bottom:2px solid #e9ecef;" align="right">
                        <b>{!!$money_symbol!!} {{number_format($total,2)}}</b>
                     </td>
                  </tr>
                
                  <?php
                     //net pay per annum in words
                     $number = round($total);
                     $no = floor($number);
                     $point = round($number - $no, 2) * 100;
                     $hundred = null;
                     $digits_1 = strlen($no);
                     $i = 0;
                     $str = array();
                     $words = array('0' => '', '1' => 'One', '2' => 'Two',
                      '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
                      '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
                      '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
                      '13' => 'Thirteen', '14' => 'Fourteen',
                      '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
                      '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
                      '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
                      '60' => 'Sixty', '70' => 'Seventy',
                      '80' => 'Eighty', '90' => 'Ninety');
                     $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
                     while ($i < $digits_1) {
                       $divider = ($i == 2) ? 10 : 100;
                       $number = floor($no % $divider);
                       $no = floor($no / $divider);
                       $i += ($divider == 10) ? 1 : 2;
                       if ($number) {
                          $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                          $hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
                          $str [] = ($number < 21) ? $words[$number] .
                              " " . $digits[$counter] . $plural . " " . $hundred
                              :
                              $words[floor($number / 10) * 10]
                              . " " . $words[$number % 10] . " "
                              . $digits[$counter] . $plural . " " . $hundred;
                       } else $str[] = null;
                     }
                     $str = array_reverse($str);
                     $result = implode('', $str);
                     $points = ($point) ?
                      "And " . $words[$point / 10] . " " . 
                            $words[$point = $point % 10] : '';
                     $net_pay_per_annum_words = $result . "Rupees  ";
                     
                     ?>
               
                  <tr>
                     <td style="width: 100%;padding-top:10px;" align="right" colspan="2">
                        <b>{{trans('lang.po_amount_in_words')}}</b>
                        <br>
                        <p>{{$net_pay_per_annum_words}}</p>
                      </td>
                  </tr>
                  
               </table>
              
               <table style="width: 100%;">
                  <tr style="line-height:15px;">
                     <td width="50%" style="border:none; ">
                     </td>
                     <td width="50%" style="border:none; vertical-align:bottom; text-align: right;">
                          <img src="{{$app_invoice_signature}}" style="width:10%; text-align:center;">
                          <br>
                          {{ trans('lang.po_table_signature') }} {{setting('app_name')}}
                     </td>
                  </tr>
               </table>

            </div>
         </div>
      </div>
   </div>
</div>