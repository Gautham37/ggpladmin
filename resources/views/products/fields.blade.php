@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
    <div class="row">
    
    <input type="hidden" id="cat_count" value="{{$category_count+1}}">

    <!-- <div class="col-md-8"> -->
    
    <!-- Name Field -->
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('name', trans("lang.product_name"), ['class' => ' control-label text-left required']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_name_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
                {{ trans("lang.product_name_help") }}
            </div> -->
        </div>
    </div>    

    <!-- </div>
    <div class="col-md-4">
    </div> -->

    <!--Product Type Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('product_type', trans("lang.product_type"),['class' => 'control-label text-right required']) !!}
            <div class="input-group-append">
                {!! Form::select('product_type', array('0' => 'Please Select', '1' => 'Standard Products', '2' => 'Organic Products', '3' => 'GMO Products', '4' => 'Confectionaries', '5'=>'Non GMO Products', '6'=>'Value Added Products'), null, ['class' => 'select2 form-control product_type','id'=>'product_type']) !!}
            </div>
        </div>
    </div>
    
    <!-- Department Id Field -->
    <div class="col-md-3">
        <div class="form-group add-btn-product-page">
            {!! Form::label('department_id', trans("lang.department"),['class' => ' control-label text-left required']) !!}
            <div class="input-group-append">
                {!! Form::select('department_id', $departments, null, ['class' => 'select2 form-control department_id']) !!}
                @if(!isset($product))
                    <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> 
                        <a  href="" data-toggle="modal" data-target="#DeptModal"><i class="fa fa-plus mr-1"></i></a>
                    </span>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Category Id Field -->
    <div class="col-md-3">
        <div class="form-group ">
            {!! Form::label('category_id', trans("lang.product_category_id"),['class' => ' control-label text-left required']) !!}
            <div class="input-group-append">
                {!! Form::select('category_id', $category, $categorySelected, ['class' => 'select2 form-control category_id','id'=>'category']) !!}
                @if(!isset($product))
                    <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#CategoryModal"><i class="fa fa-plus mr-1"></i></a></i></span>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Subcategory Id Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('subcategory_id', trans("lang.subcategory"),['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
            {!! Form::select('subcategory_id', $subcategory, $subcategorySelected, ['class' => 'select2 form-control subcategory','id'=>'subcategory']) !!}
            @if(!isset($product))
                <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#SubCategoryModal"><i class="fa fa-plus mr-1"></i></a></i></span>
            @endif
            </div>
        </div>
    </div>

    
    <!-- quality grade Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('quality_grade', trans("lang.quality_grade"),['class' => 'control-label text-right']) !!}      
            <div class="input-group-append">
            {!! Form::select('quality_grade', $quality_grade, null, ['class' => 'select2 form-control quality_grade','id'=>'quality_grade']) !!}
            @if(!isset($product))
                <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#QualitygradeModal"><i class="fa fa-plus mr-1"></i></a></i></span>
            @endif
            </div>
        </div>
    </div>
    
    

    <!-- product status Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('product_status', trans("lang.product_status"),['class' => 'control-label text-right']) !!}  
            <div class="input-group-append">
                {!! Form::select('product_status', ['active'=>'Active','inactive'=>'Inactive'], null, ['class' => 'select2 form-control product_status','id'=>'product_status']) !!}
            </div>
        </div>
    </div>
    
    <!-- stock status Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('stock_status', trans("lang.stock_status"),['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
                {!! Form::select('stock_status', $stock_status, null, ['class' => 'select2 form-control']) !!}
                @if(!isset($product))
                    <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#StockstatusModal"><i class="fa fa-plus mr-1"></i></a></i></span>
                @endif
            </div>
        </div>
    </div>


    <!-- Product Season Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('season_id', 'Season',['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
                {!! Form::select('season_id', $seasons, null, ['class' => 'select2 form-control']) !!}
                @if(!isset($product))
                    <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#productSeasonModal"><i class="fa fa-plus mr-1"></i></a></i></span>
                @endif
            </div>
        </div>
    </div>


    <!-- Product Color Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('color_id', 'Color',['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
                {!! Form::select('color_id', $colors, null, ['class' => 'select2 form-control']) !!}
                @if(!isset($product))
                    <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#productColorModal"><i class="fa fa-plus mr-1"></i></a></i></span>
                @endif
            </div>
        </div>
    </div>


    <!-- Product Nutrition Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('nutrition_id[]', 'Nutrition',['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
                {!! Form::select('nutrition_id[]', $nutritions, isset($product->nutritions) ? $product->nutritions->pluck('product_nutrition_id') : null , ['class' => 'select2 form-control','multiple'=>'multiple']) !!}
                @if(!isset($product))
                    <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#productNutritionModal"><i class="fa fa-plus mr-1"></i></a></i></span>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Taste Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('taste_id', 'Taste',['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
                {!! Form::select('taste_id', $tastes, null, ['class' => 'select2 form-control']) !!}
                @if(!isset($product))
                    <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#productTasteModal"><i class="fa fa-plus mr-1"></i></a></i></span>
                @endif
            </div>
        </div>
    </div>

    <!-- HSN Code Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('hsn_code', trans("lang.product_hsn_code"), ['class' => ' control-label text-right required']) !!}
            {!! Form::text('hsn_code', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_hsn_code_placeholder")]) !!}
        </div>
    </div>

    <div class="col-md-12">
        <hr>
    </div>

    <div class="col-md-12 column custom-from-css">
        <div class="row">

        <!-- Purchse Price Field -->
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('purchase_price', trans("lang.product_purchase_price"), ['class' => 'control-label text-right required']) !!}
                {!! Form::number('purchase_price', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_purchase_price_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            </div>
        </div>
        
        <!-- Price Field -->
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('price', trans("lang.product_price"), ['class' => ' control-label text-right required']) !!}
                {!! Form::number('price', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_price_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            </div>
        </div>

        <!-- Discount Price Field -->
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('discount_price', trans("lang.product_discount_price"), ['class' => ' control-label text-right']) !!}
                {!! Form::number('discount_price', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_discount_price_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            </div>
        </div>

        <!-- Tax rate Field -->
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('tax', trans("lang.product_tax"),['class' => ' control-label text-right']) !!}
                {!! Form::select('tax', $tax_rates, $taxSelected, ['class' => 'select2 form-control']) !!}
            </div>
        </div>

        @if(isset($bar_code))    
        <input type="hidden" name="bar_code" id="bar_code" value="{{$bar_code}}">
        @endif 

        <!-- Price Variations Field -->
        <div class="col-md-3">
            <div class="form-group">
                <label>
                    Price Multiplier
                    <span data-toggle="tooltip" data-placement="top" data-original-title="Used for Product Price variation based on purchase quantity Example : 1.000 KG sell into 100.00 and 2.000 KG sell into 180.00 "> 
                      <i class="fa fa-info-circle"></i> 
                    </span>
                </label>
                <table id="price-variation-table" class="table table-bordered mt-1">
                    <thead style="background: #dfe3e8;">
                        <tr class="text-center">
                            <th>Purchase Quantity </th>
                            <th>Price Multiplier (%)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="price-variation-items">
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-center">
                                <button type="button" class="btn btn-sm btn-primary text-center price-variation-btn">
                                   <i class="fa fa-plus"></i> &nbsp; Add new price varitation
                                </button>
                            </td>
                        </tr>
                    </tfoot>    
                </table>
            </div>
        </div>


        <!-- VAS Services Field -->
        <div class="col-md-3">
            <div class="form-group">
                <label>
                    Value Added Services
                    <span data-toggle="tooltip" data-placement="top" data-original-title="Used for Product extra services. Example : Chopping, Slicing"> 
                      <i class="fa fa-info-circle"></i> 
                    </span>
                </label>
                <table id="vas-service-table" class="table table-bordered mt-1">
                    <thead style="background: #dfe3e8;">
                        <tr class="text-center">
                            <th>Service</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="vas-service-items">
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-center">
                                <button type="button" class="btn btn-sm btn-primary text-center vas-service-btn">
                                   <i class="fa fa-plus"></i> &nbsp; Add new VAS Service
                                </button>
                            </td>
                        </tr>
                    </tfoot>    
                </table>
            </div>
        </div>


        <div class="col-md-3">    
            <div class="form-group">
                {!! Form::label('customer_group_id[]', trans("lang.customer_group").' ('.trans("lang.product_price").')',['class' => ' text-left control-label text-right']) !!}
                {!! Form::select('customer_group_id[]', $customer_groups, $CustomerGroupsSelected, ['class' => 'select2 form-control', 'multiple'=>'multiple', 'id'=>'dropdownss']) !!}
            </div>
        </div>

        <div class="col-md-3">
            <div id="textboxDiv" class="row"></div>
        </div>

        </div>
    </div>

    <div class="col-md-12">
        <hr>
    </div>

    <!-- unit Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('unit', trans("lang.product_unit"), ['class' => ' control-label text-left']) !!}
            {!! Form::select('unit', $uom, isset($product->unit) ? $product->unit : 19 , ['class' => 'select2 form-control']) !!}
            <!-- <div class="form-text text-muted">
                {{ trans("lang.product_unit_help") }}
            </div> -->
        </div>
    </div>

    <!-- Low stock unit Field -->
    <div class="col-md-3" >
        <div class="form-group">
            {!! Form::label('low_stock_unit', trans("lang.product_low_stock_unit"), ['class' => ' control-label text-left']) !!}
            {!! Form::number('low_stock_unit', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_low_stock_unit_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            <!-- <div class="form-text text-muted">
                {{ trans("lang.product_low_stock_unit_help") }}
            </div> -->
        </div>
    </div>

    @if(!isset($product->stock))
    <!-- Opening Stock Field -->
    <div class="col-md-3">
        <div class="form-group">
        {!! Form::label('unit', trans("lang.product_opening_stock"), ['class' => ' control-label text-left']) !!}
        {!! Form::number('stock', null,  ['class' => 'form-control', 'placeholder'=>  trans("lang.product_opening_stock_placeholder")]) !!}
            <!-- <div class="form-text text-muted">
                {{ trans("lang.product_opening_stock_help") }}
            </div>  -->
        </div> 
    </div>        
    @endif

    <!-- 'Boolean Low stock warning Field' -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('alternative_unit', trans("lang.product_alternative_unit"),['class' => ' control-label text-left']) !!}
            {!! Form::select('alternative_unit', ['0' => 'Disable', '1' => 'Enable'], null, ['class' => 'select2 form-control', 'onchange' => 'enableAlternativeUnit(this);']) !!}
        </div>
    </div>

    <?php /* ?>
    <!-- Capacity Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('capacity', trans("lang.product_capacity"), ['class' => ' control-label text-left']) !!}
            {!! Form::number('capacity', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_capacity_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
            <!-- <div class="form-text text-muted">
                {{ trans("lang.product_capacity_help") }}
            </div> -->
        </div>
    </div>
    <?php /*/ ?>

    <!-- value added service affiliated Field -->
    <!-- <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('value_added_service_affiliated', trans("lang.value_added_service_affiliated"),['class' => 'control-label text-right']) !!}
            <div class="input-group-append">
                {!! Form::select('value_added_service_affiliated', $value_added_service_affiliated, null, ['class' => 'select2 form-control value_added_service_affiliated','id'=>'value_added_service_affiliated']) !!}
                @if(!isset($product))
                    <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a  href="" data-toggle="modal" data-target="#ValueaddedserviceModal"><i class="fa fa-plus mr-1"></i></a></i></span>
                @endif
            </div>
        </div>
    </div> -->

    <!-- package_items_count Field -->
    <!-- <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('package_items_count', trans("lang.product_package_items_count"), ['class' => ' control-label text-left']) !!}
            {!! Form::number('package_items_count', null,  ['class' => 'form-control','placeholder'=>  trans("lang.product_package_items_count_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
        </div>
    </div> -->

    <!-- vas_charges_amt Field -->
    <!-- <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('vas_charges_amt', trans("lang.vas_charges_amt"), ['class' => ' control-label text-left']) !!}
            {!! Form::number('vas_charges_amt', null,  ['class' => 'form-control','placeholder'=>  trans("lang.vas_charges_amt_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
        </div>
    </div> -->
    
     <!-- vas_charges_unit_quantity Field -->
     <!-- <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('vas_charges_unit_quantity', trans("lang.vas_charges_unit_quantity"), ['class' => ' control-label text-left']) !!}
            {!! Form::number('vas_charges_unit_quantity', null,  ['class' => 'form-control','placeholder'=>  trans("lang.vas_charges_unit_quantity_placeholder"),'step'=>"any", 'min'=>"0"]) !!}
        </div>
    </div> -->
    
    

    <div class="col-md-8">
    </div>
    
    <?php $uom[null] = 'Please Select'; ?>

    <div class="col-md-3 secondary_unit offset-md-3" style="display:none;">
        <div class="form-group">
            {!! Form::label('secondary_unit', trans("lang.product_secondary_unit"), ['class' => ' control-label text-left']) !!}
            {!! Form::select('secondary_unit', $uom, null, ['class' => ' form-control']) !!}
        </div>
    </div>
    
    <div class="col-md-3 conversion_rate" style="display:none;">
        <div class="form-group">
            {!! Form::label('secondary_unit_quantity', trans("lang.product_secondary_unit_quantity"), ['class' => ' control-label text-left']) !!}
            <div class="input-group">
                <span class="input-group-addon" style="padding:5px; flex:65px !important; background:#dce4ec;">
                    1 <span class="sec_unit"></span> =   
                </span>
                {!! Form::text('secondary_unit_quantity', null,  ['class' => 'form-control']) !!}
                <span class="input-group-addon" style="padding:5px; width:65px !important; background:#dce4ec;">
                    <span class="primary_unit"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>


    <div class="col-md-3 tertiary_unit offset-md-3" style="display:none;">
        <div class="form-group">
            {!! Form::label('tertiary_unit', trans("lang.product_tertiary_unit"), ['class' => ' control-label text-left']) !!}
            {!! Form::select('tertiary_unit', $uom, null, ['class' => ' form-control']) !!}
        </div>
    </div>
    
    <div class="col-md-3 conversion_rate" style="display:none;">
        <div class="form-group">
            {!! Form::label('tertiary_unit_quantity', trans("lang.product_tertiary_unit_quantity"), ['class' => ' control-label text-left']) !!}
            <div class="input-group">
                <span class="input-group-addon" style="padding:5px; flex:65px !important; background:#dce4ec;">
                    1 <span class="ter_unit"></span> = 
                </span>
                {!! Form::text('tertiary_unit_quantity', null,  ['class' => 'form-control']) !!}
                <span class="input-group-addon" style="padding:5px; width:65px !important; background:#dce4ec;">
                    <span class="primary_unit"></span> 
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>


    <div class="col-md-3 custom_unit offset-md-3" style="display:none;">
        <div class="form-group">
            {!! Form::label('custom_unit', trans("lang.product_custom_unit"), ['class' => ' control-label text-left']) !!}
            {!! Form::select('custom_unit', $uom, null, ['class' => ' form-control']) !!}
        </div>
    </div>
    
    <div class="col-md-3 conversion_rate" style="display:none;">
        <div class="form-group">
            {!! Form::label('custom_unit_quantity', trans("lang.product_custom_unit_quantity"), ['class' => ' control-label text-left']) !!}
            <div class="input-group">
                <span class="input-group-addon" style="padding:5px; flex:65px !important; background:#dce4ec;">
                    1 <span class="cus_unit"></span> = 
                </span>
                {!! Form::text('custom_unit_quantity', null,  ['class' => 'form-control']) !!}
                <span class="input-group-addon" style="padding:5px; width:65px !important; background:#dce4ec;">
                    <span class="primary_unit"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>


    <div class="col-md-3 bulk_buy_unit offset-md-3" style="display:none;">
        <div class="form-group">
            {!! Form::label('bulk_buy_unit', trans("lang.product_bulk_buy_unit"), ['class' => ' control-label text-left']) !!}
            {!! Form::select('bulk_buy_unit', $uom, null, ['class' => ' form-control']) !!}
        </div>
    </div>
    
    <div class="col-md-3 conversion_rate" style="display:none;">
        <div class="form-group">
            {!! Form::label('bulk_buy_unit_quantity', trans("lang.product_bulk_buy_unit_quantity"), ['class' => ' control-label text-left']) !!}
            <div class="input-group">
                <span class="input-group-addon" style="padding:5px; flex:65px !important; background:#dce4ec;">
                    1 <span class="buyb_unit"></span> = 
                </span>
                {!! Form::text('bulk_buy_unit_quantity', null,  ['class' => 'form-control']) !!}
                <span class="input-group-addon" style="padding:5px; width:65px !important; background:#dce4ec;">
                    <span class="primary_unit"></span> 
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>


    <div class="col-md-3 wholesale_purchase_unit offset-md-3" style="display:none;">
        <div class="form-group">
            {!! Form::label('wholesale_purchase_unit', trans("lang.product_wholesale_purchase_unit"), ['class' => ' control-label text-left']) !!}
            {!! Form::select('wholesale_purchase_unit', $uom, null, ['class' => ' form-control']) !!}
        </div>
    </div>
    
    <div class="col-md-3 conversion_rate" style="display:none;">
        <div class="form-group">
            {!! Form::label('wholesale_purchase_unit_quantity', trans("lang.product_wholesale_purchase_unit_quantity"), ['class' => ' control-label text-left']) !!}
            <div class="input-group">
                <span class="input-group-addon" style="padding:5px; flex:65px !important; background:#dce4ec;">
                    1 <span class="wp_unit"></span> = 
                </span>
                {!! Form::text('wholesale_purchase_unit_quantity', null,  ['class' => 'form-control']) !!}
                <span class="input-group-addon" style="padding:5px; width:65px !important; background:#dce4ec;">
                    <span class="primary_unit"></span> 
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>


    <div class="col-md-3 pack_weight_unit offset-md-3" style="display:none;">
        <div class="form-group">
            {!! Form::label('pack_weight_unit', trans("lang.product_pack_weight_unit"), ['class' => ' control-label text-left']) !!}
            {!! Form::select('pack_weight_unit', $uom, null, ['class' => ' form-control']) !!}
        </div>
    </div>
    
    <div class="col-md-3 conversion_rate" style="display:none;">
        <div class="form-group">
            {!! Form::label('pack_weight_unit_quantity', trans("lang.product_pack_weight_unit_quantity"), ['class' => ' control-label text-left']) !!}
            <div class="input-group">
                <span class="input-group-addon" style="padding:5px; flex:65px !important; background:#dce4ec;">
                    1 <span class="pac_unit"></span>  = 
                </span>
                {!! Form::text('pack_weight_unit_quantity', null,  ['class' => 'form-control']) !!}
                <span class="input-group-addon" style="padding:5px; width:65px !important; background:#dce4ec;">
                    <span class="primary_unit"></span> 
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
    


    <div class="col-md-12">
        <hr>
    </div>
    

    <!-- alpha Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('alpha', trans("lang.alpha"), ['class' => ' control-label text-left']) !!}
            {!! Form::text('alpha', null,  ['class' => 'form-control','placeholder'=>""]) !!}
        </div>
    </div>
    
    <!-- product_code_short Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('product_code_short', trans("lang.product_code_short"), ['class' => ' control-label text-left']) !!}
            {!! Form::text('product_code_short', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
        </div>
    </div>
    
    <!-- product_varient Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('product_varient', trans("lang.product_varient"), ['class' => ' control-label text-left']) !!}
            {!! Form::select('product_varient',[null=>'Please Select','yes'=>'Yes','no'=>'No'], null,  ['class' => 'select2 form-control']) !!}
        </div>
    </div>
    
    <!-- product_code_short Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('product_varient_number', trans("lang.product_varient_number"), ['class' => ' control-label text-left']) !!}
            {!! Form::text('product_varient_number', null,  ['class' => 'form-control','placeholder'=>""]) !!}
        </div>
    </div>
    
    <!-- Con Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('con', "Con", ['class' => ' control-label text-left']) !!}
            {!! Form::text('con', null,  ['class' => 'form-control','placeholder'=>""]) !!}
        </div>
    </div>

    <!-- Product Code Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('product_code', "Product Code", ['class' => ' control-label text-left']) !!}
            {!! Form::text('product_code', null,  ['class' => 'form-control','placeholder'=>""]) !!}
        </div>
    </div>

    <!-- Short Product Code Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('short_product_code', "Short Product Code", ['class' => ' control-label text-left']) !!}
            {!! Form::text('short_product_code', null,  ['class' => 'form-control','placeholder'=>""]) !!}
        </div>
    </div>

    <!-- product_size Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('product_size', trans("lang.product_size"), ['class' => ' control-label text-left']) !!}
            {!! Form::select('product_size',array(0=>'Please Select',1=>'Small',2=>'Medium',3=>'Large'), null,  ['class' => 'select2 form-control']) !!}
        </div>
    </div>
    
    <!-- spare Field -->
    <!-- <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('spare', trans("lang.spare"), ['class' => ' control-label text-left']) !!}
            {!! Form::text('spare', null,  ['class' => 'form-control','placeholder'=>  ""]) !!}
        </div>
    </div> -->
    
    <!-- spare_2 Field -->
    <!-- <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('spare_2', trans("lang.spare_2"), ['class' => ' control-label text-left']) !!}
            {!! Form::text('spare_2', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
        </div>
    </div> -->
    
    
    <!-- ave_weight_if_known Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('ave_weight_if_known', trans("lang.ave_weight_if_known"), ['class' => ' control-label text-left']) !!}
            {!! Form::text('ave_weight_if_known', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
        </div>
    </div>

    <!-- nutrition_benefit Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('nutrition_benefit', trans("lang.nutrition_benefit"), ['class' => ' control-label text-left']) !!}
             @if(isset($productDetails))
            {!! Form::text('nutrition_benefit', $productDetails->nutrition_benefit,  ['class' => 'form-control','placeholder'=>  trans("lang.nutrition_benefit_placeholder")]) !!}
            @else
            {!! Form::text('nutrition_benefit', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
            @endif
        </div>
    </div>

    <!-- health_benefit Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('health_benefit', trans("lang.health_benefit"), ['class' => ' control-label text-left']) !!}
             @if(isset($productDetails))
            {!! Form::text('health_benefit', $productDetails->health_benefit,  ['class' => 'form-control','placeholder'=>  trans("lang.health_benefit_placeholder")]) !!}
             @else
            {!! Form::text('health_benefit', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
            @endif
        </div>
    </div>
    
    <!-- product_life Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('product_life', trans("lang.product_life"), ['class' => ' control-label text-left']) !!}
             @if(isset($productDetails))
            {!! Form::text('product_life', $productDetails->product_life,  ['class' => 'form-control','placeholder'=>  trans("lang.product_life_placeholder")]) !!}
              @else
             {!! Form::text('product_life', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
            @endif
        </div>
    </div>
    
    <!-- ambient_temprature Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('ambient_temprature', trans("lang.ambient_temprature"), ['class' => ' control-label text-left']) !!}
             @if(isset($productDetails))
            {!! Form::text('ambient_temprature', $productDetails->ambient_temprature,  ['class' => 'form-control','placeholder'=>  trans("lang.ambient_temprature_placeholder")]) !!}
              @else
             {!! Form::text('ambient_temprature', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
            @endif
        </div>
    </div>
    
    <!-- storage_type Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('storage_type', trans("lang.storage_type"), ['class' => ' control-label text-left']) !!}
             @if(isset($productDetails))
            {!! Form::text('storage_type', $productDetails->storage_type,  ['class' => 'form-control','placeholder'=>  trans("lang.storage_type_placeholder")]) !!}
             @else
             {!! Form::text('storage_type', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
            @endif
        </div>
    </div>
    
    <!-- storage_method Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('storage_method', trans("lang.storage_method"), ['class' => ' control-label text-left']) !!}
             @if(isset($productDetails))
            {!! Form::text('storage_method', $productDetails->storage_method,  ['class' => 'form-control','placeholder'=>  trans("lang.storage_method_placeholder")]) !!}
              @else
             {!! Form::text('storage_method', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
            @endif
        </div>
    </div>
    
    <!-- range_standard Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('range_standard', trans("lang.range_standard"), ['class' => ' control-label text-left']) !!}
             @if(isset($productDetails))
            {!! Form::text('range_standard', $productDetails->range_standard,  ['class' => 'form-control','placeholder'=>  trans("lang.range_standard_placeholder")]) !!}
            @else
             {!! Form::text('range_standard', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
            @endif
        </div>
    </div>
    
    <!-- short_description_product_code Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('short_description_product_code', trans("lang.short_description_product_code"), ['class' => ' control-label text-left']) !!}
             @if(isset($productDetails))
            {!! Form::text('short_description_product_code', $productDetails->short_description_product_code,  ['class' => 'form-control','placeholder'=>  trans("lang.short_description_product_code_placeholder")]) !!}
             @else
             {!! Form::text('short_description_product_code', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
            @endif
        </div>
    </div>
    
    <!-- stock_level Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('stock_level', trans("lang.stock_level"), ['class' => ' control-label text-left']) !!}
             @if(isset($productDetails))
            {!! Form::text('stock_level', $productDetails->stock_level,  ['class' => 'form-control','placeholder'=>  trans("lang.stock_level_placeholder")]) !!}
             @else
            {!! Form::text('stock_level', null,  ['class' => 'form-control','placeholder'=>""]) !!}
            @endif
        </div>
    </div>
    
    <!-- stock_purchased_date Field -->
    <div class="col-md-3">
     <div class="form-group">
        {!! Form::label('stock_purchased_date', trans("lang.stock_purchased_date"), ['class' => 'label-left control-label text-left']) !!}
         @if(isset($productDetails))
        {!! Form::text('stock_purchased_date', $productDetails->stock_purchased_date,  ['class' => 'form-control datepicker','placeholder'=>  trans("lang.stock_level_placeholder")]) !!}
         @else
        {!! Form::text('stock_purchased_date', date('d-m-Y'),  ['class' => 'form-control datepicker','placeholder'=> ""]) !!}
        @endif
      </div>
    </div>
    
    <!-- alternate_weight_kg Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('alternate_weight_kg', trans("lang.alternate_weight_kg"), ['class' => ' control-label text-left']) !!}
             @if(isset($productDetails))
            {!! Form::text('alternate_weight_kg', $productDetails->alternate_weight_kg,  ['class' => 'form-control','placeholder'=>  trans("lang.alternate_weight_kg_placeholder")]) !!}
             @else
           {!! Form::text('alternate_weight_kg', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
            @endif
        </div>
    </div>
    
    <!-- other_key_search_words Field -->
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('other_key_search_words', trans("lang.other_key_search_words"), ['class' => ' control-label text-left']) !!}
             @if(isset($productDetails))
            {!! Form::text('other_key_search_words', $productDetails->other_key_search_words,  ['class' => 'form-control','placeholder'=>  trans("lang.other_key_search_words_placeholder")]) !!}
            @else
            {!! Form::text('other_key_search_words', null,  ['class' => 'form-control','placeholder'=>  ""]) !!}
            @endif
        </div>
    </div>
    
    <!--source_confirm-->
    <div class="col-md-3">
        <div class="">
            <div class="form-group">
                {!! Form::label('source_confirm', trans("lang.source_confirm"), ['class' => ' control-label text-left']) !!}
                 @if(isset($productDetails))
                {!! Form::text('source_confirm', $productDetails->source_confirm, ['class' => 'form-control','placeholder'=>  trans("lang.reason_discontinuation_placeholder")]) !!}
                 @else
                {!! Form::text('source_confirm', null, ['class' => 'form-control','placeholder'=> ""]) !!}
                @endif
            </div>
        </div>
    </div>
    
    <!--reason_discontinuation-->
    <div class="col-md-3">
        <div class="">
            <div class="form-group">
                {!! Form::label('reason_discontinuation', trans("lang.reason_discontinuation"), ['class' => ' control-label text-left']) !!}
                 @if(isset($productDetails))
                {!! Form::text('reason_discontinuation', $productDetails->reason_discontinuation, ['class' => 'form-control','placeholder'=> ""]) !!}
                @else
                {!! Form::text('reason_discontinuation', null, ['class' => 'form-control','placeholder'=> ""]) !!}
                @endif
            </div>
        </div>
    </div>
    
    <!--sugar_level-->
    <div class="col-md-3">
        <div class="">
            <div class="form-group">
                {!! Form::label('sugar_level', 'Sugar Level', ['class' => ' control-label text-left']) !!}
                {!! Form::text('sugar_level', null, ['class' => 'form-control','placeholder'=> ""]) !!}
            </div>
        </div>
    </div>    

</div>
</div>

<div class="col-md-12 column custom-from-css mt-5">
    <div class="row">
        

    <div class="col-md-3">
        <!-- area Field -->
        <div class="">
            <div class="form-group">
                {!! Form::label('area', trans("lang.product_area"), ['class' => ' control-label text-left']) !!}
                {!! Form::textarea('area', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <!-- Description Field -->
        <div class="">
            <div class="form-group">
                {!! Form::label('description', trans("lang.product_description"), ['class' => ' control-label text-left required']) !!}
                {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
                 trans("lang.product_description_placeholder")  ]) !!}
            </div>
        </div>
    </div>
    
    <div class="col-md-3">

        <!-- 'Boolean Featured Field' -->
        <div class="form-group custom-checkbx row"> 
        {!! Form::label('featured', trans("lang.product_featured"),['class' => 'col-md-7 control-label text-left']) !!}
        <div class="col-md-4 checkbox icheck text-right">
            <label class="form-check-inline">
                {!! Form::hidden('featured', 0) !!}
                {!! Form::checkbox('featured', 1, null) !!}
            </label>
        </div>
        </div>

        <!-- 'Boolean deliverable Field' -->
        <div class="form-group custom-checkbx custom-checkbx-nxt row"> 
        {!! Form::label('deliverable', trans("lang.product_deliverable"),['class' => 'col-md-7 control-label text-left']) !!}
        <div class="checkbox icheck col-md-4 text-right">
            <label class="form-check-inline">
                {!! Form::hidden('deliverable', 0) !!}
                {!! Form::checkbox('deliverable', 1, null) !!}
            </label>
        </div>
        </div>

        <!-- 'Boolean Online Store Field' -->
        <div class="form-group custom-checkbx custom-checkbx-nxt row"> 
        {!! Form::label('online_store', trans("lang.product_online_store"),['class' => 'col-md-7 control-label text-left']) !!}
        <div class="checkbox icheck col-md-4 text-right">
            <label class=" form-check-inline">
                {!! Form::hidden('online_store', 0) !!}
                {!! Form::checkbox('online_store', 1, null) !!}
            </label>
        </div>
        </div>

        <!-- 'Boolean Low stock warning Field' -->
        <div class="form-group custom-checkbx custom-checkbx-nxt row"> 
        {!! Form::label('low_stock_warning', trans("lang.product_low_stock_warning"),['class' => 'col-md-7 control-label text-left']) !!}
        <div class="checkbox icheck col-md-4 text-right">
            <label class="form-check-inline">
                {!! Form::hidden('low_stock_warning', 0) !!}
                {!! Form::checkbox('low_stock_warning', 1, null) !!}
            </label>
        </div>
        </div>

        <div class="form-group custom-checkbx custom-checkbx-nxt row"> 
            {!! Form::label('weight_loss', 'Weight Loss',['class' => 'col-md-7 control-label text-left']) !!}
            <div class="checkbox icheck col-md-4 text-right">
                <label class="form-check-inline">
                    {!! Form::hidden('weight_loss', 0) !!}
                    {!! Form::checkbox('weight_loss', 1, null) !!}
                </label>
            </div>
        </div>

        <div class="form-group custom-checkbx custom-checkbx-nxt row"> 
            {!! Form::label('freeze_well', 'Freeze Well',['class' => 'col-md-7 control-label text-left']) !!}
            <div class="checkbox icheck col-md-4 text-right">
                <label class="form-check-inline">
                    {!! Form::hidden('freeze_well', 0) !!}
                    {!! Form::checkbox('freeze_well', 1, null) !!}
                </label>
            </div>
        </div>

        <div class="form-group custom-checkbx custom-checkbx-nxt row"> 
            {!! Form::label('grows_on_tree', 'Grow on Tree',['class' => 'col-md-7 control-label text-left']) !!}
            <div class="checkbox icheck col-md-4 text-right">
                <label class="form-check-inline">
                    {!! Form::hidden('grows_on_tree', 0) !!}
                    {!! Form::checkbox('grows_on_tree', 1, null) !!}
                </label>
            </div>
        </div>

        <div class="form-group custom-checkbx custom-checkbx-nxt row"> 
            {!! Form::label('salad_vegetable', 'Salad Vegetable',['class' => 'col-md-7 control-label text-left']) !!}
            <div class="checkbox icheck col-md-4 text-right">
                <label class="form-check-inline">
                    {!! Form::hidden('salad_vegetable', 0) !!}
                    {!! Form::checkbox('salad_vegetable', 1, null) !!}
                </label>
            </div>
        </div>

    </div>

     <!-- Image Field -->
    <div class="col-md-3">
      <div class="form-group">
        {!! Form::label('image', trans("lang.product_image"), ['class' => ' control-label text-left required']) !!}
        <div style="width: 100%" class="dropzone image" id="image" data-field="image">
          <input type="hidden" name="image[]">
        </div>
        <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
        <!-- <div class="form-text text-muted w-50">
          {{ trans("lang.product_image_help") }}
        </div> -->
      </div>
    </div>
    
    @prepend('scripts')
        <script type="text/javascript">
            var var15671147171873255749ble = [];
            @if(isset($product) && $product->hasMedia('image'))
            @forEach($product->getMedia('image') as $media)
            var15671147171873255749ble.push({
                name: "{!! $media->name !!}",
                size: "{!! $media->size !!}",
                type: "{!! $media->mime_type !!}",
                uuid: "{!! $media->getCustomProperty('uuid'); !!}",
                thumb: "{!! $media->getUrl('thumb'); !!}",
                collection_name: "{!! $media->collection_name !!}"
            });
                    @endforeach
                    @endif
            var dz_var15671147171873255749ble = $(".dropzone.image").dropzone({
                    url: "{!!url('uploads/store')!!}",
                    addRemoveLinks: true,
                    maxFiles: 5 - var15671147171873255749ble.length,
                    init: function () {
                        @if(isset($product) && $product->hasMedia('image'))
                        var15671147171873255749ble.forEach(media => {
                            dzInit(this, media, media.thumb);
                        });
                        @endif
                    },
                    accept: function (file, done) {
                        dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                    },
                    sending: function (file, xhr, formData) {
                        dzSendingMultiple(this, file, formData, '{!! csrf_token() !!}');
                    },
                    complete: function (file) {
                        dzCompleteMultiple(this, file);

                        dz_var15671147171873255749ble[0].mockFile = file;
                    },
                    removedfile: function (file) {
                        console.log(file);
                        //this.removeFile(file);
                        dzRemoveFile(
                            file, var15671147171873255749ble, '{!! url("products/remove-media") !!}',
                            'image', '{!! isset($product) ? $product->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                        );
                    }
                });
            dz_var15671147171873255749ble[0].mockFile = var15671147171873255749ble;
            dropzoneFields['image'] = dz_var15671147171873255749ble;
        </script>
    @endprepend
    
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
    <button type="submit" class="btn btn-{{setting('theme_color')}} product-form-submit"><i class="fa fa-save"></i> Save Product</button>
    <a href="{!! route('products.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Cancel</a>
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
                });
            }
        })
    });
    
    
    $('#category').on('change',function(e) {
        var cat_id = e.target.value;
        var text   = $("#category option:selected").text().charAt(0).toUpperCase();
        $('#alpha').val(text); 
        $.ajax({
            url:"{{ route('products.showSubcategory') }}",
            type:"POST",
            data: {
                cat_id: cat_id,
                "_token": "{{ csrf_token() }}",
            },
            success:function (data) {
                $('#subcategory').empty();
                $('#subcategory').append('<option value="">Please Select</option>');
                $.each(data.subcategories[0].subcategories,function(index,subcategory){
                    $('#subcategory').append('<option value="'+subcategory.id+'">'+subcategory.name+'</option>');
                })
            }
        })
    });

});

    var vas_service = [];
    var vas_list = [];
    var price_variation = [];

    function pvarrayToTable() {
        var html     = '';
        $(".price-variation-items").html('');
        $.each(price_variation, function(key,value){
            html += '<tr>';
            html += '<td>';
            if(value.price_variation_id != undefined && value.price_variation_id != ''){
                html += '<input type="hidden" name="price_variation_id[]" value="'+value.price_variation_id+'" />';   
            }
            html += '<input type="number" step="any" min="0.001" name="product_purchase_quantity_from[]" placeholder="From" value="'+value.quantity_1+'" class="form-control change_pv_info ppqf" index="' + key + '" field="quantity_1" id="quantity_1_'+key+'">';
            html += '</td>';
            html += '<td rowspan="2" style="vertical-align:middle;">';
            html += '<input type="number" step="any" min="0.001" name="product_price_multiplier[]" placeholder="Multiplier" value="'+value.price+'" class="form-control change_pv_info ppm" index="' + key + '" field="price" id="price_'+key+'">';
            html += '</td>';
            html += '<td rowspan="2" style="vertical-align:middle;">';
            html += '<a title="Delete" class="delete-pv btn btn-sm btn-danger text-white" index="'+key+'"><i class="fa fa-remove"></i></a>';
            html += '</td>';
            html += '</tr>';

            html += '<tr>';
            html += '<td>';
            html += '<input type="number" step="any" min="0.001" name="product_purchase_quantity_to[]" placeholder="To" value="'+value.quantity_2+'" class="form-control change_pv_info ppqt" index="' + key + '" field="quantity_2" id="quantity_2_'+key+'">';
            html += '</td>';
            html += '</tr>';
        });
        $(".price-variation-items").html(html);
    }

    $('.price-variation-btn').click(function() {
        var price_variation_obj = {};
        price_variation_obj.quantity_1 = '';
        price_variation_obj.quantity_2 = '';
        price_variation_obj.price = '';
        price_variation.push(price_variation_obj);
        pvarrayToTable();
    });    

    $(document).on('blur','.change_pv_info',function (e) { 
        var field = $(this).attr('field');
        var index = $(this).attr('index');
        var val = $(this).val();
        if(field == 'quantity_1'){
            if(val != undefined && val!=''){
                price_variation[index].quantity_1 = val;
                $("#quantity_1_" + index).val(val);
            } else {
                price_variation[index].quantity_1 = '';
                $("#quantity_1_" + index).val('');
            }
        } else if(field == 'quantity_2'){
            if(val != undefined && val!=''){
                price_variation[index].quantity_2 = val;
                $("#quantity_2_" + index).val(val);
            } else {
                price_variation[index].quantity_2 = '';
                $("#quantity_2_" + index).val('');
            }
        } else if(field == 'price'){
            if(val != undefined && val!=''){
                price_variation[index].price = val;
                $("#price_" + index).val(val);
            } else {
                price_variation[index].price = '';
                $("#price_" + index).val('');
            }
        }
        setTimeout(function() {
            pvarrayToTable();
        },300);
        e.preventDefault();
    });


    $(document).on('click', '.delete-pv', function(e){
        var index = $(this).attr('index');
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
                    

                    if(price_variation[index].price_variation_id == undefined || price_variation[index].price_variation_id == ''){
                        instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName');
                        price_variation.splice(index,1);
                        pvarrayToTable();
                    } else {


                        var token = "{{ csrf_token() }}";
                        var url   = '{!!  route('products.destroy', [':productID']) !!}';
                        url       = url.replace(':productID', price_variation[index].product_id);
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                '_token': token, 
                                'id': price_variation[index].price_variation_id, 
                                '_method': 'DELETE',
                                'type': 'delete_price_variation'
                            },
                            success: function (response) {
                                iziToast.success({
                                    backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                                    messageColor: '#fff',
                                    timeout: 3000, 
                                    icon: 'fa fa-check', 
                                    position: "topRight", 
                                    iconColor:'#fff',
                                    message: 'Deleted Sucessfully'
                                });
                                instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName');
                                price_variation.splice(index,1);
                                pvarrayToTable();
                            }
                        });

                        
                    }

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
   });


    

    function vasarrayToTable() {
        var html     = '';
        $(".vas-service-items").html('');
        $.each(vas_service, function(key,value){
            html += '<tr>';
            html += '<td>';
            if(value.vas_service_id != undefined && value.vas_service_id != ''){
                html += '<input type="hidden" name="vas_service_id[]" value="'+value.vas_service_id+'" />';   
            }

            html += '<select id="vas_id_'+key+'" name="vas_id[]" index="' + key + '" field="vas_id" class="form-control change_vas_info">';
            html += '<option selected="selected" disabled="disabled" value=""></option>';
            $.each(vas_list[0], function(key1,value1) {
                (value.vas_id==value1.id) ? selected='selected="selected"' : selected='' ;
                html += '<option '+selected+' value="'+value1.id+'">'+value1.name+'</option>';   
            });   
            html += '</select>';
         
            html += '</td>';
            html += '<td>';
            html += '<input type="number" step="any" min="0.001" name="vas_price[]" placeholder="Price" value="'+value.price+'" class="form-control change_vas_info" index="' + key + '" field="vas_price" id="vas_price_'+key+'">';
            html += '</td>';
            html += '<td>';
            html += '<a title="Delete" class="delete-vas btn btn-sm btn-danger text-white" index="'+key+'"><i class="fa fa-remove"></i></a>';
            html += '</td>';
            html += '</tr>';
        });
        $(".vas-service-items").html(html);
    }

    $('.vas-service-btn').click(function() {
        var vas_service_obj = {};
        vas_service_obj.vas_id = '';
        vas_service_obj.price = '';
        vas_service.push(vas_service_obj);
        vasarrayToTable();
    });

    $(document).on('blur','.change_vas_info',function (e) { 
        var field = $(this).attr('field');
        var index = $(this).attr('index');
        var val = $(this).val();
        if(field == 'vas_id'){
            if(val != undefined && val!=''){
                vas_service[index].vas_id = val;
                $("#vas_id_" + index).val(val);
            } else {
                vas_service[index].vas_id = '';
                $("#vas_id_" + index).val('');
            }
        } else if(field == 'vas_price'){
            if(val != undefined && val!=''){
                vas_service[index].price = val;
                $("#vas_price_" + index).val(val);
            } else {
                vas_service[index].price = '';
                $("#vas_price_" + index).val('');
            }
        }
        setTimeout(function() {
            vasarrayToTable();
        },300);
        e.preventDefault();
    });

    function loadvasList() {
      vas_list  = [];
      var url   = '{!!route('valueAddedServiceAffiliated.index')!!}';
      var token = "{{ csrf_token() }}";
      $.ajax({
          type: 'GET',
          data: {
              '_token': token
          },
          url: url,
          success: function (response) {
             vas_list.push(response.data);
          }
      });
    }
    loadvasList();

    $(document).on('click', '.delete-vas', function(e){
        var index = $(this).attr('index');
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
                    

                    if(vas_service[index].vas_service_id == undefined || vas_service[index].vas_service_id == ''){
                        instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName');
                        vas_service.splice(index,1);
                        vasarrayToTable();
                    } else {

                        var token = "{{ csrf_token() }}";
                        var url   = '{!!  route('products.destroy', [':productID']) !!}';
                        url       = url.replace(':productID', vas_service[index].product_id);
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                '_token': token, 
                                'id': vas_service[index].vas_service_id, 
                                '_method': 'DELETE',
                                'type': 'delete_vas_service'
                            },
                            success: function (response) {
                                iziToast.success({
                                    backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                                    messageColor: '#fff',
                                    timeout: 3000, 
                                    icon: 'fa fa-check', 
                                    position: "topRight", 
                                    iconColor:'#fff',
                                    message: 'Deleted Sucessfully'
                                });
                                instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName');
                                vas_service.splice(index,1);
                                vasarrayToTable();
                            }
                        });

                    }

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

    });

    @if(isset($product))

        $(document).ready(function() {

            var url   = '{!!  route('products.show', [':productID']) !!}';
            url       = url.replace(':productID', "{{$product->id}}");
            var token = "{{ csrf_token() }}";
            $.ajax({
                 type: 'GET',
                 data: {
                     '_token': token,
                     'id': "{{$product->id}}"
                 },
                 url: url,
                 success: function (response) {
                   
                    if(response.data.pricevaritations.length > 0){
                        $.each(response.data.pricevaritations, function(key,value) {
                            var price_variation_obj = {};
                            price_variation_obj.price_variation_id = value.id;
                            price_variation_obj.quantity_1 = value.purchase_quantity_from;
                            price_variation_obj.quantity_2 = value.purchase_quantity_to;
                            price_variation_obj.price = value.price_multiplier;
                            price_variation.push(price_variation_obj);
                            pvarrayToTable();
                        });
                    }

                    if(response.data.vasservices.length > 0){
                        $.each(response.data.vasservices, function(key,value) {
                            
                            var vas_service_obj = {};
                            vas_service_obj.vas_service_id = value.id;
                            vas_service_obj.vas_id = value.vas_id;
                            vas_service_obj.price = parseFloat(value.price).toFixed(2);
                            vas_service.push(vas_service_obj);
                            vasarrayToTable();

                        });
                    }

                 }
            });        

        });

    @endif


      

</script>

@push('scripts_lib')
<script>

    $(document).on("change", "#alpha, #product_code_short, #product_varient_number, #subcategory", function() { 
        var alpha                  = $('#alpha').val();
        var product_code_short     = $('#product_code_short').val(); 
        var product_varient_number = $('#product_varient_number').val(); 
        var subcategory            = $('#subcategory').val();

        if(alpha!='' && product_code_short !='' && product_varient_number !='' && subcategory !='') {

            var url   = '{!!  route('subcategory.show', [':subcategoryID']) !!}';
            url       = url.replace(':subcategoryID', subcategory);
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'GET',
                data: {
                      '_token': token,
                      'id': subcategory
                },
                url: url,
                success: function (response) {
                    
                    var sub_short_name = response.data.short_name; 
                    $('#con').val(product_code_short+product_varient_number+alpha+sub_short_name);
                    $('#product_code').val(alpha+'-'+sub_short_name+'-'+product_code_short+'-'+product_varient_number);
                    $('#short_product_code').val(alpha+'-'+product_varient_number);

                }
            });    

            
        }
    });


        $('#unit').change(function() {
            var text = $("#unit option:selected").text();
            var unit = text.substr(text.length - 5);
            $('.primary_unit').html(unit);
        });

        $('#secondary_unit').change(function() {
            //var unit = $(this).val();
            var text = $("#secondary_unit option:selected").text();
            var unit = text.substr(text.length - 5); 
            $('.sec_unit').html(unit);
        });

        $('#tertiary_unit').change(function() {
            var text = $("#tertiary_unit option:selected").text();
            var unit = text.substr(text.length - 5); 
            $('.ter_unit').html(unit);
        });

        $('#custom_unit').change(function() {
            var text = $("#custom_unit option:selected").text();
            var unit = text.substr(text.length - 5);
            $('.cus_unit').html(unit);
        });

        $('#bulk_buy_unit').change(function() {
            var text = $("#bulk_buy_unit option:selected").text();
            var unit = text.substr(text.length - 5);
            $('.buyb_unit').html(unit);
        });

        $('#wholesale_purchase_unit').change(function() {
            var text = $("#wholesale_purchase_unit option:selected").text();
            var unit = text.substr(text.length - 5);
            $('.wp_unit').html(unit);
        });

        $('#pack_weight_unit').change(function() {
            var text = $("#pack_weight_unit option:selected").text();
            var unit = text.substr(text.length - 5);
            $('.pac_unit').html(unit);
        });
    
</script>
@endpush
