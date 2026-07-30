<?php

declare(strict_types=1);

use App\Http\Controllers\BlogController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\WeddingController;
use App\Http\Controllers\WeddingTimelineController;
use App\Http\Controllers\WishController;
use Illuminate\Support\Facades\Route;

// Subdomain Wedding Routes (e.g. wedding.vcwedding.test)
Route::domain(config('app.wedding_subdomain', 'wedding.vcwedding.test'))->group(function () {
    Route::get('/', [WeddingController::class, 'index'])->name('wedding.index');
    Route::get('/timeline', [WeddingTimelineController::class, 'index'])->name('wedding.timeline');
    Route::post('/tasks/{task}/toggle', [WeddingTimelineController::class, 'toggleTask'])->name('wedding.tasks.toggle');
    Route::post('/tasks/{task}/details', [WeddingTimelineController::class, 'updateTaskDetails'])->name('wedding.tasks.details');
    Route::post('/tasks/{task}/upload-image', [WeddingTimelineController::class, 'uploadTaskImage'])->name('wedding.tasks.upload_image');
    Route::post('/tasks/{task}/delete-image', [WeddingTimelineController::class, 'deleteTaskImage'])->name('wedding.tasks.delete_image');
    Route::get('/{guest_slug}', [WeddingController::class, 'invitation'])->name('wedding.invitation');
    Route::post('/rsvp', [RsvpController::class, 'store'])->name('wedding.rsvp.store');
    Route::post('/wishes', [WishController::class, 'store'])->name('wedding.wishes.store');
    Route::post('/memories/upload', [WeddingController::class, 'uploadMemory'])->name('wedding.memories.upload');
});

// Fallback Subpath Routes for Wedding
Route::prefix('wedding')->group(function () {
    Route::get('/', [WeddingController::class, 'index']);
    Route::get('/timeline', [WeddingTimelineController::class, 'index']);
    Route::post('/tasks/{task}/toggle', [WeddingTimelineController::class, 'toggleTask']);
    Route::post('/tasks/{task}/details', [WeddingTimelineController::class, 'updateTaskDetails']);
    Route::post('/tasks/{task}/upload-image', [WeddingTimelineController::class, 'uploadTaskImage']);
    Route::post('/tasks/{task}/delete-image', [WeddingTimelineController::class, 'deleteTaskImage']);
    Route::get('/invitation/{guest_slug}', [WeddingController::class, 'invitation']);
    Route::post('/rsvp', [RsvpController::class, 'store']);
    Route::post('/wishes', [WishController::class, 'store']);
    Route::post('/memories/upload', [WeddingController::class, 'uploadMemory']);
});

// Main Domain Eloria Wedding OS Routes
Route::get('/', [WeddingController::class, 'index'])->name('eloria.home');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

