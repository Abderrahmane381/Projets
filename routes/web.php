<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\ThemeResponsibleController;
use App\Http\Controllers\UserSubscriptionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BrowsingHistoryController;
use App\Http\Controllers\Editor\UserController;

// Basic routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes - Updated namespace
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
});



// Public routes
Route::get('/themes', [ThemeController::class, 'index'])->name('themes.index');
Route::get('/themes/{theme}', [ThemeController::class, 'show'])->name('themes.show');
Route::get('/issues', [IssueController::class, 'index'])->name('issues.index');
Route::get('/issues/{issue}', [IssueController::class, 'show'])->name('issues.show');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
// Route::get('/issues/create', [IssueController::class, 'create'])->name('issues.create');

// Auth required routes
Route::middleware(['auth'])->group(function () {
    // Article routes - create must come before show
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    Route::post('/articles/{article}/rate', [ArticleController::class, 'rate'])->name('articles.rate');
    // Protected routes
    // Removed 'web' since it's already applied
    // Editor routes - Use either role or check.role, not both
    Route::middleware('role:editor')->prefix('editor')->group(function () {
    // OR if that doesn't work, try:
    // Route::middleware('check.role:editor')->prefix('editor')->group(function () {
        Route::get('/dashboard', [EditorController::class, 'dashboard'])->name('editor.dashboard');
        Route::get('/users', [EditorController::class, 'manageUsers'])->name('editor.users.index');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('editor.users.update-role');
        Route::patch('/users/{user}/toggle-status', [EditorController::class, 'toggleUserStatus'])->name('editor.users.toggle-status');
        Route::get('/themes', [EditorController::class, 'manageThemes'])->name('editor.themes.index');
        Route::get('/articles/review', [EditorController::class, 'reviewArticles'])->name('editor.articles.review');
        Route::get('/issues', [EditorController::class, 'manageIssues'])->name('editor.issues.index');
        Route::get('/articles/create', [EditorController::class, 'createArticle'])->name('editor.articles.create');
        Route::post('/articles', [EditorController::class, 'storeArticle'])->name('editor.articles.store');
        Route::get('/issues/create', [EditorController::class, 'createIssue'])->name('editor.issues.create');
        Route::get('/themes/create', [EditorController::class, 'createTheme'])->name('editor.themes.create');
        Route::get('/users/create', [EditorController::class, 'createUser'])->name('editor.users.create');
        Route::post('/users', [EditorController::class, 'storeUser'])->name('editor.users.store');
        Route::post('/themes', [EditorController::class, 'storeTheme'])->name('editor.themes.store');
        Route::get('/themes/{theme}/edit', [EditorController::class, 'edit'])->name('editor.themes.edit');
        Route::put('/themes/{theme}', [EditorController::class, 'update'])->name('editor.themes.update');
        Route::delete('/themes/{theme}', [EditorController::class, 'destroy'])->name('editor.themes.destroy');
        Route::post('/issues', [EditorController::class, 'storeIssue'])->name('editor.issues.store');
        Route::get('/issues/{issue}/edit', [EditorController::class, 'editIssue'])->name('editor.issues.edit');
        Route::put('/issues/{issue}', [EditorController::class, 'updateIssue'])->name('editor.issues.update');
        Route::delete('/issues/{issue}', [EditorController::class, 'destroyIssue'])->name('editor.issues.destroy');
        Route::delete('/users/{user}', [EditorController::class, 'destroyuser'])->name('editor.users.destroy');
        Route::get('/editor/articles', [EditorController::class, 'index'])->name('editor.articles.index');
        Route::patch('/editor/articles/{article}/status', [EditorController::class, 'updateStatus'])->name('editor.articles.update-status');
        Route::delete('/editor/articles/{article}', [EditorController::class, 'destroyarticle'])->name('editor.articles.destroy');

        
    });

    // Admin routes
    Route::prefix('admin')->middleware('role:editor')->group(function () {
    // OR if that doesn't work, try:
    // Route::prefix('admin')->middleware('check.role:editor')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
        Route::get('/themes', [AdminController::class, 'themes'])->name('admin.themes.index');
        Route::get('/articles', [AdminController::class, 'articles'])->name('admin.articles.index');
        Route::get('/issues', [AdminController::class, 'issues'])->name('admin.issues.index');
        Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::patch('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('admin.users.toggle');
        Route::patch('/themes/{theme}/toggle', [AdminController::class, 'toggleTheme'])->name('admin.themes.toggle');
        Route::patch('/articles/{article}/toggle', [AdminController::class, 'toggleArticle'])->name('admin.articles.toggle');
        Route::patch('/issues/{issue}/toggle', [AdminController::class, 'toggleIssue'])->name('admin.issues.toggle');
    });

    // Theme Responsible routes
    Route::prefix('theme-responsible')->middleware('role:theme_responsible')->group(function () {
        Route::get('/dashboard', [ThemeResponsibleController::class, 'dashboard'])->name('theme-responsible.dashboard');
        Route::get('/themes/{theme}/articles', [ThemeResponsibleController::class, 'manageArticles'])->name('theme-responsible.articles.index');
        Route::get('/articles/{article}/review', [ThemeResponsibleController::class, 'reviewArticle'])->name('theme-responsible.articles.review');
        Route::get('/themes/{theme}/comments', [ThemeResponsibleController::class, 'moderateComments'])->name('theme-responsible.comments.moderate');
        Route::get('/themes/{theme}/statistics', [ThemeResponsibleController::class, 'themeStatistics'])->name('theme-responsible.statistics');
        Route::patch('/articles/{article}/update-status', [ThemeResponsibleController::class, 'updateArticleStatus'])
            ->name('theme-responsible.articles.update-status');
        Route::get('/themes/{theme}/articles/create', [ThemeResponsibleController::class, 'createArticle'])
            ->name('theme-responsible.articles.create');
        Route::post('/themes/{theme}/articles', [ThemeResponsibleController::class, 'storeArticle'])
            ->name('theme-responsible.articles.store');
        Route::patch('/comments/{comment}/toggle-approval', [ThemeResponsibleController::class, 'toggleCommentApproval'])
            ->name('theme-responsible.comments.toggle-approval');
        Route::delete('/comments/{comment}', [ThemeResponsibleController::class, 'deleteComment'])
            ->name('theme-responsible.comments.delete');
       
        // Pour la modification du statut
        Route::patch('/theme-responsible/{theme}/articles/{article}/status', [ThemeResponsibleController::class, 'updateStatus'])
            ->name('theme-responsible.articles.updateStatus');

        // Pour la suppression
        Route::delete('/theme-responsible/{theme}/articles/{article}', [ThemeResponsibleController::class, 'destroyArticle'])
            ->name('theme-responsible.articles.destroy');
        Route::get('/theme/{theme}/subscriptions', [ThemeResponsibleController::class, 'ViewSubscriptions'])
            ->name('theme-responsible.subscriptions.index');
            
         
        Route::patch('/themes/{theme}/subscriptions/{user}/role', [ThemeResponsibleController::class, 'updateRole'])
         ->name('theme-subscriptions.update-role');

        Route::delete('/themes/{theme}/subscriptions/{user}', [ThemeResponsibleController::class, 'destroy'])
         ->name('theme-subscriptions.destroy');
         Route::get('/articles/{article}/chat', [CommentController::class, 'showArticleComments'])->name('chat.moderate');
  
    });

    // Subscriber routes
    Route::middleware('role:subscriber')->prefix('user')->group(function () {

        
        Route::get('/subscriptions', [UserSubscriptionController::class, 'index'])->name('user.subscriptions');
        Route::get('/history', [UserSubscriptionController::class, 'history'])->name('user.history');
        Route::get('/stats', [UserSubscriptionController::class, 'stats'])->name('user.stats');
        Route::get('/dashboard', [UserSubscriptionController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/my-articles', [ArticleController::class, 'myArticles'])->name('subscriber.articles');
        
        Route::post('/history/clear', [BrowsingHistoryController::class, 'clear'])->name('user.history.clear');
        
       
    });

    // Comments routes - add these before the chat routes
    Route::post('/articles/{article}/chat', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::get('/articles/{article}/chat', [CommentController::class, 'show'])->name('comments.afficher');
    
   
    
   
    // Theme subscription routes
    Route::post('/themes/{theme}/subscribe', [ThemeController::class, 'subscribe'])->name('themes.subscribe');
    Route::delete('/themes/{theme}/unsubscribe', [ThemeController::class, 'unsubscribe'])->name('themes.unsubscribe');
});

// This must come after the create route
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
