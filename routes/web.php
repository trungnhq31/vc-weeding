<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\GroundedAiController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PlannerDashboardController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\WeddingController;
use App\Http\Controllers\WeddingTimelineController;
use App\Http\Controllers\WishController;
use Illuminate\Support\Facades\Route;

// Auth & Onboarding Setup Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding.index');
Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

// Master List / Catalog of Online Invitations & Sub-routes
Route::get('/invitations', [PortfolioController::class, 'index'])->name('invitations.index');
Route::get('/invitations/{template_slug}', [WeddingController::class, 'index'])->name('invitations.show');
Route::get('/invitations/{template_slug}/guest/{guest_slug}', [WeddingController::class, 'invitation'])->name('invitations.guest');

// Public Collaborative Guest List Share Routes
Route::get('/wedding/share-guest-list/{token}', [WeddingController::class, 'showSharedGuestList'])->name('wedding.share_guest_list');
Route::post('/wedding/share-guest-list/{token}/add', [WeddingController::class, 'storeSharedGuest'])->name('wedding.store_shared_guest');

// Subpath & Subdomain Wedding Routes (Supports both localhost:8085 and custom subdomains)
$weddingRoutes = function () {
    Route::get('/', [WeddingController::class, 'index'])->name('wedding.index');
    Route::get('/timeline', [WeddingTimelineController::class, 'index'])->name('wedding.timeline');
    Route::post('/tasks/{task}/toggle', [WeddingTimelineController::class, 'toggleTask'])->name('wedding.tasks.toggle');
    Route::post('/tasks/{task}/details', [WeddingTimelineController::class, 'updateTaskDetails'])->name('wedding.tasks.details');
    Route::post('/tasks/{task}/upload-image', [WeddingTimelineController::class, 'uploadTaskImage'])->name('wedding.tasks.upload_image');
    Route::post('/tasks/{task}/delete-image', [WeddingTimelineController::class, 'deleteTaskImage'])->name('wedding.tasks.delete_image');
    Route::post('/tasks/{task}/execute-action', [WeddingTimelineController::class, 'executeTaskAction'])->name('wedding.tasks.execute_action');
    Route::get('/tasks/{task}/ai-recommendation', [WeddingTimelineController::class, 'getTaskAiRecommendation'])->name('wedding.tasks.ai_recommendation');
    Route::post('/milestones/{milestone}/auto-complete-ai', [WeddingTimelineController::class, 'autoCompleteMilestoneAi'])->name('wedding.milestones.auto_complete_ai');

    // Vendor CRM & Grounded AI Assistant
    Route::get('/vendors', [VendorController::class, 'index'])->name('wedding.vendors.index');
    Route::post('/vendors', [VendorController::class, 'store'])->name('wedding.vendors.store');
    Route::post('/vendors/{vendor}/payment', [VendorController::class, 'recordPayment'])->name('wedding.vendors.payment');
    Route::post('/ai-query', [GroundedAiController::class, 'query'])->name('wedding.ai.query');
    Route::post('/ai-personalize-timeline', [WeddingTimelineController::class, 'aiPersonalizeTimeline'])->name('wedding.ai.personalize');

    // Planning Modules
    Route::get('/budget', [WeddingController::class, 'budget'])->name('wedding.budget');
    Route::post('/budget/select-venue', [WeddingController::class, 'selectVenue'])->name('wedding.budget.select_venue');
    Route::get('/budget/export', [WeddingController::class, 'exportBudget'])->name('wedding.budget.export');
    Route::get('/guests', [WeddingController::class, 'guests'])->name('wedding.guests');
    Route::get('/guests/export', [WeddingController::class, 'exportGuests'])->name('wedding.guests.export');
    Route::get('/documents', [WeddingController::class, 'documents'])->name('wedding.documents');
    Route::get('/invitation-editor', [WeddingController::class, 'invitationEditor'])->name('wedding.invitation_editor');
    Route::post('/invitation-editor/save', [WeddingController::class, 'saveInvitationCms'])->name('wedding.invitation_editor.save');
    Route::get('/settings', [WeddingController::class, 'settings'])->name('wedding.settings');
    Route::get('/planner-dashboard', [PlannerDashboardController::class, 'index'])->name('wedding.planner_dashboard');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('wedding.subscription.upgrade');

    Route::get('/invitation/{guest_slug}', [WeddingController::class, 'invitation'])->name('wedding.invitation');
    Route::post('/rsvp', [RsvpController::class, 'store'])->name('wedding.rsvp.store');
    Route::post('/wishes', [WishController::class, 'store'])->name('wedding.wishes.store');
    Route::post('/memories/upload', [WeddingController::class, 'uploadMemory'])->name('wedding.memories.upload');
};

// Always register prefix /wedding for local dev & fallback
Route::prefix('wedding')->group($weddingRoutes);

// Optional Subdomain registration if explicit subdomain is set in env
if ($subdomain = config('app.wedding_subdomain')) {
    Route::domain($subdomain)->group($weddingRoutes);
}

// Main Domain Eloria Wedding OS Routes (Public Marketing & Dynamic Sitemap)
Route::get('/', [LandingController::class, 'index'])->name('eloria.home');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Dynamic SEO Engine Routes
Route::get('/sitemap.xml', [SitemapController::class, 'sitemapXml'])->name('sitemap.xml');
Route::get('/robots.txt', [SitemapController::class, 'robotsTxt'])->name('robots.txt');
