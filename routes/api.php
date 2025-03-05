<?php

    use App\Http\Controllers\Api\V1\AddressController;
    use App\Http\Controllers\Api\V1\ClientController;
    use App\Http\Controllers\Api\V1\InvoiceController;
    use App\Http\Controllers\Api\V1\UserController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::prefix('v1')->group(function () {
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
        });

        Route::prefix('my')->name('my.')->group(function () {
            Route::get('settings', [UserController::class, 'settings'])->name('settings');
        });
    });
