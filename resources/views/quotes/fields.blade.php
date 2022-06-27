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
    
    <div class="row" style="margin-top:20px;">
        
      @include('layouts.invoice_header')
      
        <div class="col-md-8" data-toggle="tooltip" title="Shift + Y">
          <div class="row"> 
            <div class="col-md-12">
                <div class="form-group add-btn-product-page">
                    {!! Form::label('market_id', 'Party',['class'=>' control-label text-left required']) !!}
                    <div class="input-group-append">
                        {!! Form::select('market_id', $users, null, ['class' => 'select2 form-control market_id']) !!}
                        <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> 
                          <a  href="" data-toggle="modal" data-target="#partyModal"><i class="fa fa-plus mr-1"></i></a>
                        </span>
                    </div>
                </div>
                <div class="party_details mt-15 mb-15 col-md-12"></div>
            </div>
          </div>
        </div>
      
        <div class="col-md-4 mt-4">

          <div class="form-group row">
            {!! Form::label('code', 'Quote No', ['class' => 'label-left col-md-4 control-label text-left']) !!}
            {!! Form::text('code', isset($quote_no) ? $quote_no : null ,  ['class' => 'col-md-8 form-control']) !!}
          </div>

          <div class="form-group row">
            {!! Form::label('date', 'Date', ['class' => 'label-left col-md-4 control-label text-left']) !!}
            {!! Form::text('date', isset($quote) ? $quote->date->format('d-m-Y') : date('d-m-Y'),  ['class' => 'col-md-8 form-control datepicker', 'readonly' => 'readonly']) !!}
          </div>

          <div class="form-group row">
            {!! Form::label('valid_date', 'Valid Date', ['class' => 'label-left col-md-4 control-label text-left']) !!}
            {!! Form::text('valid_date', isset($quote) ? $quote->valid_date->format('d-m-Y') : date('d-m-Y', time() + (86400 * 15)),  ['class' => 'col-md-8 form-control datepicker', 'readonly' => 'readonly']) !!}
          </div>
      
        </div><!-- /.col -->

    </div><!-- /.col -->

      
      <div class="col-md-12">
          <table id="quoteItems" class="table table-bordered tbl-product-details">
            <thead>
              <tr>
                <th>NO</th>
                <th>ITEMS</th>
                <th>HSN</th>
                <th>MRP</th>
                <th>QTY</th>
                <th>PRICE/ITEM</th>
                <th>DISCOUNT</th>
                <th>TAX</th>
                <th>AMOUNT</th>
                <th></th>
              </tr>
            </thead>
            <tbody class="quotes-items"></tbody>
            <!-- <tfoot>
              <th colspan="6">Subtotal</th>
              <th>
                <div class="input-group">
                  <span class="input-group-addon">{{setting('default_currency')}}</span>
                  {!! Form::text('discount_total', null,  ['class' => 'form-control discount_total', 'readonly' => 'readonly']) !!}
                </div>
              </th>
              <th>
                <div class="input-group">
                  <span class="input-group-addon">{{setting('default_currency')}}</span>
                  {!! Form::text('tax_total', null,  ['class' => 'form-control tax_total', 'readonly' => 'readonly']) !!}
                </div>
              </th>
              <th>
                <div class="input-group">
                  <span class="input-group-addon">{{setting('default_currency')}}</span>
                  {!! Form::text('sub_total', null,  ['class' => 'form-control sub_total', 'readonly' => 'readonly']) !!}
                </div>
              </th>
              <th></th>
            </tfoot> -->

            <tfoot class="text-center">
              <tr>
                <th colspan="8">

                  <button data-toggle="tooltip" title="Shift + M" type="button" data-toggle="tooltip" class="btn btn-primary text-center add-item-btn-custom">
                    <i class="fa fa-plus"></i> &nbsp; Add Item
                  </button>

                </th>
                <th colspan="2">
                
                  <div data-toggle="tooltip" title="Shift + B" onclick="addItembyBarcode();">
                    <img src="{{ url('images/scanner.png') }}" width="40" Title="Scan Barcode" alt="Scan Barcode" />
                    <br>
                    Scan Barcode
                  </div>

                </th>
              </tr>
            </tfoot>
          </table>
      </div>

      <div class="delete_items"></div>

    <div class="row">  

      <div class="col-md-7">
        <div class="col-md-12">
          {!! Form::label('description', 'Notes', ['class' => 'control-label text-left']) !!}
          {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=> ""]) !!}      
        </div>
        <div class="col-md-12"> 
          {!! Form::label('terms_and_conditions', 'Terms and Conditions', ['class' => 'control-label text-left']) !!}
          {!! Form::textarea('terms_and_conditions', isset($quote) ? null : setting('app_invoice_terms_and_conditions'), ['class' => 'form-control']) !!}
        </div>
      </div>

      <div class="col-md-5">
          <table class="table table-bordered">
            
            <tr>
              <td class="text-right" style="width:40%;">Sub Total</td>
              <td class="text-right">
                <div class="input-group">
                  <span class="input-group-addon">{{setting('default_currency')}}</span>
                  {!! Form::text('sub_total', null,  ['class' => 'form-control sub_total', 'readonly' => 'readonly']) !!}
                </div>
              </td>
            </tr>

            <tr>
              <td class="text-right">GST</td>
              <td class="text-right">
                <div class="input-group">
                  <span class="input-group-addon">{{setting('default_currency')}}</span>
                  {!! Form::text('tax_total', null,  ['class' => 'form-control tax_total', 'readonly' => 'readonly']) !!}
                </div>
              </td>
            </tr>

            <tr>
              <td class="text-right">Discount</td>
              <td class="text-right">
                <div class="input-group">
                  <span class="input-group-addon">{{setting('default_currency')}}</span>
                  {!! Form::text('discount_total', null,  ['class' => 'form-control discount_total', 'readonly' => 'readonly']) !!}
                </div>
              </td>
            </tr>

            <tr>
              
              <tr>
                <td class="text-right">Additional Charges</td>
                <td class="text-right">
                    <div class="input-group">
                      <span class="input-group-addon">{{setting('default_currency')}}</span>
                      {!! Form::text('additional_charge', null,  ['class' => 'form-control additional_charge change_info', 'field'=>'additional_charge', 'index'=>'0']) !!}
                    </div>
                </td>
              </tr>
              <tr>
                <td class="text-right">Additional Charges Description</td>
                <td class="text-right">
                    <div class="input-group">
                      {!! Form::text('additional_charge_description', null,  ['class' => 'form-control additional_charge_description change_info', 'field'=>'additional_charge_description', 'index'=>'0']) !!}
                    </div>
                </td>
              </tr>

            </tr>  

            <tr>
              <td class="text-right">Delivery Charge</td>
              <td class="text-right">
                  <div class="input-group">
                    <span class="input-group-addon">{{setting('default_currency')}}</span>
                      {!! Form::text('delivery_charge', null,  ['class' => 'form-control delivery_charge change_info', 'field'=>'delivery_charge', 'index'=>'0']) !!}
                  </div>  
              </td>
            </tr>
            
            <?php /*/ ?>
            @if(!isset($sales_invoice))
            <tr style="background: linear-gradient(2deg, #584424, #d9b44b); color: #fff; font-weight: bold; border-radius: 4px;">
              <td class="text-left" >
                  <!--Redeem Points-->
                  <p>
                    <span>POINTS</span> - 
                    <span style="font-size:18px;" class="total-rewards">0</span>
                    <input type="hidden" name="total_rewards" id="total_rewards" class="total_rewards" />
                  </p>
                  <p>
                    <span>WORTH</span> - 
                    <span style="font-size:18px;" >{!!setting('default_currency')!!} <span class="points-worth">0</span></span>
                    <input type="hidden" name="points_worth" id="points_worth" class="points_worth" />
                  </p>
              </td>
              <td class="text-right">
                  <div class="input-group">
                      {!! Form::number('redeem_points', null, ['class'=>'form-control redeem_points', 'min'=>'100', 'max'=>'1000', 'placeholder'=>'Enter Points to Redeem']) !!}
                      <button type="button" class="btn btn-info btn-sm redeem-points-button">Redeem</button>
                      <input type="hidden" name="used_point_worth" id="used_point_worth" class="used_point_worth" />
                  </div>  
                  <small><b>Minimum 100 reward points redeem at a time</b></small>
                  <p class="text-left">
                    REDEEM APPLIED 
                    <span class="btn btn-sm btn-success">  
                      {{setting('default_currency')}} <span class="redeemed-point">0.00</span>
                    </span>
                  </p>
              </td>
            </tr>
            @endif
            <?php /*/ ?>
            
            <tr>
              <td class="text-right">Total Amount</td>
              <td class="text-right">
                  <div class="input-group">
                    <span class="input-group-addon">{{setting('default_currency')}}</span>
                      {!! Form::text('total', null,  ['class' => 'form-control total', 'id' => 'total', 'readonly' => 'readonly']) !!}
                  </div> 
              </td>
            </tr>
            
            <?php /*/ ?>
            <tr>
              <td class="text-right"> 
                <span> 
                  Cash Paid / Mark as Fully Paid 
                  <input type="checkbox" class="mark_as_paid" name="mark_as_paid" id="mark_as_paid" value="1">
                </span>
              </td>
              <td class="text-right">
                  
                  <div class="input-group">
                    <span class="input-group-addon">₹</span>
                        {!! Form::text('cash_paid', null, ['class' => 'form-control cash_paid change_info','field'=>'cash_paid','index'=>'0']) !!}
                        {!! Form::select('payment_method', $payment_types, null, ['class' => 'form-control']) !!}
                  </div>
              </td>
            </tr>
            <tr>
              <td class="text-right">Balance Amount</td>
              <td class="text-right">
                  <div class="input-group">
                    <span class="input-group-addon">₹</span>
                    {!! Form::text('amount_due', null,  ['class' => 'form-control amount_due', 'readonly' => 'readonly']) !!}
                  </div>
              </td>
            </tr>
            <?php /*/ ?>

          </table>
      </div>

    </div><!-- /.row -->

  </div><!-- /.container-fluid -->
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}} quotes-form-submit" ><i class="fa fa-save"></i> Save Quote</button>
  <a href="{!! route('quotes.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Cancel </a>
</div>


@push('scripts_lib')

<script>
  $('.add-item-btn-custom').click(function() {
      $('#cartModal').modal('show');
      var url       = "{{route('products.index')}}";
      var token     = "{{csrf_token()}}";
      var market_id = $('#market_id').val();
      $.ajax({
          type: 'GET',
          data: {
              '_token': token,
              'type': 'cart_products',
              'section': 'quotes',
              'market_id': market_id
          },
          url: url,
          success: function (response) {
             $('.cart-items').html(response.data);
          }
      });
  });
</script>

<script>

  var invoice_items = [];

  function cartarrayToTable() {
    var in_html = '';
    var i     = 0;
    $(".quotes-items").html('');
    $.each(invoice_items, function(key,value) { 
      i++;

      if(parseFloat(value.quantity) > 0 && parseFloat(value.unit_price) > 0) {         
          if(value.tax_type == 'exclusive'){             
             total_amount   = parseFloat(value.unit_price) * parseFloat(value.quantity) - parseFloat(value.discount_amount);
             gst_amount     = parseFloat(total_amount) / 100 * parseFloat(value.tax);
             amount         = parseFloat(total_amount) + parseFloat(gst_amount); 
          } else {             
             amount     = parseFloat(value.unit_price) * parseFloat(value.quantity) - parseFloat(value.discount_amount);
             gst_amount = 0;
             value.tax  = 0;
          }
      } else {
          amount     = 0;
          gst_amount = 0;
      }

      value.amount      = parseFloat(amount).toFixed(2);
      value.tax_amount  = parseFloat(gst_amount).toFixed(2);
      $("#tax_amount_" + key).val(parseFloat(gst_amount).toFixed(2));
      $("#amount_" + key).val(parseFloat(amount).toFixed(2));


      in_html += '<tr>';
      in_html += '<td>'+i+'</td>';
      in_html += '<td>';
      if(value.invoice_item_id != undefined && value.invoice_item_id != ''){
          in_html += '<input type="hidden" name="invoice_item_id[]" value="'+value.invoice_item_id+'" />';   
      }
      in_html += '<input type="hidden" name="product_id[]" value="'+value.product_id+'">';
      in_html += '<input type="hidden" name="unit[]" value="'+value.unit+'">';
      in_html += '<input type="text" name="product_name[]" class="form-control" value="'+value.product_name+'">';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<input type="text" name="product_hsn_code[]" class="form-control" value="'+value.product_hsn_code+'">';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<input type="text" name="mrp[]" id="mrp_' + key + '" index="' + key + '" field="description" class="form-control change_info" value="' + value.mrp + '">';
      in_html += '</td>';
      in_html += '<td class="inline-flex">';
      in_html += '<input type="text" name="quantity[]" id="quantity_' + key + '" index="' + key + '" field="quantity" class="form-control change_info qty" value="'+value.quantity+'">';
      in_html += '<span class="item-unit">'+value.unit_name+'</span>';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<input type="text" name="unit_price[]" id="unit_price_' + key + '" index="' + key + '" field="unit_price" class="form-control change_info" value="'+value.unit_price+'">';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<div class="input-group">';
      in_html += '<span class="input-group-addon">%</span>';
      in_html += '<input type="text" name="discount[]" id="discount_' + key + '" index="' + key + '" field="discount" class="form-control change_info" value="'+value.discount+'">';
      in_html += '</div>';
      in_html += '<div class="input-group">';
      in_html += '<span class="input-group-addon">{{setting('default_currency')}}</span>';
      in_html += '<input type="text" name="discount_amount[]" id="discount_amount_' + key + '" index="' + key + '" field="discount_amount" class="form-control change_info discount_amount" value="'+value.discount_amount+'">';
      in_html += '</div>';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<div class="input-group">';
      in_html += '<span class="input-group-addon">%</span>';
      in_html += '<input type="text" name="tax[]" id="tax_' + key + '" index="' + key + '" field="tax" class="form-control change_info" value="'+value.tax+'">';
      in_html += '<input type="hidden" name="tax_amount[]" id="tax_amount_' + key + '" index="' + key + '" field="tax_amount" class="form-control change_info tax_amount" value="'+value.tax_amount+'">';
      in_html += '</div>';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<input type="text" name="amount[]" id="amount_' + key + '" index="' + key + '" field="amount" class="form-control change_info amount" value="'+value.amount+'">';
      in_html += '</td>';
      in_html += '<td>';
      in_html += '<a title="Delete" class="delete btn btn-sm btn-danger text-white" index="'+key+'"><i class="fa fa-remove"></i></a>';
      in_html += '</td>';
      in_html += '</tr>'; 
    });
    $(".quotes-items").html(in_html);
    calculateInvoiceTotals();
    //$('.qty').trigger('keyup');
  }

  $(document).on('keyup','.change_info',function (e) { 
    var field = $(this).attr('field');
    var index = $(this).attr('index');
    var val = $(this).val();
    
    if(field == 'quantity'){
        if(val != undefined && val!=''){
            invoice_items[index].quantity = val;
            $("#quantity_" + index).val(val);

            
            if(invoice_items[index].invoice_item_id == undefined || invoice_items[index].invoice_item_id == ''){
              var url       = '{!!  route('products.show', [':productID']) !!}';
              url           = url.replace(':productID', invoice_items[index].product_id);
              var token     = "{{ csrf_token() }}";
              $.ajax({
                  type: 'GET',
                  data: {
                      '_token': token,
                      'product_id': invoice_items[index].product_id
                  },
                  url: url,
                  success: function (response) {
                      pc = 0;
                      if(response.data.pricevaritations.length > 0) {
                        $.each(response.data.pricevaritations, function(key1,value1) { 
                          if(parseFloat(invoice_items[index].quantity) >= parseFloat(value1.purchase_quantity_from) && parseFloat(invoice_items[index].quantity) <= parseFloat(value1.purchase_quantity_to)) {
                            pc++;
                            invoice_items[index].unit_price = (parseFloat(invoice_items[index].df_unit_price) * value1.price_multiplier / 100).toFixed(2);
                          }
                        });
                      }
                      if(pc == 0) {
                        invoice_items[index].unit_price = invoice_items[index].df_unit_price;
                      }
                  }
              });
            }


        } else {
            invoice_items[index].quantity = '';
            $("#quantity_" + index).val('');
        }
    } else if(field == 'unit_price') {
        if(val != undefined && val!=''){
            invoice_items[index].unit_price = val;
            $("#unit_price_" + index).val(val);
        } else {
            invoice_items[index].unit_price = '';
            $("#unit_price_" + index).val('');
        }
    } else if(field == 'discount') {
        if(val != undefined && val!=''){
            invoice_items[index].discount = val;
            $("#discount_" + index).val(val);

            total           = parseFloat(invoice_items[index].unit_price) * parseFloat(invoice_items[index].quantity);
            discount_amount = parseFloat(total) / 100 * parseFloat(invoice_items[index].discount);
            invoice_items[index].discount_amount = (parseFloat(discount_amount)).toFixed(2); 

        } else {
            invoice_items[index].discount = '';
            $("#discount_" + index).val('');
        }
    } else if(field == 'discount_amount') {
        if(val != undefined && val!=''){
            invoice_items[index].discount_amount = val;
            $("#discount_amount_" + index).val(val);

            var total            = parseFloat(invoice_items[index].unit_price) * parseFloat(invoice_items[index].quantity);
            var discount_percent = (parseFloat(total) - (parseFloat(total) - parseFloat(val))) / parseFloat(total) * 100;
            invoice_items[index].discount = (parseFloat(discount_percent)).toFixed(2);  

        } else {
            invoice_items[index].discount_amount = '';
            $("#discount_amount_" + index).val('');
        }
    } else if(field == 'tax') {
        if(val != undefined && val!=''){
            invoice_items[index].tax = val;
            $("#tax_" + index).val(val);
        } else {
            invoice_items[index].tax = '';
            $("#tax_" + index).val('');
        }
    }

    setTimeout(function() {
        cartarrayToTable();
    },300);
    e.preventDefault();
  });


  $('.market_id').change(function() {

      $(".party_details").addClass("card-loader");
      var market_id = $(this).val();
      var url       = '{!!  route('markets.view', [':marketID']) !!}';
      url           = url.replace(':marketID', market_id);
      var token     = "{{ csrf_token() }}";

      $.ajax({
          type: 'GET',
          data: {
              '_token': token,
              'market_id': market_id,
              'type': 'load_address'
          },
          url: url,
          success: function (response) {
            if(response.data != null) {

                $(".party_details").removeClass("card-loader");
                var phtml = '';
                phtml += '<p>';
                phtml += '<strong> Address : </strong> <br>';
                phtml += (response.data.street_no!=null) ? response.data.street_no+', ' : '' ;
                phtml += (response.data.street_name!=null) ? response.data.street_name+', ' : '' ;
                phtml += (response.data.street_type!=null) ? response.data.street_type+', ' : '' ;
                phtml += (response.data.address_line_1!=null) ? response.data.address_line_1+', ' : '' ;
                phtml += (response.data.address_line_2!=null) ? response.data.address_line_2+', ' : '' ;
                phtml += (response.data.town!=null) ? response.data.town+', ' : '' ;
                phtml += (response.data.city!=null) ? response.data.city+', ' : '' ;
                phtml += (response.data.state!=null) ? response.data.state+' - ' : '' ;
                phtml += (response.data.pincode!=null) ? response.data.pincode+'. ' : '' ;
                phtml += '<br>';
                phtml += '<strong> Landmark : </strong>';
                phtml += (response.data.landmark_1!=null) ? response.data.landmark_1+', ' : '' ;
                phtml += (response.data.landmark_2!=null) ? response.data.landmark_2+'.' : '' ;  
                phtml += '<br>';
                phtml += '<strong> Balance : </strong>';
                phtml += "{{setting('default_currency')}} ";
                phtml += (response.data.balance).toFixed(2);
                phtml += '&nbsp;&nbsp;&nbsp;&nbsp;';
                phtml += '<strong> Reward Level : </strong>';
                if(response.data.customer_level) {
                phtml += response.data.customer_level.name;   
                }
                phtml += '';
                
                phtml += '<p>';
                $('.party_details').html(phtml);
                $('.total-rewards').html(parseFloat(response.data.user.points).toFixed(2));
                $('.points-worth').html((parseFloat(response.data.user.points) / 100).toFixed(2));
                $('.total_rewards').val(parseFloat(response.data.user.points).toFixed(2));
                $('.points_worth').val((parseFloat(response.data.user.points) / 100).toFixed(2));
                $('.delivery_charge').val((response.data.delivery_charge) ? parseFloat(response.data.delivery_charge) : 0 );

            } 
          }
      });

  });

  
  $(document).on('click', '.delete', function(e){
      var index = $(this).attr('index');
      if(invoice_items[index].invoice_item_id == undefined || invoice_items[index].invoice_item_id == ''){
         invoice_items.splice(index,1);
         cartarrayToTable();
      } else {

         iziToast.show({
             theme: 'dark',
             icon: 'fa fa-trash',
             overlay: true,
             title: 'Delete',
             message: 'Are you sure?',
             position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
             progressBarColor: 'yellow',
             backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
             messageColor: '#fff', 
             buttons: [
                 ['<button>Yes</button>', function (instance, toast) {
                    
                     $('.delete_items').html('<input type="hidden" name="delete_item_id[]" value="'+invoice_items[index].invoice_item_id+'" />');
                     instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName');
                     invoice_items.splice(index,1);
                     cartarrayToTable();

                 }, true], // true to focus
                 ['<button>No</button>', function (instance, toast) {
                     instance.hide({
                         transitionOut: 'fadeOutUp',
                         onClosing: function(instance, toast, closedBy){
                             console.info('closedBy: ' + closedBy); // The return will be: 'closedBy: buttonName'
                         }
                     }, toast, 'buttonName');
                 }]
             ]
         });

      }

   });


  @if(isset($quote))
  
    $(document).ready(function() {

      var url   = '{!!route('quotes.show',$quote->id)!!}';
      var token = "{{ csrf_token() }}";
      $.ajax({
          type: 'GET',
          data: {
              '_token': token
          },
          url: url,
          success: function (response) {      
               
               $('.market_id').trigger('change');
               if(response.data.items.length > 0){
                  $.each(response.data.items, function(key,value){
                     
                    var invoice_item_obj = {};
                    invoice_item_obj.invoice_item_id  = value.id;
                    invoice_item_obj.product_id       = value.product_id;
                    invoice_item_obj.product_name     = value.product_name;
                    invoice_item_obj.product_hsn_code = value.product_hsn_code;
                    invoice_item_obj.mrp              = value.mrp;
                    invoice_item_obj.quantity         = value.quantity;
                    invoice_item_obj.primary_unit     = value.product.unit;
                    invoice_item_obj.unit             = value.unit;
                    invoice_item_obj.unit_name        = value.uom.name;
                    invoice_item_obj.df_unit_price    = value.unit_price;
                    invoice_item_obj.unit_price       = value.unit_price;
                    invoice_item_obj.discount         = value.discount;
                    invoice_item_obj.discount_amount  = value.discount_amount;
                    invoice_item_obj.tax              = value.tax;
                    invoice_item_obj.tax_type         = 'exclusive';
                    invoice_item_obj.tax_amount       = value.tax_amount;
                    invoice_item_obj.amount           = value.amount;
                    invoice_items.push(invoice_item_obj);

                  });
                  cartarrayToTable();
               }

          }
      });

    });  

  @endif

</script>

@endpush
