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
                     <b>{{ trans('lang.supplier_request') }} : </b> {{$supplier_request->sr_code}}
                  </td>
                  <td width="33%">
                     <b>{{ trans('lang.supplier_request') }} : </b> {{date('d-m-Y',strtotime($supplier_request->sr_date))}}
                  </td>
                  <td width="33%">
                     <b>{{ trans('lang.po_expiry_date') }} : </b> {{date('d-m-Y',strtotime($supplier_request->sr_valid_date))}}
                  </td>
               </tr>
            </table>
            <div class="pdf_whole" style="padding:10px;background-color:#f4f4f533;">
               
               <table style="width: 100%; line-height:15px;">
                  <tr>
                     <td width="33%">  
                        <b>{{ trans('lang.supplied_by') }}</b><br>
                        <b>{{$supplier_request_party->name}}</b><br>
                        <span>{{$supplier_request_party->address_line_1}}, {{$supplier_request_party->address_line_2}}</span><br>
                        <span>{{$supplier_request_party->city}} - {{$supplier_request_party->pincode}}, {{$supplier_request_party->state}}.</span><br>
                        @if($supplier_request_party->mobile!='')
                            <span>{{trans('lang.market_mobile')}} :  {{$supplier_request_party->mobile}}</span><br> 
                        @endif      
                        @if($supplier_request_party->gstin!='')               
                            <span>{{trans('lang.gstin')}} :  {{$supplier_request_party->gstin}}</span>  
                        @endif
                     </td>
                     <td width="33%"></td>
                     <td width="33%">
                        <?php /* ?>
                        <b>{{ trans('lang.sales_ship_to') }}</b><br>
                        <b>{{$supplier_request_party->name}}</b><br>
                        <span>{{$supplier_request_party->address}}<span><br>
                        @if($supplier_request_party->mobile!='')
                            <span>{{trans('lang.market_mobile')}} :  {{$supplier_request_party->mobile}}</span><br> 
                        @endif     
                        @if($supplier_request_party->gstin!='')
                            <span>{{trans('lang.gstin')}} :  {{$supplier_request_party->gstin}}</span>  
                        @endif
                        <?php /*/ ?>
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
                     <td width="8%"  align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_mrp') }}</b></td>
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_qty') }}</b></td>
                     <!--<td width="12%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_price') }} {!!$money_symbol!!}</b></td>-->
                     <td width="12%" align="center" {!!$sty_pdf!!} ><b>{{ trans('lang.po_table_amount') }} {!!$money_symbol!!}</b></td>
                  </tr>
               </table>
               <table cellpadding="5" style="width: 100%;border-spacing: 0px;">
                  <?php $sty_con = 'style="border-top:2px solid #e9ecef;"'; ?>
                  <?php
                     $i=1;
                     foreach($supplier_request_detail as $supplier_request_item) {
                       if($i!=1){
                          $styles = 'style="border-top:1px solid #e9ecef;border-bottom:1px solid #e9ecef;"';
                       } else {
                          $styles = '';
                       } 
                       $tl_quantity[] = $supplier_request_item->sr_detail_quantity;
                  ?>
                  <tr>
                     <td width="18%" align="left" {!!$sty_con!!} >{{ $supplier_request_item->sr_detail_product_name }}<br>{{ $supplier_request_item->bar_code }}</td>
                     <td width="12%" align="center" {!!$sty_con!!} >{{$supplier_request_item->sr_detail_product_hsn_code}}</td>
                     <td width="8%" align="center" {!!$sty_con!!} >{{number_format($supplier_request_item->sr_detail_mrp,2)}}</td>
                     <td width="12%" align="center" {!!$sty_con!!} >{{$supplier_request_item->sr_detail_quantity}} {{$supplier_request_item->sr_detail_unit}}</td>
                     <!--<td width="12%" align="center" {!!$sty_con!!} >{{number_format($supplier_request_item->sr_detail_price,2)}}</td>-->
                     <td width="12%" align="center" {!!$sty_con!!} >{{number_format($supplier_request_item->sr_amount,2)}}</td>
                  </tr>
                  <?php 
                     $i++;
                     } ?>
              </table>
              <br><br>
              <!--<table style="width: 100%; line-height: 10px;">

                  <tr>
                     <td style="width: 50%; border:none;" rowspan="9">   
                     </td>
                     <td style="width: 35%;border:none;" align="right">{{ trans('lang.po_taxable_amount') }}</td>
                     <td style="width: 15%;border:none;" align="right">
                       <b>{!!$money_symbol!!} {{number_format($supplier_request->sr_taxable_amount,2)}}</b>
                     </td>
                  </tr>
                  
               </table>-->

               <table style="width: 100%;">
                  <tr style="line-height:15px;">
                     <td width="50%" style="border:none; ">
                        <span><b>{{ trans('lang.po_table_notes') }}:</b></span>
                        {!!$supplier_request->sr_notes!!}
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