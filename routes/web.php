<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\CrawlController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('checkLogin')->group(function () {
    Route::middleware('checkAdmin')->group(function () {
        Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
            // class home
            Route::get("/", [\App\Http\Controllers\Admin\HomeController::class, "showDashboard"])->name('dashboard');

            // class seo
            Route::get("seo", [SeoController::class, "index"])->name('seo');
            Route::post("updateSeo", [SeoController::class, "update"])->name('updateSeo');
            Route::get('sitemap', [SeoController::class, 'sitemap'])->name('sitemap');
            Route::post('generateSitemap', [SeoController::class, 'generateSitemap'])->name('generateSitemap');
            Route::get('advanced', [\App\Http\Controllers\Admin\HomeController::class, 'showAdvanced'])->name('advanced');
            Route::post('updateAdvanced', [\App\Http\Controllers\Admin\HomeController::class, 'updateAdvanced'])->name('updateAdvanced');

            // class crawler
            Route::get("api", [\App\Http\Controllers\Admin\HomeController::class, "showApi"])->name('api');
            Route::get("api-damconuong", [\App\Http\Controllers\Admin\HomeController::class, "showApiDamCoNuong"])->name('api.damconuong');
            Route::get('setting', [\App\Http\Controllers\Admin\HomeController::class, 'showSetting'])->name('setting');
            Route::post('saveconfig', [\App\Http\Controllers\Admin\HomeController::class, 'saveConfig'])->name('crawler.saveConfig');
            Route::post("comics/v1/addChapterByCrawl", [CrawlController::class, 'addChapterByCrawl'])->name('addChapterByCrawl');
            Route::post("comics/v1/addComicByCrawl", [CrawlController::class, 'addComicByCrawl'])->name('addComicByCrawl');

            Route::resource('user', UserController::class);
            Route::resource("comment", CommentController::class);
            Route::resource('category', CategoryController::class);
            Route::resource('level', LevelController::class);
            Route::resource('author', AuthorController::class);
            Route::resource('blog', BlogController::class);
            Route::resource('comic', ComicController::class);
            Route::post('upload-many-images', [ComicController::class, 'uploadManyImages'])->name('comic.upload-many-images');
            Route::post('upload-images', [ComicController::class, 'uploadImages'])->name('comic.upload-images');
            Route::resource('chapterComic', ChapterController::class);
            Route::get('chapter/chuong-loi', [ChapterController::class, 'error'])->name('chapter.error');
            Route::post('chapter/chuong-loi/update', [ChapterController::class, 'updateError'])->name('chapter.updateError');

            // class keyword bans
            Route::post("/addKeyword", [\App\Http\Controllers\Admin\HomeController::class, 'addKeyword'])->name('addKeyword');
            Route::post('/deleteKeyword', [\App\Http\Controllers\Admin\HomeController::class, 'deleteKeyword'])->name('deleteKeyword');

            Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
                ->name('ckfinder_connector');
            Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
                ->name('ckfinder_browser');
        });
    });
});

// Auth
Route::get('/auth-logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/reset-password', [AuthController::class, 'showResetPass'])->name('reset-password');
Route::get('/quen-mat-khau', [AuthController::class, 'showForgotPass'])->name('showForgotPass');
Route::post('/auth-login', [AuthController::class, 'login'])->name('auth-login');
Route::post('/auth-forgot-password', [AuthController::class, 'resetPass'])->name('auth-forgot-password');
Route::post('/auth-register', [AuthController::class, 'register'])->name('auth-register');
Route::post('/change-password', [AuthController::class, 'changePass'])->name('change-password');

// Action with auth
Route::post('/mangas/{id}/vote', [HomeController::class, 'vote'])->name('vote');
Route::post('/mangas/{id}/follow', [HomeController::class, 'follow'])->name('follow');
Route::get('/comments', [HomeController::class, 'getComments'])->name('getComments');
Route::post('/comments', [HomeController::class, 'comments'])->name('comments');
Route::post('/comments/{id}/reply', [HomeController::class, 'reply'])->name('reply');
Route::patch('/comments/{id}/like', [HomeController::class, 'like'])->name('like');
Route::post('/dislike', [HomeController::class, 'dislike'])->name('dislike');
Route::post('/upExp', [HomeController::class, 'upExp'])->name('upExp');
Route::get('/trang-ca-nhan', [HomeController::class, 'showProfile'])->name('profile');
Route::post('/update-profile', [HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password', [HomeController::class, 'updatePassword'])->name('updatePassword');
Route::post('/changeServer', [HomeController::class, 'changeServer'])->name('changeServer');
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return response()->json(['status' => 'success']);
})->name('clearCache');
Route::post('remove-history', [HomeController::class, 'removeHistory'])->name('removeHistory');
Route::get('/get-google-sign-in-url', [\App\Http\Controllers\GoogleController::class, 'getGoogleSignInUrl'])->name('loginGoogle');
Route::get('auth/google/callback', [\App\Http\Controllers\GoogleController::class, 'loginCallback']);

// Page
Route::get('/trang/ve-chung-toi', [PageController::class, 'showAbout'])->name('about');
Route::get('/trang/phuong-thuc-lien-he', [PageController::class, 'showContact'])->name('contact');
Route::get('/trang/dieu-khoan-dich-vu', [PageController::class, 'showTerms'])->name('terms');
Route::get('/trang/tuyen-bo-mien-tru-trach-nhiem', [PageController::class, 'showDisclaimer'])->name('disclaimer');
Route::get('/trang/chinh-sach-bao-mat', [PageController::class, 'showPrivacy'])->name('privacy');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('random', [HomeController::class, 'random'])->name('random');
Route::get('hot-nhat', [HomeController::class, 'hot'])->name('hot');
Route::get('the-loai', [HomeController::class, 'showCategory'])->name('showCategory');
Route::get('da-hoan-thanh', [HomeController::class, 'showCompleted'])->name('showCompleted');
Route::get('tin-tuc', [HomeController::class, 'showNews'])->name('showNews');
Route::get('new', [HomeController::class, 'showNewUpdate'])->name('showNewUpdate');
Route::post('apiSearch', [HomeController::class, 'searchComic'])->name('search');
Route::get('tim-kiem', [HomeController::class, 'showSearch'])->name('showSearch');
Route::get('truyen-dang-theo-doi', [HomeController::class, 'showFollow'])->name('showFollow');
Route::get('lich-su', [HomeController::class, 'showHistory'])->name('showHistory');
Route::get('trang-binh-luan', [HomeController::class, 'showComment'])->name('showComment');
Route::get('quan-ly-tai-khoan', [HomeController::class, 'showProfile'])->name('showProfile');
Route::get('/{slug}', [HomeController::class, 'showDetailComic'])->name('detail');
Route::get('/the-loai/{slug}', [HomeController::class, 'showCategoryBySlug'])->name('showCategoryBySlug');
Route::get('/tac-gia/{slug}', [HomeController::class, 'showAuthor'])->name('showAuthor');
Route::get('/tin-tuc/{slug}', [HomeController::class, 'showBlog'])->name('showBlog');
Route::get('/{slug}/{chapter}', [HomeController::class, 'showReadComicPage'])->name('showRead');
