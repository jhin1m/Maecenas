<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SitemapController;
use GuzzleHttp\Client;
use App\Http\Controllers\Admin\CrawlController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/generateSitemap', [ApiController::class, 'generateSitemap']);
Route::post('chapter/reportChapter', [HomeController::class, 'reportChapter'])->name('reportChapter');
Route::get('mangas/search', [ApiController::class, 'searchManga'])->name('searchManga');
