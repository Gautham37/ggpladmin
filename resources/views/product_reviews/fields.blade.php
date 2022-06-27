@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
    <div class="row">
        
        <div class="col-md-5">
            
            <!-- User Id Field -->
            <div class="form-group row ">
                <div class="col-md-12">
              {!! Form::label('user_id', trans("lang.product_review_user_id"),['class' => ' control-label text-right']) !!}
                {!! Form::select('user_id', $user, null, ['class' => 'select2 form-control']) !!}
                <!--<div class="form-text text-muted">{{ trans("lang.product_review_user_id_help") }}</div>-->
                </div>
            </div>
            
            <!-- Product Id Field -->
            <div class="form-group row ">
                <div class="col-md-12">
              {!! Form::label('product_id', trans("lang.product_review_product_id"),['class' => ' control-label text-right']) !!}
                {!! Form::select('product_id', $product, null, ['class' => 'select2 form-control']) !!}
                <!--<div class="form-text text-muted">{{ trans("lang.product_review_product_id_help") }}</div>-->
                </div>
            </div>
            
            <!-- Rate Field -->
            <div class="form-group row ">
                <div class="col-md-12">
              {!! Form::label('rate', trans("lang.product_review_rate"), ['class' => ' control-label text-right']) !!}
                {!! Form::number('rate', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_review_rate_placeholder")]) !!}
                <!--<div class="form-text text-muted">-->
                <!--  {{ trans("lang.product_review_rate_help") }}-->
                <!--</div>-->
                </div>
            </div>
            
            

            <div class="form-group  row" style="margin-top:30px;">
                {!! Form::label('active', 'Approve / Disapprove',['class' => 'col-4 control-label text-left']) !!}
                <div class="col-8 checkbox icheck">
                    <label class="form-check-inline">
                        {!! Form::hidden('active', 0) !!}
                        {!! Form::checkbox('active', 1, null) !!}
                    </label>
                </div>
            </div>
        </div>
            
        <div class="col-md-5">
            <!-- Review Field -->
            <div class="form-group row ">
              {!! Form::label('review', trans("lang.product_review_review"), ['class' => ' control-label text-right']) !!}
                {!! Form::textarea('review', null, ['class' => 'form-control','placeholder'=>
                 trans("lang.product_review_review_placeholder")  ]) !!}
                <!--<div class="form-text text-muted">{{ trans("lang.product_review_review_help") }}</div>-->
            </div>
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.product_review')}}</button>
  <a href="{!! route('productReviews.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
