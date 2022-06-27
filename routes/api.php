<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('settings', 'API\UserAPIController@settings');

Route::post('login', 'API\UserAPIController@login');
Route::post('socialLogin', 'API\UserAPIController@socialLogin');
Route::post('register', 'API\UserAPIController@register');
Route::get('logout', 'API\UserAPIController@logout');
Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
Route::get('user', 'API\UserAPIController@user');


Route::resource('slides', 'API\SlideAPIController')->except([
    'show'
]);
Route::resource('farmer_slides', 'API\FarmerSlideAPIController')->except([
    'show'
]);

//Products
Route::resource('products', 'API\ProductAPIController');
Route::get('product_units/{id}', 'API\ProductAPIController@productUnits');
Route::get('product_unit_price/{unit_id}/{product_id}', 'API\ProductAPIController@productUnitPrice');





Route::prefix('driver')->group(function () {
    Route::post('login', 'API\Driver\UserAPIController@login');
    Route::post('socialLogin', 'API\Driver\UserAPIController@socialLogin');
    Route::post('register', 'API\Driver\UserAPIController@register');
    Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
    Route::get('user', 'API\Driver\UserAPIController@user');
    Route::get('logout', 'API\Driver\UserAPIController@logout');
    Route::get('settings', 'API\Driver\UserAPIController@settings');
});

Route::prefix('manager')->group(function () {
    Route::post('login', 'API\Manager\UserAPIController@login');
    Route::post('register', 'API\Manager\UserAPIController@register');
    Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
    Route::get('user', 'API\Manager\UserAPIController@user');
    Route::get('logout', 'API\Manager\UserAPIController@logout');
    Route::get('settings', 'API\Manager\UserAPIController@settings');
});


#Route::post('login', 'API\UserAPIController@login');
#Route::post('socialLogin', 'API\UserAPIController@socialLogin');
#Route::post('emailOtpGeneration', 'API\UserAPIController@emailOtpGeneration');

#Route::post('register', 'API\UserAPIController@register');
#Route::get('user', 'API\UserAPIController@user');
#Route::get('logout', 'API\UserAPIController@logout');
#Route::get('settings', 'API\UserAPIController@settings');

#Route::resource('fields', 'API\FieldAPIController');
#Route::resource('categories', 'API\CategoryAPIController');
#Route::resource('markets', 'API\MarketAPIController');

#Route::resource('faq_categories', 'API\FaqCategoryAPIController');
#Route::get('products/categories', 'API\ProductAPIController@categories');
#Route::get('products/pricevaritations', 'API\ProductAPIController@pricevaritations');
Route::get('highVolumeProducts', 'API\ProductAPIController@getHighVolumeProducts');
#Route::resource('products', 'API\ProductAPIController');
#Route::resource('galleries', 'API\GalleryAPIController');


Route::resource('products_refund', 'API\ProductsRefundAPIController');
Route::resource('delivery_tips', 'API\DeliveryTipsAPIController');
Route::resource('complaints', 'API\ComplaintsAPIController');
Route::resource('faqs', 'API\FaqAPIController');
Route::resource('market_reviews', 'API\MarketReviewAPIController');
Route::resource('currencies', 'API\CurrencyAPIController');

Route::resource('customerFarmerReviews', 'API\CustomerFarmerReviewsAPIController');
Route::resource('vendor_stock', 'API\SupplierRequestAPIController');
Route::resource('stripe', 'API\StripeAPIController');

Route::resource('option_groups', 'API\OptionGroupAPIController');
Route::resource('options', 'API\OptionAPIController');

Route::get('getDriverTipsById', 'API\DeliveryTipsAPIController@getDriverTipsById');
Route::get('getDriverReviewsById', 'API\DriverReviewAPIController@getDriverReviewsById');

Route::get('deliveryZones', 'API\DriverAPIController@deliveryZones');
Route::get('deliveryFees', 'API\DriverAPIController@deliveryFees');
Route::get('getDriversDelivery', 'API\DriverAPIController@getDriversDelivery');
Route::get('getDriverSupplies', 'API\SupplierRequestAPIController@getDriverSupplies');

Route::get('getCustomerFarmerReviewsById', 'API\CustomerFarmerReviewsAPIController@getCustomerFarmerReviewsById');
Route::get('getCancelOrders', 'API\OrderAPIController@getCancelOrders');
Route::get('getCancelVendorStock', 'API\SupplierRequestAPIController@getCancelVendorStock');

Route::post('users/{id}', 'API\UserAPIController@update');
Route::post('users/profile/{id}', 'API\UserAPIController@userProfilePicture');



Route::middleware('auth:api')->group(function () {

    Route::resource('notifications', 'API\NotificationAPIController');
    Route::resource('orders', 'API\OrderAPIController');
    Route::resource('delivery_addresses', 'API\DeliveryAddressAPIController');
    Route::get('order_delivery_duration', 'API\DeliveryAddressAPIController@getDeliveryDuration');

    //Rewards
    Route::get('rewards', 'API\UserAPIController@userRewards');

    //Cart
    Route::get('cart/count', 'API\CartAPIController@count')->name('carts.count');
    Route::resource('cart', 'API\CartAPIController');

    //Product Review
    Route::resource('product_reviews', 'API\ProductReviewAPIController');

    //Driver Review
    Route::resource('driver_reviews', 'API\DriverReviewAPIController');

    //Coupons
    Route::resource('coupons', 'API\CouponAPIController');

    //Wishlist
    Route::get('favorites/exist', 'API\FavoriteAPIController@exist');
    Route::resource('favorites', 'API\FavoriteAPIController');
    
    //Delivery Charge
    Route::get('delivery_charge', 'API\OrderAPIController@calculateDeliveryCharge');


    //Farmer Dasboard
    Route::get('farmer_dashboard', 'API\UserAPIController@farmerDashboard');

    //Vendor Stock
    Route::resource('vendor_stocks', 'API\VendorStockAPIController');
    Route::resource('purchase_invoice', 'API\PurchaseInvoiceAPIController');
    Route::resource('vendor_stock_payments', 'API\VendorStockPaymentAPIController');

    //Request Callback
    Route::resource('request_callback', 'API\RequestCallbackAPIController');




    Route::group(['middleware' => ['role:driver']], function () {
        Route::prefix('driver')->group(function () {
            Route::resource('orders', 'API\OrderAPIController');
            Route::resource('notifications', 'API\NotificationAPIController');
            Route::post('users/{id}', 'API\UserAPIController@update');
            Route::resource('faq_categories', 'API\FaqCategoryAPIController');
            Route::resource('faqs', 'API\FaqAPIController');
        });
    });

    Route::group(['middleware' => ['role:manager']], function () {
        Route::prefix('manager')->group(function () {
            Route::post('users/{id}', 'API\UserAPIController@update');
            Route::get('users/drivers_of_market/{id}', 'API\Manager\UserAPIController@driversOfMarket');
            Route::get('dashboard/{id}', 'API\DashboardAPIController@manager');
            Route::resource('markets', 'API\Manager\MarketAPIController');
            Route::resource('notifications', 'API\NotificationAPIController');
        });
    });
   

    #Route::resource('order_statuses', 'API\OrderStatusAPIController');

    Route::get('payments/byMonth', 'API\PaymentAPIController@byMonth')->name('payments.byMonth');
    Route::resource('payments', 'API\PaymentAPIController');


    Route::resource('product_orders', 'API\ProductOrderAPIController');
    

    Route::resource('drivers', 'API\DriverAPIController');

    Route::resource('earnings', 'API\EarningAPIController');

    Route::resource('driversPayouts', 'API\DriversPayoutAPIController');

    Route::resource('marketsPayouts', 'API\MarketsPayoutAPIController');

    
    
    Route::resource('driversTimers', 'API\DriversTimersAPIController');
    
    Route::post('vendor_supply_image/{id}', 'API\SupplierRequestAPIController@uploadVendorSupplyImage');
    Route::post('vendor_supply_image/remove-media/{id}','API\SupplierRequestAPIController@removeMedia');
    Route::post('vendorStockDelete/{id}', 'API\SupplierRequestAPIController@vendorStockDelete');
    
    Route::get('driver_vendor_stock', 'API\SupplierRequestAPIController@driverVendorStock');
    Route::get('driver_vendor_stock_show/{id}', 'API\SupplierRequestAPIController@driverVendorStockShow');
    Route::post('driver_vendor_stock_update/{id}', 'API\SupplierRequestAPIController@driverVendorStockUpdate');
    Route::resource('purchase_invoice', 'API\PurchaseInvoiceAPIController');
    
    Route::get('farmer_reports', 'API\PurchaseInvoiceAPIController@farmerReports');
    
   
   /*8.12.2021*/ 
   Route::get('totalRewardPoints', 'API\UserAPIController@totalRewardPoints');
   Route::post('remainingRewardPoints', 'API\UserAPIController@remainingRewardPoints'); 
   Route::post('repeatLastOrder', 'API\OrderAPIController@repeatLastOrder');     
   Route::post('orders/destroy/{id}', 'API\OrderAPIController@destroy');
  
   Route::post('updateUserDeliveryAddress', 'API\UserAPIController@updateUserDeliveryAddress');
   
   Route::get('appFaqs', 'API\FaqCategoryAPIController@getFaqs');
   Route::post('driverClockIn', 'API\DriversTimersAPIController@driverClockIn');
   Route::post('driverClockOut', 'API\DriversTimersAPIController@driverClockOut');
   Route::post('deliveryClockIn', 'API\DeliveryTimersAPIController@deliveryClockIn');
   Route::post('deliveryClockOut', 'API\DeliveryTimersAPIController@deliveryClockOut');
   
   
   /*13-01-2022  Stripe payment */ 
  
//Route::post('stripe', 'API\StripeAPIController');

   Route::get('ordersbyMonth', 'API\ReportsAPIController@byMonth')->name('ordersByMonth');
   Route::get('topSellingProducts', 'API\ReportsAPIController@topSellingProducts')->name('topSellingProducts');
    
});