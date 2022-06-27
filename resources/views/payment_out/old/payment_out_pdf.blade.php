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

<?php
 $money_symbol = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
 ?>

<!-- <link rel="stylesheet" href="{{asset('css/style.css')}}"> -->
<div class="pdf_all" style="font-size:12px;margin: -25px;" cellpadding="5">
   <div class="stylish_original">
      <div class="invoice_page_original" style="font-size:12px;background-color:#f4f4f533;">
         <div class="invoice_page1">
            <table style="width: auto; background-color:#f4f4f533;">
               <tr>
                  <td style="border: 0px solid #000; padding: 3px;"><b>{{ trans('lang.payment_out_plural') }}</b></td>
                  <td style="border: 1px solid #000; padding: 3px;">
                    @if($type=='1')
                      <b>{{ trans('lang.paymentout_original') }}</b>
                    @elseif($type=='2')
                      <b>{{ trans('lang.paymentout_duplicate') }}</b>
                    @elseif($type=='3')
                      <b>{{ trans('lang.paymentout_triplicate') }}</b>
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
            <table style="width: 100%; line-height:15px;" class="pdf_div1" cellpadding="15">
               <tr>
                  <td width="33%">
                     <b>{{ trans('lang.paymentout_party_name') }} : </b> {{$paymentout_party->name}}
                  </td>
                  <td width="33%">
                     <b>{{ trans('lang.paymentout_payment_date') }} : </b> {{date('d-m-Y',strtotime($payment_out->payment_out_date))}}
                  </td>
                  <td width="33%">
                     <b>{{ trans('lang.paymentout_payment_amount') }} : </b> {!!$money_symbol!!} {{$payment_out->payment_out_amount}}
                  </td>
               </tr>
           
                  <tr>
                     <td width="33%">  
                        <b>{{ trans('lang.paymentout_payment_type') }}</b><br><br>
                        {{$paymentout_mode->name}}
                     </td>
                     <td width="33%">
                        <b>{{trans('lang.paymentout_payment_notes')}}</b><br>
                       {!! $payment_out->payment_out_notes !!}
                     </td>
                      <td width="33%">  
                      <b>{{ trans('lang.payment_out_no') }}</b><br><br>
                        {{$payment_out->payment_out_no}}
                     </td>
                  </tr>
               </table>

               <br>
                <h4>{{trans('lang.Invoices settled')}}</h6><br>
               <table class="pdf_list_original"  cellpadding="5" style="width: 100%;">
                  <?php
                  if(setting('app_invoice_color')!='')
                  {
                      $col_val = setting('app_invoice_color');
                  } else {
                      $col_val = '#000';
                  }
                  $sty_pdf = 'style="border-top:3px solid '.$col_val.';border-bottom:3px solid '.$col_val.'"';

                  ?>
                  <tr>
                     <td width="25%" {!!$sty_pdf!!} ><b>{{ trans('lang.payment_out_invoice_date') }}</b></td>
                     <td  width="25%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.payment_out_invoice_number') }}</b></td>
                     <td  width="25%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.payment_out_invoice_amount') }}</b></td>
                     <td  width="25%"  align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.payment_out_invoice_amount_paid') }}</b></td>
                  </tr>
               </table>
               <table cellpadding="5" style="width: 100%;border-spacing: 0px;">
                  <?php $sty_con = 'style="border-top:2px solid #e9ecef;"'; ?>
                  <?php
                     $i=1;
                      foreach($paymentout_detail as $paymentout_item)
                      {
                       if($i!=1){
                          $styles = 'style="border-top:1px solid #e9ecef;border-bottom:1px solid #e9ecef;"';
                       } else {
                          $styles = '';
                       } 
                  ?>
                  <tr>
                     <td  width="25%" {!!$sty_con!!} >{{$paymentout_item->transaction_track_date}}</td>
                     <td  width="25%" align="center" {!!$sty_con!!} >{{$paymentout_item->transaction_number}}</td>
                     <td  width="25%" align="center" {!!$sty_con!!} >{!!$money_symbol!!} {{number_format($purchase_invoice->purchase_total_amount,2)}}</td>
                     <td  width="25%" align="center" {!!$sty_con!!} >{!!$money_symbol!!} {{number_format($paymentout_item->transaction_track_amount,2)}}</td>
                   
                  </tr>
                  <?php 
                     $i++;
                     } ?>
               </table>
               <br> <br>
               
                     

         </div>
      </div>
   </div>
</div>