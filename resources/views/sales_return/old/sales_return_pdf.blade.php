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
                  <td style="border: 0px solid #000; padding: 3px;"><b>{{ trans('lang.sales_return') }}</b></td>
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
                        <span class="pdf_head"  style="font-size:30px;line-height: 25px;" > {{ setting('app_name') }}</span><br>
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
            <table style="width: 100%; line-height:15px;" class="pdf_div1" cellpadding="15">
               <tr>
                  <td width="33%">
                     <b>{{ trans('lang.sales_return') }} : </b> {{$sales_return->sales_code}}
                  </td>
                  <td width="33%">
                     <b>{{ trans('lang.sales_return') }} : </b> {{date('d-m-Y',strtotime($sales_return->sales_date))}}
                  </td>
                  <td width="33%">
                     <b>{{ trans('lang.po_expiry_date') }} : </b> {{date('d-m-Y',strtotime($sales_return->sales_valid_date))}}
                  </td>
               </tr>
            </table>
            <div class="pdf_whole" style="padding:10px;background-color:#f4f4f533;">
               
               <table style="width: 100%; line-height:15px;">
                  <tr>
                     <td width="33%">  
                        <b>{{ trans('lang.sales_bill_to') }}</b><br>
                        <b>{{$sales_party->name}}</b><br>
                        <span>{{$sales_party->address_line_1}}, {{$sales_party->address_line_2}}</span><br>
                        <span>{{$sales_party->city}} - {{$sales_party->pincode}}, {{$sales_party->state}}</span><br>
                        @if($sales_party->mobile!='')
                            <span>{{trans('lang.market_mobile')}} :  {{$sales_party->mobile}}</span><br> 
                        @endif      
                        @if($sales_party->gstin!='')               
                            <span>{{trans('lang.gstin')}} :  {{$sales_party->gstin}}</span>  
                        @endif
                     </td>
                     <td width="33%"></td>
                     <td width="33%">
                        <b>{{ trans('lang.sales_ship_to') }}</b><br>
                        <b>{{$sales_party->name}}</b><br>
                        <span>{{$sales_party->address}}<span><br>
                        @if($sales_party->mobile!='')
                            <span>{{trans('lang.market_mobile')}} :  {{$sales_party->mobile}}</span><br> 
                        @endif     
                        @if($sales_party->gstin!='')
                            <span>{{trans('lang.gstin')}} :  {{$sales_party->gstin}}</span>  
                        @endif
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
                     <td width="8%"  align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_mrp') }}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_price') }} {!!$money_symbol!!}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_discount') }}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_tax') }}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_amount') }} {!!$money_symbol!!}</b></td>
                  </tr>
               </table>
               <table cellpadding="5" style="width: 100%;border-spacing: 0px;">
                  <?php $sty_con = 'style="border-top:2px solid #e9ecef;"'; ?>
                  <?php
                     $i=1;
                     foreach($sales_detail as $sales_item) {
                       if($i!=1){
                          $styles = 'style="border-top:1px solid #e9ecef;border-bottom:1px solid #e9ecef;"';
                       } else {
                          $styles = '';
                       } 
                       $tl_quantity[] = $sales_item->sales_detail_quantity;
                  ?>
                  <tr>
                     <td width="18%" align="left" {!!$sty_con!!} >{{ $sales_item->sales_detail_product_name }}<br>{{ $sales_item->bar_code }}</td>
                     <td width="12%" align="center" {!!$sty_con!!} >{{$sales_item->sales_detail_product_hsn_code}}</td>
                     <td width="12%" align="center" {!!$sty_con!!} >{{$sales_item->sales_detail_quantity}} {{$sales_item->sales_detail_unit}}</td>
                     <td width="8%" align="center" {!!$sty_con!!} >{{number_format($sales_item->sales_detail_mrp,2)}}</td>
                     <td width="12%" align="center" {!!$sty_con!!} >{{number_format($sales_item->sales_detail_price,2)}}</td>
                     <td width="12%" align="center" {!!$sty_con!!} >
                          {{$sales_item->sales_detail_discount_amount}} ({{$sales_item->sales_detail_discount_percent}} %)
                     </td>
                     <td width="12%" align="center" {!!$sty_con!!} >
                          {{$sales_item->sales_detail_igst}} ({{$sales_item->sales_detail_tax_percent}}%)
                     </td>
                     <td width="12%" align="center" {!!$sty_con!!} >{{$sales_item->sales_detail_amount}}</td>
                  </tr>
                  <?php 
                     $i++;
                     } ?>
               </table>
               <br> <br>
               <table class="pdf_list_original"  cellpadding="5">
                  <tr>
                     <td width="18%" align="left" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_sub_total') }}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{array_sum($tl_quantity)}}</b></td>
                     <td width="8%"  align="center" {!!$sty_pdf!!} ></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} > 
                          {!!$money_symbol!!}
                          <b>{{number_format($sales_return->sales_discount_amount,2)}}</b>
                     </td>
                     <td width="12%" align="center" {!!$sty_pdf!!} >
                          {!!$money_symbol!!}
                          <b>{{number_format($sales_return->sales_igst_amount,2)}}</b>
                     </td>
                     <td width="12%" align="center" {!!$sty_pdf!!} >
                          {!!$money_symbol!!}
                          <b>{{number_format($sales_return->sales_total_amount - $sales_return->sales_additional_charge,2)}}</b>
                     </td>
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
                       <b>{!!$money_symbol!!} {{number_format($sales_return->sales_taxable_amount,2)}}</b>
                     </td>
                  </tr>

                  <?php if($sales_return->sales_igst_amount!=0){ ?>
                  <tr>
                     <td style="width: 35%;border:none;" align="right">{{ trans('lang.po_sgst_amount')}}</td>
                     <td style="width: 15%;border:none;" align="right">
                          <b>{!!$money_symbol!!} {{number_format($sales_return->sales_sgst_amount,2)}}</b>
                     </td>
                  </tr>
                  <tr>
                     <td style="width: 35%;border:none;" align="right">{{ trans('lang.po_cgst_amount')}}</td>
                     <td style="width: 15%;border:none;" align="right">
                          <b>{!!$money_symbol!!} {{number_format($sales_return->sales_cgst_amount,2)}}</b>
                     </td>
                  </tr>
                  <?php } ?>  

                  @if($sales_return->sales_additional_charge>0)   
                  <tr>
                     <td style="width: 35%;" align="right"> Additional <br> <small>{{$sales_return->sales_additional_charge_description}}</small></td>
                     <td style="width: 15%;" align="right">
                          <b>{!!$money_symbol!!} {{number_format($sales_return->sales_additional_charge,2)}}</b>
                     </td>
                  </tr>
                  @endif
                  <tr >
                     <td style="width: 35%;" align="right">{{trans('lang.po_round_off')}}</td>
                     <td style="width: 15%;" align="right">
                          <b>{!!$money_symbol!!} {{number_format($sales_return->sales_round_off,2)}}</b>
                     </td>
                  </tr>
                  <tr>
                     <td style="width: 35%;border-top:2px solid #e9ecef;border-bottom:2px solid #e9ecef;" align="right">
                        {{ trans('lang.po_table_grand_total')}}
                     </td>
                     <td style="width: 15%;border-top:2px solid #e9ecef;border-bottom:2px solid #e9ecef;" align="right">
                        <b>{!!$money_symbol!!} {{number_format(round($sales_return->sales_total_amount),2)}}</b>
                     </td>
                  </tr>

                  <tr>     
                     <td style="width: 35%;border-bottom:2px solid #e9ecef;" align="right">{{ trans('lang.received_amount') }}</td>
                     <td style="width: 15%;border-bottom:2px solid #e9ecef;" align="right">
                        <b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$sales_return->sales_total_amount}}</b>
                     </td>
                  </tr>

                  <tr>     
                     <td style="width: 35%;border-bottom:2px solid #e9ecef;" align="right">{{ trans('lang.balance_amount') }}</td>
                     <td style="width: 15%;border-bottom:2px solid #e9ecef;" align="right">
                        <b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$sales_return->sales_balance_amount}}</b>
                     </td>
                  </tr>

                  <?php
                     //net pay per annum in words
                     $number = round($sales_return->sales_total_amount);
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
               <!-- <table class="bank_info" style="width: 100%;line-height:12px;">
                  <?php $balance_amount = 0;//$balance_amount = $grand_total - {{$sales_return->sales_total_amount}}; ?>
                  
                  <tr>
                    <td style="width: 20%;border:none;"></td>        
                    <td style="width: 35%;border:none;"></td>
                    <td style="width: 25%;border-top:2px solid #e9ecef;" align="right">{{ trans('lang.balance_amount') }}</td>
                    <td style="width: 15%;border-top:2px solid #e9ecef;" align="right"><b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> <?= number_format($balance_amount)?></b></td>
                  </tr>  
                  <tr>
                    <td style="width: 20%;border:none;"></td>        
                    <td style="width: 35%;border:none;"></td>
                    <td style="width: 25%;border-top:2px solid #e9ecef;" align="right"></td>
                    <td style="width: 15%;border-top:2px solid #e9ecef;" align="right"></td>
                  </tr> 
                  </table> -->
               <table style="width: 100%;">
                  <tr style="line-height:15px;">
                     <td width="50%" style="border:none; ">
                        <span><b>{{ trans('lang.po_table_notes') }}:</b></span>
                        {!!$sales_return->sales_notes!!}
                        <span><b>{{ trans('lang.po_terms_and_conditions') }}:</b></span>
                        {!!$sales_return->sales_terms_and_conditions!!}
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