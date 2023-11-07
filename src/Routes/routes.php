<?php

use Hani221b\Grace\Controllers\DashboardController;
use Hani221b\Grace\Controllers\Relations\RelationController;
use Hani221b\Grace\Controllers\StubsControllers\CreateController;
use Hani221b\Grace\Controllers\StubsControllers\CreateFullResource;
use Hani221b\Grace\Controllers\StubsControllers\CreateMigration;
use Hani221b\Grace\Controllers\StubsControllers\CreateModel;
use Hani221b\Grace\Controllers\StubsControllers\CreateRequest;
use Hani221b\Grace\Controllers\StubsControllers\CreateResource;
use Hani221b\Grace\Controllers\Validations\ValidationController;
use Illuminate\Support\Facades\Route;

Route::get('grace_cp', [DashboardController::class, 'grace_cp'])->name('factory');
Route::get('dashboard', [DashboardController::class, 'get_dashboard']);
Route::get('success', [DashboardController::class, 'success'])->name('success');

Route::post('create_model', [CreateModel::class, 'makeAlive'])->name('makeModelAlive');
Route::post('create_controller', [CreateController::class, 'makeAlive'])->name('makeControllerAlive');
Route::post('create_migration', [CreateMigration::class, 'makeAlive'])->name('makeMigrationAlive');
Route::post('create_request', [CreateRequest::class, 'makeAlive'])->name('makeRequestAlive');
Route::post('create_resource', [CreateResource::class, 'makeResourceAlive'])->name('makeResourceAlive');
Route::post('create_full_resource', [CreateFullResource::class, 'makeFullResourceAlive'])->name('makeFullResourceAlive');

//===========================================================
// Languages
//===========================================================

Route::get('dashboard/languages', [DashboardController::class, 'get_languages'])->name('grace.languages');
Route::get('dashboard/languages/change_status/{id}', [DashboardController::class, 'changeStatusForLanguage'])->name('grace.languages.change_status');
Route::get('dashboard/languages/set_to_default/{id}', [DashboardController::class, 'setLanguageAsDefault'])->name('grace.languages.set_to_default');

//===========================================================
// tables
//===========================================================

Route::get('grace_tables', [DashboardController::class, 'getTables'])->name('grace_tables');
Route::get('delete_table/{id}', [DashboardController::class, 'delete_table'])->name('delete_table');
Route::get('add_validation/{id}', [ValidationController::class, 'getAddValidationRulesOnFields'])->name('add_validation');
Route::post('submit_validation', [ValidationController::class, 'submitValidationRulesOnFields'])->name('submit_validation');
Route::get('add_relation/{id}', [DashboardController::class, 'getAddRelation'])->name('add_relation');
Route::post('submit_relations', [RelationController::class, 'addRelationToModel'])->name('submit_relations');
