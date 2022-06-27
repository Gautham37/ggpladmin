<div class="modal-header">
  <h4 class="modal-title" id="myModalLabel">Edit Wastage Disposal</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

<style type="text/css">
.dropzone {
    box-sizing: border-box;
    min-height: 9.75rem;
    padding: .5rem;
    border: 2px dashed #ced4da;
    border-radius: 0.3rem;
    background: transparent;
    }
.dropzone * {
    box-sizing: border-box; }
.dropzone.dz-clickable {
    cursor: pointer; }
.dropzone.dz-clickable * {
    cursor: default; }
.dropzone.dz-clickable .dz-message {
    cursor: pointer; }
.dropzone.dz-clickable .dz-message * {
    cursor: pointer; }
.dropzone.dz-started .dz-message {
    display: none; }
.dropzone.dz-drag-hover {
    border-color: #28a745;
    background: rgba(40, 167, 69, 0.15); }
.dropzone.dz-drag-hover .dz-message {
    opacity: .5;
    color: #28a745; }
.dropzone.dz-drag-hover .dz-message span:before {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%2328a745' d='M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z'/%3E%3C/svg%3E"); }
.dropzone .dz-message {
    margin-top: 2.25rem;
    font-size: 0.875rem;
    text-align: center;
    line-height: 1;
    color: #ccc; }
.dropzone .dz-message span:before {
    display: block;
    position: relative;
    top: 0;
    left: calc(50% - (2.5rem / 2));
    width: 2.5rem;
    height: 2.5rem;
    content: "";
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23ccc' d='M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z'/%3E%3C/svg%3E");
    background-size: 2.5rem 2.5rem;
    background-position: center center;
    background-repeat: no-repeat; }
.dropzone .dz-preview {
    position: relative;
    display: inline-block;
    min-height: 5rem;
    margin: .5rem;
    vertical-align: top; }
.dropzone .dz-preview:hover {
    z-index: 1000; }
.dropzone .dz-preview:hover .dz-details {
    opacity: 1; }
.dropzone .dz-preview.dz-file-preview .dz-image {
    background-color: #fff; }
.dropzone .dz-preview.dz-file-preview .dz-image:hover {
    background-color: #007bff; }
.dropzone .dz-preview.dz-file-preview .dz-details {
    opacity: 1; }
.dropzone .dz-preview.dz-image-preview {
    background: transparent; }
.dropzone .dz-preview.dz-image-preview .dz-details {
    transition: opacity 200ms linear; }
.dropzone .dz-preview .dz-remove {
    display: block;
    cursor: pointer;
    border: none;
    text-align: center; }
.dropzone .dz-preview .dz-remove:hover {
    text-decoration: underline; }
.dropzone .dz-preview:hover .dz-details {
    opacity: 1; }
.dropzone .dz-preview .dz-details {
    z-index: 20;
    position: absolute;
    top: 0;
    left: 0;
    min-width: 100%;
    max-width: 100%;
    height: auto;
    padding: .5rem;
    font-size: 0.75rem;
    text-align: center;
    line-height: 150%;
    color: white;
    opacity: 0; }
.dropzone .dz-preview .dz-details .dz-size {
    margin-bottom: 4.5rem;
    font-size: 0.875rem; }
.dropzone .dz-preview .dz-details .dz-size,
.dropzone .dz-preview .dz-details .dz-filename {
    white-space: nowrap; }
.dropzone .dz-preview .dz-details .dz-size:hover:before,
.dropzone .dz-preview .dz-details .dz-filename:hover:before {
    content: "";
    margin-left: -100%; }
.dropzone .dz-preview .dz-details .dz-size:hover:after,
.dropzone .dz-preview .dz-details .dz-filename:hover:after {
    content: "";
    margin-right: -100%; }
.dropzone .dz-preview .dz-details .dz-size:hover span,
.dropzone .dz-preview .dz-details .dz-filename:hover span {
    padding: .125rem .375rem;
    background-color: rgba(0, 0, 0, 0.8);
    border-radius: 0.2rem; }
.dropzone .dz-preview .dz-details .dz-size:not(:hover),
.dropzone .dz-preview .dz-details .dz-filename:not(:hover) {
    overflow: hidden;
    text-overflow: ellipsis; }
.dropzone .dz-preview .dz-image {
    overflow: hidden;
    width: 7.5rem;
    height: 7.5rem;
    position: relative;
    display: block;
    z-index: 10; }
.dropzone .dz-preview .dz-image img {
    display: block;
    width: 100%;
    height: 100%;
    }
.dropzone .dz-preview.dz-success .dz-success-mark {
    animation: passing-through 300ms cubic-bezier(0.77, 0, 0.175, 1); }
.dropzone .dz-preview.dz-error .dz-error-mark {
    opacity: 1;
    animation: slide-in 300ms cubic-bezier(0.77, 0, 0.175, 1); }
.dropzone .dz-preview .dz-success-mark,
.dropzone .dz-preview .dz-error-mark {
    pointer-events: none;
    opacity: 0;
    z-index: 500;
    position: absolute;
    display: block;
    top: 50%;
    left: 50%;
    margin-top: -1.5rem;
    margin-left: -1.5rem; }
.dropzone .dz-preview .dz-success-mark svg,
.dropzone .dz-preview .dz-error-mark svg {
    display: block;
    width: 3rem;
    height: 3rem; }
.dropzone .dz-preview .dz-success-mark svg * {
    fill: #28a745;
    fill-opacity: 1; }
.dropzone .dz-preview .dz-error-mark svg * {
    fill: #dc3545;
    fill-opacity: 1; }
.dropzone .dz-preview.dz-processing .dz-progress {
    opacity: 1;
    transition: all 200ms linear; }
.dropzone .dz-preview.dz-complete .dz-progress {
    opacity: 0;
    transition: opacity 400ms ease-in; }
.dropzone .dz-preview:not(.dz-processing) .dz-progress {
    animation: pulse 6s ease infinite; }
.dropzone .dz-preview .dz-progress {
    opacity: 1;
    z-index: 1000;
    pointer-events: none;
    position: absolute;
    left: 50%;
    top: 50%;
    width: 5rem;
    height: 1rem;
    margin-top: -.5rem;
    margin-left: -2.5rem;
    border-radius: 0.2rem;
    background: rgba(255, 255, 255, 0.6);
    -webkit-transform: scale(1);
    overflow: hidden; }
.dropzone .dz-preview .dz-progress .dz-upload {
    background: #28a745;
    background: linear-gradient(to bottom, #28a745, #1e7d34);
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 0;
    transition: width 300ms ease-in-out; }
.dropzone .dz-preview.dz-error .dz-error-message {
    display: block; }
.dropzone .dz-preview.dz-error:hover .dz-error-message {
    opacity: 1;
    pointer-events: auto; }
.dropzone .dz-preview .dz-error-message {
    pointer-events: none;
    z-index: 1000;
    position: absolute;
    display: block;
    display: none;
    top: 8rem;
    left: -.5rem;
    width: 8.5rem;
    padding: .25rem .5rem;
    border-radius: 0.25rem;
    background: #dc3545;
    font-size: 0.875rem;
    color: white;
    opacity: 0;
    transition: opacity 300ms ease; }
.dropzone .dz-preview .dz-error-message:after {
    content: '';
    position: absolute;
    top: -.5rem;
    left: 3.75rem;
    width: 0;
    height: 0;
    border-left: .5rem solid transparent;
    border-right: .5rem solid transparent;
    border-bottom: 0.5rem solid #dc3545; }
</style>

{!! Form::model($wastage_disposal, ['route' => ['wastageDisposal.update', $wastage_disposal->id], 'method' => 'patch', 'class' => 'wastage-disposal-form']) !!}

  <div class="modal-body">

    <div class="row">
      <div class="col-md-12">  
        
        {!! Form::hidden('inventory_id', $wastage_disposal->inventory_id) !!}
        {!! Form::hidden('product_id', $product->id) !!}

        <div class="form-group text-center">
            @if($wastage_disposal->hasMedia('image'))
                <img style="width: auto; height: 150px;" src="{!! url($wastage_disposal->getFirstMediaUrl('image','medium')) !!}">
            @endif
            <h5><b>{{$product->name}}</b></h5>
        </div>

        <div class="form-group">
          {!! Form::label('quantity', 'Quantity', ['class' => ' control-label text-right required']) !!}
          {!! Form::text('quantity', null,  ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('unit', 'Unit', ['class' => ' control-label text-right required']) !!}
          <select name="unit" id="unit" class="form-control">

            <option value="{{$product->primaryunit->id}}">{{$product->primaryunit->name}}</option>

            @if($product->secondaryunit && $product->secondary_unit_quantity > 0)
              <option value="{{$product->secondaryunit->id}}">{{$product->secondaryunit->name}}</option>
            @endif
            
            @if($product->tertiaryunit && $product->tertiary_unit_quantity > 0)
              <option value="{{$product->tertiaryunit->id}}">{{$product->tertiaryunit->name}}</option>
            @endif
            
            @if($product->customunit && $product->custom_unit_quantity > 0)
              <option value="{{$product->customunit->id}}">{{$product->customunit->name}}</option>
            @endif
            
            @if($product->bulkbuyunit && $product->bulk_buy_unit_quantity > 0)
              <option value="{{$product->bulkbuyunit->id}}">{{$product->bulkbuyunit->name}}</option>
            @endif
            
            @if($product->wholesalepurchaseunit && $product->wholesale_purchase_unit_quantity > 0)
              <option value="{{$product->wholesalepurchaseunit->id}}">{{$product->wholesalepurchaseunit->name}}</option>
            @endif
            
            @if($product->packweightunit && $product->pack_weight_unit_quantity > 0)
              <option value="{{$product->packweightunit->id}}">{{$product->packweightunit->name}}</option>
            @endif

          </select>
        </div>

        <div class="form-group">
            {!! Form::label('description', 'Description', ['class' => ' control-label text-left']) !!}
            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '4' ]) !!}
        </div>

        <!-- Image Field -->
        <div class="form-group">
            {!! Form::label('image', 'Disposal Image', ['class' => 'col-3 control-label text-left']) !!}
            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
              <input type="hidden" name="image">
            </div>
        </div>

        <script type="text/javascript">
            var var15866134771240834480ble = '';
            @if(isset($wastage_disposal) && $wastage_disposal->hasMedia('image'))
            var15866134771240834480ble = {
                name: "{!! $wastage_disposal->getFirstMedia('image')->name !!}",
                size: "{!! $wastage_disposal->getFirstMedia('image')->size !!}",
                type: "{!! $wastage_disposal->getFirstMedia('image')->mime_type !!}",
                collection_name: "{!! $wastage_disposal->getFirstMedia('image')->collection_name !!}"};
            @endif
            var dz_var15866134771240834480ble = $(".dropzone.image").dropzone({
                url: "{!!url('uploads/store')!!}",
                addRemoveLinks: true,
                maxFiles: 1,
                init: function () {
                @if(isset($wastage_disposal) && $wastage_disposal->hasMedia('image'))
                    dzInit(this,var15866134771240834480ble,'{!! url($wastage_disposal->getFirstMediaUrl('image','thumb')) !!}')
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
                        file, var15866134771240834480ble, '{!! url("wastageDispoal/remove-media") !!}',
                        'image', '{!! isset($wastage_disposal) ? $wastage_disposal->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                    );
                }
            });
            dz_var15866134771240834480ble[0].mockFile = var15866134771240834480ble;
            dropzoneFields['image'] = dz_var15866134771240834480ble;
        </script>

      </div>  
      <div class="clearfix"></div>
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary wastage-disposal-submit">Save changes</button>
  </div>

{!! Form::close() !!}

<script>

  $('.wastage-disposal-form').validate({ // initialize the plugin
      rules: {
          product_id: {
              required: true
          },
          quantity: {
              required: true
          },
          unit: {
              required: true
          },
          description: {
              required: true
          }
      },
      highlight: function (element, errorClass, validClass) {   
          var elem = $(element);
          if(elem.hasClass('select2')) {
              elem.siblings('.select2-container').addClass(errorClass);
          } else {
              elem.addClass(errorClass);
          }
      },
      unhighlight: function (element, errorClass, validClass) {
          var elem = $(element); 
          if(elem.hasClass('select2')) {
              elem.siblings('.select2-container').removeClass(errorClass);
          } else {
              elem.removeClass(errorClass);
          }
      },
      errorElement: "div",
      errorPlacement: function(error, element) { },
      success: function (error) {
          error.remove();
      },
      submitHandler: function(form) {
          
          $('.wastage-disposal-submit').html('<i class="fa fa-spinner fa-spin"></i>');
          $('.wastage-disposal-submit').attr("disabled", true);
          
          $.ajax({
              url: form.action,
              type: form.method,
              data: $(form).serialize()         
          }).done(function(results) {            
              
              $('.wastage-disposal-submit').html('<i class="fa fa-save"></i> Save Changes');
              $('.wastage-disposal-submit').attr("disabled", false);
              $('#productInventoryModal').modal('hide');

              iziToast.success({
                  backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                  messageColor: '#fff',
                  timeout: 3000, 
                  icon: 'fa fa-check', 
                  position: "bottomRight", 
                  iconColor:'#fff',
                  message: 'Wastage Disposed Sucessfully'
              });

              setTimeout(function () { location.reload(); }, 1000);

          })
          .fail(function(results) {            
              
              $('.wastage-disposal-submit').html('<i class="fa fa-save"></i> Save Changes');
              $('.wastage-disposal-submit').attr("disabled", false);

              iziToast.success({
                  backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                  messageColor: '#fff', 
                  timeout: 3000, 
                  icon: 'fa fa-remove', 
                  position: "bottomRight", 
                  iconColor:'#fff', 
                  message: results.statusText
              });

          });
      }
  });


</script>