<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/products');

Route::middleware([
    'cache.headers:no_store;no_cache;must_revalidate;max_age=0',
])->group(static function (): void {
    Route::controller(LoginController::class)
        ->prefix('/auth')
        ->group(static function (): void {
            Route::get('/login', 'showForm')->name('login');
            Route::post('/login', 'authenticate')->name('authentication');
            Route::post('/logout', 'logout')->name('logout');
        });

    Route::middleware(['auth'])->group(static function (): void {
            /**
             * Register the default index route alias to the list endpoint.
             */
        $registerIndexRoute = static function (Router $router): void {
            $groupStack = $router->getGroupStack();
            $prefixName = $groupStack[array_key_last($groupStack)]['as'] ?? '';

            Route::any('', static fn() => redirect()->route("{$prefixName}list"))
                ->name('index');
        };

        // Products Routes
        Route::controller(ProductController::class)
            ->prefix('/products')
            ->name('products.')
            ->group(static function (Router $router) use ($registerIndexRoute): void {
                $registerIndexRoute($router);

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

        // Shops Routes
        Route::controller(ShopController::class)
            ->prefix('/shops')
            ->name('shops.')
            ->group(static function (Router $router) use ($registerIndexRoute): void {
                $registerIndexRoute($router);

                Route::get('', 'list')->middleware('can:list,App\Models\Shop')->name('list');
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
                        Route::get('/update', 'showUpdateForm')->name('update-form');
                        Route::post('/delete', 'delete')->name('delete');
                    });
            });

        // Categories Routes
        Route::controller(CategoryController::class)
            ->prefix('/categories')
            ->name('categories.')
            ->group(static function (Router $router) use ($registerIndexRoute): void {
                $registerIndexRoute($router);

                Route::get('', 'list')->middleware('can:list,App\Models\Category')->name('list');
                Route::post('', 'create')->name('create');
                Route::get('/create', 'showCreateForm')->name('create-form');

                Route::prefix('/{category}')
                    ->group(static function (): void {
                        Route::get('', 'view')->name('view');
                        Route::prefix('/products')->group(static function (): void {
                            Route::get('', 'viewProducts')->name('view-products');
                            Route::post('', 'addProduct')->name('add-product');
                            Route::get('/add', 'showAddProductsForm')->name('add-products-form');
                        });
                        Route::post('', 'update')->name('update');
                        Route::get('/update', 'showUpdateForm')->name('update-form');
                        Route::post('/delete', 'delete')->name('delete');
                    });
            });

        Route::prefix('/users')->name('users.')->group(static function () use ($registerIndexRoute): void {
            Route::controller(UserController::class)->prefix('/self')->name('selves.')->group(static function (): void {
                Route::get('', 'viewSelf')->name('view');
                Route::get('/update', 'showUpdateSelfForm')->name('update-form');
                Route::post('', 'updateSelf')->name('update');
            });

            Route::middleware('can:manage,App\Models\User')->group(static function () use ($registerIndexRoute): void {
                Route::controller(UserController::class)->group(static function (Router $router) use ($registerIndexRoute): void {
                    $registerIndexRoute($router);
                    Route::get('', 'list')->name('list');
                    Route::get('/create', 'showCreateForm')->name('create-form');
                    Route::post('', 'create')->name('create');
                    Route::get('/{user}/update', 'showUpdateForm')->name('update-form');
                    Route::post('/{user}', 'update')->name('update');
                    Route::post('/{user}/delete', 'delete')->name('delete');
                    Route::get('/{user}', 'view')->name('view');
                });
            });
        });
    });
});
