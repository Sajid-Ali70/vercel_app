<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $settings = null;
    try {
        $settings = DB::table('app_settings')->where('id', 1)->first();
    } catch (\Exception $e) {}

    if (!$settings) {
        $settings = (object)[
            'app_name' => 'Alfa Mobiles',
            'developer' => 'Alfa Mobiles Mart Karachi',
            'category' => 'Shopping',
            'tags' => 'Contains ads · In-app purchases',
            'app_icon' => '',
            'rating_score' => '4.3',
            'reviews_count' => '1.9K reviews',
            'downloads_count' => '3K+',
            'content_rating' => 'Rated for 3+',
            'updated_date' => 'Aug 14, 2026',
            'description' => 'Alfa Mobiles is Pakistan\'s trusted online mobile shopping app. Buy 100% original smartphones on easy monthly installments. No advance payment, 0% markup, no hidden charges. Enjoy a safe, simple & reliable shopping experience with Alfa Mobiles.',
            'release_notes' => '• New mobile booking system with easy installment plan\r\n• Improved app performance and faster browsing\r\n• Bug fixes and overall user experience improvements\r\n• Enhanced security for safe shopping',
            'screenshots' => '[]',
            'apk_url' => '/apk/app-release.apk',
            'active_theme' => 'playstore'
        ];
    }

    $theme = $settings->active_theme ?? 'playstore';
    if ($theme === 'landing') {
        return view('index', compact('settings'));
    }

    return view('frontend.index', compact('settings'));
});

// Admin Login Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');

// Protected Admin Routes
Route::middleware(['admin.auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Play Store Settings API
    Route::post('/admin/settings/update', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::post('/admin/settings/remove-screenshot', [AdminController::class, 'removeScreenshot'])->name('admin.settings.remove_screenshot');

    // APK Management API
    Route::post('/admin/apk/update-url', [AdminController::class, 'updateApkUrl'])->name('admin.apk.update_url');
    Route::post('/admin/apk/upload', [AdminController::class, 'uploadApk'])->name('admin.apk.upload');

    // Review Management API
    Route::post('/admin/reviews/add', [AdminController::class, 'addReview'])->name('admin.reviews.add');
    Route::post('/admin/reviews/delete', [AdminController::class, 'deleteReview'])->name('admin.reviews.delete');

    // Security API
    Route::post('/admin/password/update', [AdminController::class, 'updatePassword'])->name('admin.password.update');
});
