@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
    <div class="row">
        
    <!-- User Id Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('user_id', trans("lang.order_user_id"),['class' => ' control-label text-right']) !!}
        {!! Form::select('user_id', $user, null, ['class' => 'select2 form-control']) !!}
        <!--<div class="form-text text-muted">{{ trans("lang.order_user_id_help") }}</div>-->
    </div>

    <!-- Driver Id Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('driver_id', trans("lang.order_driver_id"),['class' => ' control-label text-right']) !!}
        {!! Form::select('driver_id', $driver, null, ['data-empty'=>trans("lang.order_driver_id_placeholder"),'class' => 'select2 not-required form-control']) !!}
        <!--<div class="form-text text-muted">{{ trans("lang.order_driver_id_help") }}</div>-->
    </div>

    <!-- Order Status Id Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('order_status_id', trans("lang.order_order_status_id"),['class' => ' control-label text-right']) !!}
        {!! Form::select('order_status_id', $orderStatus, null, ['class' => 'select2 form-control']) !!}
        <!--<div class="form-text text-muted">{{ trans("lang.order_order_status_id_help") }}</div>-->
    </div>

    <!-- Status Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('status', trans("lang.payment_status"),['class' => ' control-label text-right']) !!}
        {!! Form::select('status',
        [
        'Waiting for Client' => trans('lang.order_pending'),
        'Not Paid' => trans('lang.order_not_paid'),
        'Paid' => trans('lang.order_paid'),
        ]
        , isset($order->payment) ? $order->payment->status : '', ['class' => 'select2 form-control']) !!}
        <!--<div class="form-text text-muted">{{ trans("lang.payment_status_help") }}</div>-->
    </div>
    
    <!-- delivery_fee Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('delivery_fee', trans("lang.order_delivery_fee"), ['class' => ' control-label text-right']) !!}
        {!! Form::number('delivery_fee', null,  ['class' => 'form-control','step'=>"any",'placeholder'=>  trans("lang.order_delivery_fee_placeholder")]) !!}
        <!--<div class="form-text text-muted">-->
        <!--    {{ trans("lang.order_delivery_fee_help") }}-->
        <!--</div>-->
    </div>
    
    <!-- 'Boolean active Field' -->
    <div class="form-group col-md-4 checkbox-cust1">
        <div class="row">
        {!! Form::label('active', trans("lang.order_active"),['class' => 'col-md-2 control-label text-left']) !!}
        <div class="col-md-10 checkbox icheck">
            <label class=" ml-2 form-check-inline">
                {!! Form::hidden('active', 0) !!}
                {!! Form::checkbox('active', 1, null) !!}
            </label>
        </div>
        </div>
    </div>
    
    <!-- Hint Field -->
    <div class="form-group col-md-4 ">
        {!! Form::label('hint', trans("lang.order_hint"), ['class' => ' control-label text-right']) !!}
        {!! Form::textarea('hint', null, ['class' => 'form-control','placeholder'=>
         trans("lang.order_hint_placeholder")  ]) !!}
        <!--<div class="form-text text-muted">{{ trans("lang.order_hint_help") }}</div>-->
    </div>
    
    <!-- Tax Field -->
   <!-- <div class="form-group col-md-4 ">
        {!! Form::label('tax', trans("lang.order_tax"), ['class' => ' control-label text-right']) !!}
        {!! Form::number('tax', null,  ['class' => 'form-control', 'step'=>"any",'placeholder'=>  trans("lang.order_tax_placeholder")]) !!}
        <div class="form-text text-muted">
            {{ trans("lang.order_tax_help") }}
        </div>
    </div>-->
    
    </div>
</div>

@if($customFields)
    <div class="clearfix"></div>
    <div class="col-12 custom-field-container">
        <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
        {!! $customFields !!}
    </div>
@endif
<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.order')}}</button>
    <a href="{!! route('orders.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
