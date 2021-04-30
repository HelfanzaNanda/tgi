<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
	Route::get('/login', 'Auth\LoginController@index');
	Route::post('/login', 'Auth\LoginController@authorizes');
	Route::get('/logout', 'Auth\LoginController@logout');

	Route::get('/city_by_province/{province_id}', ['as' => 'show.city', 'uses' => 'Ref\CityController@cityByProvince']);
	
	Route::group(['middleware' => 'auth.primary'], function () {
		Route::group(['namespace' => 'Dashboard'], function () {
			Route::get('/', 'DashboardController@index');
			Route::get('/home', 'DashboardController@index');
		});

		Route::group(['namespace' => 'Inventory'], function () {
			Route::get('categories', ['as' => 'get.category', 'uses' => 'CategoryController@index']);
			Route::get('units', ['as' => 'get.unit', 'uses' => 'UnitController@index']);
			Route::get('inventories', ['as' => 'get.inventory', 'uses' => 'InventoryController@index']);
			Route::get('inventories/{id}/galleries', ['as' => 'get.inventory.gallery', 'uses' => 'InventoryController@gallery']);
			Route::post('inventories/{id}/galleries', ['as' => 'post.inventory.gallery', 'uses' => 'InventoryController@postGallery']);
			Route::delete('inventories/{itemId}/galleries/{id}', ['as' => 'delete.inventory.gallery', 'uses' => 'InventoryController@deleteGallery']);
			Route::post('inventories/qrcode_pdf', ['as' => 'post.inventory.qrcode_pdf', 'uses' => 'InventoryController@printQrcode']);
			Route::get('inventory_groups', ['as' => 'get.inventory_group', 'uses' => 'InventoryGroupController@index']);
			Route::get('variants', ['as' => 'get.variant', 'uses' => 'VariantController@index']);
			Route::get('stock_opnames', ['as' => 'get.stock_opname', 'uses' => 'StockOpnameController@index']);
		});

		Route::group(['namespace' => 'Master'], function () {
			Route::get('suppliers', ['as' => 'get.supplier', 'uses' => 'SupplierController@index']);
			Route::get('users', ['as' => 'get.user', 'uses' => 'UserController@index']);
			Route::get('inspections', ['as' => 'get.inspection', 'uses' => 'InspectionController@index']);
		});

		Route::group(['namespace' => 'Report'], function () {
			Route::get('report/inventories', ['as' => 'get.report.inventory', 'uses' => 'InventoryController@index']);
			Route::get('report/incoming_inventories', ['as' => 'get.report.incoming.inventory', 'uses' => 'InventoryInController@index']);
			Route::get('report/outcoming_inventories', ['as' => 'get.report.outcoming.inventory', 'uses' => 'InventoryOutController@index']);
			Route::get('report/inventory_locations', ['as' => 'get.report.inventory.location', 'uses' => 'InventoryLocationController@index']);
		});

		Route::group(['namespace' => 'Storage'], function () {
			Route::get('warehouses', ['as' => 'get.warehouse', 'uses' => 'WarehouseController@index']);
			Route::post('warehouses/qrcode_pdf', ['as' => 'post.warehouse.qrcode_pdf', 'uses' => 'WarehouseController@printQrcode']);
			Route::get('racks', ['as' => 'get.rack', 'uses' => 'RackController@index']);
			Route::post('racks/qrcode_pdf', ['as' => 'post.rack.qrcode_pdf', 'uses' => 'RackController@printQrcode']);
		});
		
		Route::group(['namespace' => 'Transaction'], function () {
			Route::get('incoming_inventories', ['as' => 'get.incoming.inventory', 'uses' => 'InventoryInController@index']);
			Route::get('outcoming_inventories', ['as' => 'get.outcoming.inventory', 'uses' => 'InventoryOutController@index']);
			Route::get('request_inventories', ['as' => 'get.request.inventory', 'uses' => 'RequestInventoryController@index']);
			Route::get('transactions', ['as' => 'get.transaction', 'uses' => 'TransactionController@index']);
			Route::get('transaction_details', ['as' => 'get.transaction.detail', 'uses' => 'TransactionDetailController@index']);
		});

		Route::get('roles', 'Role\RoleController@index');

		Route::group(['namespace' => 'Permission'], function () {
			Route::get('permissions', ['as' => 'get.permission', 'uses' => 'PermissionController@index']);
			Route::post('permissions', ['as' => 'post.permission', 'uses' => 'PermissionController@update']);
		});
	});
});