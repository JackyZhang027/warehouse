<?php

use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\ItemArrivalController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemOutController;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UOMController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseItemController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PagePasswordController;
use App\Models\ItemArrival;
use Illuminate\Support\Facades\Route;


Auth::routes();



Route::group(['middleware' => ['auth']], function() {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::get('/warehouse/search-items', [WarehouseItemController::class, 'searchItems'])->name('warehouse.items.search');
    Route::resource('warehouse', WarehouseController::class);
    Route::resource('uoms', UOMController::class);
    Route::resource('categories', ItemCategoryController::class);

    
    Route::get('/items/search', [ItemController::class, 'searchItems'])->name('items.search');
    Route::resource('items', ItemController::class);

    Route::resource('material', MaterialRequestController::class);
    Route::get('/delivery/list', [DeliveryOrderController::class, 'list'])->name('delivery.list');
    Route::get('/delivery/{id}/warehouse', [DeliveryOrderController::class, 'deliveryByWarehouse'])->name('delivery.warehouse');
    Route::resource('delivery', DeliveryOrderController::class);
    
    
    Route::get('/arrival/top10', [ItemArrivalController::class, 'top10Arival'])->name('arrival.top10');
    Route::resource('arrival', ItemArrivalController::class);

    
    Route::get('/out/top10', [ItemOutController::class, 'top10Out'])->name('out.top10');
    Route::resource('out', ItemOutController::class);

    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::post('/report/generate', [ReportController::class, 'generate'])->name('report.generate');
    Route::get('/history', [ItemOutController::class, 'history'])->name('out.history');


    Route::get('/material/export/{id}/{type}', [MaterialRequestController::class, 'export'])->name('material.export');
    Route::get('/material/warehouse/list', [MaterialRequestController::class, 'listByWarehouse'])->name('material.warehouse.list');
    Route::get('/material/item/list', [MaterialRequestController::class, 'itemListByMaterialRequest'])->name('material.item.list');
    Route::post('/delivery/{id}/items/store', [DeliveryOrderController::class, 'storeItems'])->name('delivery.items.store');
    Route::delete('/delivery/{id}/item/destroy', [DeliveryOrderController::class, 'destroyItems'])->name('delivery.item.destroy');
    Route::post('/out/{id}/items/store', [ItemOutController::class, 'storeItems'])->name('out.items.store');
    Route::delete('/out/{id}/item/destroy', [ItemOutController::class, 'destroyItems'])->name('out.item.destroy');
    Route::get('/delivery/export/{id}/{type}', [DeliveryOrderController::class, 'export'])->name('delivery.export');
    Route::post('/get-delivery-items', [DeliveryOrderController::class, 'getDeliveryItems'])->name('getDeliveryItems');
    


    Route::post('/arrival/search', [ItemArrivalController::class, 'searchData']);
    Route::post('/out/search', [ItemOutController::class, 'searchData']);



    Route::resource('sites', SiteController::class);
    Route::resource('announcement', AnnouncementController::class);
    Route::resource('permissions', PermissionController::class)->middleware('password.required');


    Route::get('/password-input', [PagePasswordController::class, 'show'])->name('password.input');
    Route::post('/password-validate', [PagePasswordController::class, 'validatePassword'])->name('password.validate');



});
