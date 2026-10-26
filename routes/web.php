<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;

// Phía Khách & Người dùng (Public)
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Chức năng bình luận (Bắt buộc đăng nhập)
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');

Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->middleware('auth')
    ->name('comments.destroy');

// Route Dashboard mặc định của Breeze (Điều hướng theo Role)
Route::get('/dashboard', function () {
    if (Auth::user()?->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

// Route Quản lý tài khoản cá nhân (Profile của Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Khu vực Quản trị Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Loại bỏ show để tránh lỗi Runtime
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Quản lý bài viết
    Route::get('/posts', [PostController::class, 'adminIndex'])->name('posts.index');
    Route::patch('/posts/{post}/status', [PostController::class, 'updateStatus'])->name('posts.status');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/my-posts', [PostController::class, 'userPosts'])->name('my.posts');
    Route::get('/my-posts/create', [PostController::class, 'userCreate'])->name('my.posts.create');
    Route::post('/my-posts', [PostController::class, 'userStore'])->name('my.posts.store');
    Route::get('/my-posts/{post}/edit', [PostController::class, 'userEdit'])->name('my.posts.edit');
    Route::put('/my-posts/{post}', [PostController::class, 'userUpdate'])->name('my.posts.update');
});

require __DIR__.'/auth.php';
