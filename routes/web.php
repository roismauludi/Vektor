<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListAsetController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ApprovalController;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('register', [AuthController::class, 'showregistrationform'])->name('register');
Route::post('register', [AuthController::class, 'register']);

// Route User Management
Route::get('/user', [AuthController::class, 'user'])->name('user');
Route::get('/detail-{id}', [AuthController::class, 'detailUser'])->name('user.detail');
Route::get('/edit-{id}', [AuthController::class, 'EditUser'])->name('user.edit');
Route::post('/user/updateuser', [AuthController::class, 'updateuser'])->name('user.updateuser');
Route::get('/user/profil', [AuthController::class, 'profil'])->name('user.profil');
Route::post('/user/updateprofil', [AuthController::class, 'updateprofil'])->name('user.updateprofil');
Route::post('/user/delete', [AuthController::class, 'delete'])->name('user.delete');

// Route Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route To
Route::get('/aset', [ListAsetController::class, 'index'])->name('aset.index');
Route::get('/aset/detail/{id}', [ListAsetController::class, 'detail'])->name('aset.detail');
Route::get('/aset/create', [ListAsetController::class, 'create'])->name('aset.create');
Route::get('/aset/list', [ListAsetController::class, 'list'])->name('aset.list');
Route::post('/aset/store', [ListAsetController::class, 'store'])->name('aset.store');
Route::get('/aset/edit/{id}', [ListAsetController::class, 'edit'])->name('aset.edit');
Route::put('/aset/update/{id}', [ListAsetController::class, 'update'])->name('aset.update');
Route::delete('/aset/delete/{id}', [ListAsetController::class, 'delete'])->name('aset.delete');



// Route Log Aktivitas
Route::get('/logaktivitas', [LogController::class, 'logaktivitas'])->name('logaktivitas');

// Form Pengajuan
Route::get('/pengajuan/tower', fn() => app(PengajuanController::class)->create('tower'))->name('pengajuan.tower');
Route::get('/pengajuan/central', fn() => app(PengajuanController::class)->create('central'))->name('pengajuan.central');
Route::get('/pengajuan/list', [PengajuanController::class, 'list'])->name('pengajuan.list');
Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
Route::get('/pengajuan/{id}/detail', [PengajuanController::class, 'detail'])->name('pengajuan.detail');
Route::get('/pengajuan/{id}/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
Route::put('/pengajuan/{id}', [PengajuanController::class, 'update'])->name('pengajuan.update');
Route::get('/pengajuan/{id}/upload', [PengajuanController::class, 'showUploadForm'])->name('pengajuan.upload');
Route::post('/pengajuan/{id}/upload', [PengajuanController::class, 'upload'])->name('pengajuan.upload.post');
Route::get('/pengajuan/{id}/selesai', [PengajuanController::class, 'showSelesaiForm'])->name('pengajuan.selesai');
Route::post('/pengajuan/{id}/selesai', [PengajuanController::class, 'selesai'])->name('pengajuan.selesai.post');


// Route Approval
Route::get('/approval', [ApprovalController::class, 'index'])->name('approval.index');
Route::get('/approval/{id}/detail', [ApprovalController::class, 'detail'])->name('approval.detail');
Route::put('/approval/{id}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
Route::put('/approval/{id}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');

});