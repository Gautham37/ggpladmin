<?php
/**
 * File name: web.php
 * Last modified: 2020.06.07 at 07:02:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    echo 'Cache Cleared';
    // return what you want
});

Route::get('reports/exportReport','ReportsController@exportReport')->name('reports.exportReport');

Route::get('login/{service}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{service}/callback', 'Auth\LoginController@handleProviderCallback');
Auth::routes();

Route::get('payments/failed', 'PayPalController@index')->name('payments.failed');
Route::get('payments/razorpay/checkout', 'RazorPayController@checkout');
Route::post('payments/razorpay/pay-success/{userId}/{deliveryAddressId?}/{couponCode?}', 'RazorPayController@paySuccess');
Route::get('payments/razorpay', 'RazorPayController@index');

Route::get('payments/paypal/express-checkout', 'PayPalController@getExpressCheckout')->name('paypal.express-checkout');
Route::get('payments/paypal/express-checkout-success', 'PayPalController@getExpressCheckoutSuccess');
Route::get('payments/paypal', 'PayPalController@index')->name('paypal.index');

Route::get('firebase/sw-js','AppSettingController@initFirebase');


Route::get('storage/app/public/{id}/{conversion}/{filename?}', 'UploadController@storage');
Route::middleware('auth')->group(function () {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::post('uploads/store', 'UploadController@store')->name('medias.create');
    Route::get('users/profile', 'UserController@profile')->name('users.profile');
    Route::post('users/remove-media', 'UserController@removeMedia');
    Route::resource('users', 'UserController');
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    Route::group(['middleware' => ['permission:medias']], function () {
        Route::get('uploads/all/{collection?}', 'UploadController@all');
        Route::get('uploads/collectionsNames', 'UploadController@collectionsNames');
        Route::post('uploads/clear', 'UploadController@clear')->name('medias.delete');
        Route::get('medias', 'UploadController@index')->name('medias');
        Route::get('uploads/clear-all', 'UploadController@clearAll');
    });

    Route::group(['middleware' => ['permission:permissions.index']], function () {
        Route::get('permissions/role-has-permission', 'PermissionController@roleHasPermission');
        Route::get('permissions/refresh-permissions', 'PermissionController@refreshPermissions');
    });
    Route::group(['middleware' => ['permission:permissions.index']], function () {
        Route::post('permissions/give-permission-to-role', 'PermissionController@givePermissionToRole');
        Route::post('permissions/revoke-permission-to-role', 'PermissionController@revokePermissionToRole');
    });

    Route::group(['middleware' => ['permission:app-settings']], function () {
        Route::prefix('settings')->group(function () {
            Route::resource('permissions', 'PermissionController');
            Route::resource('roles', 'RoleController');
            Route::resource('customFields', 'CustomFieldController');
            Route::resource('currencies', 'CurrencyController')->except([
                'show'
            ]);
            Route::get('bankDetails', 'AppSettingController@bankDetails');
            Route::get('addressDetails', 'AppSettingController@addressDetails');
            Route::get('invoiceThemes', 'AppSettingController@invoiceThemes')->name('app.invoiceThemes');
            Route::get('thermalPrint', 'AppSettingController@thermalPrint')->name('app.thermalPrint');
            Route::get('users/login-as-user/{id}', 'UserController@loginAsUser')->name('users.login-as-user');
            Route::patch('update', 'AppSettingController@update');
            Route::patch('translate', 'AppSettingController@translate');
            Route::get('sync-translation', 'AppSettingController@syncTranslation');
            Route::get('clear-cache', 'AppSettingController@clearCache');
            Route::get('check-update', 'AppSettingController@checkForUpdates');
            Route::get('birthdayCustomerDetails', 'AppSettingController@birthdayCustomerDetails');
            // disable special character and number in route params
            Route::get('/{type?}/{tab?}', 'AppSettingController@index')
                ->where('type', '[A-Za-z]*')->where('tab', '[A-Za-z]*')->name('app-settings');
        });
    });

    Route::post('fields/remove-media','FieldController@removeMedia');
    Route::resource('fields', 'FieldController')->except([
        'show'
    ]);

    
    //New Expenses
    Route::post('expenses/remove-media','ExpensesController@removeMedia');
    Route::resource('expenses', 'ExpensesController'); 
    //New Expenses

    //New Expenses Categories
    Route::post('expensesCategory/remove-media', 'ExpensesCategoryController@removeMedia'); 
    Route::resource('expensesCategory', 'ExpensesCategoryController')->except([
        'show'
    ]);
    //New Expenses Categories


    //New Expenses Report
    Route::resource('reports','ReportsController');


    Route::get('reports/show','ReportsController@show')->name('reports.show');    
    Route::get('reports/expenseTransaction','ReportsController@expenseTransaction')->name('reports.expenseTransaction');
    Route::get('reports/expenseCategory','ReportsController@expenseCategory')->name('reports.expenseCategory');
    Route::get('reports/salesSummaryReport','ReportsController@salesSummaryReport');
    Route::get('reports/salesPartyStatement','ReportsController@salesPartyStatement');
    Route::get('reports/dayBook','ReportsController@dayBook');
    
    Route::get('reports/billWiseProfit','ReportsController@billWiseProfit');

    Route::get('reports/stockSummary','ReportsController@stockSummary');
    Route::get('reports/rateList','ReportsController@rateList');
    Route::get('reports/itemSalesSummary','ReportsController@itemSalesSummary');
    Route::get('reports/lowStockSummary','ReportsController@lowStockSummary');
    Route::get('reports/stockDetailReport','ReportsController@stockDetailReport');
    Route::get('reports/itemReportbyParty','ReportsController@itemReportbyParty');

    Route::get('reports/partyWiseOutstanding','ReportsController@partyWiseOutstanding');
    Route::get('reports/partyReportbyItem','ReportsController@partyReportbyItem');

    Route::get('reports/expenseTransactionReport','ReportsController@expenseTransactionReport');
    Route::get('reports/expenseCategoryReport','ReportsController@expenseCategoryReport');
    
     Route::get('reports/popularProductsReport','ReportsController@popularProductsReport');
     Route::get('reports/profitableProductsReport','ReportsController@profitableProductsReport');
     Route::get('reports/staffLoginReport','ReportsController@staffLoginReport');
     Route::get('reports/productsReport','ReportsController@productsReport');
     Route::get('reports/productsHistoryReport','ReportsController@productsHistoryReport');
     Route::get('reports/customersReport','ReportsController@customersReport');
     Route::get('reports/stockPurchaseReport','ReportsController@stockPurchaseReport');
     Route::get('reports/wastageReport','ReportsController@wastageReport');
     Route::get('reports/deliveryReport','ReportsController@deliveryReport');

    Route::get('reports/exportReport','ReportsController@exportReport');
    //New Expenses Report     

    //Quotes 
    Route::resource('quotes', 'QuotesController');
    Route::post('quotes/remove-media', 'QuotesController@removeMedia');
    Route::get('quotes/print/{id}/{type}/{view_type}','QuotesController@print')->name('quotes.print');
    //Quotes

    //Sales Invoice 
    Route::resource('salesInvoice', 'SalesInvoiceController');
    Route::post('salesInvoice/remove-media', 'SalesInvoiceController@removeMedia');
    Route::get('salesInvoice/print/{id}/{type}/{view_type}','SalesInvoiceController@print')->name('salesInvoice.print');
    Route::post('salesInvoice/paytmResult','SalesInvoiceController@paytmResult');
    //Sales Invoice

    //Sales Return
    Route::resource('salesReturn', 'SalesReturnController');
    Route::post('salesReturn/remove-media', 'SalesReturnController@removeMedia');
    Route::get('salesReturn/print/{id}/{type}/{view_type}','SalesReturnController@print')->name('salesReturn.print');
    Route::post('salesReturn/paytmResult','SalesReturnController@paytmResult');
    //Sales Return

    //Payment In
    Route::resource('paymentIn', 'PaymentInController');
    Route::post('paymentIn/remove-media','PaymentInController@removeMedia');
    Route::get('paymentIn/print/{id}/{type}/{view_type}','PaymentInController@print')->name('paymentIn.print');
    //Payment In

    //Payment Out
    Route::resource('paymentOut', 'PaymentOutController');
    Route::post('paymentOut/remove-media','PaymentOutController@removeMedia');
    Route::get('paymentOut/print/{id}/{type}/{view_type}','PaymentOutController@print')->name('paymentOut.print');
    //Payment Out

    //Purchase Order
    Route::resource('purchaseOrder', 'PurchaseOrderController');
    Route::post('purchaseOrder/remove-media', 'PurchaseOrderController@removeMedia');
    Route::get('purchaseOrder/print/{id}/{type}/{view_type}','PurchaseOrderController@print')->name('purchaseOrder.print');
    //Purchase Order

    //Purchase Invoice
    Route::resource('purchaseInvoice', 'PurchaseInvoiceController');
    Route::post('purchaseInvoice/remove-media', 'PurchaseInvoiceController@removeMedia');
    Route::get('purchaseInvoice/print/{id}/{type}/{view_type}','PurchaseInvoiceController@print')->name('purchaseInvoice.print');
    //Purchase Invoice

    //Purchase Return
    Route::resource('purchaseReturn', 'PurchaseReturnController');
    Route::post('purchaseReturn/remove-media', 'PurchaseReturnController@removeMedia');
    Route::get('purchaseReturn/print/{id}/{type}/{view_type}','PurchaseReturnController@print')->name('purchaseReturn.print');
    //Purchase Return

    //Vendor Stock
    Route::resource('vendorStock', 'VendorStockController');
    Route::post('vendorStock/remove-media', 'VendorStockController@removeMedia');
    Route::get('vendorStock/print/{id}/{type}/{view_type}','VendorStockController@print')->name('vendorStock.print');
    //Vendor Stock

    //Stock Take
    Route::resource('stockTake', 'StockTakeController');
    Route::post('stockTake/remove-media', 'StockTakeController@removeMedia');
    Route::get('stockTake/print/{id}/{type}/{view_type}','StockTakeController@print')->name('stockTake.print');
    //Stock Take



    //Delivery Challan
    Route::post('deliveryChallan/remove-media', 'DeliveryChallanController@removeMedia'); 
    Route::resource('deliveryChallan', 'DeliveryChallanController');
    Route::post('deliveryChallan/loaddeliveryDetailItems','DeliveryChallanController@loaddeliveryDetailItems');
    Route::post('deliveryChallan/loadDeliveryItems','DeliveryChallanController@loadDeliveryItems');
    Route::post('deliveryChallan/loadDeliveryProducts','DeliveryChallanController@loadDeliveryProducts');
    Route::post('deliveryChallan/loadDeliveryProductsbyBarcode','DeliveryChallanController@loadDeliveryProductsbyBarcode');
    Route::post('deliveryChallan/loadParty','DeliveryChallanController@loadParty');
    Route::get('deliveryChallan/downloadDeliveryChallan/{id}/{type}','DeliveryChallanController@downloaddeliveryChallan');
    Route::get('deliveryChallan/printDeliveryChallan/{id}/{type}','DeliveryChallanController@printdeliveryChallan');
    Route::get('deliveryChallan/thermalprintDeliveryChallan/{id}','DeliveryChallanController@thermalprintdeliveryChallan');
    //Delivery Challan    

    //Supplier Request
    Route::post('supplierRequest/remove-media', 'SupplierRequestController@removeMedia'); 
    Route::resource('supplierRequest', 'SupplierRequestController');
    Route::post('supplierRequest/loadSupplierRequestItems','SupplierRequestController@loadSupplierRequestItems');
    Route::post('supplierRequest/loadSupplierRequestDetailItems','SupplierRequestController@loadSupplierRequestDetailItems');
    Route::post('supplierRequest/loadSupplierRequestProducts','SupplierRequestController@loadSupplierRequestProducts');
    Route::post('supplierRequest/loadSupplierRequestProductsbyBarcode','SupplierRequestController@loadSupplierRequestProductsbyBarcode');
    Route::post('supplierRequest/loadParty','SupplierRequestController@loadParty');
    Route::get('supplierRequest/downloadSupplierRequest/{id}/{type}','SupplierRequestController@downloadSupplierRequest');
    Route::get('supplierRequest/printSupplierRequest/{id}/{type}','SupplierRequestController@printSupplierRequest');
    Route::get('supplierRequest/thermalprintsupplierRequest/{id}','SupplierRequestController@thermalprintSupplierRequest');
    //Supplier Request
    

    //Staffs
    Route::resource('staffs', 'StaffsController');
    Route::post('showStaffDepartments','UserController@showStaffDepartments')->name('staffs.showStaffDepartments');
    //Staffs 

    //Customer Groups
    Route::post('CustomerGroups/remove-media', 'CustomerGroupsController@removeMedia'); 
    Route::resource('CustomerGroups', 'CustomerGroupsController')->except([
        'show'
    ]);
    //Customer Groups


    Route::post('markets/remove-media', 'MarketController@removeMedia');
    Route::get('requestedMarkets', 'MarketController@requestedMarkets')->name('requestedMarkets.index'); //adeed
    Route::get('markets/view/{id}', 'MarketController@view')->name('markets.view');
    Route::get('markets/getTransactionDetails', 'MarketController@getTransactionDetails');
     Route::get('markets/getLedgerStatement', 'MarketController@getLedgerStatement');
    Route::get('markets/getRewardsDetails', 'MarketController@getRewardsDetails');
    Route::resource('markets', 'MarketController');
    Route::post('markets/showPartySubTypes','MarketController@showPartySubTypes')->name('markets.showPartySubTypes');

    Route::resource('marketActivity','MarketActivityController');
    Route::resource('marketNotes','MarketNotesController');

    Route::get('marketsImport', 'MarketController@import')->name('markets.import');
    Route::post('products/importMarkets', 'MarketController@importMarkets')->name('markets.importmarkets');

    Route::post('categories/remove-media', 'CategoryController@removeMedia'); 
    Route::resource('categories', 'CategoryController')->except([
        'show'
    ]);
    
    Route::post('subcategory/remove-media', 'SubcategoryController@removeMedia'); 
    Route::resource('subcategory', 'SubcategoryController');
    
    Route::post('departments/remove-media', 'DepartmentsController@removeMedia'); 
    Route::resource('departments', 'DepartmentsController')->except([
        'show'
    ]);
    Route::get('departments-products/{id}','ProductController@departmentsProducts');


    Route::resource('faqCategories', 'FaqCategoryController')->except([
        'show'
    ]);

    Route::resource('orderStatuses', 'OrderStatusController')->except([
        'create', 'store', 'destroy'
    ]);;

    Route::get('products/getUnitList', 'ProductController@getUnitList');
    Route::post('products/remove-media', 'ProductController@removeMedia');
    Route::get('products/stock','ProductController@addStock')->name('products.stock');
    Route::post('products/getProductDetails','ProductController@getProductDetails');
    Route::post('products/getProductDetailsbyID','ProductController@getProductDetailsbyID');
    Route::post('products/updateInventory','ProductController@updateInventory');
    Route::get('products/view/{id}', 'ProductController@view')->name('products.view');
    Route::get('products/getStockdetails', 'ProductController@getStockdetails');
    Route::get('products/getTaxRates', 'ProductController@getTaxRates');
    Route::get('products/printBarcodes/{id}', 'ProductController@printBarCodes');
    Route::post('products/getPriceVariations', 'ProductController@getPriceVariations');
    Route::post('products/getPriceVariationsbyquantity', 'ProductController@getPriceVariationsbyquantity');
    Route::post('createProductPrice','ProductController@createProductPrice')->name('products.createProductPrice');
    Route::post('updateProductPrice','ProductController@updateProductPrice')->name('products.updateProductPrice');
    Route::resource('products', 'ProductController');
    Route::get('category-products/{id}','ProductController@categoryProducts');

    Route::post('galleries/remove-media', 'GalleryController@removeMedia');
    Route::resource('galleries', 'GalleryController')->except([
        'show'
    ]);

    Route::resource('productReviews', 'ProductReviewController')->except([
        'show'
    ]);

    Route::post('options/remove-media', 'OptionController@removeMedia');
    

    Route::resource('payments', 'PaymentController')->except([
        'create', 'store','edit', 'destroy'
    ]);;

    Route::resource('faqs', 'FaqController')->except([
        'show'
    ]);
    Route::resource('marketReviews', 'MarketReviewController')->except([
        'show'
    ]);

    Route::resource('favorites', 'FavoriteController')->except([
        'show'
    ]);

    Route::resource('orders', 'OrderController');
    Route::resource('deliveryTracker', 'DeliveryTrackController');
    Route::get('orders/print/{id}/{type}/{view_type}','OrderController@print')->name('orders.print');
    Route::get('orders/downloadorders/{id}/{type}','OrderController@downloadorders');
    Route::get('orders/printorders/{id}/{type}','OrderController@printorders');


    Route::resource('notifications', 'NotificationController')->except([
        'create', 'store', 'update','edit',
    ]);;

    Route::resource('carts', 'CartController')->except([
        'show','store','create'
    ]);
    Route::resource('deliveryAddresses', 'DeliveryAddressController')->except([
        'show'
    ]);

    Route::resource('drivers', 'DriverController')->except([
        'show'
    ]);
    Route::get('driversDelivery', 'DriverController@driversDelivery')->name('drivers.driversDelivery');

    Route::resource('earnings', 'EarningController')->except([
        'show','edit','update'
    ]);

    Route::resource('driversPayouts', 'DriversPayoutController')->except([
        'show','edit','update'
    ]);

    Route::resource('marketsPayouts', 'MarketsPayoutController')->except([
        'show','edit','update'
    ]);

    Route::resource('optionGroups', 'OptionGroupController')->except([
        'show'
    ]);

    Route::post('options/remove-media','OptionController@removeMedia');

    Route::resource('options', 'OptionController')->except([
        'show'
    ]);
    Route::resource('coupons', 'CouponController')->except([
        'show'
    ]);
    Route::post('slides/remove-media','SlideController@removeMedia');
    Route::resource('slides', 'SlideController')->except([
        'show'
    ]);
    
    Route::get('/importPriceVariations', 'ProductController@importPriceVariations')->name('products.importPriceVariations');
    Route::post('savePriceVariations','ProductController@savePriceVariations')->name('products.savePriceVariations');
    Route::resource('CustomerLevels', 'CustomerLevelsController')->except([
        'show'
    ]);
    Route::resource('partystream', 'PartyStreamController')->except([
        'show'
    ]);
    
    Route::resource('staffdepartment', 'StaffDepartmentController')->except([
        'show'
    ]);
    Route::resource('staffdesignation', 'StaffDesignationController')->except([
        'show'
    ]);
    Route::get('emailnotifications/previewEmailTemplate','EmailnotificationsController@previewEmailTemplate');
    Route::resource('emailnotifications', 'EmailnotificationsController');

    Route::post('emailnotifications/loadScheduledetails','EmailnotificationsController@loadScheduledetails');
    Route::post('emailnotifications/save_schedule_notifications','EmailnotificationsController@save_schedule_notifications');
    Route::get('emailnotifications/showrecipients','EmailnotificationsController@showrecipients');
    
    
    Route::post('websiteSlides/remove-media','WebsiteSlideController@removeMedia');
    Route::resource('websiteSlides', 'WebsiteSlideController')->except([
        'show'
    ]);

    Route::post('farmerSlides/remove-media','FarmerSlideController@removeMedia');
    Route::resource('farmerSlides', 'FarmerSlideController')->except([
        'show'
    ]);
    
    Route::post('websiteTestimonials/remove-media','WebsiteTestimonialsController@removeMedia');
    Route::resource('websiteTestimonials', 'WebsiteTestimonialsController')->except([
        'show'
    ]);

    Route::post('specialOffers/remove-media','SpecialOffersController@removeMedia');
    Route::resource('specialOffers', 'SpecialOffersController')->except([
        'show'
    ]);
    
    Route::post('rewards/remove-media','LoyalityPointsController@removeMedia');
    Route::resource('rewards', 'LoyalityPointsController')->except([
        'show'
    ]);
    
    Route::resource('deliveryZones', 'DeliveryZonesController')->except([
        'show'
    ]);
    
    //Inventory Track
    Route::post('inventory/remove-media','InventoryController@removeMedia');
    Route::resource('inventory', 'InventoryController');
    Route::get('inventoryList', 'InventoryController@list')->name('inventory.list');
    
    //Wastage Disposal
    Route::post('wastageDisposal/remove-media','WastageDisposalController@removeMedia');
    Route::resource('wastageDisposal', 'WastageDisposalController');
    Route::get('wastageDisposalList', 'WastageDisposalController@list')->name('wastageDisposal.list');

    Route::get('partyFields', 'MarketController@partyFields');
    Route::post('validateEmail', 'UserController@validateEmail');
    Route::post('dashboardDatas', 'DashboardController@dashboardDatas');
    Route::get('driverOrders', 'DashboardController@driverOrders');
    Route::post('markets/storeNotes/{id}', 'MarketController@storeNotes');

    Route::resource('productSeasons', 'ProductSeasonController');
    Route::resource('productColors', 'ProductColorController');
    Route::resource('productNutritions', 'ProductNutritionController');
    Route::resource('productTastes', 'ProductTasteController');
    Route::get('productsImport', 'ProductController@import')->name('products.import');
    Route::post('products/importProducts', 'ProductController@importProducts')->name('products.importproducts');

    Route::resource('attendance', 'AttendanceController');
    Route::post('attendance/summary', 'AttendanceController@summaryData')->name('attendance.summary');
    Route::get('attendances/mark', 'AttendanceController@mark')->name('attendance.mark');
    Route::post('attendance/punch', 'AttendanceController@punch')->name('attendance.punch');
    Route::post('attendance/enable', 'AttendanceController@enable');

    Route::resource('paymentFor', 'PaymentForController');
    Route::resource('charity', 'CharityController');

});

//Sales Invoice
Route::group(['prefix' => 'si'], function () {
    Route::get('/{invoice_id}/{invoice_type?}', [
        'uses' => 'SalesInvoiceController@printSalesInvoice',
    ]);
});

//Sales Return
Route::group(['prefix' => 'sr'], function () {
    Route::get('/{invoice_id}/{invoice_type?}', [
        'uses' => 'SalesReturnController@printSalesReturn',
    ]);
});

//Delivery Challan
Route::group(['prefix' => 'dc'], function () {
    Route::get('/{invoice_id}/{invoice_type?}', [
        'uses' => 'DeliveryChallanController@printDeliveryChallan',
    ]);
});

//Purchase Invoice
Route::group(['prefix' => 'pi'], function () {
    Route::get('/{invoice_id}/{invoice_type?}', [
        'uses' => 'PurchaseInvoiceController@printPurchaseInvoice',
    ]);
});

//Purchase Return
Route::group(['prefix' => 'pr'], function () {
    Route::get('/{invoice_id}/{invoice_type?}', [
        'uses' => 'PurchaseReturnController@printPurchaseReturn',
    ]);
});

//Purchase Order
Route::group(['prefix' => 'po'], function () {
    Route::get('/{invoice_id}/{invoice_type?}', [
        'uses' => 'PurchaseOrderController@printPurchaseOrder',
    ]);
});


Route::get('subcategory-products/{id}','ProductController@subcategoryProducts');
Route::post('products/showSubcategory','ProductController@showSubcategory')->name('products.showSubcategory');
Route::post('products/showDepartments','ProductController@showDepartments')->name('products.showDepartments');

//Party Types
Route::resource('partyTypes', 'PartyTypesController')->except([
        'show'
    ]);
    
//Party Sub Types
Route::resource('partySubTypes', 'PartySubTypesController')->except([
        'show'
    ]);
    
//Complaints
Route::resource('complaints', 'ComplaintsController')->except([
        'show','create','store','destroy'
    ]);
Route::get('complaints/{id}/comments', 'ComplaintsController@complaintsComments')->name('complaints.comments');
Route::post('complaints/{id}/viewComments','ComplaintsController@viewComments')->name('complaints.viewComments');
Route::get('complaints/{id}/closeComplaints','ComplaintsController@closeComplaints')->name('complaints.closeComplaints');
Route::post('complaints/loadComplaintsProducts','ComplaintsController@loadComplaintsProducts');
Route::post('complaints/loadComplaintsItems','ComplaintsController@loadComplaintsItems');
Route::post('complaints/{id}/saveCloseComplaints','ComplaintsController@saveCloseComplaints')->name('complaints.saveCloseComplaints');
// Route::post('complaints/getComplaintsCommentsID','ComplaintsController@getComplaintsCommentsID');
// Route::post('complaints/addComplaintsComments','ComplaintsController@addComplaintsComments');

Route::get('runSchedule', [App\Http\Controllers\CronController::class, 'calcMonthlyRewards']);    
/*Driver Reviews*/
Route::post('driverReviews/remove-media','DriverReviewController@removeMedia');
Route::resource('driverReviews', 'DriverReviewController')->except([
        'show'
    ]);
/*Delivery Tips*/
Route::post('deliveryTips/remove-media','DeliveryTipsController@removeMedia');
Route::resource('deliveryTips', 'DeliveryTipsController')->except([
        'show'
    ]);
/*Customer Farmer Reviews */
Route::post('customerFarmerReviews/remove-media','CustomerFarmerReviewsController@removeMedia');
Route::resource('customerFarmerReviews', 'CustomerFarmerReviewsController')->except([
        'show'
    ]);
/*Quality Grade*/
Route::post('qualityGrade/remove-media', 'QualityGradeController@removeMedia'); 
Route::resource('qualityGrade', 'QualityGradeController')->except([
        'show'
    ]);
/*Product Status*/
Route::post('productStatus/remove-media', 'ProductStatusController@removeMedia'); 
Route::resource('productStatus', 'ProductStatusController')->except([
        'show'
    ]);
/*Stock Status*/
Route::post('stockStatus/remove-media', 'StockStatusController@removeMedia'); 
Route::resource('stockStatus', 'StockStatusController')->except([
        'show'
    ]);
/*Value Added Service Affiliated*/
Route::post('valueAddedServiceAffiliated/remove-media', 'ValueAddedServiceAffiliatedController@removeMedia'); 
Route::resource('valueAddedServiceAffiliated', 'ValueAddedServiceAffiliatedController')->except([
        'show'
    ]);


//PDF

    Route::get('{id}/Quotes', 'QuotesController@frontView');
    Route::get('{id}/Quotes/DownloadPDF', 'QuotesController@DownloadPDF');
    Route::post('quotes/statusUpdate', 'QuotesController@statusUpdate');
    Route::post('quotes/commentSend', 'QuotesController@commentSend');

    Route::get('{id}/SalesInvoice', 'SalesInvoiceController@frontView');
    Route::get('{id}/SalesInvoice/DownloadPDF', 'SalesInvoiceController@DownloadPDF');

    Route::get('{id}/SalesReturn', 'SalesReturnController@frontView');
    Route::get('{id}/SalesReturn/DownloadPDF', 'SalesReturnController@DownloadPDF');

    Route::get('{id}/PaymentIn', 'PaymentInController@frontView');
    Route::get('{id}/PaymentIn/DownloadPDF', 'PaymentInController@DownloadPDF');

    Route::get('{id}/PurchaseOrder', 'PurchaseOrderController@frontView');
    Route::get('{id}/PurchaseOrder/DownloadPDF', 'PurchaseOrderController@DownloadPDF');
    Route::post('purchaseOrder/statusUpdate', 'PurchaseOrderController@statusUpdate');

    Route::get('{id}/PurchaseInvoice', 'PurchaseInvoiceController@frontView');
    Route::get('{id}/PurchaseInvoice/DownloadPDF', 'PurchaseInvoiceController@DownloadPDF');

    Route::get('{id}/PurchaseReturn', 'PurchaseReturnController@frontView');
    Route::get('{id}/PurchaseReturn/DownloadPDF', 'PurchaseReturnController@DownloadPDF');

    Route::get('{id}/PaymentOut', 'PaymentOutController@frontView');
    Route::get('{id}/PaymentOut/DownloadPDF', 'PaymentOutController@DownloadPDF');

//PDF
