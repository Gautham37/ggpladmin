@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
    <div class="row">
        <!-- Question Field -->
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('question', trans("lang.faq_question"), ['class' => 'col-3 control-label text-left required']) !!}
            {!! Form::textarea('question', null,  ['class' => 'form-control','placeholder'=>  trans("lang.faq_question_placeholder")]) !!}
          </div>
        </div>
        
         <!-- Answer Field -->
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('answer', trans("lang.faq_answer"), ['class' => 'col-3 control-label text-left required']) !!}
            {!! Form::textarea('answer', null,  ['class' => 'form-control','placeholder'=>  trans("lang.faq_answer_placeholder")]) !!}
          </div>
        </div>

       <!--  Faq Category Field -->
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('faq_category_id', trans("lang.faq_faq_category_id"), ['class' => 'col-3 control-label text-left required']) !!}
            {!! Form::select('faq_category_id', $faqCategory, null, ['class' => 'select2 form-control']) !!}
          </div>
        </div>

       <!--  App type Field -->
        <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('app_type', trans("lang.app_type"), ['class' => 'col-3 control-label text-left required']) !!}
            {!! Form::select('app_type', [ 0 => 'Please Select App Type', 1 => 'Customer App', 2 => 'Farmer App', 3 => 'Delivery App' ], null, ['class' => 'select2 form-control']) !!}
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> Save FAQ</button>
  <a href="{!! route('departments.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Cancel</a>
</div>
