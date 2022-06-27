@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-4 column custom-from-css">
    <div class="row">
        <!-- Name Field -->
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('name', trans("lang.department_name"), ['class' => 'col-3 control-label text-left required']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.department_name_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
              {{ trans("lang.category_name_help") }}
            </div> -->
          </div>
        </div>

        <!-- Image Field -->
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('image', trans("lang.department_image"), ['class' => 'col-3 control-label text-left']) !!}
            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
              <input type="hidden" name="image">
            </div>
            <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
            <!-- <div class="form-text text-muted w-50">
              {{ trans("lang.category_image_help") }}
            </div> -->
          </div>
        </div>
        @prepend('scripts')
        <script type="text/javascript">
            var var15866134771240834480ble = '';
            @if(isset($departments) && $departments->hasMedia('image'))
            var15866134771240834480ble = {
                name: "{!! $departments->getFirstMedia('image')->name !!}",
                size: "{!! $departments->getFirstMedia('image')->size !!}",
                type: "{!! $departments->getFirstMedia('image')->mime_type !!}",
                collection_name: "{!! $departments->getFirstMedia('image')->collection_name !!}"};
            @endif
            var dz_var15866134771240834480ble = $(".dropzone.image").dropzone({
                url: "{!!url('uploads/store')!!}",
                addRemoveLinks: true,
                maxFiles: 1,
                init: function () {
                @if(isset($departments) && $departments->hasMedia('image'))
                    dzInit(this,var15866134771240834480ble,'{!! url($departments->getFirstMediaUrl('image','thumb')) !!}')
                @endif
                },
                accept: function(file, done) {
                    dzAccept(file,done,this.element,"{!!config('medialibrary.icons_folder')!!}");
                },
                sending: function (file, xhr, formData) {
                    dzSending(this,file,formData,'{!! csrf_token() !!}');
                },
                maxfilesexceeded: function (file) {
                    dz_var15866134771240834480ble[0].mockFile = '';
                    dzMaxfile(this,file);
                },
                complete: function (file) {
                    dzComplete(this, file, var15866134771240834480ble, dz_var15866134771240834480ble[0].mockFile);
                    dz_var15866134771240834480ble[0].mockFile = file;
                },
                removedfile: function (file) {
                    dzRemoveFile(
                        file, var15866134771240834480ble, '{!! url("departments/remove-media") !!}',
                        'image', '{!! isset($departments) ? $departments->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                    );
                }
            });
            dz_var15866134771240834480ble[0].mockFile = var15866134771240834480ble;
            dropzoneFields['image'] = dz_var15866134771240834480ble;
        </script>
        @endprepend

        <div class="col-md-12">
        <div class="form-group row">
            {!! Form::label('active', trans("lang.department_active"),['class' => 'col-md-2 control-label text-left']) !!}
            <div class="col-md-4 checkbox icheck">
                <label class="form-check-inline">
                    {!! Form::hidden('active', 0) !!}
                    {!! Form::checkbox('active', 1, 1) !!}
                </label>
            </div>
        </div>
        </div>

</div>  
</div>

<div class="col-md-8 column custom-from-css">
    <div class="row">
        <!-- Description Field -->
        <div class="col-md-12">
          <div class="form-group">
            {!! Form::label('description', trans("lang.department_description"), ['class' => 'col-3 control-label text-left required ']) !!}
            {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
             trans("lang.department_description_placeholder")  ]) !!}
            <!-- <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div> -->
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.department')}}</button>
  <a href="{!! route('departments.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
