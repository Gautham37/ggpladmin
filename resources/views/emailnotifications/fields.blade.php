
<div class="col-md-12 column custom-from-css">
  <div class="row">

    <div class="col-md-6">
      <div class="form-group ">
          {!! Form::label('subject', 'Subject', ['class' => 'col-3 control-label text-left']) !!}
          {!! Form::text('subject', null,  ['class' => 'form-control','placeholder'=>  'Email Subject']) !!}
      </div>
    </div>  
    <div class="col-md-3">
      <div class="form-group ">
          {!! Form::label('party_type_id', 'Party Type', ['class' => ' control-label text-left']) !!}
          {!! Form::select('party_type_id', $party_types, null, ['class' => 'select2 form-control']) !!}
      </div>
    </div>
    <div class="col-md-3">  
      <div class="form-group">
          {!! Form::label('party_sub_type_id', 'Party Sub Types', ['class' => ' control-label text-left']) !!}
          {!! Form::select('party_sub_type_id', [], null, ['class' => 'select2 form-control party_sub_type_id']) !!}
      </div>
    </div>
    <!-- <div class="col-md-3">  
      <div class="form-group markets-section" style="display:none;">
          {!! Form::label('market_id', 'Parties', ['class' => ' control-label text-left']) !!}
          {!! Form::select('market_id', $markets, null, ['class' => 'select2 form-control','multiple'=>'multiple']) !!}
      </div>
    </div> -->
    <div class="col-md-3">
      <div class="form-group">
          {!! Form::label('type', 'Email Type', ['class' => ' control-label text-left']) !!}
          {!! Form::select('type', [null=>'Please Select','save'=>'Save Draft','send'=>'Send','schedule'=>'Schedule'], null, ['class' => 'select2 form-control']) !!}
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group schedule-section" style="display:none;">
          {!! Form::label('schedule_date', 'Shedule Date', ['class' => 'control-label text-left']) !!}
          {!! Form::text('schedule_date', null,  ['class' => 'form-control']) !!}
      </div>
    </div>


    <div class="col-md-12">

      <!-- Description Field -->
      <div class="form-group ">
        {!! Form::label('message', 'Message', ['class' => ' control-label text-left']) !!}
        {!! Form::textarea('message', null, ['class' => 'form-control','id' => 'message']) !!}
      </div>

    </div>

  </div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">

  <button type="submit" class="btn btn-{{setting('theme_color')}}" >
    <i class="fa fa-save"></i> Save Notification
  </button>

  <a href="{!! route('emailnotifications.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Cancel</a>

</div>

@push('scripts_lib')

<script>


  $("#party_type_id").change(function(){
    var party_type_id = $(this).val();
    $.ajax({
        url: "{{ route('markets.showPartySubTypes') }}",
        type: "POST",
        data: {
            type: party_type_id,
            "_token": "{{ csrf_token() }}",
        },
        success: function(data) {
            $('.party_sub_type_id').empty();
            $('.party_sub_type_id').append('<option value="">Please Select</option>');
            $.each(data.party_sub_types[0].party_sub_types, function(index, party_sub_type) {
                $('.party_sub_type_id').append('<option value="' + party_sub_type.id + '">' + party_sub_type.name + '</option>');
            })
        }
    })
  });

  $('#party_type_id').trigger('change');

  $("#type").change(function(){
    var type = $(this).val();
    if(type == 'schedule'){
      $('.schedule-section').show();
    } else {
      $('.schedule-section').hide();
      $('#schedule_date').val('');
    }
  });

  $("#schedule_date").datetimepicker({
      format: 'yyyy-mm-dd hh:ii',
      autoclose: true,
      todayBtn: true
  });
</script>

@endpush