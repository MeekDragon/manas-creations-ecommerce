<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\AuthController;

// ── Public Routes ──────────────────────────────────
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/inquiry', [InquiryController::class, 'store'])->middleware('email.verified')->name('inquiry.store');

// ── Authentication ─────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ── Password Reset ─────────────────────────────────
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Firebase Auth ID token callback
Route::post('/auth/firebase/callback', [AuthController::class, 'handleFirebaseCallback'])->name('auth.firebase.callback');

Route::get('/verify-email', [AuthController::class, 'showVerifyEmail'])->name('verification.notice');
Route::post('/verify-email/resend', [AuthController::class, 'resendEmailOtp'])->name('verification.resend');
Route::get('/verify-email/status', [AuthController::class, 'checkVerificationStatus'])->name('verification.status');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmailLink'])->middleware(['signed'])->name('verification.verify.email');
Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationEmail'])->middleware(['auth', 'throttle:6,1'])->name('verification.send.email');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Admin Panel (protected) ────────────────────────
Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/trash', [AdminController::class, 'usersTrash'])->name('users.trash');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::post('/users/{id}/restore', [AdminController::class, 'restoreUser'])->name('users.restore');
    Route::delete('/users/{id}/force-delete', [AdminController::class, 'forceDeleteUser'])->name('users.force-delete');

    // ── Admins CRUD (Super Admin Only) ──────────────────
    Route::get('/admins', [AdminController::class, 'adminsIndex'])->name('admins.index');
    Route::get('/admins/trash', [AdminController::class, 'adminsTrash'])->name('admins.trash');
    Route::get('/admins/create', [AdminController::class, 'adminsCreate'])->name('admins.create');
    Route::post('/admins', [AdminController::class, 'adminsStore'])->name('admins.store');
    Route::get('/admins/{user}/edit', [AdminController::class, 'adminsEdit'])->name('admins.edit');
    Route::put('/admins/{user}', [AdminController::class, 'adminsUpdate'])->name('admins.update');
    Route::delete('/admins/{user}', [AdminController::class, 'adminsDestroy'])->name('admins.destroy');
    Route::post('/admins/{id}/restore', [AdminController::class, 'adminsRestore'])->name('admins.restore');
    Route::delete('/admins/{id}/force-delete', [AdminController::class, 'adminsForceDelete'])->name('admins.force-delete');

    Route::get('/inquiries', [AdminController::class, 'inquiries'])->name('inquiries');
    Route::get('/inquiries/trash', [InquiryController::class, 'trash'])->name('inquiries.trash');
    Route::patch('/inquiries/{inquiry}/toggle', [InquiryController::class, 'toggleStatus'])->name('inquiries.toggle');
    Route::patch('/inquiries/{inquiry}/resolve', [InquiryController::class, 'resolveWithResponse'])->name('inquiries.resolve');
    Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');
    Route::post('/inquiries/{id}/restore', [InquiryController::class, 'restore'])->name('inquiries.restore');
    Route::delete('/inquiries/{id}/force-delete', [InquiryController::class, 'forceDelete'])->name('inquiries.force-delete');

    // ── Categories CRUD ───────────────────────────
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');

    // ── Products CRUD ─────────────────────────────
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/trash', [AdminController::class, 'productsTrash'])->name('products.trash');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('/products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');
    Route::post('/products/upload-image', [ProductController::class, 'uploadImage'])->name('products.upload-image');
});
