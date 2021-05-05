<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'App\Http\Controllers'], function () {
	Route::group(['namespace' => 'API'], function() {
		Route::group(['namespace' => 'Auth'], function () {
		    Route::post('login', 'AuthController@login');
		    Route::group(['middleware' => 'auth:api'], function () {
		        Route::get('me', 'AuthController@user'); 
		        Route::post('setting', 'AuthController@setting');
		        Route::get('logout', 'AuthController@logout');
		    }); 
		});

		Route::group(['middleware' => 'auth:api'], function () {
			Route::group(['namespace' => 'Ref'], function () {
				Route::get('countries/{id?}', ['as' => 'get.country', 'uses' => 'CountryController@get']);
			});

			Route::group(['namespace' => 'Master'], function () {
				Route::get('users/{id?}', ['as' => 'get.user', 'uses' => 'UserController@get']);
				Route::post('users', ['as' => 'post.user', 'uses' => 'UserController@post']);
				Route::patch('users/{id}', ['as' => 'patch.user', 'uses' => 'UserController@patch']);
				Route::put('users/{id}', ['as' => 'put.user', 'uses' => 'UserController@put']);
				Route::delete('users/{id}', ['as' => 'delete.user', 'uses' => 'UserController@delete']);
				Route::post('user_datatables', ['as' => 'datatable.user', 'uses' => 'UserController@datatables']);

				Route::get('warehouses/{id?}', ['as' => 'get.warehouse', 'uses' => 'WarehouseController@get']);
				Route::post('warehouses', ['as' => 'post.warehouse', 'uses' => 'WarehouseController@post']);
				Route::patch('warehouses/{id}', ['as' => 'patch.warehouse', 'uses' => 'WarehouseController@patch']);
				Route::put('warehouses/{id}', ['as' => 'put.warehouse', 'uses' => 'WarehouseController@put']);
				Route::delete('warehouses/{id}', ['as' => 'delete.warehouse', 'uses' => 'WarehouseController@delete']);
				Route::post('warehouse_datatables', ['as' => 'datatable.warehouse', 'uses' => 'WarehouseController@datatables']);
				Route::get('warehouse_numbers', ['as' => 'number.warehouse', 'uses' => 'WarehouseController@countTotalRecords']);

				Route::get('racks/{id?}', ['as' => 'get.rack', 'uses' => 'RackController@get']);
				Route::post('racks', ['as' => 'post.rack', 'uses' => 'RackController@post']);
				Route::patch('racks/{id}', ['as' => 'patch.rack', 'uses' => 'RackController@patch']);
				Route::put('racks/{id}', ['as' => 'put.rack', 'uses' => 'RackController@put']);
				Route::delete('racks/{id}', ['as' => 'delete.rack', 'uses' => 'RackController@delete']);
				Route::post('rack_datatables', ['as' => 'datatable.rack', 'uses' => 'RackController@datatables']);
				Route::get('rack_numbers', ['as' => 'number.rack', 'uses' => 'RackController@countTotalRecords']);

				Route::get('suppliers/{id?}', ['as' => 'get.supplier', 'uses' => 'SupplierController@get']);
				Route::post('suppliers', ['as' => 'post.supplier', 'uses' => 'SupplierController@post']);
				Route::patch('suppliers/{id}', ['as' => 'patch.supplier', 'uses' => 'SupplierController@patch']);
				Route::put('suppliers/{id}', ['as' => 'put.supplier', 'uses' => 'SupplierController@put']);
				Route::delete('suppliers/{id}', ['as' => 'delete.supplier', 'uses' => 'SupplierController@delete']);
				Route::post('supplier_datatables', ['as' => 'datatable.supplier', 'uses' => 'SupplierController@datatables']);

				Route::get('customers/{id?}', ['as' => 'get.customer', 'uses' => 'CustomerController@get']);
				Route::post('customers', ['as' => 'post.customer', 'uses' => 'CustomerController@post']);
				Route::patch('customers/{id}', ['as' => 'patch.customer', 'uses' => 'CustomerController@patch']);
				Route::put('customers/{id}', ['as' => 'put.customer', 'uses' => 'CustomerController@put']);
				Route::delete('customers/{id}', ['as' => 'delete.customer', 'uses' => 'CustomerController@delete']);
				Route::post('customer_datatables', ['as' => 'datatable.customer', 'uses' => 'CustomerController@datatables']);

				Route::get('media/{id?}', ['as' => 'get.media', 'uses' => 'MediaController@get']);
				Route::post('media', ['as' => 'post.media', 'uses' => 'MediaController@post']);
				Route::patch('media/{id}', ['as' => 'patch.media', 'uses' => 'MediaController@patch']);
				Route::put('media/{id}', ['as' => 'put.media', 'uses' => 'MediaController@put']);
				Route::delete('media/{id}', ['as' => 'delete.media', 'uses' => 'MediaController@delete']);
				Route::post('media_datatables', ['as' => 'datatable.media', 'uses' => 'MediaController@datatables']);

				Route::get('inspection_questions/{id?}', ['as' => 'get.inspection_question', 'uses' => 'InspectionQuestionController@get']);
				Route::post('inspection_questions', ['as' => 'post.inspection_question', 'uses' => 'InspectionQuestionController@updateOrCreate']);
				Route::delete('inspection_questions/{id}', ['as' => 'delete.inspection_question', 'uses' => 'InspectionQuestionController@delete']);
				Route::post('inspection_question_datatables', ['as' => 'datatable.inspection_question', 'uses' => 'InspectionQuestionController@datatables']);

				Route::post('role_datatables', ['as' => 'datatable.role', 'uses' => 'RoleController@datatables']);
				Route::get('role/{id?}', ['as' => 'get.role', 'uses' => 'RoleController@get']);
				Route::post('role', ['as' => 'post.role', 'uses' => 'RoleController@updateOrCreate']);
				Route::delete('role/{id}', ['as' => 'delete.role', 'uses' => 'RoleController@delete']);
			});

			Route::group(['namespace' => 'Inventory'], function () {
				Route::get('inventories/{id?}', ['as' => 'get.inventory', 'uses' => 'InventoryController@get']);
				Route::post('inventories', ['as' => 'post.inventory', 'uses' => 'InventoryController@post']);
				Route::patch('inventories/{id}', ['as' => 'patch.inventory', 'uses' => 'InventoryController@patch']);
				Route::put('inventories/{id}', ['as' => 'put.inventory', 'uses' => 'InventoryController@put']);
				Route::delete('inventories/{id}', ['as' => 'delete.inventory', 'uses' => 'InventoryController@delete']);
				Route::post('inventory_datatables', ['as' => 'datatable.inventory', 'uses' => 'InventoryController@datatables']);
				Route::get('inventory_numbers', ['as' => 'number.inventory', 'uses' => 'InventoryController@countTotalRecords']);

				Route::get('inventory_locations/{id?}', ['as' => 'show.inventory.location', 'uses' => 'InventoryLocationController@get']);
				Route::post('inventory_locations', ['as' => 'show.inventory.location', 'uses' => 'InventoryLocationController@post']);
				Route::patch('inventory_locations/{id}', ['as' => 'show.inventory.location', 'uses' => 'InventoryLocationController@patch']);
				Route::put('inventory_locations/{id}', ['as' => 'show.inventory.location', 'uses' => 'InventoryLocationController@put']);
				Route::delete('inventory_locations/{id}', ['as' => 'show.inventory.location', 'uses' => 'InventoryLocationController@delete']);
				Route::post('inventory_location_datatables', ['as' => 'datatable.inventory.location', 'uses' => 'InventoryLocationController@datatables']);

				Route::get('categories/{id?}', ['as' => 'get.category', 'uses' => 'CategoryController@get']);
				Route::post('categories', ['as' => 'post.category', 'uses' => 'CategoryController@post']);
				Route::patch('categories/{id}', ['as' => 'patch.category', 'uses' => 'CategoryController@patch']);
				Route::put('categories/{id}', ['as' => 'put.category', 'uses' => 'CategoryController@put']);
				Route::delete('categories/{id}', ['as' => 'delete.category', 'uses' => 'CategoryController@delete']);
				Route::post('category_datatables', ['as' => 'datatable.category', 'uses' => 'CategoryController@datatables']);

				Route::get('units/{id?}', ['as' => 'get.unit', 'uses' => 'UnitController@get']);
				Route::post('units', ['as' => 'post.unit', 'uses' => 'UnitController@post']);
				Route::patch('units/{id}', ['as' => 'patch.unit', 'uses' => 'UnitController@patch']);
				Route::put('units/{id}', ['as' => 'put.unit', 'uses' => 'UnitController@put']);
				Route::delete('units/{id}', ['as' => 'delete.unit', 'uses' => 'UnitController@delete']);
				Route::post('unit_datatables', ['as' => 'datatable.unit', 'uses' => 'UnitController@datatables']);

				Route::get('inventory_groups/{id?}', ['as' => 'get.inventory_group', 'uses' => 'InventoryGroupController@get']);
				Route::post('inventory_groups', ['as' => 'post.inventory_group', 'uses' => 'InventoryGroupController@post']);
				Route::patch('inventory_groups/{id}', ['as' => 'patch.inventory_group', 'uses' => 'InventoryGroupController@patch']);
				Route::put('inventory_groups/{id}', ['as' => 'put.inventory_group', 'uses' => 'InventoryGroupController@put']);
				Route::delete('inventory_groups/{id}', ['as' => 'delete.inventory_group', 'uses' => 'InventoryGroupController@delete']);
				Route::post('inventory_group_datatables', ['as' => 'datatable.inventory_group', 'uses' => 'InventoryGroupController@datatables']);

				Route::get('variants/{id?}', ['as' => 'get.variant', 'uses' => 'VariantController@get']);
				Route::post('variants', ['as' => 'post.variant', 'uses' => 'VariantController@post']);
				Route::patch('variants/{id}', ['as' => 'patch.variant', 'uses' => 'VariantController@patch']);
				Route::put('variants/{id}', ['as' => 'put.variant', 'uses' => 'VariantController@put']);
				Route::delete('variants/{id}', ['as' => 'delete.variant', 'uses' => 'VariantController@delete']);
				Route::post('variant_datatables', ['as' => 'datatable.variant', 'uses' => 'VariantController@datatables']);

				Route::get('stock_opnames/{id?}', ['as' => 'get.stock_opname', 'uses' => 'StockOpnameController@get']);
				Route::post('stock_opnames', ['as' => 'post.stock_opname', 'uses' => 'StockOpnameController@post']);
				Route::patch('stock_opnames/{id}', ['as' => 'patch.stock_opname', 'uses' => 'StockOpnameController@patch']);
				Route::put('stock_opnames/{id}', ['as' => 'put.stock_opname', 'uses' => 'StockOpnameController@put']);
				Route::delete('stock_opnames/{id}', ['as' => 'delete.stock_opname', 'uses' => 'StockOpnameController@delete']);
				Route::post('stock_opname_datatables', ['as' => 'datatable.stock_opname', 'uses' => 'StockOpnameController@datatables']);
			});

			Route::group(['namespace' => 'Transaction'], function () {
				Route::get('incoming_inventories/{id?}', ['as' => 'get.incoming.inventory', 'uses' => 'IncomingInventoryController@get']);
				Route::post('incoming_inventories', ['as' => 'post.incoming.inventory', 'uses' => 'IncomingInventoryController@post']);
				Route::patch('incoming_inventories/{id}', ['as' => 'patch.incoming.inventory', 'uses' => 'IncomingInventoryController@patch']);
				Route::put('incoming_inventories/{id}', ['as' => 'put.incoming.inventory', 'uses' => 'IncomingInventoryController@put']);
				Route::delete('incoming_inventories/{id}', ['as' => 'delete.incoming.inventory', 'uses' => 'IncomingInventoryController@delete']);
				Route::post('incoming_inventory_datatables', ['as' => 'datatable.incoming.inventory', 'uses' => 'IncomingInventoryController@datatables']);
				Route::post('store_incoming_inventories', ['as' => 'store.incoming.inventory', 'uses' => 'IncomingInventoryController@formPost']);

				Route::get('outcoming_inventories/{id?}', ['as' => 'get.outcoming.inventory', 'uses' => 'OutcomingInventoryController@get']);
				Route::post('outcoming_inventories', ['as' => 'post.outcoming.inventory', 'uses' => 'OutcomingInventoryController@post']);
				Route::patch('outcoming_inventories/{id}', ['as' => 'patch.outcoming.inventory', 'uses' => 'OutcomingInventoryController@patch']);
				Route::put('outcoming_inventories/{id}', ['as' => 'put.outcoming.inventory', 'uses' => 'OutcomingInventoryController@put']);
				Route::delete('outcoming_inventories/{id}', ['as' => 'delete.outcoming.inventory', 'uses' => 'OutcomingInventoryController@delete']);
				Route::post('outcoming_inventory_datatables', ['as' => 'datatable.outcoming.inventory', 'uses' => 'OutcomingInventoryController@datatables']);
				Route::post('store_outcoming_inventories', ['as' => 'store.outcoming.inventory', 'uses' => 'OutcomingInventoryController@formPost']);

				Route::get('request_inventories/{id?}', ['as' => 'get.request.inventory', 'uses' => 'RequestInventoryController@get']);
				Route::post('request_inventories', ['as' => 'post.request.inventory', 'uses' => 'RequestInventoryController@post']);
				Route::patch('request_inventories/{id}', ['as' => 'patch.request.inventory', 'uses' => 'RequestInventoryController@patch']);
				Route::put('request_inventories/{id}', ['as' => 'put.request.inventory', 'uses' => 'RequestInventoryController@put']);
				Route::delete('request_inventories/{id}', ['as' => 'delete.request.inventory', 'uses' => 'RequestInventoryController@delete']);
				Route::post('request_inventory_datatables', ['as' => 'datatable.request.inventory', 'uses' => 'RequestInventoryController@datatables']);
				Route::post('store_request_inventories', ['as' => 'store.request.inventory', 'uses' => 'RequestInventoryController@formPost']);

				Route::get('transactions/{id?}', ['as' => 'get.transaction', 'uses' => 'TransactionController@get']);
				Route::post('transactions', ['as' => 'post.transaction', 'uses' => 'TransactionController@post']);
				Route::patch('transactions/{id}', ['as' => 'patch.transaction', 'uses' => 'TransactionController@patch']);
				Route::put('transactions/{id}', ['as' => 'put.transaction', 'uses' => 'TransactionController@put']);
				Route::delete('transactions/{id}', ['as' => 'delete.transaction', 'uses' => 'TransactionController@delete']);
				Route::post('transaction_datatables', ['as' => 'datatable.transaction', 'uses' => 'TransactionController@datatables']);

				Route::get('transaction_details/{id?}', ['as' => 'get.transaction.detail', 'uses' => 'TransactionDetailController@get']);
				Route::post('transaction_details', ['as' => 'post.transaction.detail', 'uses' => 'TransactionDetailController@post']);
				Route::patch('transaction_details/{id}', ['as' => 'patch.transaction.detail', 'uses' => 'TransactionDetailController@patch']);
				Route::put('transaction_details/{id}', ['as' => 'put.transaction.detail', 'uses' => 'TransactionDetailController@put']);
				Route::delete('transaction_details/{id}', ['as' => 'delete.transaction.detail', 'uses' => 'TransactionDetailController@delete']);
				Route::post('transaction_detail_datatables', ['as' => 'datatable.transaction.detail', 'uses' => 'TransactionDetailController@datatables']);

				Route::get('scheduled_arrivals/{id?}', ['as' => 'get.scheduled.arrival', 'uses' => 'ScheduledArrivalController@get']);
				Route::post('scheduled_arrivals', ['as' => 'post.scheduled.arrival', 'uses' => 'ScheduledArrivalController@post']);
				Route::patch('scheduled_arrivals/{id}', ['as' => 'patch.scheduled.arrival', 'uses' => 'ScheduledArrivalController@patch']);
				Route::put('scheduled_arrivals/{id}', ['as' => 'put.scheduled.arrival', 'uses' => 'ScheduledArrivalController@put']);
				Route::delete('scheduled_arrivals/{id}', ['as' => 'delete.scheduled.arrival', 'uses' => 'ScheduledArrivalController@delete']);
				Route::post('scheduled_arrival_datatables', ['as' => 'datatable.scheduled.arrival', 'uses' => 'ScheduledArrivalController@datatables']);
			});

			Route::group(['namespace' => 'Report'], function () {
				Route::post('report_scheduled_arrival_datatables', ['as' => 'datatable.report.scheduled.arrival', 'uses' => 'ScheduledArrivalController@datatables']);
			});
		});
	});
});