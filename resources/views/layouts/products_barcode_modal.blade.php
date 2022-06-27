      
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

          @push('css_lib')
          @include('layouts.datatables_css')
          @endpush

          <input type="text" name="item_barcode" class="form-control" id="item_barcode" placeholder="Product Code" title="Type product code">
          @if(isset($party) && $party!='')
          <input type="hidden" name="party" id="party" value="{{$party}}">  
          @endif
          <table id="productTable" class="table table-bordered table-scroll small-first-col">
             <thead>
               <tr>
                 <th>ITEM NAME</th>
                 <th>ITEM CODE</th>
                 <th>SALES PRICE</th>
                 <th>PURCHASE PRICE</th>
                 <!-- <th>MRP</th> -->
                 <th>CURRENT STOCK</th>
                 <th>QUANTITY</th>  
               </tr>
             </thead>
             <tbody id="barcode-items">
              <tr>
                <td colspan="6">
                  <img width="100" src="{{url('images/scanner.png')}}" alt="Scan Barcode" title="Scan Barcode">  
                  <p><b>Scan items to add them to your invoice</b></p>
                </td>
              </tr>
             </tbody> 
          </table>

          @push('scripts_lib')
          @include('layouts.datatables_js')
          @endpush

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm add-items">Done</button>
      </div>

      <script>
        $('#item_barcode').on("keydown", function(e) {
            if (e.keyCode == 13) {
              var id = this.value;
              $.ajax({
                  url: "{{ url('/products/getProductDetails') }}",
                  type: 'post',
                  data: {
                    'product_id' : id,
                    '_token' : "{{ csrf_token() }}"
                  }
              })
              .done(function(response) {
                  if(response!='null') {
                      var datas = JSON.parse(response);
                      var options  = '<option value="'+datas.unit+'">'+datas.unit+'</option>';
                      if(datas.secondary_unit != '' && datas.conversion_rate!='') {
                        var extra_option = '<option value="'+datas.secondary_unit+'">'+datas.secondary_unit+'</option>';  
                      } else {
                        var extra_option = '';
                      }
                      $('#barcode-items').prepend('<tr><td>'+datas.name+'</td><td>'+datas.bar_code+'</td><td class="price'+datas.id+'">'+datas.price+'</td><td class="mrp'+datas.id+'">'+datas.purchase_price+'</td><td style="display: grid;"><span class="stock'+datas.id+'">'+datas.stock+' '+datas.unit+'</span> <small><span class="stock-alert-'+datas.id+' text-danger"></span></small></td><td class="item'+datas.id+'" style="display:inline-flex;"><input type="hidden" name="itemId[]" value="'+datas.id+'" /> <input type="hidden" id="avstock'+datas.id+'" value="'+datas.stock+'" /> <input type="number" class="item_quantity" style="width:100%;" step="any" id="'+datas.id+'" name="itemQuantity[]" value="1" /> <select onchange="updateUnit('+datas.id+',this);" class="form-control" name="itemUnit[]">'+options+''+extra_option+'</select></td></tr>');
                      
                      $('#item_barcode').val('');  
                  } else {
                      iziToast.error({title: 'Error', position: 'topRight', message: 'Item not available'});
                      $('#item_barcode').val('');  
                  }
              })
              .fail(function(response) {
                  //console.log(response);
              });

              e.preventDefault();
              return false;   
            }
       });
      </script>