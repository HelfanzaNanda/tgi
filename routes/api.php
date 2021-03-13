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
			Route::group(['namespace' => 'Master'], function () {
				Route::get('warehouses/{id?}', ['as' => 'get.warehouse', 'uses' => 'WarehouseController@get']);
				Route::post('warehouses', ['as' => 'post.warehouse', 'uses' => 'WarehouseController@post']);
				Route::patch('warehouses/{id}', ['as' => 'patch.warehouse', 'uses' => 'WarehouseController@patch']);
				Route::put('warehouses/{id}', ['as' => 'put.warehouse', 'uses' => 'WarehouseController@put']);
				Route::delete('warehouses/{id}', ['as' => 'delete.warehouse', 'uses' => 'WarehouseController@delete']);
				Route::post('warehouse_datatables', ['as' => 'datatable.warehouse', 'uses' => 'WarehouseController@datatables']);

				Route::get('racks/{id?}', ['as' => 'get.rack', 'uses' => 'RackController@get']);
				Route::post('racks', ['as' => 'post.rack', 'uses' => 'RackController@post']);
				Route::patch('racks/{id}', ['as' => 'patch.rack', 'uses' => 'RackController@patch']);
				Route::put('racks/{id}', ['as' => 'put.rack', 'uses' => 'RackController@put']);
				Route::delete('racks/{id}', ['as' => 'delete.rack', 'uses' => 'RackController@delete']);
				Route::post('rack_datatables', ['as' => 'datatable.rack', 'uses' => 'RackController@datatables']);

				Route::get('suppliers/{id?}', ['as' => 'get.supplier', 'uses' => 'SupplierController@get']);
				Route::post('suppliers', ['as' => 'post.supplier', 'uses' => 'SupplierController@post']);
				Route::patch('suppliers/{id}', ['as' => 'patch.supplier', 'uses' => 'SupplierController@patch']);
				Route::put('suppliers/{id}', ['as' => 'put.supplier', 'uses' => 'SupplierController@put']);
				Route::delete('suppliers/{id}', ['as' => 'delete.supplier', 'uses' => 'SupplierController@delete']);
				Route::post('supplier_datatables', ['as' => 'datatable.supplier', 'uses' => 'SupplierController@datatables']);
			});

			Route::group(['namespace' => 'Inventory'], function () {
				Route::get('inventories/{id?}', ['as' => 'get.inventory', 'uses' => 'InventoryController@get']);
				Route::post('inventories', ['as' => 'post.inventory', 'uses' => 'InventoryController@post']);
				Route::patch('inventories/{id}', ['as' => 'patch.inventory', 'uses' => 'InventoryController@patch']);
				Route::put('inventories/{id}', ['as' => 'put.inventory', 'uses' => 'InventoryController@put']);
				Route::delete('inventories/{id}', ['as' => 'delete.inventory', 'uses' => 'InventoryController@delete']);
				Route::post('inventory_datatables', ['as' => 'datatable.inventory', 'uses' => 'InventoryController@datatables']);

				Route::get('inventory_locations/{id?}', ['as' => 'show.inventory.location', 'uses' => 'InventoryLocationController@get']);
				Route::post('inventory_locations', ['as' => 'show.inventory.location', 'uses' => 'InventoryLocationController@post']);
				Route::patch('inventory_locations/{id}', ['as' => 'show.inventory.location', 'uses' => 'InventoryLocationController@patch']);
				Route::put('inventory_locations/{id}', ['as' => 'show.inventory.location', 'uses' => 'InventoryLocationController@put']);
				Route::delete('inventory_locations/{id}', ['as' => 'show.inventory.location', 'uses' => 'InventoryLocationController@delete']);
				Route::post('inventory_location_datatables', ['as' => 'datatable.inventory.location', 'uses' => 'InventoryLocationController@datatables']);
			});

			Route::group(['namespace' => 'Transaction'], function () {
				Route::get('incoming_inventories/{id?}', ['as' => 'get.incoming.inventory', 'uses' => 'IncomingInventoryController@get']);
				Route::post('incoming_inventories', ['as' => 'post.incoming.inventory', 'uses' => 'IncomingInventoryController@post']);
				Route::patch('incoming_inventories/{id}', ['as' => 'patch.incoming.inventory', 'uses' => 'IncomingInventoryController@patch']);
				Route::put('incoming_inventories/{id}', ['as' => 'put.incoming.inventory', 'uses' => 'IncomingInventoryController@put']);
				Route::delete('incoming_inventories/{id}', ['as' => 'delete.incoming.inventory', 'uses' => 'IncomingInventoryController@delete']);
				Route::post('incoming_inventory_datatables', ['as' => 'datatable.incoming.inventory', 'uses' => 'IncomingInventoryController@datatables']);

				Route::get('outcoming_inventories/{id?}', ['as' => 'get.outcoming.inventory', 'uses' => 'OutcomingInventoryController@get']);
				Route::post('outcoming_inventories', ['as' => 'post.outcoming.inventory', 'uses' => 'OutcomingInventoryController@post']);
				Route::patch('outcoming_inventories/{id}', ['as' => 'patch.outcoming.inventory', 'uses' => 'OutcomingInventoryController@patch']);
				Route::put('outcoming_inventories/{id}', ['as' => 'put.outcoming.inventory', 'uses' => 'OutcomingInventoryController@put']);
				Route::delete('outcoming_inventories/{id}', ['as' => 'delete.outcoming.inventory', 'uses' => 'OutcomingInventoryController@delete']);
				Route::post('outcoming_inventory_datatables', ['as' => 'datatable.outcoming.inventory', 'uses' => 'OutcomingInventoryController@datatables']);

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
			});
		});
	});
});