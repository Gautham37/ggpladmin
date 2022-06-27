
    <div class="modal-header">
        <h6 class="modal-title" id="myModalLabel">Edit {{$row->name}} Attendance Details</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @if($type=='add')
        {!! Form::open(['route' => 'attendance.store', 'class' => 'attendance-mark-form']) !!}
    @else 
        {!! Form::open(['route' => 'attendance.store', 'class' => 'attendance-mark-form']) !!}
    @endif
    <div class="modal-body">
        
        <div class="row">
            
            {!! Form::hidden('user_id', $userid,['id' => 'userID']) !!}
            {!! Form::hidden('attendance_date', $date) !!}

            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('clock_in_time', 'Clock In', ['class' => 'control-label']) !!}
                    {!! Form::text('clock_in_time', null,  ['class' => 'form-control timepicker1',]) !!}
                </div>
            </div>
    
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('clock_in_ip', 'Clock In IP', ['class' => 'control-label']) !!}
                    {!! Form::text('clock_in_ip', Request::ip(),  ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    {!! Form::label('late', 'Late', ['class' => 'control-label']) !!}<br>
                    <label class="custom-switch ">
                        {{ Form::checkbox('late','yes', null,['class'=>'custom-switch-input']) }}
                        <span class="custom-switch-indicator"></span>
                    </label>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="form-group">
                    {!! Form::label('attendance_type', 'Type', ['class' => 'control-label']) !!}
                    {!! Form::select('attendance_type', ['regular' => 'Regular', 'paid_leave' => 'Paid Leave'], null, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('clock_out_time', 'Clock Out', ['class' => 'control-label']) !!}
                    {!! Form::text('clock_out_time', null,  ['class' => 'form-control timepicker1',]) !!}
                </div>
            </div>
    
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('clock_out_ip', 'Clock Out IP', ['class' => 'control-label']) !!}
                    {!! Form::text('clock_out_ip', Request::ip(),  ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    {!! Form::label('half_day', 'Half Day', ['class' => 'control-label']) !!}<br>
                    <label class="custom-switch ">
                        {{ Form::checkbox('half_day','yes', null,['class'=>'custom-switch-input']) }}
                        <span class="custom-switch-indicator"></span>
                    </label>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    {!! Form::label('working_from', 'Work From', ['class' => 'control-label']) !!}
                    {!! Form::text('working_from', 'Office',  ['class' => 'form-control']) !!}
                </div>
            </div>

        </div>
        <style type="text/css">
            .bootstrap-timepicker-widget {
                z-index: 9999999999 !important;
            }
        </style>
        <script type="text/javascript">
          $('.timepicker1').timepicker({
            showInputs: false
          });
        </script>

    </div> 

    <div class="modal-footer">

        <button type="submit" class="btn btn-primary attendance-mark-submit">Save</button>
        <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>

    </div>

    {!! Form::close() !!}


<script>
    $('.attendance-mark-form').validate({ // initialize the plugin
        rules: {
            userID: {
                required: true
            },
            clock_in_time: {
                required: true
            },
            clock_in_ip: {
                required: true
            },
            working_from: {
                required: true
            }
        },
        errorElement: "div",
        errorPlacement: function(error, element) { },
        success: function (error) {
            error.remove();
        },
        submitHandler: function(form) {
            
            $('.attendance-mark-submit').html('<i class="fa fa-spinner fa-spin"></i>');
            $('.attendance-mark-submit').attr("disabled", true);
            
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize()         
            }).done(function(results) {
                
                $('.attendance-mark-submit').html('Save');
                $('.attendance-mark-submit').attr("disabled", false);
                $('#attendanceModal').modal('hide');
    
                iziToast.success({
                    backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                    messageColor: '#fff',
                    timeout: 3000, 
                    icon: 'fa fa-check', 
                    position: "topRight", 
                    iconColor:'#fff',
                    message: 'Attendance Marked Sucessfully'
                });
                showTable();
            })
            .fail(function(results) {
                
                $('.attendance-mark-submit').html('Save');
                $('.attendance-mark-submit').attr("disabled", false);
                $('#attendanceModal').modal('hide');
                
                iziToast.success({
                    backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                    messageColor: '#fff', 
                    timeout: 3000, 
                    icon: 'fa fa-remove', 
                    position: "topRight", 
                    iconColor:'#fff', 
                    message: results.statusText
                });
                showTable();
            });
        }
    });
</script>
