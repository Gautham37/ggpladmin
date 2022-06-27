      
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

          <input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">
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
             <tbody>
               @foreach($products as $product)
                <tr>
                  <td>{{$product->name}}</td>
                  <td>{{$product->bar_code}}</td>
                  <td class="price{{$product->id}}">{{ number_format($product->price,2) }}</td>
                  <td class="mrp{{$product->id}}">{{ number_format($product->purchase_price,2) }}</td>
                  <td style="display: grid;"> <span class="stock{{$product->id}}"> {{ number_format($product->stock,2) }} {{ $product->unit }} </span> <small><span class="stock-alert-{{$product->id}} text-danger"></span></small></td>
                  <td class="item{{$product->id}}" style="display:inline-flex;">
                      <button id="{{$product->id}}" value="{{$product->id}}" type="button" onclick="addBasket(this);" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add</button>
                  </td>
                </tr>
               @endforeach
             </tbody> 
          </table>

          @push('scripts_lib')
          @include('layouts.datatables_js')
          @endpush

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-sm add-items">Done</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
      </div>

      <script>
        /*$('.modal-body').keydown(function(e){
           if (e.keyCode == 13) {
              //$('.add-items').trigger('click');
              e.preventDefault();
              return false; 
           } 
        });*/
      </script>