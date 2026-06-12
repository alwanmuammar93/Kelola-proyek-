<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\RabController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestNotificationController;
use App\Http\Controllers\CMSController;
use App\Http\Controllers\KatalogController;

/*
|--------------------------------------------------------------------------
| PUBLIC LANDING PAGE (TANPA LOGIN)
|--------------------------------------------------------------------------
*/

// Route Beranda (Landing Page)
Route::get('/', function () {
    return view('beranda');
})->name('beranda');

// Route Tentang Kami
Route::get('/tentang-kami', function () {
    return view('tentang-kami');
})->name('tentang-kami');

// Route Catalog
Route::get('/catalog', function () {
    return view('catalog');
})->name('catalog');

// Route Galeri Proyek
Route::get('/galeri-proyek', function () {
    return view('galeri-proyek');
})->name('galeri.proyek');

// Route Kontak
Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

/*
|--------------------------------------------------------------------------
| DEBUG: GENERATE PASSWORD HASH (HANYA UNTUK DEVELOPMENT)
|--------------------------------------------------------------------------
*/
Route::get('/generate-hash', function () {
    $password = 'password123';
    $hashedPassword = Hash::make($password);
    return "Password Asli: {$password} <br> HASH BARU: <b>{$hashedPassword}</b>";
})->middleware('web');

/*
|--------------------------------------------------------------------------
| LOGIN & LOGOUT
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout menggunakan POST method (Best Practice)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Alias GET untuk backward compatibility
Route::get('/logout', function() {
    auth()->logout();
    return redirect()->route('login')->with('success', 'Anda telah logout');
})->name('logout.get');

/*
|--------------------------------------------------------------------------
| 🔒 PROTECTED ROUTES - SEMUA ROUTE DI SINI WAJIB LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | HOME & DASHBOARD (REDIRECT OTOMATIS BERDASARKAN ROLE)
    |--------------------------------------------------------------------------
    */
    Route::get('/home', function () {
        $user = auth()->user();
        
        return match($user->role) {
            'admin' => redirect()->route('admin.index'),
            'kasir' => redirect()->route('kasir.index'),
            default => redirect()->route('login')
        };
    })->name('home');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        return match($user->role) {
            'admin' => redirect()->route('admin.index'),
            'kasir' => redirect()->route('kasir.index'),
            default => redirect()->route('login')
        };
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | 👤 PROFILE & SETTINGS ROUTES (SEMUA ROLE)
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->name('profile.')->group(function () {
        // Get Profile Data (untuk modal)
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        
        // Update Profile (Nama)
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        
        // Change Password (untuk diri sendiri)
        Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        
        // Upload/Update Avatar (Opsional - untuk fitur masa depan)
        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('update-avatar');
        
        // Delete Avatar
        Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('delete-avatar');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        // Get Settings
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        
        // Update Notification Preferences
        Route::put('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications');
        
        // Save FCM Token for Push Notifications
        Route::post('/fcm-token', [SettingsController::class, 'saveFcmToken'])->name('fcm-token.save');
        
        // Remove FCM Token (Unsubscribe Push Notifications)
        Route::delete('/fcm-token', [SettingsController::class, 'removeFcmToken'])->name('fcm-token.remove');
        
        // Update Theme Preference
        Route::put('/theme', [SettingsController::class, 'updateTheme'])->name('theme');
        
        // Update Privacy Settings
        Route::put('/privacy', [SettingsController::class, 'updatePrivacy'])->name('privacy');
        
        // Two-Factor Authentication
        Route::post('/2fa/enable', [SettingsController::class, 'enable2FA'])->name('2fa.enable');
        Route::post('/2fa/disable', [SettingsController::class, 'disable2FA'])->name('2fa.disable');
    });

    /*
    |--------------------------------------------------------------------------
    | 👨‍💼 ADMIN ONLY ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {
        
        // Admin Dashboard
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::get('/admin/kwitansi', [KwitansiController::class, 'index'])
            ->name('admin.kwitansi.index');

        Route::get('/admin/rab', function () {
            return redirect()->route('rab.index');
        })->name('admin.rab.index');

        /*
        |--------------------------------------------------------------------------
        | 🔥 ADMIN PASSWORD MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('admin')->name('admin.')->group(function () {
            // Admin ubah password kasir (tidak perlu password lama)
            Route::put('/change-kasir-password', [AdminController::class, 'changeKasirPassword'])
                ->name('change-kasir-password');
        });

        /*
        |--------------------------------------------------------------------------
        | 🔔 TEST PUSH NOTIFICATION ROUTES (DEVELOPMENT ONLY)
        |--------------------------------------------------------------------------
        */
        Route::prefix('test-notification')->name('test.notification.')->group(function () {
            // Test page (UI untuk test)
            Route::get('/', [TestNotificationController::class, 'index'])
                ->name('index');
            
            // Test send to self
            Route::post('/send-to-self', [TestNotificationController::class, 'testSendToSelf'])
                ->name('send-to-self');
            
            // Test send to all admins
            Route::post('/send-to-admins', [TestNotificationController::class, 'testSendToAdmins'])
                ->name('send-to-admins');
            
            // Test send to all users
            Route::post('/send-to-all', [TestNotificationController::class, 'testSendToAll'])
                ->name('send-to-all');
            
            // Test transaction notification
            Route::post('/test-transaction', [TestNotificationController::class, 'testTransactionNotification'])
                ->name('test-transaction');
            
            // Test project notification
            Route::post('/test-project', [TestNotificationController::class, 'testProjectNotification'])
                ->name('test-project');
            
            // Test project status notification
            Route::post('/test-project-status', [TestNotificationController::class, 'testProjectStatusNotification'])
                ->name('test-project-status');
            
            // Test custom notification
            Route::post('/test-custom', [TestNotificationController::class, 'testCustomNotification'])
                ->name('test-custom');
        });

        /*
        |--------------------------------------------------------------------------
        | 🌐 KELOLA KONTEN (CMS) - ADMIN ONLY
        |--------------------------------------------------------------------------
        */
        Route::prefix('admin/konten')->name('admin.cms.')->group(function () {
            
            /*
            |--------------------------------------------------------------------------
            | CMS DASHBOARD & SECTION MANAGEMENT
            |--------------------------------------------------------------------------
            */
            // Unified Dashboard (CMS + Katalog in One Page with Tabs)
            Route::get('/', [CMSController::class, 'index'])->name('index');
            
            // CMS Section Updates - Hero Beranda
            Route::post('/hero/update', [CMSController::class, 'heroUpdate'])->name('hero.update');
            
            // CMS Section Updates - About Company
            Route::post('/about/update', [CMSController::class, 'aboutUpdate'])->name('about.update');
            
            // CMS Section Updates - Services
            Route::post('/services/update', [CMSController::class, 'servicesUpdate'])->name('services.update');
            
            // CMS Section Updates - Catalog Hero
            Route::post('/catalog/update', [CMSController::class, 'catalogHeroUpdate'])->name('catalog.update');
            
            // CMS Section Updates - Proyek Hero
            Route::post('/proyek/update', [CMSController::class, 'proyekHeroUpdate'])->name('proyek.update');
            
            /*
            |--------------------------------------------------------------------------
            | 🛍️ CATALOG PRODUCTS MANAGEMENT
            |--------------------------------------------------------------------------
            */
            // Catalog Products Index Page
            Route::get('/catalog/products', [CMSController::class, 'catalogProductsIndex'])
                ->name('catalog.products');
            
            // Product CRUD Operations
            Route::post('/catalog/products', [CMSController::class, 'catalogProductStore'])
                ->name('catalog.product.store');
            
            Route::get('/catalog/products/{productId}', [CMSController::class, 'catalogProductShow'])
                ->name('catalog.product.show');
            
            Route::delete('/catalog/products/{productId}', [CMSController::class, 'catalogProductDelete'])
                ->name('catalog.product.delete');
            
            // Delete Product Image Only (Keep Product Data)
            Route::delete('/catalog/products/{productId}/image', [CMSController::class, 'catalogProductDeleteImage'])
                ->name('catalog.product.delete-image');
            
            // Category Management
            Route::post('/catalog/categories', [CMSController::class, 'catalogCategoryStore'])
                ->name('catalog.category.store');
            
            Route::delete('/catalog/categories/{category}', [CMSController::class, 'catalogCategoryDelete'])
                ->name('catalog.category.delete');
            
            /*
            |--------------------------------------------------------------------------
            | 📦 KATALOG PRODUCT MANAGEMENT (OLD - BACKWARD COMPATIBILITY)
            |--------------------------------------------------------------------------
            */
            Route::post('/katalog/store', [KatalogController::class, 'store'])->name('katalog.store');
            Route::get('/katalog/{id}/edit', [KatalogController::class, 'edit'])->name('katalog.edit');
            Route::put('/katalog/{id}/update', [KatalogController::class, 'update'])->name('katalog.update');
            Route::delete('/katalog/{id}/destroy', [KatalogController::class, 'destroy'])->name('katalog.destroy');
            Route::post('/katalog/reorder', [KatalogController::class, 'reorder'])->name('katalog.reorder');
            Route::patch('/katalog/{id}/toggle-status', [KatalogController::class, 'toggleStatus'])->name('katalog.toggle-status');

            /*
            |--------------------------------------------------------------------------
            | 📸 PROJECT GALLERY MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::post('/projects', [CMSController::class, 'projectStore'])->name('project.store');
            Route::delete('/projects/{projectId}', [CMSController::class, 'projectDelete'])->name('project.delete');
            Route::delete('/projects/{projectId}/image', [CMSController::class, 'projectDeleteImage'])->name('project.delete-image');
            
            Route::post('/projects/categories', [CMSController::class, 'projectCategoryStore'])->name('project.category.store');
            Route::delete('/projects/categories/{category}', [CMSController::class, 'projectCategoryDelete'])->name('project.category.delete');
            
        }); // END KELOLA KONTEN (CMS)

        /*
        |--------------------------------------------------------------------------
        | PROYEK (ADMIN ONLY)
        |--------------------------------------------------------------------------
        */
        Route::prefix('proyek')->name('proyek.')->group(function () {
            Route::get('/', [ProyekController::class, 'index'])->name('index');
            Route::get('/filter', [ProyekController::class, 'filterByStatus'])->name('filter');
            Route::get('/create', [ProyekController::class, 'create'])->name('create');
            Route::post('/', [ProyekController::class, 'store'])->name('store');
            Route::get('/{id_proyek}', [ProyekController::class, 'show'])->name('show');
            Route::get('/{id_proyek}/edit', [ProyekController::class, 'edit'])->name('edit');
            Route::put('/{id_proyek}', [ProyekController::class, 'update'])->name('update');
            Route::patch('/{id_proyek}/status', [ProyekController::class, 'updateStatus'])->name('update-status');
            Route::delete('/{id_proyek}', [ProyekController::class, 'destroy'])->name('destroy');
        });

        /*
        |--------------------------------------------------------------------------
        | RAB (ADMIN ONLY)
        |--------------------------------------------------------------------------
        */
        Route::prefix('rab')->name('rab.')->group(function () {
            // AJAX Routes (Prioritas tinggi)
            Route::get('/detail/{id_rab}', [RabController::class, 'getRabDetails'])->name('detail');
            Route::post('/generate-no', [RabController::class, 'generateNoRab'])->name('generate-no');
            
            // CRUD Routes
            Route::get('/', [RabController::class, 'index'])->name('index');
            Route::get('/create', [RabController::class, 'create'])->name('create');
            Route::post('/store', [RabController::class, 'store'])->name('store');
            Route::get('/edit/{id_rab}', [RabController::class, 'edit'])->name('edit');
            Route::put('/update/{id_rab}', [RabController::class, 'update'])->name('update');
            Route::delete('/destroy/{id_rab}', [RabController::class, 'destroy'])->name('destroy');
            Route::get('/download-pdf/{id_rab}', [RabController::class, 'downloadPDF'])->name('downloadPDF');
        });
    
        /*
        |--------------------------------------------------------------------------
        | LAPORAN (ADMIN ONLY)
        |--------------------------------------------------------------------------
        */
        Route::prefix('laporan')->name('laporan.')->group(function () {
            // AJAX Routes (Prioritas tinggi)
            Route::get('/get-data/{sumber}', [LaporanController::class, 'getData'])
                ->name('getData')
                ->where('sumber', 'RAB|Penjualan');
            
            Route::get('/get-detail/{sumber}/{id}', [LaporanController::class, 'getDetail'])
                ->name('getDetail')
                ->where(['sumber' => 'RAB|Penjualan', 'id' => '[0-9]+']);

            // CRUD Routes
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/create', [LaporanController::class, 'create'])->name('create');
            Route::post('/', [LaporanController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [LaporanController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
            Route::put('/{id}', [LaporanController::class, 'update'])->name('update')->where('id', '[0-9]+');
            Route::delete('/{id}', [LaporanController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        });

    }); // END ADMIN ONLY ROUTES

    /*
    |--------------------------------------------------------------------------
    | 💰 KASIR ONLY ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:kasir'])->group(function () {
        
        // Kasir Dashboard
        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');

        // Kasir Penjualan
        Route::get('/kasir/penjualan', function () {
            return redirect()->route('penjualan.index');
        })->name('kasir.penjualan');

    }); // END KASIR ONLY ROUTES

    /*
    |--------------------------------------------------------------------------
    | 🔀 SHARED ROUTES (ADMIN & KASIR)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin,kasir'])->group(function () {
        
        /*
        |--------------------------------------------------------------------------
        | KWITANSI (ADMIN & KASIR)
        |--------------------------------------------------------------------------
        */
        Route::prefix('kwitansi')->name('kwitansi.')->group(function () {
            // AJAX Routes (Prioritas tinggi)
            Route::get('/get-penjualan-data', [KwitansiController::class, 'getPenjualanData'])->name('getPenjualanData');
            Route::get('/get-rab-data', [KwitansiController::class, 'getRabData'])->name('getRabData');
            Route::get('/get-data-by-sumber', [KwitansiController::class, 'getDataBySumber'])->name('getDataBySumber');
            Route::get('/search-data', [KwitansiController::class, 'searchData'])->name('searchData');
            Route::get('/get-total-sumber', [KwitansiController::class, 'getTotalSumber'])->name('getTotalSumber');
            Route::get('/search', [KwitansiController::class, 'search'])->name('search');

            // CRUD Routes
            Route::get('/', [KwitansiController::class, 'index'])->name('index');
            Route::get('/create', [KwitansiController::class, 'create'])->name('create');
            Route::post('/', [KwitansiController::class, 'store'])->name('store');
            Route::get('/download/{id_kwitansi}', [KwitansiController::class, 'downloadPDF'])->name('downloadPDF');
            Route::get('/{id_kwitansi}/edit', [KwitansiController::class, 'edit'])->name('edit');
            Route::put('/{id_kwitansi}', [KwitansiController::class, 'update'])->name('update');
            Route::delete('/{id_kwitansi}', [KwitansiController::class, 'destroy'])->name('destroy');
        });

        /*
        |--------------------------------------------------------------------------
        | PENJUALAN (ADMIN & KASIR)
        |--------------------------------------------------------------------------
        */
        Route::prefix('penjualan')->name('penjualan.')->group(function () {
            Route::get('/', [PenjualanController::class, 'index'])->name('index');
            Route::get('/create', [PenjualanController::class, 'create'])->name('create');
            Route::post('/', [PenjualanController::class, 'store'])->name('store');
            Route::get('/{id_penjualan}/edit', [PenjualanController::class, 'edit'])->name('edit');
            Route::put('/{id_penjualan}', [PenjualanController::class, 'update'])->name('update');
            Route::delete('/{id_penjualan}', [PenjualanController::class, 'destroy'])->name('destroy');
        });

    }); // END SHARED ROUTES (ADMIN & KASIR)

}); // END MIDDLEWARE AUTH GROUP