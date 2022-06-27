@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="custom-from-css col-md-12 column">
    <div class="row">
        
        <div class="col-md-5">
            <!-- Market Id Field -->
            <div class="form-group ">
              {!! Form::label('market_id', trans("lang.markets_payout_market_id"),['class' => ' control-label text-left']) !!}
                {!! Form::select('market_id', $market, null, ['class' => 'select2 form-control']) !!}
                <!--<div class="form-text text-muted">{{ trans("lang.markets_payout_market_id_help") }}</div>-->
            </div>
            
            
            <!-- Method Field -->
            <div class="form-group ">
              {!! Form::label('method', trans("lang.markets_payout_method"),['class' => ' control-label text-left']) !!}
                {!! Form::select('method', ['Bank' => trans('lang.bank'),'Cash'=> trans('lang.cash')], null, ['class' => 'select2 form-control']) !!}
                <!--<div class="form-text text-muted">{{ trans("lang.markets_payout_method_help") }}</div>-->
            </div>
            
            
            <!-- Amount Field -->
            <div class="form-group ">
              {!! Form::label('amount', trans("lang.markets_payout_amount"), ['class' => ' control-label text-left']) !!}
                 {!! Form::number('amount', null,  ['class' => 'form-control','step'=>"any",'placeholder'=>  trans("lang.markets_payout_amount_placeholder")]) !!}
                <!--<div class="form-text text-muted">-->
                <!--  {{ trans("lang.markets_payout_amount_help") }}-->
                <!--</div>-->
            </div>
        </div>
        <div class="col-md-5">
            <!-- Note Field -->
            <div class="form-group  ">
              {!! Form::label('note', trans("lang.markets_payout_note"), ['class' => ' control-label text-left']) !!}
                {!! Form::textarea('note', null, ['class' => 'form-control','placeholder'=>
                 trans("lang.markets_payout_note_placeholder")  ]) !!}
                <!--<div class="form-text text-muted">{{ trans("lang.markets_payout_note_help") }}</div>-->
            </div>
        </div>
        
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.markets_payout')}}</button>
  <a href="{!! route('marketsPayouts.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
