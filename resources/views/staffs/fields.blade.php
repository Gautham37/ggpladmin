
<div class="col-md-12 column custom-from-css">
    <div class="row">

        <div class="col-md-4">
            <!-- Name Field -->
            <div class="form-group">
            {!! Form::label('name', trans("lang.user_name"), ['class' => ' control-label text-left required']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.user_name_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
          {{ trans("lang.staffdepartment_name_help") }}
                    </div> -->
            </div>



        </div>
         <div class="col-md-4">
            <!-- Prefix Field -->
            <div class="form-group">
            {!! Form::label('email', trans("lang.user_email"), ['class' => ' control-label text-left required']) !!}
            {!! Form::text('email', null,  ['class' => 'form-control','placeholder'=>  trans("lang.user_email_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
          {{ trans("lang.staffdepartment_name_help") }}
                    </div> -->
            </div>

        </div>
        
         <div class="col-md-4">
            <!-- Prefix Field -->
            <div class="form-group">
            {!! Form::label('password', trans("lang.user_password"), ['class' => ' control-label text-left required']) !!}
            {!! Form::text('password', null,  ['class' => 'form-control','placeholder'=>  trans("lang.user_password_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
          {{ trans("lang.staffdepartment_name_help") }}
                    </div> -->
            </div>

        </div>
        
         <div class="col-md-4">
            <!-- Prefix Field -->
            <div class="form-group">
            {!! Form::label('date_of_birth', trans("lang.date_of_birth"), ['class' => ' control-label text-left required']) !!}
            {!! Form::text('date_of_birth', null,  ['class' => 'form-control datepicker','placeholder'=>  trans("lang.user_date_of_birth_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
          {{ trans("lang.staffdepartment_name_help") }}
                    </div> -->
            </div>
        </div>

          
        @can('permissions.index')
        <!-- Roles Field -->
     <!--  <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('roles[]', trans("lang.user_role_id"),['class' => ' control-label text-left']) !!}
            {!! Form::select('roles[]', $role, $rolesSelected, ['class' => 'select2 form-control']) !!}
        </div>
        </div>-->
        @endcan
        
        <!-- Department Field -->
        <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('department_id', trans("lang.staffdepartment"),['class' => ' control-label text-left']) !!}
            {!! Form::select('department_id', $department, $departmentSelected, ['class' => 'select2 form-control']) !!}
            <!-- <div class="form-text text-muted">{{ trans("lang.user_role_id_help") }}</div> -->
        </div>
        </div>
        
        
         <!-- Designation Field -->
        <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('designation_id', trans("lang.staffdesignation"),['class' => ' control-label text-left']) !!}
            {!! Form::select('designation_id', $designation, $designationSelected, ['class' => 'select2 form-control']) !!}
            <!-- <div class="form-text text-muted">{{ trans("lang.user_role_id_help") }}</div> -->
        </div>
        </div>
        
         <!-- Address Field -->
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('address_line_1', trans("lang.market_address_line_1"), ['class' => 'control-label text-right']) !!}
             @if(isset($user))
            {!! Form::text('address_line_1', $staff->address_line_1,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_line_1_placeholder")]) !!}
              @else
            {!! Form::text('address_line_1', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_line_1_placeholder")]) !!}
             @endif
            <div class="form-text text-muted">
                <!-- {{ trans("lang.market_address_line_1_help") }} -->
            </div>
        </div>
    </div>
    
    <!-- Address Field -->
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('address_line_2', trans("lang.market_address_line_2"), ['class' => 'control-label text-right']) !!}
             @if(isset($user))
            {!! Form::text('address_line_2', $staff->address_line_2,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_line_2_placeholder")]) !!}
              @else
            {!! Form::text('address_line_2', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_address_line_2_placeholder")]) !!}
             @endif
            <div class="form-text text-muted">
                <!-- {{ trans("lang.market_address_line_2_help") }} -->
            </div>
        </div>
    </div>
    
    <!-- City Field -->
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('city', trans("lang.market_city"), ['class' => 'control-label text-right']) !!}
             @if(isset($user))
            {!! Form::text('city', $staff->city,  ['class' => 'form-control','placeholder'=>  trans("lang.market_city_placeholder")]) !!}
             @else
            {!! Form::text('city', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_city_placeholder")]) !!}
             @endif
            <div class="form-text text-muted">
                <!-- {{ trans("lang.market_city_help") }} -->
            </div>
        </div>
    </div>
    
    <!-- State Field -->
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('state', trans("lang.market_state"), ['class' => 'control-label text-right']) !!}
             @if(isset($user))
            {!! Form::text('state', $staff->state,  ['class' => 'form-control','placeholder'=>  trans("lang.market_state_placeholder")]) !!}
            @else
            {!! Form::text('state', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_state_placeholder")]) !!}
             @endif
            <div class="form-text text-muted">
                <!-- {{ trans("lang.market_state_help") }} -->
            </div>
        </div>
    </div>
    
    <!-- Pincode Field -->
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('pincode', trans("lang.market_pincode"), ['class' => 'control-label text-right']) !!}
             @if(isset($user))
            {!! Form::text('pincode', $staff->pincode,  ['class' => 'form-control','placeholder'=>  trans("lang.market_pincode_placeholder")]) !!}
            @else
            {!! Form::text('pincode', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_pincode_placeholder")]) !!}
            @endif
            <div class="form-text text-muted">
                <!-- {{ trans("lang.market_pincode_help") }} -->
            </div>
        </div>
    </div>

    
    <div class="col-md-4">
            <!-- Prefix Field -->
            <div class="form-group">
            {!! Form::label('date_of_joining', trans("lang.date_of_joining"), ['class' => ' control-label text-left']) !!}
             @if(isset($user))
            {!! Form::text('date_of_joining', $staff->date_of_joining,  ['class' => 'form-control datepicker','placeholder'=>  trans("lang.date_of_joining_placeholder")]) !!}
             @else
            {!! Form::text('date_of_joining', null,  ['class' => 'form-control datepicker','placeholder'=>  trans("lang.date_of_joining_placeholder")]) !!}
            @endif
            <!-- <div class="form-text text-muted">
          {{ trans("lang.staffdepartment_name_help") }}
                    </div> -->
            </div>
        </div>
        
        
        <div class="col-md-4">
            <!-- Prefix Field -->
            <div class="form-group">
            {!! Form::label('date_of_relieving', trans("lang.date_of_relieving"), ['class' => ' control-label text-left']) !!}
             @if(isset($user))
            {!! Form::text('date_of_relieving', $staff->date_of_relieving,  ['class' => 'form-control datepicker','placeholder'=>  trans("lang.date_of_relieving_placeholder")]) !!}
            @else
            {!! Form::text('date_of_relieving', null,  ['class' => 'form-control datepicker','placeholder'=>  trans("lang.date_of_relieving_placeholder")]) !!}
            @endif
            <!-- <div class="form-text text-muted">
          {{ trans("lang.staffdepartment_name_help") }}
                    </div> -->
            </div>
        </div>
        
      
         <!-- Gender Field -->
        <div class="col-md-4"> 
        <div class="form-group">
            {!! Form::label('gender', trans("lang.user_gender"), ['class' => 'control-label text-right']) !!}
            {!! Form::select('gender', [null => 'Please Select', 'male' => 'Male', 'female' => 'Female', 'others' => 'Others'], null, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">
                <!-- {{ trans("lang.user_gender_help") }} -->
            </div>
        </div>
    </div>

           <!-- Description Field -->
    <div class="col-md-4">
        <div class="form-group">
            
            {!! Form::label('description', trans("lang.staff_description"), ['class' => 'control-label text-right']) !!}
             @if(isset($user))
            {!! Form::textarea('description',$staff->description,  ['class' => 'form-control','placeholder'=>  trans("lang.staff_description_placeholder")]) !!}
            @else
            {!! Form::textarea('description',null ,  ['class' => 'form-control','placeholder'=>  trans("lang.staff_description_placeholder")]) !!}
            @endif
            <div class="form-text text-muted">
                <!-- {{ trans("lang.market_pincode_help") }} -->
            </div>
        </div>
    </div>
        
      
              <!-- $FIELD_NAME_TITLE$ Field -->
        <div class="form-group col-md-4">
            {!! Form::label('avatar', trans("lang.user_avatar"), ['class' => ' control-label text-left']) !!}
            <div style="width: 100%" class="dropzone avatar" id="avatar" data-field="avatar">
                <input type="hidden" name="avatar">
            </div>
            <a href="#loadMediaModal" data-dropzone="avatar" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
            <!-- <div class="form-text text-muted w-50">
                {{ trans("lang.user_avatar_help") }}
            </div> -->
        </div>
        @prepend('scripts')
        <script type="text/javascript">
            var user_avatar = '';
            @if(isset($user) && $user->hasMedia('avatar'))
                user_avatar = {
                name: "{!! $user->getFirstMedia('avatar')->name !!}",
                size: "{!! $user->getFirstMedia('avatar')->size !!}",
                type: "{!! $user->getFirstMedia('avatar')->mime_type !!}",
                collection_name: "{!! $user->getFirstMedia('avatar')->collection_name !!}"
            };
                    @endif
            var dz_user_avatar = $(".dropzone.avatar").dropzone({
                    url: "{!!url('uploads/store')!!}",
                    addRemoveLinks: true,
                    maxFiles: 1,
                    init: function () {
                        @if(isset($user) && $user->hasMedia('avatar'))
                        dzInit(this, user_avatar, '{!! url($user->getFirstMediaUrl('avatar','thumb')) !!}')
                        @endif
                    },
                    accept: function (file, done) {
                        dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                    },
                    sending: function (file, xhr, formData) {
                        dzSending(this, file, formData, '{!! csrf_token() !!}');
                    },
                    maxfilesexceeded: function (file) {
                        dz_user_avatar[0].mockFile = '';
                        dzMaxfile(this, file);
                    },
                    complete: function (file) {
                        dzComplete(this, file, user_avatar, dz_user_avatar[0].mockFile);
                        dz_user_avatar[0].mockFile = file;
                    },
                    removedfile: function (file) {
                        dzRemoveFile(
                            file, user_avatar, '{!! url("users/remove-media") !!}',
                            'avatar', '{!! isset($user) ? $user->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                        );
                    }
                });
            dz_user_avatar[0].mockFile = user_avatar;
            dropzoneFields['avatar'] = dz_user_avatar;
        </script>
    @endprepend
    
     <!-- Phone Field -->
    <div class="col-md-4">    
        <div class="form-group">
            {!! Form::label('phone', trans("lang.staff_phone"), ['class' => 'control-label text-right']) !!}
             @if(isset($user))
            {!! Form::text('phone', $staff->phone,  ['class' => 'form-control','placeholder'=>  trans("lang.staff_phone_placeholder")]) !!}
             @else
            {!! Form::text('phone', null,  ['class' => 'form-control','placeholder'=>  trans("lang.staff_phone_placeholder")]) !!}
             @endif
            <div class="form-text text-muted">
                <!-- {{ trans("lang.market_phone_help") }} -->
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
            <div class="column" style="margin-top:30px;">
                <div class="form-group row ">
                    {!! Form::label('active', trans("lang.active"),['class' => 'col-md-2 control-label text-left']) !!}
                    <div class="checkbox icheck">
                        <label class="col-8 ml-2 form-check-inline">
                            {!! Form::hidden('active', 0) !!}
                            {!! Form::checkbox('active', 1, 1) !!}
                        </label>
                    </div>
                </div>
            </div>
        </div>
     
    </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.staff_plural')}}</button>
    <a href="{!! route('staffs.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
 
<script type="text/javascript">

$(document).ready(function () {
$('#department_id').on('change',function(e) {
var depart_id = e.target.value;
$.ajax({
url:"{{ route('staffs.showStaffDepartments') }}",
type:"POST",
data: {
depart_id: depart_id,
"_token": "{{ csrf_token() }}",
},
success:function (data) {
$('#designation_id').empty();
$('#designation_id').append('<option value="">Please Select</option>');
$.each(data.departments[0].staffdesignations,function(index,designations){
$('#designation_id').append('<option value="'+designations.id+'">'+designations.name+'</option>');
})
}
})
});

});
</script>
