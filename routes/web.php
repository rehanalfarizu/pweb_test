<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Halaman utama (Vue mount di sini misalnya)
// Route untuk test debug model
Route::get('/test-models', function () {
    try {
        // Test if CourseDescription model exists and can be instantiated
        if (!class_exists('\App\Models\CourseDescription')) {
            return response()->json([
                'error' => 'CourseDescription class not found',
                'status' => 'error'
            ]);
        }

        $courseDescCount = \App\Models\CourseDescription::count();
        $courseCount = \App\Models\Course::count();

        return response()->json([
            'course_description_count' => $courseDescCount,
            'course_count' => $courseCount,
            'cloudinary_service' => class_exists('\App\Services\CloudinaryService') ? 'available' : 'not found',
            'status' => 'success'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'status' => 'error'
        ]);
    }
});

Route::get('/', function () {
    return view('homepage');
});

// Halaman tambahan (jika pakai blade)
Route::get('/produk', function () {
    return view('produk');
});

Route::get('/tentang', function () {
    return view('tentang');
});

// Route fallback untuk semua frontend SPA (Vue)
Route::get('/{any}', function () {
    return view('homepage');
})->where('any', '^(?!api|admin|livewire).*$');

// Auth (kalau ini ditujukan untuk frontend, lebih baik dipindah ke api.php)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Comment out admin routes yang konflik dengan Filament
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
Route::resource('users', UserProfileController::class);
});

Route::get('/login', function () {
    return response()->json([
        'message' => 'This is an API microservice. Please use frontend login.'
    ]);
})->name('login');

Route::middleware('auth:sanctum')->get('/debug/quick-fix', function(Request $request) {
    $user = $request->user();

    // Check semua data mentah
    return response()->json([
        'user_id' => $user->id,
        'payments' => DB::table('payments')->where('user_profile_id', $user->id)->get(),
        'courses' => DB::table('courses')->get(),
        'course_desc_table_exists' => Schema::hasTable('course_description'),
        'course_descs_table_exists' => Schema::hasTable('course_descriptions'),
    ]);
});
