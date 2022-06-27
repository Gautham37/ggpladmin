<div class="col-md-12">
    <div class="row">
      <div class="col-md-1">
          <img src="{{$app_logo}}" style="width:100%;" alt="{{setting('app_name')}}" />
      </div>
      <div class="col-md-11">
        <h4 class=""><b>{{ setting('app_name') }}</b></h4>
        <span style="font-size:12px;line-height: 18px;">
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
      </div>
    </div>    
    <hr>
</div>