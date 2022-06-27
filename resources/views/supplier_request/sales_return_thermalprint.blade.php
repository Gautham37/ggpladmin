<style>
   /*invoice 2inch*/
   .thermal_list_2inch_original td
   {
   border-top: 1px solid #212529;
   width: 64px;
   }
   .thermal_list_2inch_original
   {
   border-spacing:0px;
   width: 192px;
   font-size:12px;
   line-height: 18px;
   }
   /*invoice 3inch*/
   .thermal_list_3inch_original td
   {
   border-top: 1px solid #212529;
   width: 72px;
   }
   .thermal_list_3inch_original
   {
   border-spacing:0px;
   width: 288px;
   font-size:12px;
   line-height: 18px;
   }
</style>
<!--2inch-->
@php if(setting('app_thermal_print')==1){ $sty_thermal_show='style="display:block;width: 192px;margin:0px auto !important;"';} else{ 
$sty_thermal_show='style="display:none;"'; } @endphp
<div class="thermal_inch_original" <?=$sty_thermal_show?>>
   <div class="row">
      <div class="col-md-4">
      </div>
      <div class="col-md-4">
         <div class="thermal_page_original">
            <table  cellpadding="5" style="font-size:14px;line-height: 18px;">
               <tbody>
                  <tr>
                     <td>
                        <b>{{ setting('app_name') }}</b><br>
                        {{setting('app_store_address_line_1')}}<br>
                        {{setting('app_store_address_line_2')}} <br>
                        {{setting('app_store_city')}}<br>
                        {{setting('app_store_state')}}, {{setting('app_store_pincode')}}<br>
                        {{trans('lang.app_thermal_mobile')}}: {{setting('app_store_phone_no')}}<br>
                        {{ trans('lang.sales_return') }}: {{date('d-m-Y',strtotime($sales_return->sales_date))}}<br>
                        {{ trans('lang.sales_return') }}: {{$sales_return->sales_code}}<br>
                        <b>{{trans('lang.app_thermal_bill_to') }}:</b> {{trans('lang.app_thermal_cash_sale') }}
                     </td>
                  </tr>
               </tbody>
            </table>
             <table class="thermal_list_2inch_original"  cellpadding="5">
               <tr>
                  <td  align="left"><b>{{ trans('lang.po_table_items') }}<br>{{ trans('lang.po_table_mrp') }}<br>{{ trans('lang.po_table_tax') }}</b>
                  </td>
                  <td  align="left"><b>{{ trans('lang.po_table_hsn') }}<br>{{ trans('lang.po_table_price') }}<br>{{ trans('lang.po_table_amount') }}</b>
                  </td>
                  <td align="left"><b><br>{{ trans('lang.po_table_qty') }}<br>{{ trans('lang.po_table_discount') }}</b>
                  </td>
               </tr>
               @foreach($sales_detail as $sales_item)
              
               <tr>
                  <td  align="left">{{$sales_item->sales_detail_product_name}}<br>{{number_format($sales_item->sales_detail_mrp,2)}}<br>{{number_format($sales_item->sales_detail_igst,2)}}
                  </td>
                  <td  align="left">{{$sales_item->sales_detail_product_hsn_code}}<br>{{number_format($sales_item->sales_detail_price,2)}}<br>{{number_format($sales_item->sales_detail_amount,2)}}
                  </td>
                  <td  align="left"><br>{{$sales_item->sales_detail_quantity}}<br>{{number_format($sales_item->sales_detail_discount_amount,2)}}
                  </td>
               </tr>
               @endforeach
               <tr>
                  <td align="left" colspan="2"><b>{{ trans('lang.taxable_amount') }}</b></td>
                  <td align="right" ><b>{{number_format($sales_return->sales_taxable_amount,2)}}</b></td>
               </tr>
                 <?php if($sales_return->sales_igst_amount!=0){ ?>
               <tr>
                  <td align="left"  colspan="2">{{ trans('lang.po_sgst_amount') }}</td>
                  <td align="right" >{{number_format($sales_return->sales_sgst_amount,2)}}</td>
               </tr>
                <tr>
                  <td align="left"  colspan="2">{{ trans('lang.po_cgst_amount') }}</td>
                  <td align="right" >{{number_format($sales_return->sales_cgst_amount,2)}}</td>
               </tr>
                <?php } ?> 
                 @if($sales_return->sales_additional_charge>0)    
               <tr>
                  <td align="left" colspan="2" class="border_none">{{$sales_return->sales_additional_charge_description}}</td>
                  <td align="right" class="border_none">{{number_format($sales_return->sales_additional_charge,2)}}</td>
               </tr>
                @endif
               <tr>
                  <td align="left"  colspan="2" class="border_none">{{ trans('lang.po_round_off') }}</td>
                  <td align="right" class="border_none">{{number_format($sales_return->sales_round_off,2)}}</td>
               </tr>
               <tr>
                  <td align="left"  colspan="2" class="border_none"><b>{{ trans('lang.po_table_grand_total') }}</b></td>
                  <td align="right" class="border_none"><b>{{number_format($sales_return->sales_total_amount,2)}}</b></td>
               </tr>
            </table>
            <br>
            <p></p>
         </div>
      </div>
      <div class="col-md-4">
      </div>
   </div>
</div>
<!--2inch end-->

<!--3inch-->
@php if(setting('app_thermal_print')==2){ $sty_thermal_show1='style="display:block;width: 192px;margin:0px auto !important;"';} else{ 
$sty_thermal_show1='style="display:none;"'; } @endphp
<div class="thermal_inch_original1" <?=$sty_thermal_show1?>>
   <div class="row">
      <div class="col-md-4">
      </div>
      <div class="col-md-4">
         <div class="thermal_page_original">
            <table  cellpadding="5" style="font-size:14px;line-height: 18px;">
               <tbody>
                  <tr>
                     <td>
                        <b>{{ setting('app_name') }}</b><br>
                        {{setting('app_store_address_line_1')}}<br>
                        {{setting('app_store_address_line_2')}} <br>
                        {{setting('app_store_city')}}<br>
                        {{setting('app_store_state')}}, {{setting('app_store_pincode')}}<br>
                        {{trans('lang.app_thermal_mobile')}}: {{setting('app_store_phone_no')}}<br>
                        {{ trans('lang.sales_return') }}: {{date('d-m-Y',strtotime($sales_return->sales_date))}}<br>
                        {{ trans('lang.sales_return') }}: {{$sales_return->sales_code}}<br>
                        <b>{{trans('lang.app_thermal_bill_to') }}:</b> {{trans('lang.app_thermal_cash_sale') }}
                     </td>
                  </tr>
               </tbody>
            </table>
             <table class="thermal_list_3inch_original"  cellpadding="5">
               <tr>
                  <td  align="left"><b>{{ trans('lang.po_table_items') }}<br>{{ trans('lang.po_table_mrp') }}<br>{{ trans('lang.po_table_tax') }}</b>
                  </td>
                  <td  align="left"><b>{{ trans('lang.po_table_hsn') }}<br>{{ trans('lang.po_table_price') }}<br>{{ trans('lang.po_table_amount') }}</b>
                  </td>
                  <td align="left"><b><br>{{ trans('lang.po_table_qty') }}<br>{{ trans('lang.po_table_discount') }}</b>
                  </td>
               </tr>
               @foreach($sales_detail as $sales_item)
              
               <tr>
                  <td  align="left">{{$sales_item->sales_detail_product_name}}<br>{{number_format($sales_item->sales_detail_mrp,2)}}<br>{{number_format($sales_item->sales_detail_igst,2)}}
                  </td>
                  <td  align="left">{{$sales_item->sales_detail_product_hsn_code}}<br>{{number_format($sales_item->sales_detail_price,2)}}<br>{{number_format($sales_item->sales_detail_amount,2)}}
                  </td>
                  <td  align="left"><br>{{$sales_item->sales_detail_quantity}}<br>{{number_format($sales_item->sales_detail_discount_amount,2)}}
                  </td>
               </tr>
               @endforeach
               <tr>
                  <td align="left" colspan="2"><b>{{ trans('lang.taxable_amount') }}</b></td>
                  <td align="right" ><b>{{number_format($sales_return->sales_taxable_amount,2)}}</b></td>
               </tr>
                 <?php if($sales_return->sales_igst_amount!=0){ ?>
               <tr>
                  <td align="left"  colspan="2">{{ trans('lang.po_sgst_amount') }}</td>
                  <td align="right" >{{number_format($sales_return->sales_sgst_amount,2)}}</td>
               </tr>
                <tr>
                  <td align="left"  colspan="2">{{ trans('lang.po_cgst_amount') }}</td>
                  <td align="right" >{{number_format($sales_return->sales_cgst_amount,2)}}</td>
               </tr>
                <?php } ?> 
                 @if($sales_return->sales_additional_charge>0)    
               <tr>
                  <td align="left" colspan="2" class="border_none">{{$sales_return->sales_additional_charge_description}}</td>
                  <td align="right" class="border_none">{{number_format($sales_return->sales_additional_charge,2)}}</td>
               </tr>
                @endif
               <tr>
                  <td align="left"  colspan="2" class="border_none">{{ trans('lang.po_round_off') }}</td>
                  <td align="right" class="border_none">{{number_format($sales_return->sales_round_off,2)}}</td>
               </tr>
               <tr>
                  <td align="left"  colspan="2" class="border_none"><b>{{ trans('lang.po_table_grand_total') }}</b></td>
                  <td align="right" class="border_none"><b>{{number_format($sales_return->sales_total_amount,2)}}</b></td>
               </tr>
            </table>
            <br>
            <p></p>
         </div>
      </div>
      <div class="col-md-4">
      </div>
   </div>
</div>
<!--3inch end-->

