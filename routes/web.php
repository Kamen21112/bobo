<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepairController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Взимаме бройката директно от базата данни
    $carsCount = \App\Models\Car::count();
    $pendingRepairs = \App\Models\Repair::where('status', 'pending')->count();
    $completedRepairs = \App\Models\Repair::where('status', 'completed')->count();

    return view('dashboard', compact('carsCount', 'pendingRepairs', 'completedRepairs'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('cars', CarController::class);
    Route::resource('repairs', RepairController::class);
    Route::resource('employees', App\Http\Controllers\EmployeeController::class);
    Route::patch('/repairs/{repair}/claim', [App\Http\Controllers\RepairController::class, 'claim'])->name('repairs.claim');
    Route::patch('/repairs/{repair}/complete', [App\Http\Controllers\RepairController::class, 'complete'])->name('repairs.complete');
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    Route::resource('parts', App\Http\Controllers\PartController::class);
    Route::resource('clients', ClientController::class)->middleware('auth');

    Route::resource('service_requests', App\Http\Controllers\ServiceRequestController::class)->only(['index', 'create', 'store']);
    Route::resource('my_cars', App\Http\Controllers\ClientCarController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::patch('/service_requests/{serviceRequest}/approve', [App\Http\Controllers\ServiceRequestController::class, 'approve'])->name('service_requests.approve');
});

require __DIR__.'/auth.php';