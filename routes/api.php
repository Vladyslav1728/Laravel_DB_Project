<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AttachmentController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::patch('/update-profile', [AuthController::class, 'updateProfile']);

        Route::post('/me/profile-photo', [AuthController::class, 'storeProfilePhoto']);
        Route::delete('/me/profile-photo', [AuthController::class, 'destroyProfilePhoto']);
    });
});
/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | NOTES
    |--------------------------------------------------------------------------
    */
    Route::get('notes/pinned', [NoteController::class, 'pinnedNotes']);
    Route::get('notes/stats/status', [NoteController::class, 'statsByStatus']);
    Route::patch('notes/actions/archive-old-drafts', [NoteController::class, 'archiveOldDrafts']);
    Route::get('users/{user}/notes', [NoteController::class, 'userNotesWithCategories']);
    Route::get('notes-actions/search', [NoteController::class, 'search']);
    Route::get('users/{user}/latest-notes', [NoteController::class, 'latestUserNotes']);
    Route::patch('notes/{note}/pin', [NoteController::class, 'pin']);
    Route::patch('notes/{note}/unpin', [NoteController::class, 'unpin']);
    Route::patch('notes/{note}/publish', [NoteController::class, 'publish']);
    Route::patch('notes/{note}/archive', [NoteController::class, 'archive']);
    Route::apiResource('notes', NoteController::class);
    /*
    |--------------------------------------------------------------------------
    | TASKS
    |--------------------------------------------------------------------------
    */
    Route::apiResource('notes.tasks', TaskController::class)->scoped();
});
/*
|--------------------------------------------------------------------------
| CATEGORIES
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // все авторизованные могут смотреть категории
    Route::apiResource('categories', CategoryController::class)->only(['index','show']);
    // только admin может менять категории
    Route::middleware('admin')->group(function () {
        Route::apiResource('categories', CategoryController::class)->except(['index','show']);
    });
});
/*
|--------------------------------------------------------------------------
| COMMENTS
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/notes/{note}/comments', [CommentController::class, 'noteComments']);
    Route::post('/notes/{note}/comments', [CommentController::class, 'storeForNote']);

    Route::get('/tasks/{task}/comments', [CommentController::class, 'taskComments']);
    Route::post('/tasks/{task}/comments', [CommentController::class, 'storeForTask']);

    Route::patch('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});
/*
|--------------------------------------------------------------------------
| Attachment
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // list of note files
    Route::get('/notes/{note}/attachments', [AttachmentController::class, 'index']);
    // uploading files
    Route::post('/notes/{note}/attachments', [AttachmentController::class, 'store']);
    // temporary link to a file
    Route::get('/attachments/{attachment}/link', [AttachmentController::class, 'link']);
});
