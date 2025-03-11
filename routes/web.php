<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::middleware('auth')->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');





    ///////item categories routes //////////////
    Route::prefix('item-categories')->group(function () {
        // List all categories
        Route::get('/', [ItemCategoryController::class, 'index'])->name('item.categories.index');

        // Show a specific category
        Route::get('/{id}', [ItemCategoryController::class, 'show'])->name('item.categories.show');

        // Store a new category
        Route::post('/', [ItemCategoryController::class, 'store'])->name('item.categories.store');

        // Update a category
        Route::put('/{id}', [ItemCategoryController::class, 'update'])->name('item.categories.update');

        // Delete a category
        Route::delete('/{id}', [ItemCategoryController::class, 'destroy'])->name('item.categories.destroy');
    });




    //dasboard management route////////


    Route::get('/management-dashboard', [DashboardController::class, 'management'])->name('management.dashboard');






    /////// items  routes/////////////

    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('items.index');  // Display list of items
        Route::post('/', [ItemController::class, 'store'])->name('items.store'); // Store new item
        Route::get('/create', [ItemController::class, 'create'])->name('items.create'); // Show create form
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('items.edit'); // Show edit form
        Route::put('/{item}', [ItemController::class, 'update'])->name('items.update');  // Update existing item
        Route::delete('/{item}', [ItemController::class, 'destroy'])->name('items.destroy'); // Delete item
        Route::post('/restore/{id}', [ItemController::class, 'restore'])->name('items.restore'); // Restore soft-deleted item
    });

});










Route::resource('items', ItemController::class);

// Restore Route (for Soft Deleted Items)
Route::post('items/{id}/restore', [ItemController::class, 'restore'])->name('items.restore');
