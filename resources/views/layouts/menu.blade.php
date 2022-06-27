<!-- <li class="nav-header text-center">Menu</li> -->
@can('dashboard')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}" href="{!! url('dashboard') !!}">@if($icons)
                <i class="nav-icon fa fa-dashboard"></i>@endif
            <p>{{trans('lang.dashboard')}}</p></a>
    </li>
@endcan

<?php /* ?>
@can('favorites.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('favorites*') ? 'active' : '' }}" href="{!! route('favorites.index') !!}">@if($icons)
                <i class="nav-icon fa fa-heart"></i>@endif<p>{{trans('lang.favorite_plural')}}</p></a>
    </li>
@endcan
<?php /*/ ?>


<?php /* ?>
@can('fields.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('fields*') ? 'active' : '' }}" href="{!! route('fields.index') !!}">@if($icons)<i class="nav-icon fa fa-tasks"></i>@endif<p>{{trans('lang.field_plural')}}</p></a>
    </li>
@endcan
<?php /*/ ?>


<?php /* ?>
@can('expenses.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('expenses*') ? 'active' : '' }}" href="{!! route('expenses.index') !!}">@if($icons)<i class="nav-icon fa fa-credit-card"></i>@endif<p>Expenses</p></a>
    </li>
@endcan
<?php */ ?>
<?php /* ?>
@can('supplierRequest.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('supplierRequest*') ? 'active' : '' }}" href="{!! route('supplierRequest.index') !!}">@if($icons) <i class="nav-icon fa fa-paper-plane"></i>@endif<p>{{trans('lang.supplier_request_plural')}}</p></a>
    </li>
@endcan
<?php /*/ ?>

@can('markets.index')
    <li class="nav-item has-treeview {{ (Request::is('markets*') || Request::is('partyTypes*') || Request::is('partySubTypes*') || Request::is('partystream*') || Request::is('CustomerGroups*')) && !Request::is('marketsPayouts*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (Request::is('markets*') || Request::is('partyTypes*') || Request::is('partySubTypes*') || Request::is('partystream*') || Request::is('CustomerGroups*')) && !Request::is('marketsPayouts*')? 'active' : '' }}"> @if($icons)
                <i class="nav-icon fa fa-users"></i>@endif
            <p>{{trans('lang.market_plural')}} <i class="right fa fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @can('markets.index')
                <?php /* ?>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('requestedMarkets*') ? 'active' : '' }}" href="{!! route('requestedMarkets.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-reorder"></i>@endif<p>{{trans('lang.requested_markets_plural')}}</p></a>
                </li>
                <?php /*/ ?>
                
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('markets*') ? 'active' : '' }}" href="{!! route('markets.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-users"></i>@endif<p>{{trans('lang.market_plural')}}</p></a>
                </li>
            @endcan
            
            @can('partyTypes.index')
            <li class="nav-item">
                    <a class="nav-link {{ Request::is('partyTypes*') ? 'active' : '' }}" href="{!! route('partyTypes.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-puzzle-piece"></i>@endif<p>{{trans('lang.party_type_plural')}}</p></a>
                </li>
            @endcan
            
              @can('partySubTypes.index')
            <li class="nav-item">
                    <a class="nav-link {{ Request::is('partySubTypes*') ? 'active' : '' }}" href="{!! route('partySubTypes.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-puzzle-piece"></i>@endif<p>{{trans('lang.party_sub_type_plural')}}</p></a>
                </li>
            @endcan
            
             @can('partystream.index')
            <li class="nav-item">
                    <a class="nav-link {{ Request::is('partystream*') ? 'active' : '' }}" href="{!! route('partystream.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-puzzle-piece"></i>@endif<p>{{trans('lang.partystream_plural')}}</p></a>
                </li>
            @endcan

            <?php /* ?>    
            @can('galleries.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('galleries*') ? 'active' : '' }}" href="{!! route('galleries.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-image"></i>@endif<p>{{trans('lang.gallery_plural')}}</p></a>
                </li>
            @endcan
            @can('marketReviews.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('marketReviews*') ? 'active' : '' }}" href="{!! route('marketReviews.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-comments"></i>@endif<p>{{trans('lang.market_review_plural')}}</p></a>
                </li>
            @endcan
            <?php /*/ ?>

            @can('CustomerGroups.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('CustomerGroups*') ? 'active' : '' }}" href="{!! route('CustomerGroups.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-reorder"></i>
                        @endif
                        <p>{{trans('lang.customer_group')}}</p>
                    </a>
                </li>
            @endcan
            
            <!--@can('partystream.index')-->
            <!--    <li class="nav-item">-->
            <!--        <a class="nav-link {{ Request::is('partystream*') ? 'active' : '' }}" href="{!! route('partystream.index') !!}">    -->
            <!--            @if($icons)-->
            <!--                <i class="nav-icon fa fa-puzzle-piece"></i>-->
            <!--            @endif-->
            <!--            <p>{{trans('lang.partystream')}}</p>-->
            <!--        </a>-->
            <!--    </li>-->
            <!--@endcan-->
           

        </ul>
    </li>
@endcan

<?php /* ?>
@can('CustomerGroups.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('CustomerGroups*') ? 'active' : '' }}" href="{!! route('CustomerGroups.index') !!}">    
            @if($icons)
                <i class="nav-icon fa fa-users"></i>
            @endif
            <p>{{trans('lang.customer_group')}}</p>
        </a>
    </li>
@endcan
<?php */ ?>



@can('products.index')
    <li class="nav-item has-treeview {{ Request::is('products*') || Request::is('options*') || Request::is('optionGroups*') || Request::is('productReviews*') || Request::is('nutrition*') || Request::is('departments*') || Request::is('categories*') || Request::is('subcategory*') || Request::is('qualityGrade*') || Request::is('productStatus*') || Request::is('stockStatus*') || Request::is('valueAddedServiceAffiliated*') || Request::is('productSeasons*') || Request::is('productColors*') || Request::is('productNutritions*') || Request::is('productTastes*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{  Request::is('products*') || Request::is('options*') || Request::is('optionGroups*') || Request::is('productReviews*') || Request::is('nutrition*') || Request::is('departments*')|| Request::is('categories*') || Request::is('subcategory*') || Request::is('qualityGrade*') || Request::is('productStatus*') || Request::is('stockStatus*') || Request::is('valueAddedServiceAffiliated*') || Request::is('productSeasons*') || Request::is('productColors*') || Request::is('productNutritions*') || Request::is('productTastes*') ? 'active' : '' }}"> @if($icons)
                <i class="nav-icon fa fa-archive"></i>@endif
            <p>{{trans('lang.product_plural')}} <i class="right fa fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @can('products.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('products*') ? 'active' : '' }}" href="{!! route('products.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-archive"></i>@endif
                        <p>{{trans('lang.product_plural')}}</p></a>
                </li>
            @endcan
            
            <!--@can('productReviews.index')-->
            <!--    <li class="nav-item">-->
            <!--        <a class="nav-link {{ Request::is('productReviews*') ? 'active' : '' }}" href="{!! route('productReviews.index') !!}">@if($icons)-->
            <!--                <i class="nav-icon fa fa-comments"></i>@endif<p>{{trans('lang.product_review_plural')}}</p></a>-->
            <!--    </li>-->
            <!--@endcan-->

            @can('departments.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('departments*') ? 'active' : '' }}" href="{!! route('departments.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-folder"></i>@endif<p>{{trans('lang.department_plural')}}</p></a>
                </li>
            @endcan
            @can('categories.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('categories*') ? 'active' : '' }}" href="{!! route('categories.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-folder"></i>@endif<p>{{trans('lang.category_plural')}}</p></a>
                </li>
            @endcan
            @can('subcategory.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('subcategory*') ? 'active' : '' }}" href="{!! route('subcategory.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-folder"></i>@endif<p>{{trans('lang.subcategory_plural')}}</p></a>
                </li>
            @endcan
            @can('qualityGrade.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('qualityGrade*') ? 'active' : '' }}" href="{!! route('qualityGrade.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-folder"></i>@endif<p>{{trans('lang.quality_grade_plural')}}</p></a>
                </li>
            @endcan

            <?php /* ?>
            @can('productStatus.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('productStatus*') ? 'active' : '' }}" href="{!! route('productStatus.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-folder"></i>@endif<p>{{trans('lang.product_status')}}</p></a>
                </li>
            @endcan
            <?php /*/ ?>

            @can('stockStatus.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('stockStatus*') ? 'active' : '' }}" href="{!! route('stockStatus.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-folder"></i>@endif<p>{{trans('lang.stock_status')}}</p></a>
                </li>
            @endcan
            @can('valueAddedServiceAffiliated.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('valueAddedServiceAffiliated*') ? 'active' : '' }}" href="{!! route('valueAddedServiceAffiliated.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-folder"></i>@endif<p>{{trans('lang.value_added_service')}}</p></a>
                </li>
            @endcan
            
            <!--@can('optionGroups.index')-->
            <!--    <li class="nav-item">-->
            <!--        <a class="nav-link {{ Request::is('optionGroups*') ? 'active' : '' }}" href="{!! route('optionGroups.index') !!}">@if($icons)<i class="nav-icon fa fa-plus-square"></i>@endif<p>{{trans('lang.option_group_plural')}}</p></a>-->
            <!--    </li>-->
            <!--@endcan-->
            
            <!--@can('options.index')-->
            <!--    <li class="nav-item">-->
            <!--        <a class="nav-link {{ Request::is('options*') ? 'active' : '' }}" href="{!! route('options.index') !!}">@if($icons)-->
            <!--                <i class="nav-icon fa fa-plus-square-o"></i>@endif<p>{{trans('lang.option_plural')}}</p></a>-->
            <!--    </li>-->
            <!--@endcan-->

            @can('productSeasons.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('productSeasons*') ? 'active' : '' }}" href="{!! route('productSeasons.index') !!}">
                    <i class="nav-icon fa fa-folder"></i><p>Season</p></a>
                </li>
            @endcan

            @can('productColors.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('productColors*') ? 'active' : '' }}" href="{!! route('productColors.index') !!}">
                    <i class="nav-icon fa fa-folder"></i><p>Colors</p></a>
                </li>
            @endcan

            @can('productNutritions.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('productNutritions*') ? 'active' : '' }}" href="{!! route('productNutritions.index') !!}">
                    <i class="nav-icon fa fa-folder"></i><p>Nutritions</p></a>
                </li>
            @endcan

            @can('productTastes.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('productTastes*') ? 'active' : '' }}" href="{!! route('productTastes.index') !!}">
                    <i class="nav-icon fa fa-folder"></i><p>Tastes</p></a>
                </li>
            @endcan

        </ul>
    </li>
@endcan

@can('orders.index')
    <li class="nav-item has-treeview {{ Request::is('deliveryChallan*') || Request::is('salesInvoice*') || Request::is('salesReturn*') || Request::is('paymentIn*') || Request::is('quotes*')? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('deliveryChallan*') || Request::is('salesInvoice*') || Request::is('salesReturn*') || Request::is('paymentIn*') || Request::is('quotes*')? 'active' : '' }}"> @if($icons) <i class="nav-icon fa fa-shopping-bag"></i>@endif
            <p>{{trans('lang.sales_plural')}} <i class="right fa fa-angle-left"></i></p>
        </a>
        <ul class="nav nav-treeview">

            @can('quotes.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('quotes*') ? 'active' : '' }}" href="{!! route('quotes.index') !!}">
                        @if($icons) <i class="nav-icon fa fa-shopping-bag"></i> @endif<p>Quotes</p>
                    </a>
                </li>
            @endcan

            @can('salesInvoice.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('salesInvoice*') ? 'active' : '' }}" href="{!! route('salesInvoice.index') !!}">@if($icons)
                    <i class="nav-icon fa fa-shopping-bag"></i>@endif<p>{{trans('lang.sales_invoice_plural')}}</p></a>
                </li>
            @endcan

            @can('salesReturn.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('salesReturn*') ? 'active' : '' }}" href="{!! route('salesReturn.index') !!}">@if($icons)
                    <i class="nav-icon fa fa-shopping-bag"></i>@endif<p>{{trans('lang.sales_return_plural')}}</p></a>
                </li>
            @endcan

            @can('paymentIn.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('paymentIn*') ? 'active' : '' }}" href="{!! route('paymentIn.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-credit-card"></i>
                        @endif
                        <p> {{trans('lang.payment_in_plural')}} </p>
                    </a>
                </li>
            @endcan

            <!-- @can('deliveryChallan.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('deliveryChallan*') ? 'active' : '' }}" href="{!! route('deliveryChallan.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-sticky-note-o"></i>
                        @endif
                        <p> {{trans('lang.delivery_challan_plural')}} </p>
                    </a>
                </li>
            @endcan -->

            

            <?php /* ?>
            @can('orders.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('orders*') ? 'active' : '' }}" href="{!! route('orders.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-shopping-bag"></i>@endif<p>{{trans('lang.order_plural')}}</p></a>
                </li>
            @endcan

            @can('orderStatuses.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('orderStatuses*') ? 'active' : '' }}" href="{!! route('orderStatuses.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-server"></i>@endif<p>{{trans('lang.order_status_plural')}}</p></a>
                </li>
            @endcan

            @can('deliveryAddresses.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('deliveryAddresses*') ? 'active' : '' }}" href="{!! route('deliveryAddresses.index') !!}">@if($icons)<i class="nav-icon fa fa-map"></i>@endif<p>{{trans('lang.delivery_address_plural')}}</p></a>
                </li>
            @endcan
            <?php /*/ ?>

        </ul>
    </li>
@endcan
           
@can('orders.index')
    <li class="nav-item has-treeview {{ (Request::is('orders*') || Request::is('orderStatuses*') || Request::is('deliveryAddresses*') || Request::is('payments*')) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (Request::is('orders*') || Request::is('orderStatuses*') || Request::is('deliveryAddresses*') ||  Request::is('payments*')) ? 'active' : '' }}"> 
            @if($icons) <i class="nav-icon fa fa-shopping-cart"></i> @endif
            <p> {{trans('lang.order_plural')}}  <i class="right fa fa-angle-left"></i> </p>
        </a>
        <ul class="nav nav-treeview">

            @can('orders.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('orders*') ? 'active' : '' }}" href="{!! route('orders.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-shopping-bag"></i>@endif<p>{{trans('lang.order_plural')}}</p></a>
                </li>
            @endcan

            @can('orderStatuses.index')
                <!-- <li class="nav-item">
                    <a class="nav-link {{ Request::is('orderStatuses*') ? 'active' : '' }}" href="{!! route('orderStatuses.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-server"></i>@endif<p>{{trans('lang.order_status_plural')}}</p></a>
                </li> -->
            @endcan

            @can('deliveryAddresses.index')
                <!-- <li class="nav-item">
                    <a class="nav-link {{ Request::is('deliveryAddresses*') ? 'active' : '' }}" href="{!! route('deliveryAddresses.index') !!}">@if($icons)<i class="nav-icon fa fa-map"></i>@endif<p>{{trans('lang.delivery_address_plural')}}</p></a>
                </li> -->
            @endcan
            
            @can('deliveryZones.index')
            <!-- <li class="nav-item">
                <a href="{!! route('deliveryZones.index') !!}" class="nav-link {{ Request::is('deliveryZones*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-sticky-note-o"></i> @endif <p>{{trans('lang.delivery_zones_plural')}}</p>
                </a>
            </li> -->
            @endcan

            @can('payments.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('payments*') ? 'active' : '' }}" href="{!! route('payments.index') !!}">@if($icons)
                    <i class="nav-icon fa fa-money"></i>@endif<p>{{trans('lang.payment_plural')}}</p></a>
                </li>
            @endcan
            
        </ul>
    </li>    
@endcan 

@can('purchase.index')
    <li class="nav-item has-treeview {{ (Request::is('purchase*') || Request::is('purchaseInvoice*') || Request::is('vendorStock*') || Request::is('paymentOut*')) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (Request::is('purchase*') || Request::is('purchaseInvoice*') || Request::is('vendorStock*') || Request::is('paymentOut*')) ? 'active' : '' }}"> 
            @if($icons) <i class="nav-icon fa fa-shopping-cart"></i> @endif
            <p> {{trans('lang.purchase_plural')}}  <i class="right fa fa-angle-left"></i> </p>
        </a>
        <ul class="nav nav-treeview">

            @can('purchaseOrder.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('purchaseOrder*') ? 'active' : '' }}" href="{!! route('purchaseOrder.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-credit-card"></i>
                        @endif
                        <p> {{trans('lang.purchase_order_plural')}} </p>
                    </a>
                </li>
            @endcan

            @can('purchaseInvoice.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('purchaseInvoice*') ? 'active' : '' }}" href="{!! route('purchaseInvoice.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-credit-card"></i>
                        @endif
                        <p> Purchase Invoices </p>
                    </a>
                </li>
            @endcan

            @can('purchaseReturn.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('purchaseReturn*') ? 'active' : '' }}" href="{!! route('purchaseReturn.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-credit-card"></i>
                        @endif
                        <p> Purchase Return </p>
                    </a>
                </li>
            @endcan

            @can('paymentOut.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('paymentOut*') ? 'active' : '' }}" href="{!! route('paymentOut.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-credit-card"></i>
                        @endif
                        <p> Payment Out </p>
                    </a>
                </li>
            @endcan

            @can('vendorStock.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('vendorStock*') ? 'active' : '' }}" href="{!! route('vendorStock.index') !!}">    
                        @if($icons) <i class="nav-icon fa fa-paper-plane"></i>@endif
                        <p>Vendor Stocks</p>
                    </a>
                </li>
            @endcan

        </ul>
    </li>
@endcan



<?php /* ?>
@can('deliveryChallan.index')
    <li class="nav-item has-treeview {{ (Request::is('deliveryChallan*')) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (Request::is('deliveryChallan*')) ? 'active' : '' }}"> 
            @if($icons) <i class="nav-icon fa fa-truck"></i> @endif
            <p> {{trans('lang.delivery_challan_plural')}}  <i class="right fa fa-angle-left"></i> </p>
        </a>
        <ul class="nav nav-treeview">

            @can('deliveryChallan.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('deliveryChallan*') ? 'active' : '' }}" href="{!! route('deliveryChallan.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-truck"></i>
                        @endif
                        <p> {{trans('lang.delivery_challan_plural')}} </p>
                    </a>
                </li>
            @endcan

        </ul>
    </li>
@endcan
<?php /*/ ?>


<li class="nav-item has-treeview {{ (Request::is('inventory*') || Request::is('stockTake*') || Request::is('wastageDisposal*')) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (Request::is('inventory*') || Request::is('stockTake*') || Request::is('wastageDisposal*')) ? 'active' : '' }}"> 
            @if($icons) <i class="nav-icon fa fa-cubes"></i> @endif
            <p> Inventory  <i class="right fa fa-angle-left"></i> </p>
        </a>
        <ul class="nav nav-treeview">

            @can('inventory.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('inventory*') ? 'active' : '' }}" href="{!! route('inventory.index') !!}">
                        @if($icons)<i class="nav-icon fa fa-cube"></i>@endif
                        <p>Inventory</p>
                    </a>
                </li>
            @endcan

            @can('stockTake.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('stockTake*') ? 'active' : '' }}" href="{!! route('stockTake.index') !!}">
                        @if($icons)<i class="nav-icon fa fa-cubes"></i>@endif
                        <p>Stock Take </p>
                    </a>
                </li>
            @endcan

            @can('wastageDisposal.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('wastageDisposal*') ? 'active' : '' }}" href="{!! route('wastageDisposal.index') !!}">@if($icons)<i class="nav-icon fa fa-trash"></i>@endif<p>{{trans('lang.wastage_disposal_plural')}} </p></a>
                </li>
            @endcan

        </ul>
    </li>



@can('expenses.index')
    <li class="nav-item has-treeview {{ (Request::is('expenses*') || Request::is('expensesCategory*') ) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (Request::is('expenses*') || Request::is('expensesCategory*')) ? 'active' : '' }}"> 
            @if($icons) <i class="nav-icon fa fa-credit-card"></i> @endif
            <p> {{trans('lang.expenses_plural')}} <i class="right fa fa-angle-left"></i> </p>
        </a>
        <ul class="nav nav-treeview">

            
            @can('expensesCategory.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('expensesCategory*') ? 'active' : '' }}" href="{!! route('expensesCategory.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-credit-card"></i>
                        @endif
                        <p> {{trans('lang.expenses_categories_plural')}}</p>
                    </a>
                </li>
            @endcan

            @can('expenses.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('expenses*') && !Request::is('expensesCategory*')  ? 'active' : '' }}" href="{!! route('expenses.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-credit-card"></i>
                        @endif
                        <p> {{trans('lang.expenses_plural')}}</p>
                    </a>
                </li>
            @endcan
            

        </ul>
    </li>
@endcan

@can('staffs.index')
    <li class="nav-item has-treeview {{ (Request::is('staffs*') || Request::is('staffdepartment*')  ||  Request::is('staffdesignation*') ||  Request::is('attendance*')) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (Request::is('staffs*') || Request::is('staffdepartment*') ||  Request::is('staffdesignation*') ||  Request::is('attendance*')) ? 'active' : '' }}"> 
            @if($icons) <i class="nav-icon fa fa-users"></i> @endif
            <p> HRM <i class="right fa fa-angle-left"></i> </p>
        </a>
        <ul class="nav nav-treeview">

            @can('attendance.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('attendance*') ? 'active' : '' }}" href="{!! route('attendance.index') !!}">    
                        @if($icons) <i class="nav-icon fa fa-clock-o"></i> @endif <p>Attendance</p>
                    </a>
                </li>
            @endcan
            <?php /*/ ?>
            @can('staffdepartment.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('staffdepartment*') ? 'active' : '' }}" href="{!! route('staffdepartment.index') !!}">    
                        @if($icons) <i class="nav-icon fa fa-building-o"></i> @endif <p>Departments</p>
                    </a>
                </li>
            @endcan
            <?php /*/ ?> 
            @can('staffdesignation.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('staffdesignation*') ? 'active' : '' }}" href="{!! route('staffdesignation.index') !!}">    
                        @if($icons)<i class="nav-icon fa fa-level-up"></i>@endif <p>Designations</p>
                    </a>
                </li>
            @endcan

            <li class="nav-item">
                <a class="nav-link {{  Request::is('staffs*') ? 'active' : '' }}" href="{!! route('staffs.index') !!}" >
                    <i class="nav-icon fa fa-users"></i> <p>Staffs</p>
                </a>
            </li>
            
          
        </ul>
    </li>
@endcan


@can('deliveryZones.index')
    <li class="nav-item has-treeview {{ (Request::is('deliveryZones*') || Request::is('deliveryTracker*')) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (Request::is('deliveryZones*') || Request::is('deliveryTracker*')) ? 'active' : '' }}"> 
            @if($icons) <i class="nav-icon fa fa-truck"></i> @endif
            <p> Delivery <i class="right fa fa-angle-left"></i> </p>
        </a>
        <ul class="nav nav-treeview">

            @can('deliveryTracker.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('deliveryTracker*') ? 'active' : '' }}" href="{!! route('deliveryTracker.index') !!}">    
                        @if($icons) <i class="nav-icon fa fa-truck"></i> @endif <p> Delivery Tracker</p>
                    </a>
                </li>
            @endcan

            @can('deliveryZones.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('deliveryZones*') ? 'active' : '' }}" href="{!! route('deliveryZones.index') !!}">    
                        @if($icons) <i class="nav-icon fa fa-map-marker"></i> @endif <p> Delivery Zones</p>
                    </a>
                </li>
            @endcan
            
          
        </ul>
    </li>
@endcan


@can('drivers.index')
    <li class="nav-item has-treeview {{ (Request::is('drivers*') || Request::is('driversPayouts*') || Request::is('driverReviews*') || Request::is('customerFarmerReviews*') || Request::is('deliveryTips*')) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (Request::is('drivers*') || Request::is('driversPayouts*') || Request::is('driverReviews*') || Request::is('customerFarmerReviews*') || Request::is('deliveryTips*')) ? 'active' : '' }}"> 
            @if($icons) <i class="nav-icon fa fa-user-plus"></i> @endif
            <p> {{trans('lang.driver_plural')}} <i class="right fa fa-angle-left"></i> </p>
        </a>
        <ul class="nav nav-treeview">

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('drivers*') ? 'active' : '' }}" href="{!! route('drivers.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-users"></i>
                        @endif
                        <p> {{trans('lang.driver_plural')}}</p>
                    </a>
                </li>

                 @can('driverReviews.index')
                 <li class="nav-item">
                    <a class="nav-link {{ Request::is('driverReviews*') ? 'active' : '' }}" href="{!! route('driverReviews.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-star"></i>
                        @endif
                        <p> {{trans('lang.driver_review')}}</p>
                    </a>
                </li>
                @endcan
                
                 <li class="nav-item">
                    <a class="nav-link {{ Request::is('customerFarmerReviews*') ? 'active' : '' }}" href="{!! route('customerFarmerReviews.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-star"></i>
                        @endif
                        <p> {{trans('lang.customer_review')}}</p>
                    </a>
                </li>
                
                 @can('deliveryTips.index')
                 <li class="nav-item">
                    <a class="nav-link {{ Request::is('deliveryTips*') ? 'active' : '' }}" href="{!! route('deliveryTips.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-money"></i>
                        @endif
                        <p> {{trans('lang.delivery_tips')}}</p>
                    </a>
                </li>
               @endcan
               

        </ul>
    </li>
@endcan




@can('rewards.index')
    
    <li class="nav-item has-treeview {{ (Request::is('rewards*') || Request::is('CustomerLevels*') || Request::is('coupons*') || Request::is('charity*')) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (Request::is('rewards*') || Request::is('CustomerLevels*') || Request::is('coupons*') || Request::is('charity*')) ? 'active' : '' }}"> 
            @if($icons) <i class="nav-icon fa fa-trophy"></i> @endif
            <p> {{trans('lang.rewards_plural')}} <i class="right fa fa-angle-left"></i> </p>
        </a>
        <ul class="nav nav-treeview">
            
            
            @can('rewards.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('rewards*') ? 'active' : '' }}" href="{!! route('rewards.index') !!}">    
                            @if($icons)
                                <i class="nav-icon fa fa-trophy"></i>
                            @endif
                        <p>{{trans('lang.rewards')}}</p>
                    </a>
                </li>
            @endcan

            @can('CustomerLevels.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('CustomerLevels*') ? 'active' : '' }}" href="{!! route('CustomerLevels.index') !!}">    
                            @if($icons)
                                <i class="nav-icon fa fa-users"></i>
                            @endif
                        <p>{{trans('lang.customer_levels')}}</p>
                    </a>
                </li>
            @endcan
            
            @can('coupons.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('coupons*') ? 'active' : '' }}" href="{!! route('coupons.index') !!}">@if($icons)<i class="nav-icon fa fa-ticket"></i>@endif<p>{{trans('lang.coupon_plural')}} <!-- <span class="right badge badge-danger">New</span> --></p></a>
                </li>
            @endcan

            <?php /*/ ?>
            @can('charity.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('charity*') ? 'active' : '' }}" href="{!! route('charity.index') !!}">
                        @if($icons)<i class="nav-icon fa fa-ticket"></i>@endif
                        <p> Charity </p>
                    </a>
                </li>
            @endcan
            <?php /*/ ?>

        </ul>
    </li>

    
@endcan

@can('complaints.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('complaints*') ? 'active' : '' }}" href="{!! route('complaints.index') !!}">@if($icons)<i class="nav-icon fa fa-comments"></i>@endif<p>{{trans('lang.complaint_plural')}} </p></a>
    </li>
@endcan


@can('faqs.index')
    <li class="nav-item has-treeview {{ Request::is('faqCategories*') || Request::is('faqs*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('faqs*') || Request::is('faqCategories*') ? 'active' : '' }}"> @if($icons)
                <i class="nav-icon fa fa-support"></i>@endif
            <p>{{trans('lang.faq_plural')}} <i class="right fa fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @can('faqCategories.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('faqCategories*') ? 'active' : '' }}" href="{!! route('faqCategories.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-folder"></i>@endif<p>{{trans('lang.faq_category_plural')}}</p></a>
                </li>
            @endcan

            @can('faqs.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('faqs*') ? 'active' : '' }}" href="{!! route('faqs.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-question-circle"></i>@endif
                        <p>{{trans('lang.faq_plural')}}</p></a>
                </li>
            @endcan
        </ul>
    </li>
@endcan


@can('reports.index')
    <li class="nav-item has-treeview {{ (Request::is('reports*')) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (Request::is('reports*')) ? 'active' : '' }}"> 
            @if($icons) <i class="nav-icon fa fa-sticky-note-o"></i> @endif
            <p> {{trans('lang.reports_plural')}} <i class="right fa fa-angle-left"></i> </p>
        </a>
        <ul class="nav nav-treeview">

            @can('reports.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('reports*') ? 'active' : '' }}" href="{!! route('reports.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-credit-card"></i>
                        @endif
                        <p> {{trans('lang.reports_plural')}} </p>
                    </a>
                </li>
            @endcan
            

        </ul>
    </li>
@endcan

@can('notifications.index')
    <li class="nav-item has-treeview {{ (Request::is('notifications*') || Request::is('emailnotifications*')) ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ (Request::is('notifications*') || Request::is('emailnotifications*')) ? 'active' : '' }}"> 
            @if($icons) <i class="nav-icon fa fa-bell"></i> @endif
            <p> Notifications <i class="right fa fa-angle-left"></i> </p>
        </a>
        <ul class="nav nav-treeview">

            @can('notifications.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('notifications*') ? 'active' : '' }}" href="{!! route('notifications.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-bell"></i>
                        @endif
                        <p> Notifications </p>
                    </a>
                </li>
            @endcan

            @can('emailnotifications.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('emailnotifications*') ? 'active' : '' }}" href="{!! route('emailnotifications.index') !!}">    
                        @if($icons)
                            <i class="nav-icon fa fa-envelope"></i>
                        @endif
                        <p> Email Alerts </p>
                    </a>
                </li>
            @endcan

        </ul>
    </li>
@endcan


<?php /* ?>
@can('faqs.index')
    <li class="nav-item has-treeview {{ Request::is('faqCategories*') || Request::is('faqs*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('faqs*') || Request::is('faqCategories*') ? 'active' : '' }}"> @if($icons)
                <i class="nav-icon fa fa-support"></i>@endif
            <p>{{trans('lang.faq_plural')}} <i class="right fa fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            @can('faqCategories.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('faqCategories*') ? 'active' : '' }}" href="{!! route('faqCategories.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-folder"></i>@endif<p>{{trans('lang.faq_category_plural')}}</p></a>
                </li>
            @endcan

            @can('faqs.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('faqs*') ? 'active' : '' }}" href="{!! route('faqs.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-question-circle"></i>@endif
                        <p>{{trans('lang.faq_plural')}}</p></a>
                </li>
            @endcan
        </ul>
    </li>
@endcan
<?php /*/ ?>
<li class="nav-header">{{trans('lang.app_setting')}}</li>
<?php /* ?>
@can('medias')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('medias*') ? 'active' : '' }}" href="{!! url('medias') !!}">@if($icons)<i class="nav-icon fa fa-picture-o"></i>@endif
            <p>{{trans('lang.media_plural')}}</p></a>
    </li>
@endcan
<?php /*/ ?>

<?php /*/ ?>
@can('payments.index')
    <li class="nav-item has-treeview {{ Request::is('earnings*') || Request::is('driversPayouts*') || Request::is('marketsPayouts*') || Request::is('payments*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('earnings*') || Request::is('driversPayouts*') || Request::is('marketsPayouts*') || Request::is('payments*') ? 'active' : '' }}"> @if($icons)
                <i class="nav-icon fa fa-credit-card"></i>@endif
            <p>{{trans('lang.payment_plural')}}<i class="right fa fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @can('payments.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('payments*') ? 'active' : '' }}" href="{!! route('payments.index') !!}">@if($icons)
                    <i class="nav-icon fa fa-money"></i>@endif<p>{{trans('lang.payment_plural')}}</p></a>
                </li>
            @endcan
            
            @can('earnings.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('earnings*') ? 'active' : '' }}" href="{!! route('earnings.index') !!}">@if($icons)<i class="nav-icon fa fa-money"></i>@endif<p>{{trans('lang.earning_plural')}} <span class="right badge badge-danger">New</span> </p></a>
                </li>
            @endcan
            
            @can('driversPayouts.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('driversPayouts*') ? 'active' : '' }}" href="{!! route('driversPayouts.index') !!}">@if($icons)<i class="nav-icon fa fa-dollar"></i>@endif<p>{{trans('lang.drivers_payout_plural')}}</p></a>
                </li>
            @endcan
            
            @can('marketsPayouts.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('marketsPayouts*') ? 'active' : '' }}" href="{!! route('marketsPayouts.index') !!}">@if($icons)<i class="nav-icon fa fa-dollar"></i>@endif<p>{{trans('lang.markets_payout_plural')}}</p></a>
                </li>
            @endcan
            
        </ul>
    </li>
@endcan
<?php /*/ ?>

@can('app-settings')

    <li class="nav-item has-treeview {{ Request::is('settings/website*') || Request::is('websiteSlides*') || Request::is('websiteTestimonials*') || Request::is('specialOffers*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('settings/website*') || Request::is('websiteSlides*') || Request::is('websiteTestimonials*') || Request::is('specialOffers*') ? 'active' : '' }}">
            @if($icons)<i class="nav-icon fa fa-globe"></i>@endif
            <p>
                {{trans('lang.website_menu')}}
                <i class="right fa fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            <li class="nav-item">
                <a href="{!! url('settings/website/globals') !!}" class="nav-link {{  Request::is('settings/website/globals*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-cog"></i> @endif <p>{{trans('lang.app_setting_globals')}}</p>
                </a>
            </li>

            @can('websiteSlides.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('websiteSlides*') ? 'active' : '' }}" href="{!! route('websiteSlides.index') !!}">
                        @if($icons)<i class="nav-icon fa fa-magic"></i>@endif
                        <p>Website Slides</p>
                    </a>
                </li>
            @endcan

            @can('websiteTestimonials.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('websiteTestimonials*') ? 'active' : '' }}" href="{!! route('websiteTestimonials.index') !!}">@if($icons)<i class="nav-icon fa fa-quote-left"></i>@endif<p>{{trans('lang.website_testimonials')}} </p></a>
                </li>
            @endcan

            @can('specialOffers.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('specialOffers*') ? 'active' : '' }}" href="{!! route('specialOffers.index') !!}">@if($icons)<i class="nav-icon fa fa-gift"></i>@endif<p>{{trans('lang.special_offers')}} </p></a>
                </li>
            @endcan

        </ul>
    </li>

    <li class="nav-item has-treeview {{ Request::is('settings/mobile*') || Request::is('slides*') || Request::is('farmerSlides*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('settings/mobile*') || Request::is('slides*') || Request::is('farmerSlides*') ? 'active' : '' }}">
            @if($icons)<i class="nav-icon fa fa-mobile"></i>@endif
            <p>
                {{trans('lang.mobile_menu')}}
                <i class="right fa fa-angle-left"></i>
            </p></a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! url('settings/mobile/globals') !!}" class="nav-link {{  Request::is('settings/mobile/globals*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-cog"></i> @endif <p>{{trans('lang.app_setting_globals')}} <span class="right badge badge-danger">New</span> </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('settings/mobile/colors') !!}" class="nav-link {{  Request::is('settings/mobile/colors*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-pencil"></i> @endif <p>{{trans('lang.mobile_colors')}} <span class="right badge badge-danger">New</span> </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{!! url('settings/mobile/home') !!}" class="nav-link {{  Request::is('settings/mobile/home*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-home"></i> @endif <p>{{trans('lang.mobile_home')}}
                        <span class="right badge badge-danger">New</span></p>
                </a>
            </li>

            @can('slides.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('slides*') ? 'active' : '' }}" href="{!! route('slides.index') !!}">
                        @if($icons)<i class="nav-icon fa fa-magic"></i>@endif
                        <p> Customer App Slides </p>
                    </a>
                </li>
            @endcan

            @can('farmerSlides.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('farmerSlides*') ? 'active' : '' }}" href="{!! route('farmerSlides.index') !!}">
                        @if($icons)<i class="nav-icon fa fa-magic"></i>@endif
                        <p> Farmer App Slides </p>
                    </a>
                </li>
            @endcan

        </ul>

    </li>
    <li class="nav-item has-treeview {{
    (Request::is('settings*') ||
     Request::is('users*')) && !Request::is('settings/mobile*')
        ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{
        (Request::is('settings*') ||
         Request::is('users*')) && !Request::is('settings/mobile*')
          ? 'active' : '' }}"> @if($icons)<i class="nav-icon fa fa-cogs"></i>@endif
            <p>{{trans('lang.app_setting')}} <i class="right fa fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{!! url('settings/app/globals') !!}" class="nav-link {{  Request::is('settings/app/globals*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-cog"></i> @endif <p>{{trans('lang.app_setting_globals')}}</p>
                </a>
            </li>

            @can('users.index')
                <!--<li class="nav-item">
                    <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{!! route('users.index') !!}">@if($icons)
                            <i class="nav-icon fa fa-users"></i>@endif
                        <p>{{trans('lang.user_plural')}}</p></a>
                </li>-->
            @endcan

            <li class="nav-item has-treeview {{ Request::is('settings/permissions*') || Request::is('settings/roles*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Request::is('settings/permissions*') || Request::is('settings/roles*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-user-secret"></i>@endif
                    <p>
                        {{trans('lang.permission_menu')}}
                        <i class="right fa fa-angle-left"></i>
                    </p></a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('settings/permissions') ? 'active' : '' }}" href="{!! route('permissions.index') !!}">
                            @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                            <p>{{trans('lang.permission_table')}}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('settings/permissions/create') ? 'active' : '' }}" href="{!! route('permissions.create') !!}">
                            @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                            <p>{{trans('lang.permission_create')}}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('settings/roles') ? 'active' : '' }}" href="{!! route('roles.index') !!}">
                            @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                            <p>{{trans('lang.role_table')}}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('settings/roles/create') ? 'active' : '' }}" href="{!! route('roles.create') !!}">
                            @if($icons)<i class="nav-icon fa fa-circle-o"></i>@endif
                            <p>{{trans('lang.role_create')}}</p>
                        </a>
                    </li>
                </ul>

            </li>

            <?php /*/ ?>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('settings/customFields*') ? 'active' : '' }}" href="{!! route('customFields.index') !!}">@if($icons)
                        <i class="nav-icon fa fa-list"></i>@endif<p>{{trans('lang.custom_field_plural')}}</p></a>
            </li>


            <li class="nav-item">
                <a href="{!! url('settings/app/localisation') !!}" class="nav-link {{  Request::is('settings/app/localisation*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-language"></i> @endif <p>{{trans('lang.app_setting_localisation')}}</p></a>
            </li>
            <li class="nav-item">
                <a href="{!! url('settings/translation/en') !!}" class="nav-link {{ Request::is('settings/translation*') ? 'active' : '' }}">
                    @if($icons) <i class="nav-icon fa fa-language"></i> @endif <p>{{trans('lang.app_setting_translation')}}</p></a>
            </li>
            @can('currencies.index')
            <li class="nav-item">
                <a class="nav-link {{ Request::is('settings/currencies*') ? 'active' : '' }}" href="{!! route('currencies.index') !!}">@if($icons)<i class="nav-icon fa fa-dollar"></i>@endif<p>{{trans('lang.currency_plural')}}</p></a>
            </li>
            @endcan

            <li class="nav-item">
                <a href="{!! url('settings/payment/payment') !!}" class="nav-link {{  Request::is('settings/payment*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-credit-card"></i> @endif <p>{{trans('lang.app_setting_payment')}}</p>
                </a>
            </li>
            <?php /*/ ?>
            <?php /* ?>
            <li class="nav-item">
                <a href="{!! url('settings/app/social') !!}" class="nav-link {{  Request::is('settings/app/social*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-globe"></i> @endif <p>{{trans('lang.app_setting_social')}}</p>
                </a>
            </li>
            <?php /*/ ?>

            <li class="nav-item">
                <a href="{!! url('settings/app/notifications') !!}" class="nav-link {{  Request::is('settings/app/notifications*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-bell"></i> @endif <p>{{trans('lang.app_setting_notifications')}}</p>
                </a>
            </li>


            <li class="nav-item">
                <a href="{!! url('settings/mail/smtp') !!}" class="nav-link {{ Request::is('settings/mail*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-envelope"></i> @endif <p>{{trans('lang.app_setting_mail')}}</p>
                </a>
            </li>

            <?php /*/ ?>
            @can('app.invoiceThemes')
            <li class="nav-item">
                <a href="{!! route('app.invoiceThemes') !!}" class="nav-link {{ Request::is('invoiceThemes*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-sticky-note-o"></i> @endif <p>{{trans('lang.app_invoice_theme')}}</p>
                </a>
            </li>
            @endcan
            <?php /*/ ?>

            <?php /*/ ?>
            @can('app.thermalPrint') 
            <li class="nav-item">
                <a href="{!! route('app.thermalPrint') !!}" class="nav-link {{ Request::is('thermalPrint*') ? 'active' : '' }}">
                    @if($icons)<i class="nav-icon fa fa-sticky-note-o"></i> @endif <p>{{trans('lang.app_thermal_print')}}</p>
                </a>
            </li>
            @endcan
            <?php /*/ ?>
            
        </ul>
    </li>
    <br><br><br><br>
@endcan