<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\UserController as UserAuthController;
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


// Route auth
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/users/change-password',
        [UserAuthController::class, 'changePassword'])
        ->name('users.change_password');
    Route::get('/users/change-info', [UserAuthController::class, 'changeInfo'])
        ->name('users.change_info');
    Route::get('/profile', [UserAuthController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [UserAuthController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [UserAuthController::class, 'destroy'])
        ->name('profile.destroy');
    Route::get('/users', [UserAuthController::class, 'show'])
        ->name('users.show');
    // Route admin, authorize: poster, admin
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(
            ['check_role:'.UserRole::POSTER->value.','.UserRole::ADMIN->value]
        )
        ->group(function () {
            // authorize: admin
            Route::group(
                ['middleware' => ['check_role:'.UserRole::ADMIN->value]],
                function () {
                    // dashboard
                    Route::get('/', [DashboardController::class, 'index'])
                        ->name('dashboard');
                    // logout
                    Route::delete('/logout',
                        [DashboardController::class, 'index'])
                        ->name('logout');
                    // authors
                    Route::resource('authors', AuthorController::class);
                    // genres
                    Route::resource('genres', GenreController::class);
                    // menus
                    Route::resource('menus', MenuController::class);
                    // users
                    Route::get('/users/admin',
                        [UserController::class, 'showAdmins'])
                        ->name('users.admin');
                    Route::get('/users/poster',
                        [UserController::class, 'showPosters'])
                        ->name('users.poster');
                    Route::get('/users/banned',
                        [UserController::class, 'showBanneds'])
                        ->name('users.banned');
                    Route::get('/users/{user}/create-ban',
                        [UserController::class, 'createBan'])
                        ->name('users.create_ban');
                    Route::post('/users/{user}/create-ban',
                        [UserController::class, 'storeBan'])
                        ->name('users.store_ban');
                    Route::get('/users/{user}/edit-ban',
                        [UserController::class, 'editBan'])
                        ->name('users.edit_ban');
                    Route::patch('/users/{user}/edit-ban',
                        [UserController::class, 'updateBan'])
                        ->name('users.update_ban');
                    Route::delete('/users/{user}/unban',
                        [UserController::class, 'unban'])
                        ->name('users.unban');
                    Route::get('/users/{user}/edit-role',
                        [UserController::class, 'editRole'])
                        ->name('users.edit_role');
                    Route::patch('/users/{user}/update-role',
                        [UserController::class, 'updateRole'])
                        ->name('users.update_role');
                    Route::resource('users', UserController::class);
                });
            // articles
            Route::get('/articles/{article}/show-chapters',
                [ChapterController::class, 'index'])
                ->name('articles.show_chapters');
            Route::get('/articles/{article}/create-chapter',
                [ChapterController::class, 'create'])
                ->name('articles.create_chapter');
            Route::post('/articles/{article}/store-chapter',
                [ChapterController::class, 'store'])
                ->name('articles.store_chapter');
            Route::get('/articles/{article}/edit-chapter/{chapter}',
                [ChapterController::class, 'edit'])
                ->name('articles.edit_chapter');
            Route::patch('/articles/{article}/update-chapter/{chapter}',
                [ChapterController::class, 'update'])
                ->name('articles.update_chapter');
            Route::delete('/articles/{article}/destroy-chapter/{chapter}',
                [ChapterController::class, 'destroy'])
                ->name('articles.destroy_chapter');
            Route::patch('/articles/{article}/change-status/{status}',
                [ArticleController::class, 'updateStatus'])
                ->name('articles.change_status');
            Route::patch('/articles/{article}/change-complete-status',
                [ArticleController::class, 'updateCompleteStatus'])
                ->name('articles.change_complete_status');
            Route::resource('articles', ArticleController::class);
        });
});
// Route guest
Route::get('/users/{user}/posted-articles',
    [UserAuthController::class, 'showPostedArticles'])
    ->name('users.show_posted_articles');
Route::get('/users/{user}/bookmarks',
    [UserAuthController::class, 'showBookmarks'])->name('users.show_bookmarks');
Route::get('/users/{user?}', [UserAuthController::class, 'show'])
    ->name('users.show');

require __DIR__.'/auth.php';


// Route guest
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/search', [HomeController::class, 'search'])->name('home.search');
Route::get('/doc-nhieu-nhat', [HomeController::class, 'showHotArticles'])
    ->name('home.show_hot_articles');
Route::get('/moi-cap-nhat', [HomeController::class, 'showNewUpdateArticles'])
    ->name('home.show_new_update_articles');
Route::get('/da-hoan-thanh', [HomeController::class, 'showCompletedArticles'])
    ->name('home.show_completed_articles');
Route::get('/genres/{genre}',
    [App\Http\Controllers\Client\GenreController::class, 'show'])
    ->name('genres.show');
Route::get('/articles/{article}',
    [App\Http\Controllers\Client\ArticleController::class, 'show'])
    ->name('articles.show');
Route::get('/articles/{article}/chapters/{number}',
    [\App\Http\Controllers\Client\ChapterController::class, 'show'])
    ->name('articles.chapters.show');
Route::get('/authors/{author}',
    [\App\Http\Controllers\Client\AuthorController::class, 'show'])
    ->name('authors.show');
