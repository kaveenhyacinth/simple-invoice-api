<?php

    use App\Http\Controllers\Api\V1\AddressController;
    use App\Http\Controllers\Api\V1\AuthController;
    use App\Http\Controllers\Api\V1\ClientController;
    use App\Http\Controllers\Api\V1\InvoiceController;
    use App\Http\Controllers\Api\V1\UserController;
    use Illuminate\Support\Facades\Route;

    Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
        Route::prefix('addresses')->name('addresses.')->group(function () {
            Route::get('/', [AddressController::class, 'index'])->name('index');
        });

        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/', [ClientController::class, 'index'])->name('index');
            Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        });

        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
            Route::post('/', [InvoiceController::class, 'store'])->name('store');
        });

        Route::prefix('my')->name('my.')->group(function () {
            Route::get('profile', [UserController::class, 'profile'])->name('profile');
            Route::get('settings', [UserController::class, 'settings'])->name('settings');
        });
    });

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout')
            ->middleware('auth:sanctum');
    });
