@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
    <div class="row">
        <div class="col-md-6">

        <input type="hidden" id="cat_count" value="{{$category_count+1}}">
        
         <!-- Department Id Field -->
        <div class="form-group ">
            {!! Form::label('department_id', trans("lang.department"),['class' => ' control-label text-left required']) !!}
            {!! Form::select('department_id', $departments, null, ['class' => 'select2 form-control department_id']) !!}
            <!--<div class="form-text text-muted">{{ trans("lang.product_category_id_help") }}</div>-->
        </div>
    
        <!-- Category Id Field -->
        <div class="form-group ">
            {!! Form::label('category_id', trans("lang.product_category_id"),['class' => ' control-label text-left required']) !!}
            {!! Form::select('category_id', $category, $categorySelected, ['class' => 'select2 form-control category_id','id'=>'category']) !!}
            <!--<div class="form-text text-muted">{{ trans("lang.product_category_id_help") }}</div>-->
        </div>
        <!-- Name Field -->
        <div class="form-group ">
          {!! Form::label('name', trans("lang.subcategory_name"), ['class' => ' control-label text-left required']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.subcategory_name_placeholder")]) !!}
            <!--<div class="form-text text-muted">-->
            <!--  {{ trans("lang.subcategory_name_help") }}-->
            <!--</div>-->
        </div>

        <!-- Short Name Field -->
        <div class="form-group ">
            {!! Form::label('short_name', "Short Name", ['class' => ' control-label text-left required']) !!}
            {!! Form::text('short_name', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
        </div>
       
        <!-- Image Field -->
        <div class="form-group ">
          {!! Form::label('image', trans("lang.subcategory_image"), ['class' => ' control-label text-left']) !!}
            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
              <input type="hidden" name="image">
            </div>
            <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
            <!--<div class="form-text text-muted w-50">-->
            <!--  {{ trans("lang.subcategory_image_help") }}-->
            <!--</div>-->
        </div>
        @prepend('scripts')
        <script type="text/javascript">
            var var15866134771240834480ble = '';
            @if(isset($subcategory) && $subcategory->hasMedia('image'))
            var15866134771240834480ble = {
                name: "{!! $subcategory->getFirstMedia('image')->name !!}",
                size: "{!! $subcategory->getFirstMedia('image')->size !!}",
                type: "{!! $subcategory->getFirstMedia('image')->mime_type !!}",
                collection_name: "{!! $subcategory->getFirstMedia('image')->collection_name !!}"};
            @endif
            var dz_var15866134771240834480ble = $(".dropzone.image").dropzone({
                url: "{!!url('uploads/store')!!}",
                addRemoveLinks: true,
                maxFiles: 1,
                init: function () {
                @if(isset($subcategory) && $subcategory->hasMedia('image'))
                    dzInit(this,var15866134771240834480ble,'{!! url($subcategory->getFirstMediaUrl('image','thumb')) !!}')
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
                        file, var15866134771240834480ble, '{!! url("subcategory/remove-media") !!}',
                        'image', '{!! isset($subcategory) ? $subcategory->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                    );
                }
            });
            dz_var15866134771240834480ble[0].mockFile = var15866134771240834480ble;
            dropzoneFields['image'] = dz_var15866134771240834480ble;
        </script>
        @endprepend
        
        <div class="form-group row">
            {!! Form::label('active', trans("lang.subcategory_active"),['class' => 'col-md-2 control-label text-left']) !!}
        <div class="col-md-10 checkbox icheck">
            <label class="form-check-inline">
                {!! Form::hidden('active', 0) !!}
                {!! Form::checkbox('active', 1, 1) !!}
            </label>
        </div>
        </div>
    
        </div>
        <div class="col-md-6">
            <!-- Description Field -->
            <div class="form-group ">
              {!! Form::label('description', trans("lang.subcategory_description"), ['class' => ' control-label text-left required']) !!}
                {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
                 trans("lang.subcategory_description_placeholder")  ]) !!}
                <!--<div class="form-text text-muted">{{ trans("lang.subcategory_description_help") }}</div>-->
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.subcategory')}}</button>
  <a href="{!! route('subcategory.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
 
<script type="text/javascript">

$(document).ready(function () {
$('#department_id').on('change',function(e) {
var depart_id = e.target.value;
$.ajax({
url:"{{ route('products.showDepartments') }}",
type:"POST",
data: {
depart_id: depart_id,
"_token": "{{ csrf_token() }}",
},
success:function (data) {
$('#category').empty();
$('#category').append('<option value="">Please Select</option>');
$.each(data.departments[0].category,function(index,categorys){
$('#category').append('<option value="'+categorys.id+'">'+categorys.name+'</option>');
})
}
})
});

});
</script>