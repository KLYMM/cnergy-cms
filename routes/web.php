<?php

use UniSharp\LaravelFilemanager\Lfm;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\News\PhotoController;
use App\Http\Controllers\News\VideoController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Tools\ImageBankController;
use App\Http\Controllers\Tools\NewsDraftController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Tools\StaticPageController;
use App\Http\Controllers\TodayTag\TodayTagController;
use App\Http\Controllers\Tools\ActivityLogController;
use App\Http\Controllers\Tools\AnouncementController;
use App\Http\Controllers\Admin\FrontEndMenuController;
use App\Http\Controllers\Tools\NotificationController;
use App\Http\Controllers\Update\NewsTaggingController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Admin\FrontEndSettingsController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Tools\InventoryManagementController;

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

Route::group(['middleware' => ['usersPermissionRoles:1']], function () {
    Route::group(['prefix' => 'laravel-filemanager'], function () {
        Lfm::routes();
    });

    Route::get('/', [DashboardController::class, 'index']);

    // Dashboard
    Route::resource('dashboard', DashboardController::class);
    //Route::prefix('dashboard')->group(function () {
    //    Route::resource('dashboard', DashboardController::class);
    //});

    // Master
    Route::prefix('master')->group(function () {
        Route::prefix('user')->group(function () {
            Route::resource('role', RoleController::class);
            Route::resource('user-setting', UsersController::class);
        });

        Route::post('/front-end-menu/api/change/', [FrontEndMenuController::class, 'changeOrderMenu']);
        Route::get("/front-end-menu/create/{id?}", [FrontEndMenuController::class, 'create'])->name('front-end-menu.create');
        Route::resource('front-end-menu', FrontEndMenuController::class)->except(['create']);

        Route::post("/generate/token", [FrontEndSettingsController::class, 'generateToken'])->name('generate.token');
        Route::resource('/front-end-setting', FrontEndSettingsController::class);
        Route::post("/generate/configuration", [FrontEndSettingsController::class, 'generateConfiguration'])->name('generate.configuration');
        Route::post("/imagesize", [FrontEndSettingsController::class, 'imageSize'])->name('imagesize.info');
        Route::post("/cache-clear", [FrontEndSettingsController::class, 'cacheClear'])->name('clearcache');

        Route::get("/menu/create/{id?}", [MenuController::class, 'create'])->name('menu.create');
        Route::post("/menu/api/change/", [MenuController::class, 'changeOrderMenu']);
        Route::resource('menu', MenuController::class)->except(['create']);


        Route::get("/menu/create/{id?}", [MenuController::class, 'create'])->name('menu.create');
        Route::post("/menu/api/change/", [MenuController::class, 'changeOrderMenu']);
        Route::resource('menu', MenuController::class)->except(['create']);

        Route::post('/front-end-menu/api/change/', [FrontEndMenuController::class, 'changeOrderMenu'])->name('front-end-menu.order');
        Route::get("/category/create/{id?}", [CategoriesController::class, 'create'])->name('category.create');
        Route::post("/category/api/change/", [CategoriesController::class, 'changeCategoriesData']);
        Route::resource('category', CategoriesController::class)->except(['create']);
    });

    // Tools
    Route::prefix('tools')->group(function () {
        Route::resource('image-bank', ImageBankController::class);
        Route::resource('static-page', StaticPageController::class);
        Route::get('/upload-image', [ImageBankController::class, 'create']);
        Route::resource('news-draft', NewsDraftController::class);
        Route::resource('inventory-management', InventoryManagementController::class);
        Route::resource('activity-log', ActivityLogController::class)->only(['index']);
        Route::resource('anouncement', AnouncementController::class);
    });

    // Documentation
    Route::prefix('documentation')->group(function () {
    });

    // Update
    Route::prefix('update')->group(function () {
        Route::prefix('news')->group(function () {
            Route::post('/pagination/api/delete/', [NewsController::class, 'deleteNewsPagination'])->name('news.api.news_pagination');
            Route::resource('news', NewsController::class);
            Route::post('/photo/api/delete', [PhotoController::class, 'deleteNewsImages']);
            Route::resource('photo', PhotoController::class);
            Route::resource('video', VideoController::class);
        });
        Route::prefix('tags')->group(function () {
            Route::resource('tag-management', TagsController::class);
            Route::resource('today-tag', TodayTagController::class);
            Route::resource('news-tagging', NewsTaggingController::class)->only(['index']);
            Route::resource('today-tag', TodayTagController::class);
            Route::post('tagging-search', [NewsTaggingController::class, 'getTagging'])->name('tagging.search');
            Route::post('tagging-multi', [NewsTaggingController::class, 'multiTagging'])->name('tagging.multi');
            Route::post('tagging-update', [NewsTaggingController::class, 'updateTagging'])->name('tagging.edit');
        });
    });

    Route::get('image/{filename}', [ImageBankController::class, 'displayImage'])->name('image.displayImage');
    Route::get('/image-bank/api/list/', [ImageBankController::class, 'apiList'])->name('image_bank.api');
    Route::post('/image-bank/api/create', [ImageBankController::class, 'upload_image']);
    Route::post('/image-bank/api/image/store', [ImageBankController::class, 'storeImage'])->name('file-upload');
    Route::post('/image-bank/api/image/delete', [ImageBankController::class, 'deleteImageTemp'])->name('file-upload.delete');

    Route::resource('profile', ProfileController::class);

    Route::get('Notification', [NotificationController::class, 'index'])->name('notification');
    Route::post('Notification', [NotificationController::class, 'store'])->name('notification.asread');
});
// Route::post('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
//     ->middleware(['auth', 'signed']) // <-- don't remove "signed"
//     ->name('verification.verify');
Route::get('/phpinfo', function () {
    return phpinfo();
});
Route::get('/auth/redirect', [LoginController::class, 'redirectToProvider']);
Route::get('/auth/callback', [LoginController::class, 'handleProviderCallback']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('selTag', [NewsController::class, 'select'])->name('tag.index');
Route::get('selKeyword', [NewsController::class, 'select2'])->name('keyword.index');

Route::get('/email/verify/{token}', [LoginController::class, 'verify'])->name('email.verify');
Route::post('/front-end-menu/order/update', [FrontEndMenuController::class, 'changeOrderMenu'])->name('front-end-menu.order');
Route::get('/documentation',function (){
    return redirect('/api/documentation');
});