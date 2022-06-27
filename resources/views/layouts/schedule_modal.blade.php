


      <div class="modal-body">

          @push('css_lib')
          @include('layouts.datatables_css')
          @endpush

          <h4>{!! trans('lang.email_notifications_schedule_notifications') !!}</h4>
        <br>
       @if(isset($subject) && $subject!='')
          <input type="hidden" name="subject" id="subject" value="{{$subject}}">  
          @endif
       @if(isset($description) && $description!='')
          <input type="hidden" name="description" id="description" value="{{$description}}">  
          @endif
       @if(isset($customers) && $customers!='')
          <input type="hidden" name="customers" id="customers" value="{{$customers}}">  
          @endif

         @if(isset($customer_groups) && $customer_groups!='')
          <input type="hidden" name="customer_groups" id="customer_groups" value="{{$customer_groups}}">  
          @endif

          @if(isset($notification_id) && $notification_id!='')
          <input type="hidden" name="notification_id" id="notification_id" value="{{$notification_id}}">  
          @endif
      <div class="form-group row">
     @if(isset($schedule_date) && $schedule_date!='')
         {!! Form::label('schedule_date', trans("lang.email_notifications_schedule_date"), ['class' => 'col-2 control-label']) !!}
         <div class="col-6">
          {!! Form::text('schedule_date',$schedule_date, [
            'class' => 'form-control',
            'id' => 'schedule_date',
          ]) !!}
      @else
          {!! Form::label('schedule_date', trans("lang.email_notifications_schedule_date"), ['class' => 'col-2 control-label']) !!}
         <div class="col-6">
          {!! Form::text('schedule_date',null, [
            'class' => 'form-control',
            'id' => 'schedule_date',
          ]) !!}
       @endif
       </div>
      </div>

          @push('scripts_lib')
          @include('layouts.datatables_js')
          @endpush

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm add-items">Done</button>
      </div>

     <script type="text/javascript">
    $(function () {
    var today = new Date();
    var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
    var time = (today.getHours()) + ":" + today.getMinutes();
    var dateTime = date+' '+time;
    $("#schedule_date").datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        autoclose: true,
        todayBtn: true,
        startDate: dateTime
    });
});
</script>