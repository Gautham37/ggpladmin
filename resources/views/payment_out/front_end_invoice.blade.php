@extends('layouts.front_app')

@section('content')
    
<div class="dwn-button-div" style="float: right;margin-right: 20%;">
   <table>
      <tr>
         <td>
            <div class="float-right top-action-btn">
               <a href="{{url(base64_encode($payment_out->id).'/PaymentOut/DownloadPDF')}}"><button style="background: #881798;border: 1px solid #881798;" class="btn btn-primary btn-sm thermal_prints">
                 Download PDF
               </button></a>
            </div>
         </td>
      </tr>
   </table>
</div>

<div class="pdf_all" style="font-size:12px;margin: -25px;" cellpadding="5">
   <div class="stylish_original">
      <div class="invoice_page_original" style="font-size:12px;background-color:#f4f4f533;">
         <div class="invoice_page1">
            <table style="width: auto; background-color:#f4f4f533;">
               <tr>
                  <td style="border: 0px solid #000; padding: 3px;"><b>Payment Out</b></td>
               </tr>
            </table>
            <table class="table"  cellpadding="5" style="width: 100%;background-color:#f4f4f533;">
               <tbody>
                  <tr>
                     <td style="width: 20%; text-align: center;">
                        <!-- {{$app_logo}} -->
                        <img src="{{$app_logo}}" alt="{{setting('app_name')}}" style="width:100px;height:100px;" >
                     </td>
                     <td style="width: 80%;">
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
                     <b>Payment Out No : </b> 
                     {{$payment_out->code}}
                  </td>
                  <td width="33%">
                     <b>Date : </b> 
                     {{$payment_out->date->format('d M Y')}}
                  </td>
                  <td width="33%">
                     <b>Payment Method : </b> 
                     {{$payment_out->paymentmethod->name}}
                  </td>
               </tr>
            </table>
            <div class="pdf_whole" style="padding:10px;background-color:#f4f4f533;">
               
               <table style="width: 100%; line-height:15px;">
                  <tr>
                     <td width="33.33333%">  
                        <p><b>Bill To :</b></p>
                        <p><b>{{$payment_out->market->name}}</b></p>
                        <p>
                        <b>Address : </b> 
                        {!! ($payment_out->market->street_no) ? $payment_out->market->street_no.', ' : '' !!}
                        {!! ($payment_out->market->street_name) ? $payment_out->market->street_name.', ' : '' !!}
                        {!! ($payment_out->market->street_type) ? $payment_out->market->street_type.', ' : '' !!}
                        {!! ($payment_out->market->address_line_1) ? $payment_out->market->address_line_1.', ' : '' !!}
                        {!! ($payment_out->market->address_line_2) ? $payment_out->market->address_line_2.', ' : '' !!} 
                        {!! ($payment_out->market->town) ? $payment_out->market->town.', ' : '' !!} 
                        {!! ($payment_out->market->city) ? $payment_out->market->city.', ' : '' !!} 
                        {!! ($payment_out->market->state) ? $payment_out->market->state.', ' : '' !!} 
                        {!! ($payment_out->market->pincode) ? $payment_out->market->pincode.', ' : '' !!} 
                        <br>
                        <b>Landmark : </b>
                        {!! ($payment_out->market->landmark_1) ? $payment_out->market->landmark_1.', ' : '' !!}
                        {!! ($payment_out->market->landmark_2) ? $payment_out->market->landmark_2.', ' : '' !!}
                        </p>
                        <p>{!! '<b>Phone  : </b>'.$payment_out->market->phone !!}</p>
                        <p>{!! '<b>Mobile : </b>'.$payment_out->market->mobile !!}</p>
                        <p>{!! '<b>Email  : </b>'.$payment_out->market->email !!}</p>
                     </td>
                     <td width="33.33333%"></td>
                     <td width="33.33333%">
                        <p><b>Ship To :</b></p>
                        <p><b>{{$payment_out->market->name}}</b></p>
                        <p>
                        <b>Address : </b> 
                        {!! ($payment_out->market->street_no) ? $payment_out->market->street_no.', ' : '' !!}
                        {!! ($payment_out->market->street_name) ? $payment_out->market->street_name.', ' : '' !!}
                        {!! ($payment_out->market->street_type) ? $payment_out->market->street_type.', ' : '' !!}
                        {!! ($payment_out->market->address_line_1) ? $payment_out->market->address_line_1.', ' : '' !!}
                        {!! ($payment_out->market->address_line_2) ? $payment_out->market->address_line_2.', ' : '' !!} 
                        {!! ($payment_out->market->town) ? $payment_out->market->town.', ' : '' !!} 
                        {!! ($payment_out->market->city) ? $payment_out->market->city.', ' : '' !!} 
                        {!! ($payment_out->market->state) ? $payment_out->market->state.', ' : '' !!} 
                        {!! ($payment_out->market->pincode) ? $payment_out->market->pincode.', ' : '' !!} 
                        <br>
                        <b>Landmark : </b>
                        {!! ($payment_out->market->landmark_1) ? $payment_out->market->landmark_1.', ' : '' !!}
                        {!! ($payment_out->market->landmark_2) ? $payment_out->market->landmark_2.', ' : '' !!}
                        </p>
                        <p>{!! '<b>Phone  : </b>'.$payment_out->market->phone !!}</p>
                        <p>{!! '<b>Mobile : </b>'.$payment_out->market->mobile !!}</p>
                        <p>{!! '<b>Email  : </b>'.$payment_out->market->email !!}</p>
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

               @if(count($payment_out->paymentoutsettle) > 0)
               <table class="table table-bordered view-table-custom-product-item" style="width: 100%; border-collapse: collapse;">
                  <tbody>      
                     
                      <tr>
                        <th class="text-left">INVOICE NO</th>
                        <th class="text-center">DATE</th>
                        <th class="text-right">SETTLED AMOUNT</th>
                      </tr>

                      @foreach($payment_out->paymentoutsettle as $invoice)
                        <tr>
                          <td width="60%">
                            @if($invoice->settle_type=='sales')
                              {{$invoice->salesreturn->code}}
                            @elseif($invoice->settle_type=='purchase')
                              {{$invoice->purchaseinvoice->code}}
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
                           {!!$payment_out->notes!!}
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

@endsection    

@push('scripts')



@endpush