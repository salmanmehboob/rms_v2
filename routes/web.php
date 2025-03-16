<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseCategoryController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ExpenseController;

// Home page
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Auth::routes();

// Protected routes (accessible only by authenticated users)
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/management-dashboard', [DashboardController::class, 'management'])->name('management.dashboard');

    // ✅ Item Categories Routes (Standardized)
    Route::prefix('item-categories')->name('item.categories.')->group(function () {
        Route::get('/', [ItemCategoryController::class, 'index'])->name('index');  // List all categories
        Route::get('/{id}', [ItemCategoryController::class, 'show'])->name('show'); // Show a specific category
        Route::post('/', [ItemCategoryController::class, 'store'])->name('store'); // Store a new category
        Route::put('/{id}', [ItemCategoryController::class, 'update'])->name('update'); // Update a category
        Route::delete('/{id}', [ItemCategoryController::class, 'destroy'])->name('destroy'); // Delete a category
    });


    // ✅ Items Routes (Standardized + Restore)
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index'); // List all items
        Route::post('/', [ItemController::class, 'store'])->name('store'); // Store new item
        Route::get('/create', [ItemController::class, 'create'])->name('create'); // Create form
        Route::get('/{item}', [ItemController::class, 'show'])->name('show'); // Show a specific item
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('edit'); // Edit form
        Route::put('/{item}', [ItemController::class, 'update'])->name('update'); // Update item
        Route::delete('/{item}', [ItemController::class, 'destroy'])->name('destroy'); // Delete item
        Route::post('/{id}/restore', [ItemController::class, 'restore'])->name('restore'); // Restore soft-deleted item
    });

    // // Items Routes (using resource for cleaner code)
    // Route::resource('items', ItemController::class);

    // // Restore Route (for Soft Deleted Items)
    // Route::post('items/{id}/restore', [ItemController::class, 'restore'])->name('items.restore');

    // ✅ Expenses Routes (Standardized)
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('index'); // Display list of expenses
        Route::post('/', [ExpenseController::class, 'store'])->name('store'); // Store new expense
        Route::get('/create', [ExpenseController::class, 'create'])->name('create'); // Create form
        Route::get('/{expense}', [ExpenseController::class, 'show'])->name('show'); // Show specific expense
        Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('edit'); // Edit form
        Route::put('/{expense}', [ExpenseController::class, 'update'])->name('update'); // Update expense
        Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('destroy'); // Delete expense
        Route::post('/restore/{id}', [ExpenseController::class, 'restore'])->name('restore'); // Restore soft-deleted expense
    });

    // ✅ Expense Categories Routes (Standardized)
    Route::prefix('expense-categories')->name('expense.categories.')->group(function () {
        Route::get('/', [ExpenseCategoryController::class, 'index'])->name('index'); // List all expense categories
        Route::post('/', [ExpenseCategoryController::class, 'store'])->name('store'); // Store new category
        Route::get('/{id}', [ExpenseCategoryController::class, 'show'])->name('show'); // Show specific category
        Route::put('/{id}', [ExpenseCategoryController::class, 'update'])->name('update'); // Update category
        Route::delete('/{id}', [ExpenseCategoryController::class, 'destroy'])->name('destroy'); // Delete category
    });
});