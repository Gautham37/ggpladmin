
  <style>
    .pro_name {
      font-size: 13px;
    }
    .table-scroll{
      /*width:100%; */
      display: block;
      empty-cells: show;
      
      /* Decoration */
      border-spacing: 0;
      height: 550px;       /* Just for the demo          */
      overflow-y: auto;    /* Trigger vertical scroll    */
      overflow-x: hidden;
    }

    .table-scroll thead{
      background-color: #f1f1f1;  
      position:absolute;
      display: block;
      width:94%;
      /*overflow-y: scroll;*/
    }

    .table-scroll tbody{
      /* Position */
      display: block; 
      /*position:relative;*/
      width:100%;
      /* Decoration */
      border-top: 1px solid rgba(0,0,0,0.2);
      margin-top: 40px;
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
    }
    .active-tab {
      background: #f4f6f9;
      font-weight: bold !important;
    }



    /**
     * tab panel widget
     */
    .tabPanel-widget {
      position: relative;  /* containing block for headings (top:0) */
      background: #fff;
    }

    /**
     * because labels come first in source order - we use z-index to move them in front of the headings
     */
    .tabPanel-widget > label {
      position: absolute;
      z-index: 1;
    }

    /**
     * labels and headings must share same values so grouping declarations in this rule prevents async edits (risk of breakage)
     * line-height == height -> vertical centering
     * the width dictates the offset for all headings but the first one: left offset = width * number of previous heading(s)
     * note that width and offset of label/heading pair can be customized if necessary
     */

    .tabPanel-widget > label,
    .tabPanel-widget > h2 {
      font-size: 1.1em;
      width: 9em;
      height: 3em;
      line-height: 3em;
      font-weight: bold;
    }

    /**
     * position:relative is for the markers (the down arrow in tabs)
     */
    .tabPanel-widget > h2 {
      position: relative;
      margin: 0;
      text-align: center;
      background: #fff;
      color: #585858;
    }

    .tabPanel-widget > label {
      border: 1px solid #e6e6e6;
    }

    /**
     * all first level labels and headings after the very first ones 
     */
    .tabPanel-widget > label ~ label,
    .tabPanel-widget > h2 ~ h2 {
      position: absolute;
      top: 0;
    }


    /**
     * We target all the label/heading pairs
     * we increment the :nth-child() params by 4 as well as the left value (according to "tab" width)
     */

    .tabPanel-widget label:nth-child(1),
    .tabPanel-widget h2:nth-child(3) {
      left: 0em;
    }

    .tabPanel-widget label:nth-child(5),
    .tabPanel-widget h2:nth-child(7) {
      left: 9em;
    }

    .tabPanel-widget label:nth-child(9),
    .tabPanel-widget h2:nth-child(11) {
      left: 18em;
    }

    /**
     * we visually hide all the panels
     * https://developer.yahoo.com/blogs/ydn/clip-hidden-content-better-accessibility-53456.html
     */
    .tabPanel-widget input + h2 + div {
      position: absolute !important;
      clip: rect(1px, 1px, 1px, 1px);
      padding:0 !important;
      border:0 !important;
      height: 1px !important; 
      width: 1px !important; 
      overflow: hidden;
    }
    /**
     * we reveal a panel depending on which control is selected 
     */
    .tabPanel-widget input:checked + h2 + div {
      position: static !important;
      padding: 1em !important;
      height: auto !important; 
      width: auto !important; 
    }

    /**
     * shows a hand cursor only to pointing device users
     */
    .tabPanel-widget label:hover {
      cursor: pointer;
    }

    .tabPanel-widget > div {
      background: #fff;
      padding: 1em;
    }

    /**
     * we hide radio buttons and also remove them from the flow
     */
    .tabPanel-widget input[name="tabs"] {
      opacity: 0;
      position: absolute;
    }


    /** 
     * this is to style the tabs when they get focus (visual cue)
     */

    .tabPanel-widget input[name="tabs"]:focus + h2 {
      outline: 1px dotted #000;
      outline-offset: 10px;
    }


    /**
     * reset of the above within the tab panel (for pointing-device users)
     */
    .tabPanel-widget:hover h2 {
      outline: none !important;
    }

    /**
     * visual cue of the selection
     */
    .tabPanel-widget input[name="tabs"]:checked + h2 {
      background: #f1f1f1;
      color: #28a745;
      border-bottom: 2px solid;
    }

    /**
     * the marker for tabs (down arrow)


    /**
     * Make it plain/simple below 45em (stack everything)
     */
    @media screen and (max-width: 45em) {
      
      /* hide unecessary label/control pairs */
      .tabPanel-widget label,
      .tabPanel-widget input[name="tabs"] {
        display: none;
      }
      
      /* reveal all panels */
      .tabPanel-widget > input + h2 + div {
        display: block !important;
        position: static !important;
        padding: 1em !important;
        height: auto !important; 
        width: auto !important; 
      }
      
      /* "unstyle" the heading */
      .tabPanel-widget h2 {
        width: auto;
        position: static !important;
        background: #999 !important;
      }
      
      /* "kill" the marker */
      .tabPanel-widget h2:after {
        display: none !important;
      }

    }

    
    main {
      min-width: auto;
      max-width: auto;
      margin: 0 auto;
      background: #fff;
    }
    
    section {
      display: none;
      padding: 20px 0 0;
      border-top: 1px solid #ddd;
    }
    
    input {
      display: none;
    }
    
    .tab-label {
      display: inline-block;
      margin: 0 0 -1px;
      padding: 10px 25px;
      font-weight: 600;
      text-align: center;
      color: #000;
      border: 1px solid transparent;
      width: calc(100% / 5);
    }
    
    .tab-label:before {
      font-family: fontawesome;
      font-weight: normal;
      margin-right: 10px;
    }
    
    /*label[for*='1']:before {
      content: '\f1cb';
    }
    
    label[for*='2']:before {
      content: '\f17d';
    }
    
    label[for*='3']:before {
      content: '\f16b';
    }
    
    label[for*='4']:before {
      content: '\f1a9';
    }*/
    
    .tab-label:hover {
      color: #888;
      cursor: pointer;
    }
    
    input:checked + .tab-label {
      color: #555;
      border: 1px solid #ddd;
      border-top: 2px solid orange;
      border-bottom: 1px solid #fff;
    }
    
    #tab1:checked ~ #content1,
    #tab2:checked ~ #content2,
    #tab3:checked ~ #content3,
    #tab4:checked ~ #content4 {
      display: block;
    }
    
    @media screen and (max-width: 650px) {
      label {
        font-size: 0;
      }
    
      label:before {
        margin: 0;
        font-size: 18px;
      }
    }
    @media screen and (max-width: 400px) {
      label {
        padding: 15px;
      }
    }
    
    
  </style>
  <!--<div class="col-md-3">

    <input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="{{trans('lang.search_items')}}" title="Type in a name">

    <table id="productTable" class="table table-bordered table-scroll small-first-col">
      <thead>
        <tr>
          <th><h6>Items</h6></th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $pro)
          <tr @if($product->id==$pro->id) class="active-tab" @endif>
            <td>
              <a style="color:#000;" href="{{route('products.view',$pro->id)}}">
                <p class="pro_name"><b>{{$pro->name}}</b></p>
                <p>
                  <span>{{trans('lang.stock_value')}} : <b>{{setting('default_currency')}} {{number_format(($pro->stock * $pro->price),2)}}</b></span>
                  <span class="float-right"><b>{{$pro->stock}} {{$pro->unit}}</b></span>
                </p>
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    
    
  </div>-->

  <div class="col-md-12 row">
    <div class="col-md-4">
      
      <div class="form-group">
        {!! Form::label('product', 'Product', ['class' => 'control-label text-right']) !!}
        <select class="select2 form-control products">
          <option value="">Select</option>
          @foreach($products as $pro)
          <option value="{{$pro->id}}" @if($product->id==$pro->id) selected @endif >{{$pro->name}}</option>
          @endforeach
        </select>
      </div>
      
    </div>
    <div class="col-md-8">

        @can('products.edit')
          <a title="Edit Product" href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning mt-4">
            <i class="fa fa-edit"></i> Edit Item
          </a>
        @endcan

        &nbsp;&nbsp;&nbsp;
        
        @can('products.destroy')
          <button data-id="{{$product->id}}" data-url="{{route('products.destroy', $product->id)}}"  onclick="deleteItemLoad(this)" class="btn btn-danger btn-sm mt-4">
            <i class="fa fa-trash"></i> Delete Item
          </button>
        @endcan
        
        &nbsp;&nbsp;&nbsp;
        
        @if($product->stock > 0) 
          <button class="btn btn-sm btn-success mt-4">{{trans('lang.pro_in_stock')}}</button>
        @else 
          <button class="btn btn-sm btn-danger mt-4">{{trans('lang.pro_out_stock')}}</button>
        @endif
        
        &nbsp;&nbsp;&nbsp;
        
        <a class="btn btn-sm btn-primary mt-4" href="{{ url('products/printBarcodes',$product->id) }}">Print Bar Codes</a>
      
    </div>
  </div>



  <div class="col-md-12">
    
    <main>
      
      <input id="tab1" type="radio" name="tabs" checked>
      <label class="tab-label" for="tab1">Product Details</label>
        
      <input id="tab2" type="radio" name="tabs">
      <label class="tab-label" for="tab2">Stock Details</label>
        
      <input id="tab3" type="radio" name="tabs">
      <label class="tab-label" for="tab3">Party Wise Report</label>
        
      <input id="tab4" type="radio" name="tabs">
      <label class="tab-label" for="tab4">Purchase & Sales History</label>
        
      <section id="content1">

        <div class="row">
            <div class="col-md-12">
              <table class="table">
                <tbody>
                   <tr>
                      <td width="18%" style="vertical-align: top;">
                          <br>
                          @if(isset($product) && $product->hasMedia('image'))
                              <img class="product-image" src="{!! url($product->getFirstMediaUrl('image','full')) !!}">
                           @else
                              <img class="product-image" src="{{asset('images/image_default.png')}}">
                           @endif
                      </td>
                      <td width="67%" style="vertical-align: top;">
                          <br>
                          <h5><b>{{$product->name}} - {{$product->name_lang_1}} - {{$product->name_lang_2}}</b></h5>
                          <p>{{strip_tags($product->description)}}</p>
                          <table style="width: 50%;">
                            <tr>
                              <td><b>Current Stock</b></td>
                              <td>:</td>
                              <td>{{number_format($product->stock,'3','.','')}} {{$product->primaryunit->name}}</td>
                            </tr>
                            <tr>
                              <td><b>Purchase Price</b></td>
                              <td>:</td>
                              <td>{{setting('default_currency')}}{{number_format($product->purchase_price,'2','.','')}}</td>
                            </tr>
                            <tr>
                              <td><b>Sale Price</b></td>
                              <td>:</td>
                              <td>
                                @if($product->discount_price > 0)
                                  <del>{{setting('default_currency')}}{{number_format($product->price,'2','.','')}}</del>
                                  {{setting('default_currency')}}{{number_format($product->discount_price,'2','.','')}}
                                @else
                                  {{setting('default_currency')}}{{number_format($product->price,'2','.','')}}
                                @endif
                              </td>
                            </tr>
                            <tr>
                              <td><b>Status</b></td>
                              <td>:</td>
                              <td>
                                {!! ($product->product_status=='active') ? '<a class="btn btn-sm btn-success text-white">Active</a>' : '<a class="btn btn-sm btn-danger text-white">In Active</a>' !!}
                              </td>
                            </tr>
                          </table>
                      </td>
                      <td width="15%" style="vertical-align: top;">
                          <br>
                          {!!DNS2D::getBarcodeHTML('ITEM '.$product->product_code, 'QRCODE',5,5)!!}
                          <br>
                          {!! DNS1D::getBarcodeHTML('ITEM '.$product->product_code, "C128",0.9,70) !!}
                      </td>
                   </tr>
                 </tbody>
              </table>
            </div>

            <div class="col-md-6">
               <table class="table ">
                  <tbody>
                     <tr>
                        <th width="30%">Name</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->name}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Name Hindi</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->name_lang_1}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Name Gujarati</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->name_lang_2}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Department</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{($product->department) ? $product->department->name : '' ;}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Category</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{($product->category) ? $product->category->name : '' ;}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Subcategory</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{($product->subcategory) ? $product->subcategory->name : '' ;}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Quality Grade</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{($product->qualitygrade) ? $product->qualitygrade->name : '' ;}}</td>
                     </tr>
                     <!-- <tr>
                        <th width="30%">Value Added Service Affiliated</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{($product->valueaddedservicesaffiliated) ? $product->valueaddedservicesaffiliated->name : '' ;}}</td>
                     </tr> -->
                     <tr>
                        <th width="30%">Product Status</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                            {!! ($product->product_status=='active') ? '<a class="btn btn-sm btn-success text-white">Active</a>' : '<a class="btn btn-sm btn-danger text-white">In Active</a>' !!}
                        </td>
                     </tr>
                     <tr>
                        <th width="30%">Stock Status</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{($product->stockstatus) ? $product->stockstatus->name : '' ;}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Season</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{($product->season) ? $product->season->name : '' ;}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Color</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{($product->color) ? $product->color->name : '' ;}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Nutrition</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          @if($product->nutritions && count($product->nutritions) > 0) 
                            @foreach($product->nutritions as $nutrition)
                              <span>{{$nutrition->productnutrition->name}},</span>
                            @endforeach
                          @endif
                        </td>
                     </tr>
                     <tr>
                        <th width="30%">Taste</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{($product->taste) ? $product->taste->name : '' ;}}</td>
                     </tr>
                     <tr>
                        <th width="30%">HSN Code</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->hsn_code}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Purchase Price</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{setting('default_currency')}}{{number_format($product->purchase_price,'2','.','')}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Sale Price</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{setting('default_currency')}}{{number_format($product->price,'2','.','')}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Discount Price</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{setting('default_currency')}}{{number_format($product->discount_price,'2','.','')}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Tax</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{number_format($product->tax,'2','.','')}}%</td>
                     </tr>
                     <tr>
                        <th width="30%">Primary Unit</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->primaryunit->name}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Low Stock Unit</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->low_stock_unit}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Opening Stock</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{number_format($product->opening_stock,3,'.','')}}</td>
                     </tr>
                     <!-- <tr>
                        <th width="30%">V.A.S Charges AMT</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->vas_charges_amt}}</td>
                     </tr>
                     <tr>
                        <th width="30%">V.A.S Charges Unit Quantity</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->vas_charges_unit_quantity}}</td>
                     </tr>
                     <tr>
                        <th width="30%">V.A.S Charges Unit Quantity</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->vas_charges_unit_quantity}}</td>
                     </tr> -->
                     <tr>
                        <th width="30%">Alternative Unit</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          {!! ($product->alternative_unit==1) ? '<a class="btn btn-sm btn-success text-white">Enable</a>' : '<a class="btn btn-sm btn-danger text-white">Disable</a>' !!}
                        </td>
                     </tr>

                     <tr>
                        <th width="30%">Secondary Unit</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          1 - {{($product->secondaryunit) ? $product->secondaryunit->name : '' }} - Quantity = {{number_format($product->secondary_unit_quantity,'3','.','')}}
                          {{$product->primaryunit->name}}
                        </td>
                     </tr>
                     <!-- <tr>
                        <th width="30%">Secondary Unit Quantity</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{number_format($product->secondary_unit_quantity,'3','.','')}}</td>
                     </tr> -->

                     <tr>
                        <th width="30%">Tertiary Unit</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          1 - {{($product->tertiaryunit) ? $product->tertiaryunit->name : '' }} - Quantity = {{number_format($product->tertiary_unit_quantity,'3','.','')}}
                          {{$product->primaryunit->name}}
                        </td>
                     </tr>
                     <!-- <tr>
                        <th width="30%">Tertiary Unit Quantity</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{number_format($product->tertiary_unit_quantity,'3','.','')}}</td>
                     </tr> -->

                     <tr>
                        <th width="30%">Custom Unit</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          1 - {{($product->customunit) ? $product->customunit->name : '' }} - Quantity = {{number_format($product->custom_unit_quantity,'3','.','')}}
                          {{$product->primaryunit->name}}
                        </td>
                     </tr>
                     <!-- <tr>
                        <th width="30%">Custom Unit Quantity</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{number_format($product->custom_unit_quantity,'3','.','')}}</td>
                     </tr> -->

                     <tr>
                        <th width="30%">Bulk Buy Unit</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          1 - {{($product->bulkbuyunit) ? $product->bulkbuyunit->name : '' }} - Quantity = {{number_format($product->bulk_buy_quantity,'3','.','')}}
                          {{$product->primaryunit->name}}
                        </td>
                     </tr>
                     <!-- <tr>
                        <th width="30%">Bulk Buy Unit Quantity</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{number_format($product->bulk_buy_quantity,'3','.','')}}</td>
                     </tr> -->

                     <tr>
                        <th width="30%">Wholesale Purchase Unit</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          1 - {{($product->wholesalepurchaseunit) ? $product->wholesalepurchaseunit->name : '' }} - Quantity = {{number_format($product->wholesale_purchase_unit_quantity,'3','.','')}}
                          {{$product->primaryunit->name}}
                        </td>
                     </tr>
                     <!-- <tr>
                        <th width="30%">Wholesale Purchase Unit Quantity</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{number_format($product->wholesale_purchase_unit_quantity,'3','.','')}}</td>
                     </tr> -->

                     <tr>
                        <th width="30%">Pack Weight Unit</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          1 - {{($product->packweightunit) ? $product->packweightunit->name : '' }} - Quantity = {{number_format($product->pack_weight_unit_quantity,'3','.','')}}
                          {{$product->primaryunit->name}}
                        </td>
                     </tr>
                     <!-- <tr>
                        <th width="30%">Pack Weight Unit Quantity</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{number_format($product->pack_weight_unit_quantity,'3','.','')}}</td>
                     </tr> -->
                  </tbody>
               </table>
            </div>
            <div class="col-md-6">
               <table class="table ">
                  <tbody>

                     <tr>
                        <th width="30%">Alpha</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->alpha}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Con</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->con}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Product Code</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->product_code}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Product Code Short</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->product_code_short}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Product Varient</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->product_varient}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Product Varient Number</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->product_varient_number}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Product Size</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->product_size}}</td>
                     </tr>
                     <!-- <tr>
                        <th width="30%">Spare</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->spare}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Spare 2</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->spare_2}}</td>
                     </tr> -->
                     <tr>
                        <th width="30%">Ave Weight if Known</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->ave_p_u_1_weight}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Ave Weight if Known</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->ave_p_u_1_weight}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Nutrition Benefit</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->nutrition_benefit}}</td>
                     </tr> 
                     <tr>
                        <th width="30%">Health Benefit</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->health_benefit}}</td>
                     </tr> 
                     <tr>
                        <th width="30%">Product Life</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->product_life}}</td>
                     </tr> 
                     <tr>
                        <th width="30%">Ambient Temprature</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->ambient_temprature}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Storage Type</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->storage_type}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Storage Method</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->storage_method}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Range Standard</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->range_standard}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Short Description Product Code</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->short_description_product_code}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Current Stock Level</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->stock_level}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Stock Purchased date</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->stock_purchased_date}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Alternative Weight in KG</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->alternate_weight_kg}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Other Key Search Words</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->other_key_search_words}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Source Confirm</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->source_confirm}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Reason For Discontinuation</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->reason_discontinuation}}</td>
                     </tr> 

                     <tr>
                        <th width="30%">Sugar Level</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$product->sugar_level}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Weight Loss</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          {!! ($product->weight_loss==1) ? '<a class="btn btn-sm btn-success text-white">Yes</a>' : '<a class="btn btn-sm btn-warning text-white">No</a>' !!}
                        </td>
                     </tr>
                     <tr>
                        <th width="30%">Freeze Well</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          {!! ($product->freeze_well==1) ? '<a class="btn btn-sm btn-success text-white">Yes</a>' : '<a class="btn btn-sm btn-warning text-white">No</a>' !!}
                        </td>
                     </tr>
                     <tr>
                        <th width="30%">Grow on tree</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          {!! ($product->grow_on_tree==1) ? '<a class="btn btn-sm btn-success text-white">Yes</a>' : '<a class="btn btn-sm btn-warning text-white">No</a>' !!}
                        </td>
                     </tr>
                     <tr>
                        <th width="30%">Salad Vegetable</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          {!! ($product->salad_vegetable==1) ? '<a class="btn btn-sm btn-success text-white">Yes</a>' : '<a class="btn btn-sm btn-warning text-white">No</a>' !!}
                        </td>
                     </tr>
                     <tr>
                        <th width="30%">Featured</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          {!! ($product->featured==1) ? '<a class="btn btn-sm btn-success text-white">Yes</a>' : '<a class="btn btn-sm btn-warning text-white">No</a>' !!}
                        </td>
                     </tr>
                     <tr>
                        <th width="30%">Deliverable</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          {!! ($product->deliverable==1) ? '<a class="btn btn-sm btn-success text-white">Yes</a>' : '<a class="btn btn-sm btn-warning text-white">No</a>' !!}
                        </td>
                     </tr>
                     <tr>
                        <th width="30%">Online Store</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                          {!! ($product->online_store==1) ? '<a class="btn btn-sm btn-success text-white">Visible</a>' : '<a class="btn btn-sm btn-warning text-white">Not Visible</a>' !!}
                        </td>
                     </tr>

                     <!-- <tr>
                        <th width="30%">Description</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$product->description!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Product Image</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           @if(isset($product) && $product->hasMedia('image'))
                              <img class="party-avatar" src="{!! url($product->getFirstMediaUrl('image','full')) !!}">
                           @else
                              <img class="party-avatar" src="{{asset('images/image_default.png')}}">
                           @endif
                        </td>
                     </tr> -->

                  </tbody>
               </table>
            </div>

            <div class="col-md-6">
              <h6><b>Price Multipliers</b></h6>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-left">Purchase Quantity From</th>
                    <th class="text-left">Purchase Quantity To</th>
                    <th class="text-center">Price Multiplier (%)</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($product->pricevaritations) > 0)
                    @foreach($product->pricevaritations as $variation)
                      <tr>
                        <td class="text-left" width="33%">{{number_format($variation->purchase_quantity_from,'3','.','')}}</td>
                        <td class="text-left" width="33%">{{number_format($variation->purchase_quantity_to,'3','.','')}}</td>
                        <td class="text-center" width="33%">{{$variation->price_multiplier}}</td>
                      </tr>
                    @endforeach
                  @else 
                    <tr>
                      <td class="text-center" colspan="2">No Variations Available</td>
                    </tr>  
                  @endif
                </tbody>
              </table>
            </div>

            <div class="col-md-6">
              <h6><b>Value Added Services & Price</b></h6>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-left">Service</th>
                    <th class="text-center">Price (%)</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($product->vasservices) > 0)
                    @foreach($product->vasservices as $vas)
                      <tr>
                        <td class="text-left" width="50%">{{$vas->vas->name}}</td>
                        <td class="text-center" width="50%">{{number_format($vas->price,'2','.','')}}</td>
                      </tr>
                    @endforeach
                  @else 
                    <tr>
                      <td class="text-center" colspan="2">No Value added services available</td>
                    </tr>  
                  @endif
                </tbody>
              </table>
            </div>

         </div>
     
      </section>
        
      <section id="content2">
        <div class="row">
            <div class="col-md-4 form-group">
                <div id="reportrange"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> 
                </div>  
            </div>
            
            <div class="col-md-8 text-right">
              
              <!--<button id="report-st" value="export" class="btn export-buttons btn-primary btn-sm"> 
                <span><i class="fa fa-file-excel-o"></i></span> 
              </button>
              
              &nbsp;&nbsp;&nbsp;-->
			        <button id="report-st" value="download" class="btn export-buttons btn-danger btn-sm" title="Download PDF">
                <span><i class="fa fa-file-pdf-o"></i></span>
              </button>
              
              &nbsp;&nbsp;&nbsp;
              
              <button id="report-st" value="print" class="btn export-buttons btn-primary btn-sm" title="Print">
                <span><i class="fa fa-print"></i></span> 
              </button>

            </div>
            
        </div>  
        @include('products.stock_table')
      </section>
      
        
      <section id="content3">
        <div class="row">
            <div class="col-md-4 form-group">
                <div id="reportrange1"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> 
                </div>  
            </div>
            <div class="col-md-8 text-right">
              <!-- <button id="report-pr" value="export" class="btn export-buttons btn-primary btn-sm" title="Download Excel">
                <span><i class="fa fa-file-excel-o"></i></span>
              </button>
              &nbsp;&nbsp;&nbsp; -->
              <button id="report-pr" value="download" class="btn export-buttons btn-danger btn-sm" title="Download PDF">
                <span><i class="fa fa-file-pdf-o"></i></span>
              </button>
              &nbsp;&nbsp;&nbsp;
              <button id="report-pr" value="print" class="btn export-buttons btn-primary btn-sm" title="Print">
                <span><i class="fa fa-print"></i></span>
              </button>					  
            </div>
        </div>
        @include('products.party_wise_stock_table')
      </section>

        
      <section id="content4">
         <div class="row">
            <div class="col-md-4 form-group">
                <div id="reportrange2"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> 
                </div>  
            </div>
            <div class="col-md-8 text-right">
              <!-- <button id="report-ph" value="export" class="btn export-buttons btn-primary btn-sm">Excel Download 
                <span><i class="fa fa-file-excel-o"></i></span> 
              </button>
              &nbsp;&nbsp;&nbsp; -->
              <button id="report-ph" value="download" class="btn export-buttons btn-danger btn-sm" title="Download PDF">
                <span><i class="fa fa-file-pdf-o"></i></span>
              </button>
              &nbsp;&nbsp;&nbsp;
              <button id="report-ph" value="print" class="btn export-buttons btn-primary btn-sm" title="Print">
                <span><i class="fa fa-print"></i></span>
              </button>
            </div>
        </div>
        @include('products.product_purchase_history_table')
      </section>

        
    </main>
    
    <div style="clear:both;height:30px;"></div>
  </div>








