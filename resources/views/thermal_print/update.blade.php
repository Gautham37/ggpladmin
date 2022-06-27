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
   <button type="button" class="btn btn-success save_thermal_settings" style="float:right;">{{trans('lang.save_changes')}}</button><br><br>
   <div class="row">
      <div class="col-md-12">
         <table class="table">
            <tbody>
               <tr style="background-color:#f8f9fa;">
                  <td width="10%" class="thermal_paper_inch">{{trans('lang.app_thermal_select_paper')}}</td>
                  <td width="90%" rowspan="2">
                     <!--2inch-->
                     @php if(setting('app_thermal_print')==1) { $sty_show='style="display:block;"'; } else { 
                     $sty_show='style="display:none;"'; }
                     @endphp
                     <div class="thermal_2inch" <?=$sty_show?>>
                        <div class="row">
                           <div class="col-md-4">
                           </div>
                           <div class="col-md-4">
                              <div class="thermal_page">
                                 <table  cellpadding="5">
                                    <tbody>
                                       <tr>
                                          <td>
                                             <b>{{ setting('app_name') }}</b><br>
                                             #56, 2nd Floor, 12th Main Road, Sector 6, HSR<br>
                                             Layout Land MArk, next to Rasaganga Veg<br>
                                             Restaurant, HSR BDA, Bengaluru,<br>
                                             Karnataka 560102<br>
                                             Mobile: 4534533453<br>
                                             Date: {{date('d-m-Y')}}<br>
                                             Invoice Number: 2<br>
                                             <b>Bill To:</b> Cash sale
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                                 <table class="thermal_list"  cellpadding="5" >
                                    <tr>
                                       <td align="left"><b>{{ trans('lang.no') }}<br>{{ trans('lang.qty') }}</b></td>
                                       <td align="left"><b>{{ trans('lang.items') }}<br>{{ trans('lang.app_thermal_units') }}</b></td>
                                       <td  align="left"><b>{{ trans('lang.app_thermal_rate') }}<br>{{ trans('lang.amount') }}</b></td>
                                    </tr>
                                    <tr>
                                       <td  align="left">1<br>2</td>
                                       <td  align="left">Apple<br>KGS</td>
                                       <td  align="left">100.00<br>200.00</td>
                                    </tr>
                                    <tr>
                                       <td  align="left" class="border_none">2<br>4</td>
                                       <td  align="left" class="border_none">Mango<br>KGS</td>
                                       <td align="left" class="border_none">50.00<br>200.00</td>
                                    </tr>
                                    <tr>
                                       <td  align="left" colspan="2">{{trans('lang.shipping_charge')}}</td>
                                       <td  align="right" >50.00</td>
                                    </tr>
                                    <tr>
                                       <td  align="left" colspan="2" class="border_none">{{ trans('lang.discount') }}</td>
                                       <td  align="right" class="border_none">15.00</td>
                                    </tr>
                                    <tr>
                                       <td align="left" colspan="2"><b>{{ trans('lang.total_amount') }}</b></td>
                                       <td  align="right" >435.00</td>
                                    </tr>
                                    <tr>
                                       <td align="left" class="border_none" colspan="2">{{ trans('lang.received_amount') }}</td>
                                       <td  align="right"   class="border_none">0.00</td>
                                    </tr>
                                    <tr>
                                       <td  align="left"  colspan="2" style="border-top:none;border-bottom: 1px solid #212529;">{{ trans('lang.balance_amount') }}</td>
                                       <td align="right"  style="border-top:none;border-bottom: 1px solid #212529;">435.00</td>
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
                     @php if(setting('app_thermal_print')==2) { $sty_show='style="display:block;"'; } else { 
                        $sty_show='style="display:none;"'; } 
                     @endphp
                     <div class="thermal_3inch" <?=$sty_show?>>
                        <div class="row">
                           <div class="col-md-4">
                           </div>
                           <div class="col-md-4">
                              <div class="thermal_page1">
                                 <table  cellpadding="5">
                                    <tbody>
                                       <tr>
                                          <td>
                                             <b>Grounded Goodness Pvt Ltd</b><br>
                                             #56, 2nd Floor, 12th Main Road, Sector 6, HSR<br>
                                             Layout Land MArk, next to Rasaganga Veg<br>
                                             Restaurant, HSR BDA, Bengaluru,<br>
                                             Karnataka 560102<br>
                                             Mobile: 4534533453<br>
                                             Date: {{date('d-m-Y')}}<br>
                                             Invoice Number: 2<br>
                                             <b>{{trans('lang.app_thermal_bill_to') }}:</b> Cash sale
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                                 <table class="thermal_list1"  cellpadding="5">
                                    <tr>
                                       <td align="left"><b>{{ trans('lang.no') }}<br>{{ trans('lang.qty') }}</b></td>
                                       <td  align="left"><b>{{ trans('lang.items') }}<br>{{ trans('lang.app_thermal_units') }}</b></td>
                                       <td  align="left"><b><br>{{ trans('lang.app_thermal_rate') }}</b></td>
                                       <td  align="left"><b><br>{{ trans('lang.amount') }}</b></td>
                                    </tr>
                                    <tr>
                                       <td  align="left">1<br>2</td>
                                       <td  align="left">Apple<br>KGS</td>
                                       <td  align="left"><br>100.00</td>
                                       <td  align="left"><br>200.00</td>
                                    </tr>
                                    <tr>
                                       <td  align="left" class="border_none">2<br>4</td>
                                       <td  align="left" class="border_none">Mango<br>KGS</td>
                                       <td  align="left" class="border_none"><br>50.00</td>
                                       <td  align="left" class="border_none"><br>200.00</td>
                                    </tr>
                                    <tr>
                                       <td  align="left"  colspan="2">{{trans('lang.shipping_charge')}}</td>
                                       <td  align="right"  colspan="2">50.00</td>
                                    </tr>
                                    <tr>
                                       <td  align="left"  colspan="2" class="border_none">{{trans('lang.discount')}}</td>
                                       <td  align="right"  colspan="2" class="border_none">15.00</td>
                                    </tr>
                                    <tr>
                                       <td  align="left"  colspan="2"><b>{{ trans('lang.total_amount') }}</b></td>
                                       <td  align="right"  colspan="2">435.00</td>
                                    </tr>
                                    <tr>
                                       <td  align="left"  colspan="2" class="border_none">{{ trans('lang.received_amount') }}</td>
                                       <td  align="right"  colspan="2" class="border_none">0.00</td>
                                    </tr>
                                    <tr>
                                       <td  align="left"  colspan="2" style="border-top:none;border-bottom: 1px solid #212529;">{{ trans('lang.balance_amount') }}</td>
                                       <td  align="right"  colspan="2" style="border-top:none;border-bottom: 1px solid #212529;">435.00</td>
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
                  </td>
               </tr>
               <tr style="background-color:#f8f9fa;">
                  <!--Thermal Print for invoice -->
                  <td style="border:1px solid #dee2e6;vertical-align:top;width:10%;">
                     @php $style_theme = 'style="color:#ffffff;background-color:#28a745;border-color:#28a745;"';
                     @endphp
                     <!--2 inch-->
                     <button  type="button" class="btn btn-outline-success btn-lg thermal_button" id="thermal_invoice1" data-thermal="1" <?php if(setting('app_thermal_print')==1){ echo $style_theme; } ?>>{{trans('lang.app_thermal_2inch')}}</button><br><br>
                     <!--3 inch-->
                     <button type="button" class="btn btn-outline-success btn-lg thermal_button" id="thermal_invoice2" data-thermal='2' <?php if(setting('app_thermal_print')==2){ echo $style_theme; } ?>>{{trans('lang.app_thermal_3inch')}}</button>
                  </td>
               </tr>
            </tbody>
         </table>
         {!! Form::close() !!}
      </div>
   </div>
</div>
@endsection