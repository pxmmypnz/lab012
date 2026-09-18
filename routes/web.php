<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/products');

Route::controller(ProductController::class)
    ->prefix('/products')
    ->name('products.')
    ->group(static function (Router $router): void {
        $groupStack = $router->getGroupStack();
        $prefixName = $groupStack[array_key_last($groupStack)]['as'];

        Route::any('', static fn() => redirect()->route("{$prefixName}list"))
            ->name('index');
        Route::get('', 'list')->name('list');
        Route::post('', 'create')->name('create');
        Route::get('/create', 'showCreateForm')->name('create-form');

        Route::prefix('/{product}')
            ->group(static function (): void {
                Route::get('', 'view')->name('view');
                Route::prefix('/shops')->group(static function (): void {
                    Route::get('', 'viewShops')->name('view-shops');
                    Route::post('', 'addShop')->name('add-shop');
                    Route::get('/add', 'showAddShopsForm')->name('add-shops-form');
                    Route::post('/remove', 'removeShop')->name('remove-shop');
                });
                Route::post('', 'update')->name('update');
                Route::get('/update', 'showUpdateForm')->name('update-form');
                Route::post('/delete', 'delete')->name('delete');
            });
    });

Route::controller(ShopController::class)
    ->prefix('/shops')
    ->name('shops.')
    ->group(static function (): void {
        Route::get('', 'list')->name('list');
        Route::post('', 'create')->name('create');
        Route::get('/create', 'showCreateForm')->name('create-form');

        Route::prefix('/{shop}')
            ->group(static function (): void {
                Route::get('', 'view')->name('view');
                Route::prefix('/products')->group(static function (): void {
                    Route::get('', 'viewProducts')->name('view-products');
                    Route::post('', 'addProduct')->name('add-product');
                    Route::get('/add', 'showAddProductsForm')->name('add-products-form');
                    Route::post('/remove', 'removeProduct')->name('remove-product');
                });
                Route::post('', 'update')->name('update');
                Route::get('/update', 'showUpdateForm')
                    ->name('update-form');
                Route::post('/delete', 'delete')
                    ->name('delete');
            });
    });

Route::controller(CategoryController::class)
    ->prefix('/categories')
    ->name('categories.')
    ->group(static function (): void {
        Route::get('', 'list')->name('list');
        Route::post('', 'create')->name('create');
        Route::get('/create', 'showCreateForm')->name('create-form');

        Route::get('/{category}', 'view')->name('view');
        Route::prefix('/{category}/products')->group(static function (): void {
            Route::get('', 'viewProducts')->name('view-products');
            Route::post('', 'addProduct')->name('add-product');
            Route::get('/add', 'showAddProductsForm')->name('add-products-form');
        });
        Route::post('/{category}', 'update')->name('update');
        Route::get('/{category}/update', 'showUpdateForm')->name('update-form');
        Route::post('/{category}/delete', 'delete')->name('delete');
    });
