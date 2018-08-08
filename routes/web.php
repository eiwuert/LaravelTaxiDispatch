<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/


/*
Login Route
*/

//sample route use group of middleware 
/*
Route::group(['middleware' => ['roles:Admin|Employee|HR-Manager']], function() {
       Route::get('/home', 'HomeController@index');
});
*/
Route::GET('GetDriver/{id}','TaxiDriverController@GetDriver');
Route::GET('GetVehicle/{id}','TaxiDriverController@GetVehicle');

Route::GET('updb','UserController@updb');
Route::GET('/', 'UserController@index');
Route::POST('/', 'UserController@AdminLogin');
Route::get('/home', 'DashboardController@home')->middleware('roles:Admin|Employee|Franchise');
Route::GET('/logout', 'UserController@logout');
Route::get('/forgotpassword/{id}/{token}', 'UserController@forgotpassword');
Route::POST('/forgotpassword/{id}/{token}', 'UserController@resetpassword');

Route::POST('/ActivateAttachedDriver','AttachedDriversController@ActivateAttachedDriver');

Route::GET('/excel', 'UserController@excel_export');
	
//Route::get('login', 'LoginController@AdminLogin');


/*
Route::get('/', function () {
    return view('home');
});
*/
// Route to Car Controller for Car Actions
Route::resource('taxi', 'CarController');
Route::POST('delete_taxi', 'CarController@delete_taxi')->middleware('roles:Admin');
Route::GET('/taxi', 'CarController@index')->middleware('roles:Admin|Franchise');
Route::POST('/taxi', 'CarController@index')->middleware('roles:Admin');
Route::get('/taxi/add', 'CarController@create')->middleware('roles:Admin');
Route::POST('/taxi/add', 'CarController@store')->middleware('roles:Admin');
Route::get('/taxi/{id}/edit', 'CarController@edit')->middleware('roles:Admin');
Route::POST('/taxi/{id}/edit', 'CarController@update')->middleware('roles:Admin');
Route::get('/taxi/{id}/view', 'CarController@show')->middleware('roles:Admin|Franchise');
Route::get('/state/getstatelist/{id?}', 'CarTypeController@getstatelist')->middleware('roles:Admin');
Route::get('/city/getcitylist/{id?}', 'CarTypeController@getcitylist')->middleware('roles:Admin');
Route::POST('/taxi/ajax_nonactive_taxi', 'CarController@ajax_nonactive_taxi')->middleware('roles:Admin');
Route::POST('/taxi/ajax_nonactive_block_taxi', 'CarController@ajax_nonactive_block_taxi')->middleware('roles:Admin');
Route::POST('/taxi/ajax_block_nonactive_taxi', 'CarController@ajax_block_nonactive_taxi')->middleware('roles:Admin');
Route::GET('/taxi/getmodel_type_list/{id?}', 'CarController@getmodel_type_list')->middleware('roles:Admin');
Route::GET('/model/get_model/{id?}', 'CarTypeController@get_model')->middleware('roles:Admin');
Route::POST('/taxi/change_bulk_status', 'CarController@change_bulk_status')->middleware('roles:Admin');
Route::POST('/ajax_deactive_taxi', 'CarController@ajax_detivate_taxi')->middleware('roles:Admin');
Route::POST('/ajax_active_taxi', 'CarController@ajax_activate_taxi')->middleware('roles:Admin');

//CarBrand  Management
Route::get('/brand', 'CarBrandController@index')->middleware('roles:Admin');
Route::get('/brand/add', 'CarBrandController@create')->middleware('roles:Admin');
Route::POST('/brand/add', 'CarBrandController@store')->middleware('roles:Admin');
Route::get('/brand/{id}/edit', 'CarBrandController@edit')->middleware('roles:Admin');
Route::POST('/brand/{id}/edit', 'CarBrandController@update')->middleware('roles:Admin');
Route::POST('/ajax_deactive_brand', 'CarBrandController@ajax_detivate_brand')->middleware('roles:Admin');
Route::POST('/ajax_active_brand', 'CarBrandController@ajax_activate_brand')->middleware('roles:Admin');
Route::POST('/brand/change_status', 'CarBrandController@change_brand_status')->middleware('roles:Admin');
//CarModel  Management
Route::get('/model', 'CarModelController@index')->middleware('roles:Admin');
Route::get('/model/add', 'CarModelController@create')->middleware('roles:Admin');
Route::POST('/model/add', 'CarModelController@store')->middleware('roles:Admin');
Route::get('/model/{id}/edit', 'CarModelController@edit')->middleware('roles:Admin');
Route::POST('/model/{id}/edit', 'CarModelController@update')->middleware('roles:Admin');
Route::POST('/ajax_deactive_model', 'CarModelController@ajax_detivate_model')->middleware('roles:Admin');
Route::POST('/ajax_active_model', 'CarModelController@ajax_activate_model')->middleware('roles:Admin');
Route::POST('/model/change_status', 'CarModelController@change_model_status')->middleware('roles:Admin');

//CarType  Management
Route::get('/type', 'CarTypeController@index')->middleware('roles:Admin');
Route::get('/type/add', 'CarTypeController@create')->middleware('roles:Admin');
Route::POST('/type/add', 'CarTypeController@store')->middleware('roles:Admin');
Route::get('/type/{id}/edit', 'CarTypeController@edit')->middleware('roles:Admin');
Route::POST('/type/{id}/edit', 'CarTypeController@update')->middleware('roles:Admin');
Route::POST('/ajax_deactive_type', 'CarTypeController@ajax_detivate_type')->middleware('roles:Admin');
Route::POST('/ajax_active_type', 'CarTypeController@ajax_activate_type')->middleware('roles:Admin');
Route::POST('/getmodel_list', 'CarTypeController@getmodel_list')->middleware('roles:Admin');
Route::POST('/type/change_status', 'CarTypeController@change_type_status')->middleware('roles:Admin');

Route::get('/type/{id}/view', 'CarTypeController@view')->middleware('roles:Admin');

// Route to Driver Controller
Route::get('review/{id}', 'TaxiDriverController@review')->middleware('roles:Admin'); 
Route::get('view_driver/{id}', 'TaxiDriverController@ViewDriver')->middleware('roles:Admin'); 
Route::get('edit_driver/{id}', 'TaxiDriverController@EditDriver')->middleware('roles:Admin');
Route::get('add_driver', 'TaxiDriverController@AddDriver')->middleware('roles:Admin');
Route::get('manage_driver', 'TaxiDriverController@ManageDriver')->middleware('roles:Admin');
Route::GET('/edit_driver', 'TaxiDriverController@Update');
Route::POST('edit_driver/{id}', 'TaxiDriverController@Update1');
Route::POST('/add_driver', 'TaxiDriverController@InsertDriver');
Route::POST('/BlockDriver', 'TaxiDriverController@BlockDriver')->middleware('roles:Admin');
Route::POST('/ActivateDriver', 'TaxiDriverController@ActivateDriver')->middleware('roles:Admin');
Route::POST('/AssignedBlockDriver', 'TaxiDriverController@AssignedBlockDriver')->middleware('roles:Admin');
Route::POST('/DeleteDriver', 'TaxiDriverController@DeleteDriver')->middleware('roles:Admin');
Route::POST('/checkuser', 'TaxiDriverController@checkuser');
Route::POST('/checkattacheduser', 'AttachedDriversController@checkattacheduser');


//Assign Taxi
Route::get('assign_taxi', 'TaxiDriverController@AssignTaxi')->middleware('roles:Admin');
Route::POST('assign_taxi', 'TaxiDriverController@PostAssignTaxi')->middleware('roles:Admin');
Route::get('manage_assign_taxi', 'TaxiDriverController@ManageAssignTaxi')->middleware('roles:Admin');
Route::get('assign_taxi/{id}/edit', 'TaxiDriverController@edit_assign_taxi')->middleware('roles:Admin');
Route::POST('assign_taxi/{id}/edit', 'TaxiDriverController@update_assign_taxi')->middleware('roles:Admin');

Route::GET('/assign_taxi/getvehicle_driver_list/{id?}', 'TaxiDriverController@getvehicle_driver_list')->middleware('roles:Admin');

// Route to Fare Controller
Route::get('/add_fare', 'FareController@createFare')->middleware('roles:Admin');
Route::POST('/add_fare', 'FareController@storeFare')->middleware('roles:Admin');
Route::get('manage_fare', 'FareController@manageFare')->middleware('roles:Admin');
Route::POST('manage_fare', 'FareController@manageFare')->middleware('roles:Admin');
Route::get('/edit_fare/{id}/edit', 'FareController@edite_fare')->middleware('roles:Admin');
Route::POST('/edit_fare/{id}/edit', 'FareController@update_fare')->middleware('roles:Admin');
Route::get('/view_fare/{id}', 'FareController@viewfare')->middleware('roles:Admin');
Route::POST('/ajax_deactive_fare', 'FareController@ajax_deactive_fare')->middleware('roles:Admin');
Route::POST('/ajax_active_fare', 'FareController@ajax_activate_fare')->middleware('roles:Admin');
Route::POST('/fare/change_bulk_status', 'FareController@change_bulk_status')->middleware('roles:Admin');
Route::GET('/fare/gettype_basedonfare/{id?}', 'FareController@gettype_basedonfare')->middleware('roles:Admin');

Route::POST('getmanagefare','FareController@getmanagefare')->middleware('roles:Admin');


Route::get('/add-tax', 'FareController@createTax')->middleware('roles:Admin');
Route::POST('/add-tax', 'FareController@storeTax')->middleware('roles:Admin');
Route::get('/manage-tax', 'FareController@manageTax')->middleware('roles:Admin');
Route::get('/edit-tax/{id}/edit', 'FareController@editeTax')->middleware('roles:Admin');
Route::POST('/edit-tax/{id}/edit', 'FareController@updateTax')->middleware('roles:Admin');
Route::POST('/ajax_deactive_tax', 'FareController@ajax_deactive_tax')->middleware('roles:Admin');
Route::POST('/ajax_activate_tax', 'FareController@ajax_activate_tax')->middleware('roles:Admin');
Route::POST('/fare/change_tax_status', 'FareController@change_tax_status')->middleware('roles:Admin');
//Route::POST('/add_fare', 'FareController@storeFare')->middleware('roles:Admin');


// Route to Dispatch Controller
Route::get('add_dispatcher', 'DispatchController@AddDispatcher')->middleware('roles:Admin');
Route::get('manage_dispatcher', 'DispatchController@ManageDispatcher')->middleware('roles:Admin');

// Route to Customer Controller
Route::get('manage_customers', 'CustomerController@ManageCustomer')->middleware('roles:Admin');
Route::POST('/blockcustomer', 'CustomerController@blockcustomer');
Route::POST('/bulkblockcustomer', 'CustomerController@bulkblockcustomer');
Route::POST('/activatecustomer', 'CustomerController@activatecustomer');
Route::POST('/bulkactivatecustomer', 'CustomerController@bulkactivatecustomer');



// Route to Report Controller
Route::get('total_trans1', 'ReportController@TotalTrans')->middleware('roles:Admin');
Route::get('successful_rides1', 'ReportController@SuccessfulRides')->middleware('roles:Admin');
Route::get('cancel_rides1', 'ReportController@CancelRides')->middleware('roles:Admin');
Route::get('reject_rides1', 'ReportController@RejectRides')->middleware('roles:Admin');
Route::get('drivers_share1', 'ReportController@DriverShare')->middleware('roles:Admin');


//new enhanced report
Route::get('reject_rides', 'ReportController@rejected_ride')->middleware('roles:Admin|Franchise');
Route::get('cancel_rides', 'ReportController@cancel_rides')->middleware('roles:Admin|Franchise');
Route::get('success_rides', 'ReportController@success_rides')->middleware('roles:Admin|Franchise');
Route::get('total_rides', 'ReportController@total_rides')->middleware('roles:Admin|Franchise');
Route::get('drivers_share', 'ReportController@drivers_share')->middleware('roles:Admin|Franchise');

Route::get('getvehicledriver/{id}', 'ReportController@getvehicledriver');
// Route to Attached Driver

Route::get('addattacheddriver', function () {
    return view('test');
});
Route::get('edit_attached_driver/{id}', 'AttachedDriversController@EditAttachedDrivers');
Route::get('changestatus', 'AttachedDriversController@changestatus');
Route::get('view_attached_driver/{id}', 'AttachedDriversController@ViewAttachedDrivers');
Route::POST('edit_attached_driver', 'AttachedDriversController@UpdateAttachedDrivers');

// Route to Attached Drivers Controller
Route::get('/add_attached_drivers', 'AttachedDriversController@AddAttachedDrivers')->middleware('roles:Admin');
Route::get('/manage_attached_drivers', 'AttachedDriversController@ManageAttachedDrivers')->middleware('roles:Admin|Franchise');
Route::POST('/add_attached_driver', 'AttachedDriversController@InsertAttachedDrivers');
Route::POST('/UpdateAttachedDrivers', 'AttachedDriversController@UpdateAttachedDrivers');

Route::POST('/BlockAttached', 'AttachedDriversController@BlockAttached')->middleware('roles:Admin');
Route::POST('/ActivateAttached', 'AttachedDriversController@ActivateAttached')->middleware('roles:Admin');
Route::POST('/DeleteAttached', 'AttachedDriversController@DeleteDriver')->middleware('roles:Admin');

Route::get('getmodel/{id}', 'CarController@getmodel');
Route::get('getcartype/{id}', 'CarController@getcartype');

//setting

Route::GET('setting','SettingController@index');
Route::POST('setting','SettingController@update_status');

//offers
// Route to offers Controller
Route::POST('deleteoffer', 'OfferController@deleteoffer')->middleware('roles:Admin');
Route::POST('activateoffer', 'OfferController@activateoffer')->middleware('roles:Admin');
Route::POST('expireoffer', 'OfferController@expireoffer')->middleware('roles:Admin');
Route::POST('getvehicletype', 'OfferController@getvehicletype')->middleware('roles:Admin');
Route::POST('add_offers', 'OfferController@insert')->middleware('roles:Admin');
Route::get('add_offers', 'OfferController@create')->middleware('roles:Admin');
Route::get('manage_offers', 'OfferController@index')->middleware('roles:Admin');
Route::get('manage_offers', 'OfferController@index')->middleware('roles:Admin');
Route::get('view_offers/{id}', 'OfferController@view_offers')->middleware('roles:Admin');

// Franchise Routes
Route::POST('/activatefranchise','FranchiseController@activatefranchise')->middleware('roles:Admin');
Route::POST('/blockfranchise','FranchiseController@blockfranchise')->middleware('roles:Admin');
Route::POST('/deletefranchise','FranchiseController@deletefranchise')->middleware('roles:Admin');

Route::POST('/add_franchise','FranchiseController@insert')->middleware('roles:Admin');
Route::get('/add_franchise','FranchiseController@add')->middleware('roles:Admin');
Route::get('/manage_franchise','FranchiseController@manage')->middleware('roles:Admin');
Route::get('/edit_franchise/{id}','FranchiseController@edit')->middleware('roles:Admin');
Route::POST('/edit_franchise/{id}','FranchiseController@update')->middleware('roles:Admin');
Route::get('/view_franchise/{id}','FranchiseController@view')->middleware('roles:Admin');

//RATING 

Route::POST('/rating/add','RatingsController@store')->middleware('roles:Admin');
Route::get('/rating/add','RatingsController@create')->middleware('roles:Admin');
Route::get('/manage_rating','RatingsController@manage')->middleware('roles:Admin');
Route::get('/rating/{id}/edit','RatingsController@edit')->middleware('roles:Admin');
Route::POST('/rating/{id}/edit','RatingsController@update')->middleware('roles:Admin');
Route::POST('/rating/delete','RatingsController@delete')->middleware('roles:Admin');

//Tax
Route::POST('deletetax','FareController@deletetax');

Route::POST('review/{id}','TaxiDriverController@filterreview');
Route::POST('deletefare','FareController@deletefare');
