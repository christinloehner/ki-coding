<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Wiki\WikiController;
use App\Http\Controllers\Wiki\ArticleController;
use App\Http\Controllers\Wiki\CategoryController;
use App\Http\Controllers\Wiki\CommentController;
use App\Http\Controllers\Wiki\SearchController;
use App\Http\Controllers\Wiki\MarkdownController;
use App\Http\Controllers\Wiki\TagController;
use App\Http\Controllers\Wiki\UserController;
use App\Http\Controllers\Wiki\ModerationController;
use App\Http\Controllers\Wiki\ReportController;
use App\Http\Controllers\Wiki\InvitationController;
use App\Http\Controllers\Wiki\FileUploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Homepage und ursprüngliche Seiten
Route::get('/', function () {
    return view('pages.home');
})->name('home');


Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/imprint', function () {
    return view('pages.imprint');
})->name('imprint');

// Kontaktformular
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Sitemap (dynamisch generiert)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/bookmarks', [DashboardController::class, 'bookmarks'])->middleware(['auth', 'verified'])->name('dashboard.bookmarks');
Route::get('/dashboard/api-tokens', [ApiTokenController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard.api-tokens');
Route::get('/dashboard/api-tokens/index', [ApiTokenController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard.api-tokens.index');
Route::post('/dashboard/api-tokens', [ApiTokenController::class, 'store'])->middleware(['auth', 'verified'])->name('dashboard.api-tokens.store');
Route::delete('/dashboard/api-tokens/{id}', [ApiTokenController::class, 'destroy'])->middleware(['auth', 'verified'])->name('dashboard.api-tokens.destroy');

// API Dokumentation Route
Route::get('/dashboard/api-documentation', function () {
    return view('dashboard.api-documentation');
})->middleware(['auth', 'verified'])->name('dashboard.api-documentation');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Extended Profile Management
    Route::get('/profile/edit-profile', [ProfileController::class, 'editProfile'])->name('profile.edit-profile');
    Route::post('/profile/update-profile', [ProfileController::class, 'updateProfile'])->name('profile.update-profile');
    Route::post('/profile/update-privacy', [ProfileController::class, 'updatePrivacy'])->name('profile.update-privacy');
    Route::post('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.upload-avatar');
    Route::delete('/profile/remove-avatar', [ProfileController::class, 'removeAvatar'])->name('profile.remove-avatar');
});

// Public Profile Routes
Route::get('/user/{user:username}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/user/{user}', [ProfileController::class, 'show'])->name('profile.show.id')->where('user', '[0-9]+');

/*
|--------------------------------------------------------------------------
| Wiki Routes
|--------------------------------------------------------------------------
|
| All wiki-related routes with proper middleware and security measures
|
*/

// Public Wiki Routes (no authentication required)
Route::prefix('wiki')->name('wiki.')->group(function () {
    
    // Wiki Home Route - Public
    Route::get('/', [WikiController::class, 'index'])->name('index');
    
    // Article Routes - Public
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/article/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/tag/{tag:slug}', [TagController::class, 'show'])->name('tags.show');
    
    // Search Routes
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::post('/search', [SearchController::class, 'index'])->name('search.results');
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
    
    // Markdown Preview (needs auth for security)
    Route::middleware(['auth', 'throttle:markdown'])->group(function () {
        Route::post('/markdown/preview', [MarkdownController::class, 'preview'])->name('markdown.preview');
        Route::get('/markdown/editor', [MarkdownController::class, 'editor'])->name('markdown.editor');
    });
    
    // Categories - Public listing
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    
    // User Profiles - Public
    Route::get('/user/{user:username}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    
    // Invitation System - Public accept
    Route::get('/invitation/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitation/{token}', [InvitationController::class, 'register'])->name('invitations.register');
});

// Authenticated Wiki Routes
Route::prefix('wiki')->name('wiki.')->middleware(['auth', 'verified', 'wiki.security', 'check.ban'])->group(function () {
    
    // Article Management
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/create', [ArticleController::class, 'create'])->name('create')->middleware('can:create articles');
        Route::post('/', [ArticleController::class, 'store'])->name('store')->middleware('can:create articles');
        Route::get('/{article:slug}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{article:slug}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{article:slug}', [ArticleController::class, 'destroy'])->name('destroy');
        
        // Deletion request workflow
        Route::post('/{article:slug}/request-deletion', [ArticleController::class, 'requestDeletion'])->name('request-deletion');
        Route::delete('/{article:slug}/cancel-deletion', [ArticleController::class, 'cancelDeletionRequest'])->name('cancel-deletion');
        Route::post('/{article:slug}/approve-deletion', [ArticleController::class, 'approveDeletion'])->name('approve-deletion');
        Route::post('/{article:slug}/deny-deletion', [ArticleController::class, 'denyDeletion'])->name('deny-deletion');
        
        // Article Actions
        Route::post('/{article:slug}/like', [ArticleController::class, 'toggleLike'])->name('like');
        Route::post('/{article:slug}/bookmark', [ArticleController::class, 'toggleBookmark'])->name('bookmark');
        Route::post('/{article:slug}/vote', [ArticleController::class, 'vote'])->name('vote');
        Route::post('/{article:slug}/report', [ArticleController::class, 'report'])->name('report');
        Route::post('/{article:slug}/subscribe', [ArticleController::class, 'toggleSubscription'])->name('subscribe');
        
        // Article Status Management (for authors and moderators)
        Route::patch('/{article:slug}/status', [ArticleController::class, 'updateStatus'])->name('status');
        Route::patch('/{article:slug}/feature', [ArticleController::class, 'toggleFeatured'])->name('feature');
        
        // Article Revisions
        Route::get('/{article:slug}/revisions', [ArticleController::class, 'revisions'])->name('revisions');
        Route::get('/{article:slug}/revisions/{revision}', [ArticleController::class, 'showRevision'])->name('revisions.show');
        Route::post('/{article:slug}/revisions/{revision}/restore', [ArticleController::class, 'restoreRevision'])->name('revisions.restore');
    });
    
    // Tag Search API
    Route::get('/tags/search', [\App\Http\Controllers\Wiki\TagController::class, 'search'])->name('tags.search');
    
    // Comment Management
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::post('/', [CommentController::class, 'store'])->name('store');
        Route::get('/{comment}/edit', [CommentController::class, 'edit'])->name('edit');
        Route::put('/{comment}', [CommentController::class, 'update'])->name('update');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
        
        // Comment Actions
        Route::post('/{comment}/like', [CommentController::class, 'toggleLike'])->name('like');
        Route::post('/{comment}/report', [CommentController::class, 'report'])->name('report');
        
        // Comment Moderation
        Route::patch('/{comment}/approve', [CommentController::class, 'approve'])->name('approve');
        Route::patch('/{comment}/reject', [CommentController::class, 'reject'])->name('reject');
    });
    
    // User Dashboard and Settings
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [UserController::class, 'dashboard'])->name('index');
        Route::get('/articles', [UserController::class, 'articles'])->name('articles');
        Route::get('/comments', [UserController::class, 'comments'])->name('comments');
        Route::get('/bookmarks', [UserController::class, 'bookmarks'])->name('bookmarks');
        Route::get('/subscriptions', [UserController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/settings', [UserController::class, 'settings'])->name('settings');
        Route::patch('/settings', [UserController::class, 'updateSettings'])->name('settings.update');
    });
    
    // Reporting System
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::post('/article/{article}', [ReportController::class, 'reportArticle'])->name('article');
        Route::post('/comment/{comment}', [ReportController::class, 'reportComment'])->name('comment');
        Route::post('/user/{user}', [ReportController::class, 'reportUser'])->name('user');
    });
});

// Category Management (Permission-based)
Route::prefix('wiki/categories')->name('wiki.categories.')->middleware(['auth', 'verified', 'wiki.security', 'check.ban'])->group(function () {
    Route::get('/create', [CategoryController::class, 'create'])->name('create')->middleware('can:create categories');
    Route::post('/', [CategoryController::class, 'store'])->name('store')->middleware('can:create categories');
    Route::get('/{category:slug}/edit', [CategoryController::class, 'edit'])->name('edit')->middleware('can:edit categories');
    Route::put('/{category:slug}', [CategoryController::class, 'update'])->name('update')->middleware('can:edit categories');
    Route::delete('/{category:slug}', [CategoryController::class, 'destroy'])->name('destroy')->middleware('can:delete categories');
});

// Tag Management (Permission-based)
Route::prefix('wiki/tags')->name('wiki.tags.')->middleware(['auth', 'verified', 'wiki.security', 'check.ban'])->group(function () {
    Route::get('/create', [TagController::class, 'create'])->name('create')->middleware('can:create tags');
    Route::post('/', [TagController::class, 'store'])->name('store')->middleware('can:create tags');
    Route::get('/{tag:slug}/edit', [TagController::class, 'edit'])->name('edit')->middleware('can:edit tags');
    Route::put('/{tag:slug}', [TagController::class, 'update'])->name('update')->middleware('can:edit tags');
    Route::delete('/{tag:slug}', [TagController::class, 'destroy'])->name('destroy')->middleware('can:delete tags');
});

// Moderation Routes (Moderators and above)
Route::prefix('wiki/moderation')->name('wiki.moderation.')->middleware(['auth', 'verified', 'wiki.security', 'check.ban', 'permission:moderate content'])->group(function () {
    
    // Moderation Dashboard
    Route::get('/', [ModerationController::class, 'dashboard'])->name('dashboard');
    
    // Article Moderation
    Route::get('/articles', [ModerationController::class, 'articles'])->name('articles');
    Route::get('/articles/pending', [ModerationController::class, 'pendingArticles'])->name('articles.pending');
    Route::patch('/articles/{article}/approve', [ModerationController::class, 'approveArticle'])->name('articles.approve');
    Route::patch('/articles/{article}/reject', [ModerationController::class, 'rejectArticle'])->name('articles.reject');
    
    // Comment Moderation
    Route::get('/comments', [ModerationController::class, 'comments'])->name('comments');
    Route::get('/comments/flagged', [ModerationController::class, 'flaggedComments'])->name('comments.flagged');
    Route::patch('/comments/{comment}/approve', [ModerationController::class, 'approveComment'])->name('comments.approve');
    Route::patch('/comments/{comment}/reject', [ModerationController::class, 'rejectComment'])->name('comments.reject');
    
    // Reports Management
    Route::get('/reports', [ModerationController::class, 'reports'])->name('reports');
    Route::get('/reports/{report}', [ModerationController::class, 'showReport'])->name('reports.show');
    Route::patch('/reports/{report}/resolve', [ModerationController::class, 'resolveReport'])->name('reports.resolve');
    Route::patch('/reports/{report}/dismiss', [ModerationController::class, 'dismissReport'])->name('reports.dismiss');
    
    // User Moderation
    Route::get('/users', [ModerationController::class, 'users'])->name('users');
    Route::patch('/users/{user}/ban', [ModerationController::class, 'banUser'])->name('users.ban');
    Route::patch('/users/{user}/unban', [ModerationController::class, 'unbanUser'])->name('users.unban');
    Route::patch('/users/{user}/warn', [ModerationController::class, 'warnUser'])->name('users.warn');
});

// Admin Routes (Admins and Moderators)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'wiki.security', 'check.ban'])->group(function () {
    
    // User Management Routes (Admins and Moderators)
    Route::middleware('can:view users')->group(function () {
        Route::get('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'show'])->name('users.show');
        
        // Role Management (Admins and Moderators with assign roles permission)
        Route::post('/users/{user}/assign-role', [\App\Http\Controllers\Admin\UserManagementController::class, 'assignRole'])->name('users.assign-role');
        Route::delete('/users/{user}/remove-role', [\App\Http\Controllers\Admin\UserManagementController::class, 'removeRole'])->name('users.remove-role');
        Route::post('/users/assign-role', [\App\Http\Controllers\Admin\UserManagementController::class, 'assignRole'])->name('users.assign-role-bulk');
        Route::delete('/users/remove-role', [\App\Http\Controllers\Admin\UserManagementController::class, 'removeRole'])->name('users.remove-role-bulk');
        
        // User Banning (Admins and Moderators with ban permission)
        Route::post('/users/{user}/ban', [\App\Http\Controllers\Admin\UserManagementController::class, 'ban'])->name('users.ban');
        Route::post('/users/{user}/unban', [\App\Http\Controllers\Admin\UserManagementController::class, 'unban'])->name('users.unban');
        
        // User Deletion (Admins only with delete permission)
        Route::get('/users/{user}/delete', [\App\Http\Controllers\Admin\UserManagementController::class, 'confirmDelete'])->name('users.confirm-delete');
        Route::delete('/users/{user}/delete', [\App\Http\Controllers\Admin\UserManagementController::class, 'delete'])->name('users.delete');
    });
    
    // Article Management Routes (Admins and Moderators)
    Route::middleware('can:edit all articles')->group(function () {
        Route::get('/articles', [\App\Http\Controllers\Admin\ArticleManagementController::class, 'index'])->name('articles.index');
        Route::post('/articles/bulk-update-status', [\App\Http\Controllers\Admin\ArticleManagementController::class, 'bulkUpdateStatus'])->name('articles.bulk-update-status');
        Route::post('/articles/bulk-delete', [\App\Http\Controllers\Admin\ArticleManagementController::class, 'bulkDelete'])->name('articles.bulk-delete');
        Route::post('/articles/{article}/update-status', [\App\Http\Controllers\Admin\ArticleManagementController::class, 'updateStatus'])->name('articles.update-status');
        Route::delete('/articles/{article}/admin-delete', [\App\Http\Controllers\Admin\ArticleManagementController::class, 'destroy'])->name('articles.destroy');
        Route::get('/articles/deletion-requests', [\App\Http\Controllers\Admin\ArticleManagementController::class, 'deletionRequests'])->name('articles.deletion-requests');
    });
    
    // Role Management Routes (Admins only)
    Route::middleware('permission:admin access')->group(function () {
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
        Route::get('roles/{role}/permissions', [\App\Http\Controllers\Admin\RoleController::class, 'permissions'])->name('roles.permissions');
        Route::post('roles/{role}/permissions', [\App\Http\Controllers\Admin\RoleController::class, 'updatePermissions'])->name('roles.update-permissions');
    });
});

// Legacy Admin Routes (Admins only) - keeping for backwards compatibility
Route::prefix('wiki/admin')->name('wiki.admin.')->middleware(['auth', 'verified', 'wiki.security', 'check.ban', 'permission:admin access'])->group(function () {
    
    // Admin Dashboard
    Route::get('/', [ModerationController::class, 'adminDashboard'])->name('dashboard');
    
    // Report Management
    Route::post('/reports/articles/{report}/resolve', [ModerationController::class, 'resolveArticleReport'])->name('reports.articles.resolve');
    Route::post('/reports/comments/{report}/resolve', [ModerationController::class, 'resolveCommentReport'])->name('reports.comments.resolve');
    
    // Legacy User Management (redirect to new routes)
    Route::get('/users', function () {
        return redirect()->route('admin.users.index');
    });
    Route::get('/users/{user}', function ($user) {
        return redirect()->route('admin.users.show', $user);
    });
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
    Route::patch('/users/{user}/reputation', [UserController::class, 'updateReputation'])->name('users.reputation');
    
    // Invitation Management
    Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
    Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::delete('/invitations/{invitation}', [InvitationController::class, 'destroy'])->name('invitations.destroy');
    Route::post('/invitations/{invitation}/resend', [InvitationController::class, 'resend'])->name('invitations.resend');
    
    // System Settings
    Route::get('/settings', [ModerationController::class, 'settings'])->name('settings');
    Route::patch('/settings', [ModerationController::class, 'updateSettings'])->name('settings.update');
    
    // Analytics and Statistics
    Route::get('/analytics', [ModerationController::class, 'analytics'])->name('analytics');
    Route::get('/logs', [ModerationController::class, 'logs'])->name('logs');
});

// API Routes for AJAX calls
Route::prefix('api/wiki')->name('api.wiki.')->middleware(['auth', 'verified', 'wiki.security', 'throttle:api'])->group(function () {
    
    // Search API
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
    
    // Article API
    Route::get('/articles/{article}/stats', [ArticleController::class, 'stats'])->name('articles.stats');
    Route::post('/articles/{article}/view', [ArticleController::class, 'recordView'])->name('articles.view');
    
    // Tag API
    Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');
    Route::get('/tags/popular', [TagController::class, 'popular'])->name('tags.popular');
    
    // User API
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
    
    // Statistics API
    Route::get('/stats/overview', [ModerationController::class, 'statsOverview'])->name('stats.overview');
    Route::get('/stats/articles', [ModerationController::class, 'statsArticles'])->name('stats.articles');
    Route::get('/stats/users', [ModerationController::class, 'statsUsers'])->name('stats.users');
    
    // File Upload API
    Route::post('/files/upload/image', [FileUploadController::class, 'uploadImage'])->name('files.upload.image');
    Route::post('/files/upload/document', [FileUploadController::class, 'uploadDocument'])->name('files.upload.document');
    Route::delete('/files/delete', [FileUploadController::class, 'deleteFile'])->name('files.delete');
    Route::get('/files/list', [FileUploadController::class, 'listFiles'])->name('files.list');
});

// Authentication Routes
require __DIR__.'/auth.php';
