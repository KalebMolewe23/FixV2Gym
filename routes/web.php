<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\TrainerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportMember;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\WablasController;
use App\Http\Controllers\Admin\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('maintenance');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::get('/InformationMember/{id}', [MemberController::class, 'show'])->name('information.member');
Route::post('/send-messages', [WablasController::class, 'sendMessages']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/data_member', [MemberController::class, 'index'])->name('data_member');
    Route::get('/add_member', [MemberController::class, 'add_member'])->name('add_member');
    Route::post('/save_member', [MemberController::class, 'store'])->name('members.store');
    Route::post('/save_smember', [MemberController::class, 'tambah_dataMember'])->name('members.add_smember');
    Route::get('/get-states', [MemberController::class, 'getStates'])->name('get.states');
    Route::get('/get-cities', [MemberController::class, 'getCities'])->name('get.cities');
    Route::put('/members/update/{id}', [MemberController::class, 'update'])->name('members.update');
    Route::get('/admin/editmembers/{id}', [MemberController::class, 'getMember'])->name('members.editmember');
    Route::get('/admin/members', [MemberController::class, 'search'])->name('members.index');
    Route::get('/admin/cetak/{id}', [MemberController::class, 'cetakBarcode'])->name('members.cetakBarcode');
    Route::delete('/admin/members/{id}', [MemberController::class, 'destroy'])->name('members.destroy');

    Route::get('/data_trainer', [TrainerController::class, 'index'])->name('data_trainer');
    Route::get('/add_trainer', [TrainerController::class, 'add_Trainer'])->name('add_trainer');
    Route::post('/save_trainer', [TrainerController::class, 'store'])->name('trainers.store');
    Route::get('/admin/trainers/schedule-data', [TrainerController::class, 'getScheduleData'])->name('trainers.scheduleData');
    Route::get('/schedule_trainer', [TrainerController::class, 'scheduleTrainer'])->name('schedule');
    Route::get('/trainers/schedule/{id}', [TrainerController::class, 'getSchedule'])->name('trainers.getSchedule');
    Route::post('/store-schedule', [TrainerController::class, 'storeSchedule'])->name('trainers.storeSchedule');
    Route::get('/schedule/detail/{iduser}', [TrainerController::class, 'getScheduleDetail'])->name('trainers.detailschedule');
    Route::put('/schedule/detail/{id}', [TrainerController::class, 'updateScheduleDetail'])->name('trainers.updateschedule');
    Route::delete('/schedule/delete-schedule/{id}', [TrainerController::class, 'deleteSchedule'])->name('trainers.deleteschedule');

    Route::get('/report_day', [ReportMember::class, 'reportDays'])->name('report_day');
    Route::get('/report_memberactive', [ReportMember::class, 'reportmemberActive'])->name('report_memberactive');
    Route::get('/report_membernonactive', [ReportMember::class, 'reportmembernonActive'])->name('report_membernonactive');
    Route::get('/report_allmember', [ReportMember::class, 'reportmemberDays'])->name('report_allmember');
    
});

// Api
Route::post('/api/member-data', [MemberController::class, 'memberData']);

Route::middleware(['auth', 'role:trainer'])->get('/trainer/dashboard', function () {
    return view('trainer.dashboard'); // Arahkan ke tampilan trainer.dashboard
})->name('trainer.dashboard');

Route::middleware(['auth'])->post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Rute untuk profil pengguna
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/roles.php';
require __DIR__.'/cms.php';