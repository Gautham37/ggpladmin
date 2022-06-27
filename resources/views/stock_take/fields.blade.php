  <style type="text/css">
    .form-control {
      font-size: 12px !important;
      padding: 2px 5px !important;
      background: #eceaef;
    }
    span.input-group-addon {
        border: 2px solid #dce4ec;
        background: #eceaef;
        text-align: center;
    }
    select.form-control:not([size]):not([multiple]) {
        height: calc(2.25rem + 3px);
        width: auto;
    }
  </style>

  <div class="container custom-from-css">
    
    <div class="row">
      
        <div class="col-md-3">
          <div class="form-group">
            {!! Form::label('code', 'Stock Take No', ['class' => 'label-left control-label text-left']) !!}
            {!! Form::text('code', isset($stock_take_no) ? $stock_take_no : null ,  ['class' => 'form-control']) !!}
          </div>
        </div>  
        
        <div class="col-md-2">
          <div class="form-group">
            {!! Form::label('date', 'Date', ['class' => 'label-left control-label text-left']) !!}
            {!! Form::text('date', isset($stock_take) ? $stock_take->date->format('d-m-Y') : date('d-m-Y'),  ['class' => 'form-control datepicker', 'readonly' => 'readonly']) !!}
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group">
            {!! Form::label('time', 'Time', ['class' => 'label-left control-label text-left']) !!}
            {!! Form::text('time', isset($stock_take) ? $stock_take->created_at->format('H:i:s') : date('H:i:s'),  ['class' => 'form-control', 'readonly' => 'readonly']) !!}
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group">
            {!! Form::label('type', 'Type', ['class' => 'label-left control-label text-left']) !!}
            {!! Form::select('type', [null=>'Please Select', 'all'=>'All Products', 'today'=>'Today Sale Products', 'weekly'=>'Weekly Sale Products', 'monthly'=>'Monthly Sale Products'], null, ['class' => 'select2 form-control']) !!}
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            {!! Form::label('notes', 'Notes', ['class' => 'label-left control-label text-left']) !!}
            {!! Form::text('notes', null,  ['class' => 'form-control']) !!}
          </div>
        </div>

    </div><!-- /.col -->

      
      <div class="col-md-12 mt-2">
          <table id="salesItems" class="table table-bordered tbl-product-details">
            <thead>
              <tr>
                <th>NO</th>
                <th width="6%">IMAGE</th>
                <th width="20%">ITEMS</th>
                <th width="15%">PRODUCT CODE</th>
                <th width="15%">CURRENT STOCK</th>
                <th width="10%">COUNTED STOCK</th>
                <th width="10%">WASTAGE</th>
                <th width="10%">VARIANT</th>
                <th width="20%">NOTES</th>
              </tr>
            </thead>
            <tbody class="stock-take-items"></tbody>
          </table>
      </div>

  </div><!-- /.container-fluid -->
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}} stock-take-form-submit" ><i class="fa fa-save"></i> Save Stock Take</button>
  <a href="{!! route('stockTake.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Cancel </a>
</div>


@push('scripts_lib')

<script>

  var stock_take_items = [];

  function cartarrayToTable() {
    var in_html = '';
    var i     = 0;
    $(".stock-take-items").html('');
    $.each(stock_take_items, function(key,value) { 
      i++;

      in_html += '<tr>';
      in_html += '<td>'+i+'</td>';
      in_html += '<td>';
      in_html += '<img src="'+value.product_image+'" width="100%" />';
      in_html += '</td>';
      in_html += '<td>';
      if(value.stock_take_item_id != undefined && value.stock_take_item_id != ''){
          in_html += '<input type="hidden" name="stock_take_item_id[]" value="'+value.stock_take_item_id+'" />';   
      }
      in_html += '<input type="hidden" name="product_id[]" value="'+value.product_id+'">';
      in_html += '<input type="text" name="product_name[]" readonly="readonly" class="form-control" value="'+value.product_name+'">';
      in_html += '</td>';
      
      in_html += '<td>';
      in_html += '<input type="text" name="product_code[]" readonly="readonly" class="form-control" value="'+value.product_code+'">';
      in_html += '</td>';

      in_html += '<td class="inline-flex">';
      in_html += '<input type="text" name="current[]" readonly="readonly" class="form-control text-right" readonly="readonly" value="'+value.current+'">';
      in_html += '<span class="item-unit">'+value.primary_unit_name+'</span>';
      in_html += '<input type="hidden" name="current_unit[]" value="'+value.primary_unit_id+'">';
      in_html += '</td>';

      
      in_html += '<td style="display:table-cell;">';
      in_html += '<span style="display:inline-flex;">';
      
      in_html += '<input type="text" name="counted[]" id="counted_'+key+'" index="'+key+'" field="counted" class="text-right change_info" value="'+value.counted+'">';
      
      in_html += '<select name="counted_unit[]" id="counted_unit_' + key + '" index="' + key + '" field="counted_unit">';
      in_html += '<option value="'+value.primary_unit_id+'">'+value.primary_unit_name+'</option>';
      in_html += '</select>';
      
      in_html += '</span>';
      in_html += '</td>';


      in_html += '<td style="display:table-cell;">';
      in_html += '<span style="display:inline-flex;">';
      
      in_html += '<input type="text" name="wastage[]" id="wastage_'+key+'" index="'+key+'" field="wastage" class="text-right change_info" value="'+value.wastage+'">';
      
      in_html += '<select name="wastage_unit[]" id="wastage_unit_' + key + '" index="' + key + '" field="wastage_unit">';
      in_html += '<option value="'+value.primary_unit_id+'">'+value.primary_unit_name+'</option>';
      in_html += '</select>';
      
      in_html += '</span>';
      in_html += '</td>';


      in_html += '<td style="display:table-cell;">';
      in_html += '<span style="display:inline-flex;">';
      
      in_html += '<input type="text" name="missing[]" id="missing_'+key+'" index="'+key+'" readonly="readonly" field="missing" class="text-right change_info" value="'+value.missing+'">';
      
      in_html += '<select name="missing_unit[]" id="missing_unit_' + key + '" index="' + key + '" field="missing_unit">';
      in_html += '<option value="'+value.primary_unit_id+'">'+value.primary_unit_name+'</option>';
      in_html += '</select>';
      
      in_html += '</span>';
      in_html += '</td>';

      
      /*in_html += '<td>';
      in_html += '<input type="text" name="missing[]" id="missing_'+key+'" index="'+key+'" field="missing" class="form-control text-right" value="'+value.missing+'">';
      in_html += '</td>';*/

      /*in_html += '<td>';
      in_html += '<input type="text" name="wastage[]" id="wastage_'+key+'" index="'+key+'" field="wastage" class="form-control text-right" value="'+value.wastage+'">';
      in_html += '</td>';*/

      in_html += '<td>';
      in_html += '<input type="text" name="item_notes[]" id="item_notes_'+key+'" index="'+key+'" field="item_notes" class="form-control text-right" value="'+value.item_notes+'">';
      in_html += '</td>';

      in_html += '</tr>'; 
    });
    $(".stock-take-items").html(in_html);
  }


  $(document).on('keyup','.change_info',function (e) { 
    var field = $(this).attr('field');
    var index = $(this).attr('index');
    var val   = $(this).val();
    
    if(field == 'counted') {
        if(val != undefined && val!=''){
            stock_take_items[index].counted = val;
            $("#counted_" + index).val(val);

            var missing = parseFloat(stock_take_items[index].current) - parseFloat(stock_take_items[index].counted);
            $('#missing_' + index).val(missing);

        } else {
            stock_take_items[index].counted = '';
            $("#counted_" + index).val('');
        }
    } else if(field == 'wastage') {
        if(val != undefined && val!=''){
            
            stock_take_items[index].wastage = val;
            $("#wastage_" + index).val(val);

            current = (stock_take_items[index].current > 0) ? parseFloat(stock_take_items[index].current) : 0 ;
            counted = (stock_take_items[index].counted > 0) ? parseFloat(stock_take_items[index].counted) : 0 ;
            wastage = (stock_take_items[index].wastage > 0) ? parseFloat(stock_take_items[index].wastage) : 0 ;

            var missing =  current - counted - wastage;
            $('#missing_' + index).val(missing);

        } else {
            stock_take_items[index].wastage = '';
            $("#wastage_" + index).val('');
        }
    }

  });


  @if(!isset($stock_take))
  /*$(document).ready(function() {*/
  $('#type').change(function() {

    var url   = '{!!route('products.index')!!}';
    var token = "{{ csrf_token() }}";
    $.ajax({
        type: 'GET',
        data: {
            '_token': token,
            'type': 'product_list',
            'category': $(this).val()
        },
        url: url,
        success: function (response) {
            stock_take_items = [];      
            if(response.data.length > 0) { 
                $.each(response.data, function(key,value) {
                  var stock_take_item_obj = {};
                  stock_take_item_obj.product_id        = value.id;
                  stock_take_item_obj.product_name      = value.name;
                  stock_take_item_obj.product_code      = value.product_code;
                  stock_take_item_obj.current           = value.stock;
                  stock_take_item_obj.primary_unit_name = value.primaryunit.name;
                  stock_take_item_obj.primary_unit_id   = value.primaryunit.id;
                  stock_take_item_obj.counted           = '';
                  stock_take_item_obj.counted_unit      = '';
                  stock_take_item_obj.missing           = '';
                  stock_take_item_obj.wastage           = '';
                  stock_take_item_obj.item_notes        = '';
                  stock_take_item_obj.product_image     = (value.media.length > 0) ? value.media[0].original_url : "{{asset('images/image_default.png')}}" ;
                  stock_take_items.push(stock_take_item_obj);
                });
            }
            cartarrayToTable();
        }
    });

  });
  @endif

  @if(isset($stock_take))
  
    $(document).ready(function() {

      var url   = '{!!route('stockTake.show',$stock_take->id)!!}';
      var token = "{{ csrf_token() }}";
      $.ajax({
          type: 'GET',
          data: {
              '_token': token
          },
          url: url,
          success: function (response) {      
               console.log(response);
               if(response.data.items.length > 0){
                  $.each(response.data.items, function(key,value) {

                    var stock_take_item_obj = {};
                    stock_take_item_obj.stock_take_item_id  = value.id;
                    stock_take_item_obj.product_id          = value.product_id;
                    stock_take_item_obj.product_name        = value.product_name;
                    stock_take_item_obj.product_code        = value.product_code;
                    stock_take_item_obj.current             = (value.current!=null) ? value.current : '' ;
                    stock_take_item_obj.primary_unit_name   = (value.currentunit.name!=null) ? value.currentunit.name : '' ;
                    stock_take_item_obj.primary_unit_id     = (value.current_unit!=null) ? value.current_unit : '' ;
                    stock_take_item_obj.counted             = (value.counted!=null) ? value.counted : '' ;
                    stock_take_item_obj.counted_unit        = (value.counted_unit!=null) ? value.counted_unit : '' ;
                    stock_take_item_obj.missing             = (value.missing!=null) ? value.missing : '' ;
                    stock_take_item_obj.wastage             = (value.wastage!=null) ? value.wastage : '' ;
                    stock_take_item_obj.item_notes          = (value.notes!=null) ? value.notes : '' ;
                    stock_take_item_obj.product_image       = (value.product.media.length > 0) ? value.product.media[0].original_url : "{{asset('images/image_default.png')}}" ;
                    stock_take_items.push(stock_take_item_obj);

                  });
                  cartarrayToTable();
               }

          }
      });

    });  

  @endif

</script>

@endpush
