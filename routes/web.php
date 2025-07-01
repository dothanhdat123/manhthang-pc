<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ProfileController;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SocialController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\ClientNewsController;
use App\Http\Controllers\Client\ClientProductController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\PublicCustomerController;
use App\Http\Controllers\Client\PublicPageController;
use App\Http\Controllers\Client\SearchController;
use App\Http\Controllers\Client\WishlistController;
use CKSource\CKFinderBridge\Controller\CKFinderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

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



Route::name('client.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/chat', [ChatController::class, 'index']);
    Route::post('/chat/send', [ChatController::class, 'send']);
    Route::get('/chat/fetch', [ChatController::class, 'fetch']);

    Route::get('/gioi-thieu', [PublicPageController::class, 'about'])->name('about');
    Route::get('/chinh-sach-va-dieu-khoản', [PublicPageController::class, 'term'])->name('term');
    Route::get('/chinh-sach-giao-hang', [PublicPageController::class, 'delivery'])->name('delivery');
    Route::get('/chinh-sach-doi-tra', [PublicPageController::class, 'returns'])->name('returns');
    Route::get('/faqs', [PublicPageController::class, 'faqs'])->name('faqs');

    Route::post('/newsletter', [HomeController::class, 'newsletter'])->name('newsletter');

    Route::match(['get', 'post'], '/lien-he', [PublicPageController::class, 'contact'])->name('contact');

    Route::get('/danh-muc/{slug?}', [ClientProductController::class, 'category'])
        ->name('category.show');

    Route::get('/san-pham', [ClientProductController::class, 'index'])->name('product.index');

    Route::get('/san-pham/{slug?}', [ClientProductController::class, 'product'])
        ->name('product.show');

    Route::post('/reviews/create', [ClientProductController::class, 'review'])->name('product.reviews.create');

    Route::prefix('/bai-viet')->name('news.')->group(function () {
        Route::get('/', [ClientNewsController::class, 'index'])->name('index');
        Route::get('/{slug}', [ClientNewsController::class, 'show'])->name('show');
    });

    Route::name('auth.')->group(function () {
        Route::match(['get', 'post'], '/dang-nhap', [AuthController::class, 'login'])->name('login');
        Route::match(['get', 'post'], '/dang-ky', [AuthController::class, 'register'])->name('register');
    });

    Route::get('/tim-kiem', [SearchController::class, 'index'])
        ->name('search');

    Route::middleware('auth')->group(function () {
        Route::get('/dang-xuat', [AuthController::class, 'logout'])
            ->name('auth.logout');

        Route::prefix('yeu-thich')->name('wishlist.')->group(function () {
            Route::get('/', [WishlistController::class, 'index'])
                ->name('index');
            Route::get('/{id?}', [WishlistController::class, 'store'])
                ->name('store');
            Route::get('/{id?}/them-gio-hang', [WishlistController::class, 'wishlist_to_cart'])
                ->name('wishlist_to_cart');
            Route::get('/{id?}/xoa', [WishlistController::class, 'destroy'])
                ->name('destroy');
        });

        Route::prefix('/gio-hang')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])
                ->name('index');

            Route::match(['get', 'post'], '/{id}/them', [CartController::class, 'create'])
                ->name('create');

            Route::put('/cap-nhat', [CartController::class, 'update'])->name('update');

            Route::get('/{id?}/xoa', [CartController::class, 'destroy'])
                ->name('destroy');

            Route::get('/xoa-tat-ca', [CartController::class, 'remove_all'])
                ->name('remove_all');

        });

        Route::prefix('/thanh-toan')->group(function () {
            Route::match(['get', 'post'], '/', [CartController::class, 'checkout'])
                ->name('checkout');

            Route::get('/vnpay-callback', [CartController::class, 'vnPayCallback'])
                ->name('vn_pay.callback');

            Route::get('/hoan-thanh', [CartController::class, 'completeCheckout'])
                ->name('checkout.complete');
        });

        Route::prefix('/khach-hang')->name('customers.')->group(function () {
            Route::get('/', [PublicCustomerController::class, 'index'])
                ->name('index');

            Route::prefix('/don-hang')->name('orders.')->group(function () {
                Route::get('/', [PublicCustomerController::class, 'orders'])
                    ->name('index');

                Route::get('/chi-tiet/{id}', [PublicCustomerController::class, 'show_order'])
                    ->name('show');

                Route::get('/huy-don-hang/{id}', [PublicCustomerController::class, 'cancel_order'])
                    ->name('cancel');

                Route::get('/xuat-hoa-don/{id}', [PublicCustomerController::class, 'export_detail'])
                    ->name('export_detail');
            });

            Route::prefix('/dia-chi')->name('address.')->group(function () {
                Route::get('/', [PublicCustomerController::class, 'address'])
                    ->name('index');

                Route::match(['get', 'post'], '/them-moi', [PublicCustomerController::class, 'add_address'])
                    ->name('store');

                Route::match(['get', 'put'], '/cap-nhat/{id}', [PublicCustomerController::class, 'update_address'])
                    ->name('update');

                Route::get('/xoa/{id}', [PublicCustomerController::class, 'destroy_address'])
                    ->name('destroy');
            });

            Route::match(['get', 'put'], '/ca-nhan', [PublicCustomerController::class, 'profile'])
                ->name('profile');

            Route::match(['get', 'post'], '/doi-mat-khau', [PublicCustomerController::class, 'changePassword'])
                ->name('password'); // Chane password
        });
    });

//    Route::get('/hwa-dev/{key}', [DevController::class, 'dev'])->name('dev');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::prefix(hwa_admin_dir())->name('admin.')->group(function () {

    // Unauthenticated
    Route::prefix('/auth')->name('auth.')->group(function () {
        Route::match(['get', 'post'], '/login', [LoginController::class, 'login'])
            ->name('login'); // Login
        Route::match(['get', 'post'], 'register', [RegisterController::class, 'register'])
            ->name('register'); // Register

        Route::name('social.')->group(function () {
            Route::get('/redirect/{social}', [SocialController::class, 'redirectToProvider'])->name('redirect');
            Route::get('/{social}/callback', [SocialController::class, 'handleProviderCallback'])->name('callback');
        });

        // Forget and reset password
        Route::prefix('/password')->name('password.')->group(function () {
            Route::match(['get', 'post'], '/forget', [ResetPasswordController::class, 'forget'])
                ->name('forget');
            Route::match(['get', 'post'], '/reset/{token}', [ResetPasswordController::class, 'reset'])
                ->name('reset');
        });
    });

    // Authenticated
    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('home'); // Dashboard

        // Auth
        Route::prefix('/auth')->name('auth.')->group(function () {
            Route::get('/logout', [LoginController::class, 'logout'])
                ->name('logout'); // Logout

            // Profile group
            Route::prefix('/profile')->name('profile.')->group(function () {
                Route::match(['get', 'put'], '/', [ProfileController::class, 'profile'])
                    ->name('index'); // Update profile
                Route::post('/change-password', [ProfileController::class, 'changePassword'])
                    ->name('password'); // Chane password
            });
        });

        // System
        Route::prefix('/system')->name('system.')->group(function () {
            Route::get('/info', [SystemController::class, 'systemInfo'])->name('info');
        });

        Route::resources([
            '/banners' => BannerController::class,
            '/reviews' => ReviewController::class,
            '/news' => NewsController::class,
            '/suppliers' => SupplierController::class,
            '/categories' => CategoryController::class,
            '/brands' => BrandController::class,
            '/products' => ProductController::class,
            '/orders' => OrderController::class,
            '/customers' => CustomerController::class,
            '/newsletter' => NewsletterController::class,
            '/contacts' => ContactController::class,
            '/roles' => RoleController::class,
            '/users' => UserController::class,
            '/faqs' => FaqController::class,
            '/stores' => StoreController::class
        ]);

        Route::resource('/pages', PageController::class)->only(['index', 'edit', 'update']);

        Route::delete('/customers/{customer}/addresses/{address}', [CustomerController::class, 'destroy_address'])
            ->name('customers.addresses.destroy');

        Route::post('/contacts/{id}/reply', [ContactController::class, 'replyContact'])
            ->name('contacts.reply');

        Route::prefix('/export')->name('export.')->group(function () {
            Route::prefix('/orders')->name('orders.')->group(function () {
                Route::get('/', [OrderController::class, 'export'])->name('index');
                Route::get('/{id}', [OrderController::class, 'export_detail'])->name('show');
            });
        });

        /**
         * Setting module
         */
        Route::prefix('/settings')->name('settings.')->group(function () {
            Route::match(['get', 'put'], '/shop', [SettingController::class, 'shopSetting'])
                ->name('shop'); // Shop settings

            Route::match(['get', 'put'], '/', [SettingController::class, 'index'])
                ->name('index'); // General settings
            Route::match(['get', 'put'], '/email', [SettingController::class, 'email'])
                ->name('email'); // Email setting
            Route::match(['get', 'put'], '/social-login', [SettingController::class, 'socialLogin'])
                ->name('social_login'); // Social login
        });
    });
});

Route::any('/ckfinder/connector', [CKFinderController::class, 'requestAction'])->name('ckfinder_connector');
Route::any('/ckfinder/browser', [CKFinderController::class, 'browserAction'])->name('ckfinder_browser');
