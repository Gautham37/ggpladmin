<<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>{{$stock_take->code}}</title>
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
                  <td style="border: 0px solid #000; padding: 3px;"><b>Stock Take</b></td>
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
                  <td width="25%">
                     <b>Stock Take No : </b><br> 
                     {{$stock_take->code}}
                  </td>
                  <td width="25%">
                     <b>Date : </b><br> 
                     {{$stock_take->date->format('M d, Y')}}
                  </td>
                  <td width="25%">
                     <b>Created by : </b><br> 
                     {{$stock_take->createdby->name}}
                  </td>
                  <td width="25%">
                     <b>Status : </b><br>
                     {{ucfirst($stock_take->status)}}
                  </td>
               </tr>
            </table>
            <div class="pdf_whole" style="padding:10px;background-color:#f4f4f533;">

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
                        <th>ITEM</th>
                        <th class="text-center">CODE</th>
                        <th class="text-right">CURRENT STOCK</th>
                        <th class="text-right">COUNTED</th>
                        <th class="text-right">WASTAGE</th>
                        <th class="text-right">MISSING</th>
                        <th class="text-left">NOTES</th>
                      </tr>
                     @if(count($stock_take->items) > 0)
                        @foreach($stock_take->items as $item)
                          <tr>
                            <td class="text-left">{{$item->product_name}}</td>
                            <td class="text-center">{{$item->product_code}}</td>
                            <td class="text-right">{{number_format($item->current,3,'.','')}} {{$item->currentunit->name}}</td>
                            <td class="text-right">{{number_format($item->counted,3,'.','')}} {{$item->countedunit->name}}</td>
                            <td class="text-right">{{number_format($item->wastage,3,'.','')}} {{$item->wastageunit->name}}</td>
                            <td class="text-right">{{number_format($item->missing,3,'.','')}} {{$item->missingunit->name}}</td>
                            <td class="text-right">{{$item->notes}}</td>
                          </tr>
                        @endforeach
                      @endif
                  </tbody>
               </table>

               <br><br><br>

               <table class="table table-bordered view-table-custom-product-item" style="width: 100%; border-collapse: collapse;">
                  <tr>
                     <td width="50%">
                        <p>
                           <b>Notes :</b>
                           {!!$stock_take->notes!!}
                        </p>
                     </td>
                     <td width="50%" style="vertical-align:bottom; float:right; text-align: right;">
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

