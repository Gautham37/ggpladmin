@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
    <div class="row">

        <div class="col-md-5">
        <input type="hidden" name="notification_id" value="{{$id}}" id="notification_id">
        <!-- Name Field -->
        <div class="form-group ">
          {!! Form::label('name', trans("lang.email_notifications_subject"), ['class' => ' control-label text-left']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','id' => 'name','placeholder'=>  trans("lang.email_notifications_subject_placeholder")]) !!}
            <!--<div class="form-text text-muted">-->
            <!--  {{ trans("lang.email_notifications_subject_help") }}-->
            <!--</div>-->
        </div>
        <div class="form-group ">
            {!! Form::label('customer_groups', trans("lang.email_notifications_customer_groups"), ['class' => ' control-label text-left']) !!}
            <select name="customer_groups" class="select2 form-control customer_groups">
              <option value="">{{ trans("lang.email_notifications_customer_groups") }}</option>
               <option value="0" {{ $emailnotifications->customer_groups == 0  ? 'selected' : ''}}>All</option>
            @foreach($customer_groups as $key => $value1)
                    <option value="{{$value1->id}}" {{ $emailnotifications->customer_groups == $value1->id  ? 'selected' : ''}}>{{$value1->name}}</option>
            @endforeach
            </select>
        </div>
        <?php
        if($emailnotifications->customers!=''){
          $style="";
        }else{
           $style="style='display:none;'";
         }
        $selected_customers = explode(',',$emailnotifications->customers);
         ?>
        
        <div class="form-group customers_display" <?=$style?>>
            {!! Form::label('customers', trans("lang.email_notifications_recipients"), ['class' => ' control-label text-left']) !!}
            <select multiple="multiple" name="customers[]" class="select2 form-control customers">
                <option value="">{{trans("lang.email_notifications_recipients")}}</option>
                <option value="0" {{ $emailnotifications->customers == 0  ? 'selected' : ''}}>All</option>
             @foreach($markets as $key => $value)
                    <option value="{{$value->id}}" {{ in_array($value->id, $selected_customers) ? "selected" : '' }}>{{$value->name}}</option>
            @endforeach 
            </select>
        </div>
        </div>
        <div class="col-md-5">
        <!-- Description Field -->
        <div class="form-group ">
          {!! Form::label('description', trans("lang.email_notifications_message"), ['class' => ' control-label text-left']) !!}
            {!! Form::textarea('description', null, ['class' => 'form-control','id' => 'description','placeholder'=>
             trans("lang.email_notifications_message_placeholder")  ]) !!}
            <!--<div class="form-text text-muted">{{ trans("lang.email_notifications_message_help") }}</div>-->
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
<style>
  .select
  {
    width:20px;
    height:20px;
    margin-right:5px;
  }
</style>