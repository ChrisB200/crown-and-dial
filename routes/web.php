<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSetupController;
use App\Http\Controllers\Admin\SecurityController as AdminSecurityController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Admin\SupplierController as AdminSupplierController;
use App\Http\Controllers\Admin\WatchController as AdminWatchController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WatchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name("home");


Route::get('/about', function () {
    return view('about');
})->name("about");

Route::middleware(['guest'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/setup', [AdminSetupController::class, 'create'])->name('setup');
    Route::post('/setup', [AdminSetupController::class, 'store'])->name('setup.store');
});


Route::resource("watches", WatchController::class);
Route::middleware([])
    ->prefix("watches")
    ->name("watches.")
    ->group(function () {
        Route::get("/", [WatchController::class, "index"])->name("index");
        Route::get("/{watch}", [WatchController::class, "show"])->name("show");
        Route::get("/category/{slug}", [WatchController::class, "category"])->name("category");
    });


Route::middleware(['auth'])
    ->prefix('contact')
    ->name('contact.')
    ->group(function () {
        Route::get('/messages', [ContactController::class, 'index'])->name('index');
        Route::get('/', [ContactController::class, 'create'])->name('create');
        Route::post("/store", [ContactController::class, "store"])->name("store");
    });


Route::middleware(['auth'])
    ->prefix('basket')
    ->name('basket.')
    ->group(function () {
        Route::get('/', [BasketController::class, 'index'])->name('index');
        Route::post("/{watch}", [BasketController::class, "store"])->name("store");
        Route::patch("/{item}", [BasketController::class, "update"])->name("update");
        Route::delete("/{item}", [BasketController::class, "destroy"])->name("destroy");
    });


Route::middleware(['auth'])
    ->prefix('checkout')
    ->name('checkout.')
    ->group(function () {
        Route::get("/", [CheckoutController::class, "index"])->name("index");
        Route::get("/{order}", [CheckoutController::class, "show"])->name("show");
        Route::post("/", [CheckoutController::class, "store"])->name("store");
    });

Route::middleware(['auth', 'admin', 'force_password_change'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('home');

        
        Route::resource('watches', AdminWatchController::class);

        
        Route::resource("suppliers", AdminSupplierController::class);

        
        Route::resource("brands", AdminBrandController::class);

        
        Route::resource("customers", AdminCustomerController::class)->parameters(["customers" => "customer"]);

        
        Route::get("/orders", [AdminOrderController::class, "index"])->name("orders.index");
        Route::get("/orders/{order}", [AdminOrderController::class, "show"])->name("orders.show");
        Route::patch("/orders/{order}/ship", [AdminOrderController::class, "ship"])->name("orders.ship");

        
        Route::get("/inventory", [AdminInventoryController::class, "index"])->name("inventory.index");
        Route::post("/inventory/incoming", [AdminInventoryController::class, "incoming"])->name("inventory.incoming");

        
        Route::get("/reports", [AdminReportsController::class, "index"])->name("reports.index");

        
        Route::get("/security", [AdminSecurityController::class, "index"])->name("security");

        
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });


Route::middleware(['auth'])
    ->prefix('account')
    ->name('account.')
    ->group(function () {
        Route::get("/profile", [UserController::class, "edit"])->name("profile.edit");
        Route::get('/security', [UserController::class, 'security'])->name('profile.security');
        Route::get('/delete', [UserController::class, 'delete'])->name('profile.delete');
        Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');
        Route::get("/messages", [MessageController::class, 'index'])->name('messages.index');
        Route::get("/orders", [OrderController::class, 'index'])->name('orders.index');
    });



Route::get('/search', [WatchController::class, 'search'])->name('watches.search');


require __DIR__ . '/auth.php';
