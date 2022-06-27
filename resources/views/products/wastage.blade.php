<div class="modal-header">
  <h4 class="modal-title" id="myModalLabel">Update Product Wastage</h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

{!! Form::open(['route' => 'inventory.store', 'class' => 'inventory-form']) !!}

  <div class="modal-body">

    <div class="row">
      <div class="col-md-12">  
        
        {!! Form::hidden('category', 'added',['id' => 'inventoryCategory']) !!}
        {!! Form::hidden('type', 'reduce',['id' => 'inventoryType']) !!}
        {!! Form::hidden('usage', 'wastage',['id' => 'inventoryUsage']) !!}

        <div class="form-group">
          {!! Form::label('product_id', 'Product', ['class' => ' control-label text-left required']) !!}
          {!! Form::select('product_id', $products, $id, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('date', 'Date', ['class' => ' control-label text-right required datepicker']) !!}
          {!! Form::text('date', date('d-m-Y'),  ['class' => 'form-control','readonly'=>'readonly']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('quantity', 'Wastage Quantity', ['class' => ' control-label text-right required']) !!}
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

      </div>  
      <div class="clearfix"></div>
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary inventory-form-submit">Save changes</button>
  </div>

{!! Form::close() !!}

<script>
  $('#type').change(function() {
    var type = $(this).val();
    if(type=='reduce') {
      $('.usage-elem').show();
    } else {
      $('.usage-elem').hide();
      $('#usage').val('normal');
    }
  });

  $('.inventory-form').validate({ // initialize the plugin
      rules: {
          product_id: {
              required: true
          },
          type: {
              required: true
          },
          quantity: {
              required: true
          },
          unit: {
              required: true
          },
          usage: {
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
          
          $('.inventory-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
          $('.inventory-form-submit').attr("disabled", true);
          
          $.ajax({
              url: form.action,
              type: form.method,
              data: $(form).serialize()         
          }).done(function(results) {            
              
              $('.inventory-form-submit').html('<i class="fa fa-save"></i> Save Changes');
              $('.inventory-form-submit').attr("disabled", false);
              $('#productInventoryModal').modal('hide');

              iziToast.success({
                  backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                  messageColor: '#fff',
                  timeout: 3000, 
                  icon: 'fa fa-check', 
                  position: "bottomRight", 
                  iconColor:'#fff',
                  message: 'Inventory Updated Sucessfully'
              });

              setTimeout(function () { location.reload(); }, 1000);

          })
          .fail(function(results) {            
              
              $('.inventory-form-submit').html('<i class="fa fa-save"></i> Save Changes');
              $('.inventory-form-submit').attr("disabled", false);

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