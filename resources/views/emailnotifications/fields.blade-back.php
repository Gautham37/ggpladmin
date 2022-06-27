@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 80%;max-width: 80%;padding: 0 4px;" class="column">
<!-- Name Field -->
<div class="form-group row ">
  {!! Form::label('name', trans("lang.email_notifications_subject"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('name', null,  ['class' => 'form-control','id' => 'name','placeholder'=>  trans("lang.email_notifications_subject_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.email_notifications_subject_help") }}
    </div>
  </div>
</div>

<!-- Description Field -->
<div class="form-group row ">
  {!! Form::label('description', trans("lang.email_notifications_message"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::textarea('description', null, ['class' => 'form-control','id' => 'description','placeholder'=>
     trans("lang.email_notifications_message_placeholder")  ]) !!}
    <div class="form-text text-muted">{{ trans("lang.email_notifications_message_help") }}</div>
  </div>
</div>


<div class="form-group row ">
    {!! Form::label('customer_groups', trans("lang.email_notifications_customer_groups"), ['class' => 'col-3 control-label text-right']) !!}
 <div class="col-9">
<select name="customer_groups" class="form-control customer_groups">
  <option value="">{{ trans("lang.email_notifications_customer_groups") }}</option>
   <option value="0">All</option>
@foreach($customer_groups as $key => $value1)
        <option value="{{$value1->id}}">{{$value1->name}}</option>
@endforeach
</select>
</div>
</div>


<div class="form-group row customers_display" style="display:none;">
    {!! Form::label('customers', trans("lang.email_notifications_recipients"), ['class' => 'col-3 control-label text-right']) !!}
 <div class="col-9">
<select multiple="multiple" name="customers[]" class="select2 form-control customers">
   
<!-- @foreach($customers as $key => $value)
        <option value="{{$value->id}}">{{$value->name}}</option>
@endforeach -->
</select>
</div>
</div>

</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
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
  <button type="submit" name="save_type" value="1" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-send-o"></i> {{trans('lang.email_notifications_send')}} {{trans('lang.email_notifications')}}</button>
  
  <button type="submit" name="save_type" value="2" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.email_notifications_draft')}} {{trans('lang.email_notifications')}}</button>

  <button onclick="addSchedule();" data-toggle="tooltip" type="button" data-toggle="tooltip" class="btn btn-{{setting('theme_color')}}"> <i class="fa fa-calendar"></i> {{trans('lang.email_notifications_schedule')}} {{trans('lang.email_notifications')}} </button>

  <a href="{!! route('emailnotifications.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <script>
  
//   $('.myselect').on('click', function () {
//     const allCheckedCheckbox = $(this);
//     $('.select').each(function () {
//         $(this).prop('checked', allCheckedCheckbox.prop('checked'));
//     });
// });


 $(".customer_groups").change(function(){

  var customer_groups = $(this).val();

 if(customer_groups!=''){
  
            $.ajax({
                url: "{{ url('/emailnotifications/showrecipients') }}?customer_groups=" + $(this).val(),
                method: 'GET',
                success: function(data) {
                   $('.customers_display').show();
                    $('.customers').html(data.html);

                }
            });

          }else{
             $('.customers_display').hide();
          }
        });
</script>
