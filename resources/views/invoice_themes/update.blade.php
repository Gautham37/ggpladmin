@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{trans('lang.app_invoice_theme_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.app_invoice_theme_desc')}}</small></h1>
         </div>
         <!-- /.col -->
         <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
               <li class="breadcrumb-item"><a href="{!! route('orders.index') !!}">{{trans('lang.app_invoice_theme_plural')}}</a>
               </li>
               <li class="breadcrumb-item active">{{trans('lang.app_invoice_theme_table')}}</li>
            </ol>
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
   </div>
   <!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
   <div class="clearfix"></div>
   <div class="card">
      <div class="card-header">

         {!! Form::open(['route' => 'categories.store', 'id' => 'invoice_settings']) !!}

            <button type="button" class="btn btn-success save_settings" style="float:right;">{{trans('lang.save')}} {{trans('lang.app_invoice_theme')}}</button><br><br>
            <table class="table" style="border:none;width:100%;">
               <tbody>
                  
                  <tr style="background-color:#f8f9fa;">
                     <td style="border:1px solid #dee2e6;width:10%;">{{trans('lang.select_invoice_theme')}}</td>
                     <td style="border:1px solid #dee2e6;width:10%;">{{ trans('lang.select_color') }}</td>
                     <td style="border:1px solid #dee2e6;vertical-align:top;width:80%;" rowspan="2">
                        <!--Stylish-->
                        <?php 
                           if(setting('app_invoice_theme')==1) { 
                              $sty_theme_show='style="display:block;"'; 
                           } else{ 
                              $sty_theme_show='style="display:none;"'; 
                           } 
                        ?>
                        <div class="stylish" {!!$sty_theme_show!!}>
                           <div class="invoice_page" style="font-size:12px;background-color:#f4f4f533;">
                              <div class="invoice_page1">
                                 <table class="table"  cellpadding="5" style="width: 100%;background-color:#f4f4f533;">
                                    <tbody>
                                       <tr>
                                          <td style="width: 4%;">
                                             <img src="{{$app_logo}}" alt="{{setting('app_name')}}" style="width:140px;height:140px;" >
                                          </td>
                                          <td style="width: 96%;">
                                             <span class="theme_head" > {{ trans('lang.app_company') }}</span><br>
                                             <span style="font-size:12px;line-height: 25px;">Akshya Nagar 1st Block 1st Cross, Rammurthy nagar, Bangalore, 560016<br><b>{{ trans('lang.market_mobile') }}:</b> 8686783433 &nbsp;&nbsp;<b>{{ trans('lang.gstin') }}:</b> 29BFFSE3422D3G
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
                                 <table style="width: 100%;" class="pdf_div1">
                                    <tr>
                                       <td style="width: 33%;">
                                          <b>{{ trans('lang.invoice_number') }}</b><br>100
                                       </td>
                                       <td style="width: 33%;">
                                          <b>{{ trans('lang.invoice_date') }}</b><br><?= date('d-m-Y'); ?>
                                       </td>
                                       <td style="width: 34%;">
                                          <b>{{ trans('lang.due_date') }}</b><br><?= date('d-m-Y'); ?>
                                       </td>
                                    </tr>
                                 </table>
                                 <table style="width: 100%;">
                                    <tr>
                                       <td style="width: 33%;">
                                          <b>{{ trans('lang.bill_to') }}</b><br>
                                          <b>Rakesh Enterprises</b><br>
                                          Akshya Nagar 1st Block 1st Cross,<br>
                                          Rammurthy nagar, Bangalore, 560016<br>
                                          {{ trans('lang.place_of_supply')}}: Bangalore
                                       </td>
                                       <td style="width: 33%;">
                                          <b>{{ trans('lang.ship_to') }}</b><br>
                                          <b>Rakesh Enterprises</b><br>
                                          Akshya Nagar 1st Block 1st Cross,<br>
                                          Rammurthy nagar, Bangalore, 560016<br>
                                          Bangalore
                                       </td>
                                       <td style="width: 33%;">
                                          <b>{{ trans('lang.po_bill_no') }}</b>: 123456<br>
                                          <b>{{ trans('lang.eway_bill_no') }}</b>: E123456<br>
                                          <b>{{ trans('lang.vehicle_no') }}</b>: KO-23-45-06<br>
                                       </td>
                                    </tr>
                                 </table>
                                 <br>
                                 <table class="pdf_list"  cellpadding="5">
                                    <tr>
                                       <?php
                                          if(setting('app_invoice_color')!='') {
                                             $col_val = setting('app_invoice_color');
                                             $sty_pdf = 'style="border-top:3px solid '.$col_val.';border-bottom:3px solid '.$col_val.'"';
                                          } else{
                                             $sty_pdf = 'style="border-top:3px solid green;border-bottom:3px solid green;"'; 
                                          }
                                          
                                          $money_symbol = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
                                          ?>
                                       <td width="18%" align="center" <?=$sty_pdf?> class="pdf_table"><b>{{ trans('lang.items') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_table"><b>{{ trans('lang.hsn') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_table"><b>{{ trans('lang.qty') }}</b></td>
                                       <td width="8%" align="center" <?=$sty_pdf?> class="pdf_table"><b>{{ trans('lang.mrp') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_table"><b>{{ trans('lang.rate/item') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_table"><b>{{ trans('lang.discount') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_table"><b>{{ trans('lang.tax') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_table"><b>{{ trans('lang.amount') }}</b></td>
                                    </tr>
                                 </table>
                                 <table cellpadding="5" style="width: 100%;">
                                    <?php $sty_con = 'style="border-top:2px solid #e9ecef;"'; ?>
                                    <tr>
                                       <td width="18%" align="left" <?=$sty_con?>><b>Apple</b></td>
                                       <td width="12%" align="center" <?=$sty_con?>>1234</td>
                                       <td width="12%" align="center" <?=$sty_con?>>2 KGS</td>
                                       <td width="8%" align="center" <?=$sty_con?>>90.00</td>
                                       <td width="12%" align="center" <?=$sty_con?>>90.00</td>
                                       <td width="12%" align="center" <?=$sty_con?>>10.00</td>
                                       <td width="12%" align="center" <?=$sty_con?>>20.00</td>
                                       <td width="12%" align="center" <?=$sty_con?>>200.00</td>
                                    </tr>
                                    <tr style="border-bottom: 2px solid #e9ecef;">
                                       <td width="18%" align="left" <?=$sty_con?>><b>Mango</b></td>
                                       <td width="12%" align="center" <?=$sty_con?>>1234</td>
                                       <td width="12%" align="center" <?=$sty_con?>>6 KGS</td>
                                       <td width="8%" align="center" <?=$sty_con?>>50.00</td>
                                       <td width="12%" align="center" <?=$sty_con?>>50.00</td>
                                       <td width="12%" align="center" <?=$sty_con?>>20.00</td>
                                       <td width="12%" align="center" <?=$sty_con?>>10.00</td>
                                       <td width="12%" align="center" <?=$sty_con?>>300.00</td>
                                    </tr>
                                 </table>
                                 <br> <br>
                                 <table class="pdf_list"  cellpadding="5">
                                    <tr>
                                       <td width="18%" align="left" <?=$sty_pdf?> class="pdf_table"></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_table"></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_table">8 KGS</td>
                                       <td width="8%" align="center" <?=$sty_pdf?> class="pdf_table"></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_table"><b>{{ trans('lang.subtotal') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_table">10.00</td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_table">30.00</td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_table">500.00</td>
                                    </tr>
                                 </table>
                                 <table class="bank_info" style="width: 100%;">
                                    <tr>
                                       <td style="width: 25%;border:none;">{{ trans('lang.app_bank_detail') }}</td>
                                       <td style="width: 35%;border:none;"></td>
                                       <td style="width: 25%;border:none;" align="right"><b>{{ trans('lang.taxable_amount') }}</b></td>
                                       <td style="width: 15%;border:none;" align="right"><b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 500.00</b></td>
                                    </tr>
                                    <tr>
                                       <td style="width: 25%;border:none;">{{ trans('lang.app_bank_account_number') }}:</td>
                                       <td style="width: 35%;border:none;">123456778678452</td>
                                       <td style="width: 25%;border:none;" align="right">{{ trans('lang.sgst')}}2.5:</td>
                                       <td style="width: 15%;border:none;" align="right"><b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 15.00</b></td>
                                    </tr>
                                    <tr>
                                       <td style="width: 25%;border:none;">{{ trans('lang.app_bank_ifsc_code') }}:</td>
                                       <td style="width: 35%;border:none;">SBI0000422</td>
                                       <td style="width: 25%;border:none;" align="right">{{ trans('lang.cgst')}}2.5:</td>
                                       <td style="width: 15%;border:none;" align="right"><b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 15.00</b></td>
                                    </tr>
                                    <tr>
                                       <td style="width: 25%;border:none;">{{ trans('lang.app_bank_bankbranch') }}:</td>
                                       <td style="width: 35%;border:none;">State Bank of India, Bangalore</td>
                                       <td style="width: 25%;" align="right">{{ trans('lang.grand_total')}}</td>
                                       <td style="width: 15%;" align="right" style="border-top:2px solid #e9ecef;"><b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 500.00</b></td>
                                    </tr>
                                 </table>
                                 <table class="bank_info" style="width: 100%;">
                                    <tr>
                                       <td style="width: 25%;border:none;">{{ trans('lang.payment_qr_code') }}</td>
                                       <td style="width: 35%;border:none;" rowspan="3"><img src="{{$app_upi_code}}" style="width:100px;height:100px;"></td>
                                       <td style="width: 25%;" align="right"><b>{{ trans('lang.received_amount') }}</b></td>
                                       <td style="width: 15%;" align="right"><b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 500.00</b></td>
                                    </tr>
                                    <tr>
                                       <td style="width: 20%;border:none;">{{ trans('lang.upi_id') }}</td>
                                       <td style="width: 25%;" align="right"><b>{{ trans('lang.balance_amount') }}</b></td>
                                       <td style="width: 15%;" align="right"><b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 0.00</b></td>
                                    </tr>
                                    <tr>
                                       <td style="width: 20%;border:none;">groundedgoodness1@hdfcbank</td>
                                       <td style="width: 25%;" align="right"></td>
                                       <td style="width: 15%;" align="right"></td>
                                    </tr>
                                 </table>
                                 <table style="width: 100%;" cellpadding="5">
                                    <tr style="line-height:20px;">
                                       <td width="62%" style="border:none;"><span>{{ trans('lang.terms_and_conditions') }}</span><br>{{setting('terms_and_conditions')}}</td>
                                       <td width="13%" style="border:none;"></td>
                                       <td width="25%" style="border:none;"><img src="{{$app_invoice_signature}}" style="width:100px;height:80px;text-align:center;"></td>
                                    </tr>
                                 </table>
                                 <table style="width: 100%;" cellpadding="5">
                                    <tr>
                                       <td width="50%" style="border:none;"></td>
                                       <td width="50%" align="right" style="border:none;">{{ trans('lang.authorized_signature') }}</td>
                                    </tr>
                                 </table>
                              </div>
                           </div>
                           </div>
                        <!--Stylish End-->
                        
                        <!--Simple-->
                        <?php 
                           if(setting('app_invoice_theme')==2) { 
                              $sty_theme_show='style="display:block;"'; 
                           } else{ 
                              $sty_theme_show='style="display:none;"'; 
                           }
                        ?>
                        <div class="simple" <?=$sty_theme_show?>>
                           <div class="invoice_page" style="font-size:12px;background-color:#f4f4f533;">
                              <div class="invoice_page1">
                                 <table class="table"  cellpadding="5" style="width: 100%;background-color:#f4f4f533;">
                                    <tbody>
                                       <tr>
                                          <td style="width: 4%;">
                                             <img src="{{$app_logo}}" alt="{{setting('app_name')}}" style="width:140px;height:140px;" >
                                          </td>
                                          <td style="width:46%;border-right:1px solid #212529;vertical-align: top;">
                                             <span class="theme_head" > {{ trans('lang.app_company') }}</span><br>
                                             <span style="font-size:12px;line-height: 25px;">Akshya Nagar 1st Block 1st Cross, Rammurthy nagar, Bangalore, 560016<br><b>{{ trans('lang.market_mobile') }}:</b> 8686783433 &nbsp;&nbsp;<b>{{ trans('lang.gstin') }}:</b> 29BFFSE3422D3G
                                             </span>
                                          </td>
                                          <td style="width:25%;vertical-align: top;">
                                             <span class="theme_invoice_head"> {{trans('lang.tax_invoice')}}</span><br>
                                             <span>{{trans('lang.invoice_number')}}<br>{{trans('lang.invoice_date')}}<br>{{ trans('lang.due_date')}}<br>{{trans('lang.po_bill_no')}}<br>{{trans('lang.eway_bill_no') }}<br>{{trans('lang.vehicle_no')}}
                                             </span>
                                          </td>
                                          <td style="width:25%;vertical-align: top;">
                                             <span style="line-height: 32px;"> {{trans('lang.original_for_recipient')}}</span><br>
                                             <span>157<br><?=date('Y-m-d')?><br><?=date('Y-m-d')?><br>1234565<br>E2212312<br>KA-00-6H-3122
                                             </span>
                                          </td>
                                       </tr>
                                       <?php  
                                          if(setting('app_invoice_color')!='') {
                                             $col_val  = setting('app_invoice_color');
                                             $sty_head = 'style="background-color: '.$col_val.'"';
                                          } else {
                                             $sty_head = 'style="background-color: green;"'; 
                                          } 
                                       ?>
                                       <tr <?=$sty_head?> class="pdf_border">
                                          <td style="width: 4%;">
                                             <b>{{ trans('lang.bill_to') }}</b>
                                          </td>
                                          <td style="width:46%;border-right:1px solid #212529;">                
                                          </td>
                                          <td style="width:25%;">
                                             <b>{{ trans('lang.ship_to') }}</b>
                                          </td>
                                          <td style="width:25%;">
                                          </td>
                                       </tr>
                                       <tr>
                                          <td colspan="2" style="border-right:1px solid #212529;">
                                             <b>Rakesh Enterprises</b><br>
                                             Akshya Nagar 1st Block 1st Cross,<br>
                                             Rammurthy nagar, Bangalore, 560016<br>
                                             {{ trans('lang.place_of_supply')}}: Bangalore
                                          </td>
                                          <!-- <td style="width:0%;border-right:1px solid #212529;">                
                                             </td> -->
                                          <!-- <td>
                                             <b>Rakesh Enterprises</b><br>
                                             Akshya Nagar 1st Block 1st Cross,<br>
                                             Rammurthy nagar, Bangalore, 560016<br>
                                             {{ trans('lang.place_of_supply')}}: Bangalore
                                             </td> -->
                                          <td >
                                             <b>Rakesh Enterprises</b><br>
                                             Akshya Nagar 1st Block 1st Cross,<br>
                                             Rammurthy nagar, Bangalore, 560016<br>
                                             {{ trans('lang.place_of_supply')}}: Bangalore
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                                 <?php
                                    if(setting('app_invoice_color')!='') {
                                       $col_val    = setting('app_invoice_color');
                                       $choose_col = 'style="background-color:'.$col_val.';"';
                                    } else {
                                       $choose_col = "style='';";
                                    }
                                 ?>
                                 <br>
                                 <table class="pdf_list"  cellpadding="5" style="margin-top: -34px;">
                                    <tr>
                                       <?php
                                          if(setting('app_invoice_color')!='') {
                                             $col_val = setting('app_invoice_color');
                                             $sty_pdf = 'style="background-color:'.$col_val.'"';
                                          } else {
                                             $sty_pdf = 'style="background-color:green;"'; 
                                          }
                                          
                                          $money_symbol = '<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>';
                                       ?>
                                       <td width="3%" align="center" <?=$sty_pdf?> class="pdf_border"><b>S.No</b></td>
                                       <td width="15%" align="left" <?=$sty_pdf?> class="pdf_border"><b>{{ trans('lang.items') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_border"><b>{{ trans('lang.hsn') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_border"><b>{{ trans('lang.qty') }}</b></td>
                                       <td width="8%" align="center" <?=$sty_pdf?> class="pdf_border"><b>{{ trans('lang.mrp') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_border"><b>{{ trans('lang.rate/item') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_border"><b>{{ trans('lang.discount') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_border"><b>{{ trans('lang.tax') }}</b></td>
                                       <td width="12%" align="center" <?=$sty_pdf?> class="pdf_border"><b>{{ trans('lang.amount') }}</b></td>
                                    </tr>
                                 </table>
                                 <table cellpadding="5" style="width: 100%;">
                                    <tr>
                                       <td style="border:none;" width="3%" align="center"><b>1</b></td>
                                       <td style="border:none;" width="15%" align="left"><b>Apple</b></td>
                                       <td style="border:none;" width="12%" align="center">1234</td>
                                       <td style="border:none;" width="12%" align="center">2 KGS</td>
                                       <td style="border:none;" width="8%" align="center">90.00</td>
                                       <td style="border:none;" width="12%" align="center">90.00</td>
                                       <td style="border:none;" width="12%" align="center">10.00</td>
                                       <td style="border:none;" width="12%" align="center">20.00</td>
                                       <td style="border:none;" width="12%" align="center">200.00</td>
                                    </tr>
                                    <tr >
                                       <td style="border:none;" width="3%" align="center"><b>2</b></td>
                                       <td style="border:none;" width="15%" align="left"><b>Mango</b></td>
                                       <td style="border:none;" width="12%" align="center">1234</td>
                                       <td style="border:none;" width="12%" align="center">6 KGS</td>
                                       <td style="border:none;" width="8%" align="center">50.00</td>
                                       <td style="border:none;" width="12%" align="center">50.00</td>
                                       <td style="border:none;" width="12%" align="center">20.00</td>
                                       <td style="border:none;" width="12%" align="center">10.00</td>
                                       <td style="border:none;" width="12%" align="center">300.00</td>
                                    </tr>
                                 </table>
                                 <br>
                                 <table style="width: 100%;" cellspacing="0" cellpadding="0">
                                    <tbody>
                                       <tr style="line-height:10px;">
                                          <td width="62%" class="top_border_simple right_border_simple">
                                             <b>{{trans('lang.amount_in_words')}}</b>
                                             <br><br> Five Hundred Rupees
                                          </td>
                                          <td class="text-right top_border_simple" width="19%">
                                             <b>{{trans('lang.taxable_amount')}}</b>
                                          </td>
                                          <td class="text-right top_border_simple" width="19%">
                                             <b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 500.00</b>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                                 <table style="width: 100%;" cellspacing="0" cellpadding="0">
                                    <tbody>
                                       <tr>
                                          <td colspan="6" rowspan="6" width="62%" class="top_border_simple right_border_simple" style="line-height:20px;vertical-align: top;">
                                             <table style="width: 100%;padding: 0rem;" cellspacing="0" cellpadding="0" class='border_none'>
                                                <tr>
                                                   <td style="width: 20%;border-top:none;vertical-align: top;" >
                                                      <p><b>{{trans('lang.app_bank_detail')}}</b></p>
                                                      {{ trans('lang.app_bank_account_number')}}:<br>
                                                      {{ trans('lang.app_bank_ifsc_code')}}: <br>
                                                      {{ trans('lang.app_bank_bankbranch')}}:<br>
                                                   </td>
                                                   <td style="width: 30%;border-top:none;vertical-align: top;" class="right_border_simple">
                                                      <p></p>
                                                      <br>
                                                      1234567897<br>
                                                      SBI0234223<br>
                                                      State Bank of India, <br>
                                                      Bangalore<br>
                                                   </td>
                                                   <td style="width:25%;border-top:none;">
                                                      <p></p>
                                                      {{trans('lang.payment_qr_code')}}<br>
                                                      {{ trans('lang.upi_id')}}: <br>
                                                      pmcares@sbi: <br>
                                                   </td>
                                                   <td style="width: 25%;border-top:none;"><img src="{{$app_upi_code}}" style="width:100px;height:100px;"></td>
                                                </tr>
                                             </table>
                                          </td>
                                          <td colspan="2" width="19%" class="text-right border_none" style="line-height:1px;">
                                             {{trans('lang.sgst')}}@2.5:
                                          </td>
                                          <td colspan="3" class="text-right border_none" width="19%">
                                             <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 12.50      
                                          </td>
                                       </tr>
                                       <tr style="line-height:1px;">
                                          <td colspan="2" width="19%" class="text-right border_none">
                                             {{trans('lang.cgst')}}@2.5:
                                          </td>
                                          <td colspan="3" class="text-right border_none" width="19%">
                                             <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 12.50  
                                          </td>
                                       </tr>
                                       <tr style="line-height:1px;">
                                          <td colspan="2" width="19%" class="text-right border_none">
                                             {{trans('lang.shipping_charge')}}:
                                          </td>
                                          <td colspan="3" class="text-right border_none" width="19%">
                                             <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 100.00      
                                          </td>
                                       </tr>
                                       <tr style="line-height:1px;">
                                          <td colspan="2" width="19%" class="text-right border_none">
                                             {{trans('lang.discount')}}:
                                          </td>
                                          <td colspan="3" class="text-right border_none" width="19%">
                                             <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 10.00     
                                          </td>
                                       </tr>
                                       <tr style="line-height:1px;">
                                          <td colspan="2" width="19%" class="text-right border_none">
                                             {{trans('lang.round_off')}}:
                                          </td>
                                          <td colspan="3" class="text-right border_none" width="19%">
                                             <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 0.00      
                                          </td>
                                       </tr>
                                       <tr style="line-height:1px;">
                                          <td colspan="2" width="19%" class="text-right border_none">
                                             <b> {{trans('lang.grand_total')}}:</b>
                                          </td>
                                          <td colspan="3" class="text-right border_none" width="19%">
                                             <b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 102.50</b>  
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                                 <table style="width: 100%;" cellspacing="0" cellpadding="0">
                                    <tbody>
                                       <tr>
                                          <td width="62%"  colspan="3" rowspan="2" class="top_border_simple right_border_simple" style="padding: 0.25rem;vertical-align:top">
                                             <p><b>{{ trans('lang.terms_and_conditions') }}</b></p>
                                             {{setting('terms_and_conditions')}}
                                          </td>
                                          <td width="19%" align="right" class="border_none" style="padding: 0.55rem;line-height: 24px;">  
                                             {{trans('lang.received_amount')}}: <br>
                                             {{trans('lang.balance_amount')}}:
                                          </td>
                                          <td width="19%" align="right" class="border_none" style="padding: 0.55rem;line-height: 24px;"> 
                                             <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 0.00<br>
                                             <b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 102.50</b>  
                                          </td>
                                       </tr>
                                       <tr>
                                          <td width="19%" align="right" class="top_border_simple" style="padding: 0.25rem;line-height: 24px;"> </td>
                                          <td width="38%" align="right" class="top_border_simple" style="padding: 0.25rem;line-height: 24px;">  <img src="{{$app_invoice_signature}}" style="width:100px;height:80px;text-align:center;">
                                             <br>{{ trans('lang.authorized_signature') }}
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                        <!--Simple End-->
                     </td>
                  </tr>
                  
                  <tr style="background-color:#f8f9fa;">
                     
                     <?php $style_theme = 'color:#ffffff; background-color:#28a745; border-color:#28a745;'; ?>
                     <!--Theme picker for invoice -->
                     <td style="border:1px solid #dee2e6;vertical-align:top;width:10%;">
                        <!--Stylish-->
                        <button type="button" class="btn btn-outline-success btn-lg invoice_button" id="invoice_theme_1" data-id="1" style="@if(setting('app_invoice_theme')=='1') {{$style_theme}}  @endif ">
                           {{trans('lang.app_invoice_stylish')}}
                        </button>

                        <br><br>
                        
                        <button type="button" class="btn btn-outline-success btn-lg invoice_button" id="invoice_theme_2" data-id="2" style="@if(setting('app_invoice_theme')=='2') {{$style_theme}}  @endif ">
                           {{trans('lang.app_invoice_simple')}}
                        </button>

                     </td>

                     <!--Color picker for invoice -->          
                     <td style="border:1px solid #dee2e6; vertical-align:top;" width="10%">
                        @foreach($invoice_colors as $key => $invoice_color) 
                           <div class="invoice_color_picker invoice_button{!!$invoice_color->id!!}" data-id="{!!$invoice_color->id!!}" data-col="{!!$invoice_color->invoice_color_code!!}" style="background-color:{!!$invoice_color->invoice_color_code!!};
                           @if(setting('app_invoice_color')==$invoice_color->invoice_color_code) border:3px solid #007bff; @endif " >
                           </div><br>
                        @endforeach
                     </td>

                  </tr>

               </tbody>
            </table>

         {!! Form::close() !!}

      </div>
   </div>
</div>
@endsection