@if(count($products) > 0)      
<style>
  .table-scroll{
    /*width:100%; */
    display: block;
    empty-cells: show;
    
    /* Decoration */
    border-spacing: 0;
    border: 1px solid;
    height: 400px;       /* Just for the demo          */
    overflow-y: auto;    /* Trigger vertical scroll    */
    overflow-x: hidden;
  }

  .table-scroll thead{
    background-color: #f1f1f1;  
    position:relative;
    display: block;
    width:100%;
    overflow-y: scroll;
  }

  .table-scroll tbody{
    /* Position */
    display: block; position:relative;
    width:100%; overflow-y:scroll;
    /* Decoration */
    border-top: 1px solid rgba(0,0,0,0.2);
  }

  .table-scroll tr{
    width: 100%;
    display:flex;
  }

  .table-scroll td,.table-scroll th{
    flex-basis:100%;
    flex-grow:2;
    display: block;
    padding: 1rem;
    text-align:center;
  }
</style>

<div class="modal-body">

  <input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">
  
  @if(isset($market_id) && $market_id!='')
  	<input type="hidden" name="market_id" id="market_id" value="{{$market_id}}">  
  @endif
  
  <table id="productTable" class="table table-bordered table-scroll small-first-col">
     <thead>
       <tr>
         <th>PRODUCT NAME</th>
         <th>PRODUCT CODE</th>
         <th>SALES PRICE</th>
         <th>PURCHASE PRICE</th>
         <th>CURRENT STOCK</th>
         <th>QUANTITY</th>  
       </tr>
     </thead>
     <tbody class="cart-product-list"></tbody> 
  </table>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary btn-sm cart-form-submit"> Add Products </button>
	<button type="button" class="btn btn-default btn-sm" data-dismiss="modal"> Cancel </button>
</div>

@else
	
	<p class="text-center mt-4 mb-4">No Products Found</p>

@endif

<script>

  var product_list  = [];

  $(document).ready(function() {
      var url   = '{!! route('products.index') !!}';
      var token = "{{ csrf_token() }}";
      $.ajax({
            type: 'GET',
            data: {
              '_token': token,
              'type': 'product_list'
            },
          url: url,
          success: function (response) {
              var html     = '';
              $(".cart-product-list").html('');
              if(response.data.length > 0) {
                $.each(response.data, function(key,value) { 

                  html += '<tr>';
                  html += '<td>';
                  html += value.name;
                  html += '</td>';
                  html += '<td>';
                  html += value.product_code;
                  html += '</td>';
                  html += '<td class="price-'+value.id+'">';
                  html += (parseFloat(value.price).toFixed(2));
                  html += '</td>';
                  html += '<td class="purchase-price-'+value.id+'">';
                  html += (parseFloat(value.purchase_price).toFixed(2));
                  html += '</td>';
                  html += '<td style="display: grid;">';
                  html += '<span class="stock-av-'+value.id+'">';
                  html += (parseFloat(value.stock).toFixed(3));
                  html += value.primaryunit.name;
                  html += '</span>';
                  html += '<small><span class="stock-alert-'+value.id+' text-danger"></span></small>';
                  html += '</td>';
                  html += '<td class="product-'+value.id+'" style="display:inline-flex;">';
                  html += '<button style="width:100%;" id="'+value.id+'" onclick="addtoCart(this);" type="button" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add </button>';
                  html += '</td>';
                  html += '</tr>';

                });
              }
              $(".cart-product-list").html(html);   
          }
      });
  });

	function addtoCart(elem) {

		  var id    = $(elem).attr('id');
		  var url   = '{!! route('products.show', [':productID']) !!}';
      url       = url.replace(':productID', id);
      var token = "{{ csrf_token() }}";
	    $.ajax({
          	type: 'GET',
          	data: {
              '_token': token,
              'id': id
          	},
         	  url: url,
          	success: function (response) {

              @if($section=='purchase' || $section=='vendor_stock')
                  response.data.purchase_price  = response.data.purchase_price;
                  response.data.price           = response.data.purchase_price;
              @else 
                  response.data.purchase_price  = response.data.price;   
              @endif

              var invoice_item_obj = {};
              invoice_item_obj.product_id       = response.data.id;
              invoice_item_obj.product_name     = response.data.name;
              invoice_item_obj.product_hsn_code = response.data.hsn_code;
              invoice_item_obj.mrp              = response.data.purchase_price;
              invoice_item_obj.quantity         = 0;
              invoice_item_obj.primary_unit     = response.data.unit;
              invoice_item_obj.unit             = response.data.unit;
              invoice_item_obj.unit_name        = response.data.primaryunit.name;
              invoice_item_obj.df_unit_price    = response.data.price;
              invoice_item_obj.unit_price       = response.data.price;
              invoice_item_obj.discount_price   = response.data.discount_price;
              invoice_item_obj.discount         = 0;
              invoice_item_obj.discount_amount  = 0;
              invoice_item_obj.tax              = (response.data.tax!=null && response.data.tax > 0) ? response.data.tax : 0 ;
              invoice_item_obj.tax_type         = 'exclusive';
              invoice_item_obj.tax_amount       = 0;
              invoice_item_obj.amount           = 0;

              if(invoice_items.findIndex(p => p.product_id == response.data.id) < 0) {
                invoice_items.push(invoice_item_obj);  
              }
              

          		var html  = '';
          		//html += '<input type="hidden" name="c_product_id[]" value="'+response.data.id+'" />';
          		html += '<input type="hidden" id="avstock-'+response.data.id+'" value="'+response.data.stock+'" />';
          		html += '<input type="number" style="width:100%;" class="c_quantity" id="'+response.data.id+'" name="c_quantity[]" min="0.01" step="0.01" />';
          		html += '<select id="'+response.data.id+'" onchange="updateUnit(this);" class="form-control" name="c_unit[]">';
          		html += '<option value="'+response.data.primaryunit.id+'">'+response.data.primaryunit.name+'</option>';
	          	if(response.data.secondaryunit!="undefined" && parseFloat(response.data.secondary_unit_quantity) > 0) {
	            	html += '<option value="'+response.data.secondaryunit.id+'">'+response.data.secondaryunit.name+'</option>';  
	          	}
	          	if(response.data.tertiaryunit!="undefined" && parseFloat(response.data.tertiary_unit_quantity) > 0) {
	            	html += '<option value="'+response.data.tertiaryunit.id+'">'+response.data.tertiaryunit.name+'</option>';  
	          	}
	          	if(response.data.customunit!="undefined" && parseFloat(response.data.custom_unit_quantity) > 0) {
	            	html += '<option value="'+response.data.customunit.id+'">'+response.data.customunit.name+'</option>';  
	          	}
	          	if(response.data.bulkbuyunit!="undefined" && parseFloat(response.data.bulk_buy_unit_quantity) > 0) {
	            	html += '<option value="'+response.data.bulkbuyunit.id+'">'+response.data.bulkbuyunit.name+'</option>';  
	          	}
	          	if(response.data.wholesalepurchaseunit!="undefined" && parseFloat(response.data.wholesale_purchase_unit_quantity) > 0) {
	            	html += '<option value="'+response.data.wholesalepurchaseunit.id+'">'+response.data.wholesalepurchaseunit.name+'</option>';  
	          	}
	          	if(response.data.packweightunit!="undefined" && parseFloat(response.data.pack_weight_unit_quantity) > 0) {
	            	html += '<option value="'+response.data.packweightunit.id+'">'+response.data.packweightunit.name+'</option>';  
	          	}
	          	html += '</select>';
	          	$('.product-'+response.data.id).html(html);


          	}
	    });

	}

	$(document).on("keyup", ".c_quantity", function() {
	    var id      = this.id;
      var index   = invoice_items.findIndex(p => p.product_id == id);
	    var qty     = this.value;
	    var avstock = $('#avstock-'+id).val();

      @if($section!='purchase' && $section!='vendor_stock')
      	if(parseFloat(qty) > parseFloat(avstock)) {
            $('.stock-alert-'+id).html('Insufficient Stock');
            $('.c_quantity').val('');
        } else {
            $('.stock-alert-'+id).html('');
        }
        invoice_items[index].quantity = (parseFloat(qty) <= parseFloat(avstock)) ? qty : 0 ;
      @else 
        invoice_items[index].quantity = (parseFloat(qty) > 0) ? qty : 0 ;
      @endif

       

      @if($section!='purchase' && $section!='vendor_stock')
        if(parseFloat(invoice_items[index].quantity) > 0) {
          if(parseFloat(invoice_items[index].discount_price) > 0 && parseFloat(invoice_items[index].unit_price) > parseFloat(invoice_items[index].discount_price) ) {
              
              var total            = parseFloat(invoice_items[index].unit_price) * parseFloat(invoice_items[index].quantity);
              var dis_total        = parseFloat(invoice_items[index].discount_price) * parseFloat(invoice_items[index].quantity);
              var dis_val          = parseFloat(total) - parseFloat(dis_total);

              var discount_percent = (parseFloat(total) - (parseFloat(total) - parseFloat(dis_val))) / parseFloat(total) * 100;
              var discount_amount  = parseFloat(total) / 100 * parseFloat(discount_percent);
              
              invoice_items[index].discount        = (parseFloat(discount_percent)).toFixed(2);
              invoice_items[index].discount_amount = (parseFloat(discount_amount)).toFixed(2);

          }
        }          
      @endif

	});

	function updateUnit(elem) {

		var id    = $(elem).attr('id');
		var unit  = $(elem).val();
		var url   = '{!! route('products.show', [':productID']) !!}';
        url       = url.replace(':productID', id);
        var token = "{{ csrf_token() }}";
	    $.ajax({
          	type: 'GET',
          	data: {
              '_token': token,
              'id': id
          	},
         	url: url,
          	success: function (response) {

      		if(response.data.secondaryunit!='undefined' && response.data.secondary_unit==unit && parseFloat(response.data.secondary_unit_quantity)>0) {
      			
      			var quantity = parseFloat(response.data.secondary_unit_quantity);
      			var newUnit  = response.data.secondaryunit.name;

      		} else if(response.data.tertiaryunit!='undefined' && response.data.tertiary_unit==unit && parseFloat(response.data.tertiary_unit_quantity)>0) {

      			var quantity = parseFloat(response.data.tertiary_unit_quantity);
      			var newUnit  = response.data.tertiaryunit.name;

      		} else if(response.data.customunit!='undefined' && response.data.custom_unit==unit && parseFloat(response.data.custom_unit_quantity)>0) {

      			var quantity = parseFloat(response.data.custom_unit_quantity);
      			var newUnit  = response.data.customunit.name;

      		} else if(response.data.bulkbuyunit!='undefined' && response.data.bulk_buy_unit==unit && parseFloat(response.data.bulk_buy_unit_quantity)>0) {

      			var quantity = parseFloat(response.data.bulk_buy_unit_quantity);
      			var newUnit  = response.data.bulkbuyunit.name;

      		} else if(response.data.wholesalepurchaseunit!='undefined' && response.data.wholesale_purchase_unit==unit && parseFloat(response.data.wholesale_purchase_unit_quantity)>0) {

      			var quantity = parseFloat(response.data.wholesale_purchase_unit_quantity);
      			var newUnit  = response.data.wholesalepurchaseunit.name;

      		} else if(response.data.packweightunit!='undefined' && response.data.pack_weight_unit==unit && parseFloat(response.data.pack_weight_unit_quantity)>0) {

      			var quantity = parseFloat(response.data.pack_weight_unit_quantity);
      			var newUnit  = response.data.packweightunit.name;

      		} else {

      			var quantity = 1;
      			var newUnit  = response.data.primaryunit.name;

      		}

            @if($section=='purchase' || $section=='vendor_stock')
                purchase_price  = (parseFloat(response.data.purchase_price) * parseFloat(quantity)).toFixed(2);
                price           = (parseFloat(response.data.purchase_price) * parseFloat(quantity)).toFixed(2);
                discount_price  = (parseFloat(response.data.purchase_price) * parseFloat(quantity)).toFixed(2);
            @else 
                purchase_price  = (parseFloat(response.data.price) * parseFloat(quantity)).toFixed(2);
                price           = (parseFloat(response.data.price) * parseFloat(quantity)).toFixed(2);
                if(parseFloat(response.data.discount_price) > 0 && parseFloat(response.data.price) > parseFloat(response.data.discount_price) ) {
                  discount_price  = (parseFloat(response.data.discount_price) * parseFloat(quantity)).toFixed(2);  
                } else {
                  discount_price  = (parseFloat(response.data.price) * parseFloat(quantity)).toFixed(2);
                }  
            @endif

            stock_avilable  = (parseFloat(response.data.stock) / parseFloat(quantity)).toFixed(3); 


          	$('.price-'+id).html(price);
		        $('.purchase-price-'+id).html(purchase_price);
		        $('.stock-av-'+id).html(stock_avilable +' '+ newUnit);
		        $('#avstock-'+id).val(stock_avilable);
		        $('.c_quantity').trigger('change');

            var index   = invoice_items.findIndex(p => p.product_id == response.data.id);
            invoice_items[index].unit_price     = price;
            invoice_items[index].mrp            = purchase_price;
            invoice_items[index].discount_price = discount_price;
            invoice_items[index].unit           = unit;
            invoice_items[index].unit_name      = newUnit;

          }
	    });

	}

  $('.cart-form-submit').click(function() {
    $('#cartModal').modal('hide');
    cartarrayToTable();
  });

</script>

