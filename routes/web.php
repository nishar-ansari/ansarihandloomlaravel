<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CMSController;
use App\Http\Controllers\CustomerProfileController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Admin\WarehouseController as AdminWarehouseController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeSetController;
use App\Http\Controllers\Admin\AccountingController as AdminAccountingController;
use App\Http\Controllers\Admin\MarketingController as AdminMarketingController;
use App\Http\Controllers\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

/*
|--------------------------------------------------------------------------
| Customer Website Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/product/{id}/review', [ProductController::class, 'submitReview'])->name('products.review.submit');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/contact', [CMSController::class, 'showContact'])->name('contact');
Route::post('/contact', [CMSController::class, 'submitContact'])->name('contact.submit');
Route::get('/about', [CMSController::class, 'showAbout'])->name('about');
Route::get('/faq', [CMSController::class, 'showFAQ'])->name('faq');

Route::get('/profile', [CustomerProfileController::class, 'index'])->name('customer.profile');

// Session-based shopping bag
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/place', [CheckoutController::class, 'place'])->name('checkout.place');

// Customer Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('customer.login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('customer.logout');

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    // Admin Guest Routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    });

    // Admin Auth Routes
    Route::middleware('auth')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        // Products Catalog
        Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
        Route::post('/products/create', [AdminProductController::class, 'store'])->name('admin.products.store');
        Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
        Route::post('/products/{id}/edit', [AdminProductController::class, 'update'])->name('admin.products.update');
        Route::post('/products/{id}/delete', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');

        // Product SKU and Image Variants Management (images belong to the SKU)
        Route::post('/products/{id}/sku/create', [AdminProductController::class, 'storeSku'])->name('admin.products.sku.store');
        Route::post('/products/sku/{id}/update', [AdminProductController::class, 'updateSku'])->name('admin.products.sku.update');
        Route::post('/products/sku/{id}/default', [AdminProductController::class, 'setDefaultSku'])->name('admin.products.sku.default');
        Route::post('/products/sku/{id}/image/upload', [AdminProductController::class, 'uploadImage'])->name('admin.products.image.upload');
        Route::post('/products/image/{id}/delete', [AdminProductController::class, 'deleteImage'])->name('admin.products.image.delete');
        Route::post('/products/image/{id}/primary', [AdminProductController::class, 'setPrimaryImage'])->name('admin.products.image.primary');

        // Categories & Brands
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('/categories/create', [AdminCategoryController::class, 'store'])->name('admin.categories.store');

        // Dynamic Attributes & Attribute Sets Master
        Route::get('/attributes', [AttributeController::class, 'index'])->name('admin.attributes.index');
        Route::post('/attributes/create', [AttributeController::class, 'store'])->name('admin.attributes.store');
        Route::get('/attributes/{id}/values', [AttributeController::class, 'valuesIndex'])->name('admin.attributes.values');
        Route::post('/attributes/{id}/values/create', [AttributeController::class, 'storeValue'])->name('admin.attributes.values.store');
        Route::post('/attributes/values/{id}/delete', [AttributeController::class, 'deleteValue'])->name('admin.attributes.values.delete');

        Route::get('/attribute-sets', [AttributeSetController::class, 'index'])->name('admin.attribute-sets.index');
        Route::post('/attribute-sets/create', [AttributeSetController::class, 'store'])->name('admin.attribute-sets.store');

        // Inventory Stocking
        Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('admin.inventory.index');
        Route::post('/inventory/adjust', [AdminInventoryController::class, 'adjust'])->name('admin.inventory.adjust');

        // Orders Management
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update_status');

        // Customers List
        Route::get('/customers', [AdminOrderController::class, 'customersIndex'])->name('admin.customers.index');

        // Vendors & Procurement
        Route::get('/vendors', [AdminVendorController::class, 'index'])->name('admin.vendors.index');
        Route::post('/vendors/create', [AdminVendorController::class, 'storeVendor'])->name('admin.vendors.store');
        Route::post('/vendors/po/create', [AdminVendorController::class, 'storePO'])->name('admin.po.store');

        // Warehouses & Movements
        Route::get('/warehouses', [AdminWarehouseController::class, 'index'])->name('admin.warehouses.index');
        Route::post('/warehouses/create', [AdminWarehouseController::class, 'store'])->name('admin.warehouses.store');
        Route::post('/warehouses/transfer', [AdminWarehouseController::class, 'transfer'])->name('admin.warehouses.transfer');

        // Accounting & Finance
        Route::get('/accounting', [AdminAccountingController::class, 'index'])->name('admin.accounting.index');
        Route::post('/accounting/bank/create', [AdminAccountingController::class, 'storeBank'])->name('admin.bank.store');
        Route::post('/accounting/transaction/create', [AdminAccountingController::class, 'storeTransaction'])->name('admin.transaction.store');
        Route::post('/accounting/expense/create', [AdminAccountingController::class, 'storeExpense'])->name('admin.expense.store');

        // Marketing & CMS
        Route::get('/marketing', [AdminMarketingController::class, 'index'])->name('admin.marketing.index');
        Route::post('/marketing/coupon/create', [AdminMarketingController::class, 'storeCoupon'])->name('admin.coupon.store');
        Route::post('/marketing/review/{id}/approve', [AdminMarketingController::class, 'approveReview'])->name('admin.review.approve');
        Route::post('/marketing/banner/create', [AdminMarketingController::class, 'storeBanner'])->name('admin.banner.store');
        Route::post('/marketing/blog/create', [AdminMarketingController::class, 'storeBlog'])->name('admin.blog.store');

        // Staff / Employees
        Route::get('/employees', [AdminEmployeeController::class, 'index'])->name('admin.employees.index');
        Route::post('/employees/create', [AdminEmployeeController::class, 'store'])->name('admin.employees.store');

        // Reports Panel
        Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
    });
});
