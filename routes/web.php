<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListAsetController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AccessManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
    Route::get('/dashboard/card-detail/{key}', [DashboardController::class, 'cardDetail'])->name('dashboard.card-detail');

    // User Management - berdasarkan permission
    Route::middleware('permission:view-user')->group(function () {
        Route::get('/user', [AuthController::class, 'user'])->name('user');
        Route::get('/detail-{id}', [AuthController::class, 'detailUser'])->name('user.detail');
    });
    Route::middleware('permission:create-user')->group(function () {
        Route::get('register', [AuthController::class, 'showregistrationform'])->name('register');
        Route::post('register', [AuthController::class, 'register']);
    });
    Route::middleware('permission:edit-user')->group(function () {
        Route::get('/edit-{id}', [AuthController::class, 'EditUser'])->name('user.edit');
        Route::post('/user/updateuser', [AuthController::class, 'updateuser'])->name('user.updateuser');
    });
    Route::middleware('permission:delete-user')->group(function () {
        Route::post('/user/delete', [AuthController::class, 'delete'])->name('user.delete');
    });

    // Access Management - untuk user yang punya akses kelola user (misalnya admin)
    Route::middleware('role:Admin')->group(function () {
        Route::get('/access-management', [AccessManagementController::class, 'index'])->name('access-management.index');
        Route::get('/access-management/logs', [AccessManagementController::class, 'logs'])->name('access-management.logs');
        Route::put('/access-management/{id}/role', [AccessManagementController::class, 'updateRole'])->name('access-management.update-role');
        Route::put('/access-management/{id}/permissions', [AccessManagementController::class, 'updatePermissions'])->name('access-management.update-permissions');
        Route::get('/access-management/{id}/permissions', [AccessManagementController::class, 'getUserPermissions'])->name('access-management.get-permissions');
        Route::post('/access-management/{id}/copy-role', [AccessManagementController::class, 'copyFromRole'])->name('access-management.copy-role');
    });

    // Edit Profil User (oleh Admin/PIC - mengedit data user lain)
    Route::middleware('role:Admin')->group(function () {
        Route::get('/user/profil/{id}', [AuthController::class, 'profil'])->name('user.profil');
        Route::post('/user/updateprofil', [AuthController::class, 'updateprofil'])->name('user.updateprofil');
    });

    // Aset Management - Admin
    Route::middleware('permission:view-aset')->group(function () {
        Route::get('/aset', [ListAsetController::class, 'index'])->name('aset.index');
        Route::get('/aset/detail/{id}', [ListAsetController::class, 'detail'])->name('aset.detail');
    });
    Route::middleware('permission:create-aset')->group(function () {
        Route::get('/aset/create', [ListAsetController::class, 'create'])->name('aset.create');
        Route::post('/aset/store', [ListAsetController::class, 'store'])->name('aset.store');
    });
    Route::middleware('permission:edit-aset')->group(function () {
        Route::get('/aset/edit/{id}', [ListAsetController::class, 'edit'])->name('aset.edit');
        Route::put('/aset/update/{id}', [ListAsetController::class, 'update'])->name('aset.update');
    });
    Route::middleware('permission:delete-aset')->group(function () {
        Route::delete('/aset/delete/{id}', [ListAsetController::class, 'delete'])->name('aset.delete');
    });

    
    // Form Pengajuan - berdasarkan permission
    Route::middleware('permission:create-pengajuan')->group(function () {
        Route::get('/pengajuan/create', [PengajuanController::class, 'createUmum'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    });
    Route::middleware('permission:edit-pengajuan')->group(function () {
        Route::get('/pengajuan/{id}/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
        Route::put('/pengajuan/{id}', [PengajuanController::class, 'update'])->name('pengajuan.update');
    });
    Route::middleware('permission:upload-pengajuan')->group(function () {
        Route::get('/pengajuan/{id}/upload', [PengajuanController::class, 'showUploadForm'])->name('pengajuan.upload');
        Route::post('/pengajuan/{id}/upload', [PengajuanController::class, 'upload'])->name('pengajuan.upload.post');
    });
    Route::middleware('permission:selesai-pengajuan')->group(function () {
        Route::get('/pengajuan/{id}/selesai', [PengajuanController::class, 'showSelesaiForm'])->name('pengajuan.selesai');
        Route::post('/pengajuan/{id}/selesai', [PengajuanController::class, 'selesai'])->name('pengajuan.selesai.post');
    });
    Route::middleware('permission:cancel-pengajuan')->group(function () {
        Route::get('/pengajuan/{id}/cancel', [PengajuanController::class, 'cancel'])->name('pengajuan.cancel');
        Route::post('/pengajuan/{id}/cancel', [PengajuanController::class, 'cancel'])->name('pengajuan.cancel.post');
    });
    Route::middleware('permission:view-pengajuan')->group(function () {
        Route::get('/pengajuan/list', [PengajuanController::class, 'list'])->name('pengajuan.list');
        Route::get('/pengajuan/{id}/detail', [PengajuanController::class, 'detail'])->name('pengajuan.detail');
        Route::get('/pengajuan/{id}/export-pdf', [PengajuanController::class, 'exportPdf'])->name('pengajuan.export-pdf');
    });
    

    // Route Approval - admin dan PIC//
    Route::middleware('permission:view-approval')->group(function () {
        Route::get('/approval', [ApprovalController::class, 'index'])->name('approval.index');
        Route::get('/approval/{id}/detail', [ApprovalController::class, 'detail'])->name('approval.detail');
    });
    Route::middleware('permission:approve-pengajuan')->group(function () {
        Route::put('/approval/{id}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
    });
    Route::middleware('permission:reject-pengajuan')->group(function () {
        Route::put('/approval/{id}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
    });
    
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

});
