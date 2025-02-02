<?php

use App\Http\Controllers\Admin\CMSController;

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('layout', [CMSController::class, 'layout']);
    Route::post('/cms/store', [CmsController::class, 'store'])->name('cms.store');
});
