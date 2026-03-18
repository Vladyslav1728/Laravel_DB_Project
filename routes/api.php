<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\CategoryController;

// My Custom
Route::get('notes/pinned', [NoteController::class, 'pinnedNotes']);
// Notes - CRUD
Route::apiResource('notes', NoteController::class);
// Category - CRUD
Route::apiResource('categories', CategoryController::class);
// Notes - CUSTOM
Route::get('notes/stats/status', [NoteController::class, 'statsByStatus']);
Route::patch('notes/actions/archive-old-drafts', [NoteController::class, 'archiveOldDrafts']);
Route::get('users/{user}/notes', [NoteController::class, 'userNotesWithCategories']);
Route::get('notes-actions/search', [NoteController::class, 'search']);
// New Note Methods - Homework 5
Route::patch('notes/{id}/pin', [NoteController::class, 'pin']);
Route::patch('notes/{id}/unpin', [NoteController::class, 'unpin']);
Route::patch('notes/{id}/publish', [NoteController::class, 'publish']);
Route::patch('notes/{id}/archive', [NoteController::class, 'archive']);
