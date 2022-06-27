@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
<!-- Name Field -->
<div class="form-group row ">
  {!! Form::label('name', trans("lang.testimonial_name"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.testimonial_name_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.testimonial_name_help") }}
    </div>
  </div>
</div>

<!-- Name Field -->
<div class="form-group row ">
  {!! Form::label('rating', trans("lang.testimonial_rating"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::number('rating', null,  ['class' => 'form-control', 'max' => '5', 'min' => '1', 'placeholder'=>  trans("lang.testimonial_rating_placeholder")]) !!}
    <div class="form-text text-muted">
      {{ trans("lang.testimonial_rating_help") }}
    </div>
  </div>
</div>

<!-- Description Field -->
<div class="form-group row ">
  {!! Form::label('description', trans("lang.testimonial_description"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
     trans("lang.testimonial_description_placeholder")  ]) !!}
    <div class="form-text text-muted">{{ trans("lang.testimonial_description_help") }}</div>
  </div>
</div>
</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

<?php /* ?>
<!-- Image Field -->
<div class="form-group row">
  {!! Form::label('image', trans("lang.testimonial_image"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <div style="width: 100%" class="dropzone image" id="image" data-field="image">
      <input type="hidden" name="image">
    </div>
    <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
    <div class="form-text text-muted w-50">
      {{ trans("lang.testimonial_image_help") }}
    </div>
  </div>
</div>
@prepend('scripts')
<script type="text/javascript">
    var var15866134771240834480ble = '';
    @if(isset($testimonial) && $testimonial->hasMedia('image'))
    var15866134771240834480ble = {
        name: "{!! $testimonial->getFirstMedia('image')->name !!}",
        size: "{!! $testimonial->getFirstMedia('image')->size !!}",
        type: "{!! $testimonial->getFirstMedia('image')->mime_type !!}",
        collection_name: "{!! $testimonial->getFirstMedia('image')->collection_name !!}"};
    @endif
    var dz_var15866134771240834480ble = $(".dropzone.image").dropzone({
        url: "{!!url('uploads/store')!!}",
        addRemoveLinks: true,
        maxFiles: 1,
        init: function () {
        @if(isset($testimonial) && $testimonial->hasMedia('image'))
            dzInit(this,var15866134771240834480ble,'{!! url($testimonial->getFirstMediaUrl('image','thumb')) !!}')
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
                file, var15866134771240834480ble, '{!! url("testimonial/remove-media") !!}',
                'image', '{!! isset($testimonial) ? $testimonial->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
            );
        }
    });
    dz_var15866134771240834480ble[0].mockFile = var15866134771240834480ble;
    dropzoneFields['image'] = dz_var15866134771240834480ble;
</script>
@endprepend
<?php /*/ ?>

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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.website_testimonials')}}</button>
  <a href="{!! route('categories.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
