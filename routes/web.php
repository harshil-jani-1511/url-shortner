<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\SuperAdminUrlController;
use App\Http\Controllers\TeamInvitationController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:super_admin,admin,member'])
    ->name('dashboard');

Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {

    Route::get('/clients', [ClientController::class, 'index'])
            ->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])
    ->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])
        ->name('clients.store');

    Route::get('/urls', [SuperAdminUrlController::class, 'index'])
    ->name('urls.index');
    Route::get('/urls/export', [SuperAdminUrlController::class, 'export'])
    ->name('urls.export');

});

Route::get('/invitations/accept/{token}', [InvitationController::class, 'show'])
    ->name('invitations.accept');
Route::post('/invitations/accept/{token}', [InvitationController::class, 'store'])
    ->name('invitations.accept.store');

Route::middleware(['auth', 'role:admin'])
->prefix('team')
->name('team.')
->group(function () {
    Route::get('/', [TeamInvitationController::class, 'index'])
            ->name('index');

    Route::post('/invitations', [TeamInvitationController::class, 'store'])
        ->name('invitations.store');
    Route::get('/invite', [TeamInvitationController::class, 'index'])
    ->name('invite');
    Route::get('/members', [TeamController::class, 'index'])
            ->name('members.index');
});

Route::middleware(['auth', 'role:admin,member'])
    ->prefix('app')
    ->name('app.')
    ->group(function () {
        Route::get('/urls', [UrlController::class, 'index'])
            ->name('urls.index');
        Route::get('/urls/all', [UrlController::class, 'all'])
            ->name('urls.all');
        Route::post('/urls', [UrlController::class, 'store'])
            ->name('urls.store');
        Route::get('/urls/export', [UrlController::class, 'export'])
            ->name('urls.export');
});

Route::get('/s/{shortCode}', [ShortUrlController::class, 'redirect'])
    ->name('short-urls.redirect');

require __DIR__.'/auth.php';
