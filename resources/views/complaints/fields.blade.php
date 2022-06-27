@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
    
    <div class="row">
        
            <div class="col-md-6">
            
            <!-- Name Field -->
            <div class="form-group row ">
                <div class="col-md-12">
               {!! Form::label('name', trans("lang.complaint_name"), ['class' => 'col-3 control-label text-left']) !!}
               {!! Form::text('name', null,  ['class' => 'form-control', 'readonly' => 'true', 'placeholder'=>  trans("lang.complaint_name_placeholder")]) !!}
                </div>
            </div>
            

            <!-- Phone Field -->
            <div class="form-group row ">
                <div class="col-md-12">
                {!! Form::label('phone', trans("lang.complaint_phone"), ['class' => 'col-3 control-label text-left']) !!}
                {!! Form::text('phone', null,  ['class' => 'form-control', 'readonly' => 'true', 'placeholder'=>  trans("lang.complaint_phone_placeholder")]) !!}
                </div>
            </div>
            
             <!-- staff Field -->
            <div class="form-group row ">
                <div class="col-md-12">
                {!! Form::label('staff_id', trans("lang.complaint_staff"), ['class' => 'col-3 control-label text-left required']) !!}
                {!! Form::select('staff_id', $staffs ,null,  ['class' => 'select2 form-control']) !!}
                </div>
            </div>
            
            <div class="form-group row ">
                <div class="col-md-12">
                {!! Form::label('feedback', trans("lang.complaint_feedback"), ['class' => 'col-3 control-label text-left']) !!}
               {!! Form::textarea('feedback', null,  ['class' => 'form-control', 'placeholder'=>  trans("lang.complaint_feedback_placeholder")]) !!}
                </div>
            </div>

        </div>
            
        <div class="col-md-6">
            <!-- Email Field -->
            <div class="form-group row ">
                <div class="col-md-12">
                {!! Form::label('email', trans("lang.complaint_email"), ['class' => 'col-3 control-label text-left']) !!}
                {!! Form::text('email', null,  ['class' => 'form-control', 'readonly' => 'true', 'placeholder'=>  trans("lang.complaint_email_placeholder")]) !!}
                </div>
            </div>
            
              <!-- Complaints Field -->
             <div class="form-group row ">
                 <div class="col-md-12">
               {!! Form::label('complaints', trans("lang.complaint"), ['class' => 'col-3 control-label text-left']) !!}
               {!! Form::text('complaints', null,  ['class' => 'form-control', 'readonly' => 'true', 'placeholder'=>  trans("lang.complaint_name_placeholder")]) !!}
               </div>
            </div>
            
               <!-- staff Field -->
            <div class="form-group row ">
                <div class="col-md-12">
                {!! Form::label('staff_members[]', trans("lang.complaint_add_members"), ['class' => 'col-3 control-label text-left required']) !!}
                 {!! Form::select('staff_members[]',$staff_members, $selected_staff_members, ['class' => 'select2 form-control', 'multiple']) !!}
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
  @if($complaints->status=='1')
  <button type="submit" class="btn btn-{{setting('theme_color')}}" disabled><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.complaint')}}</button>
  @else
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.complaint')}}</button>
  @endif
  <a href="{!! route('complaints.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
