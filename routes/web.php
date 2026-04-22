<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\PartRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $carsCount       = \App\Models\Car::count();
    $pendingRepairs  = \App\Models\Repair::where('status', 'pending')->count();
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

    // Repairs – claim & complete (with parts)
    Route::patch('/repairs/{repair}/claim', [RepairController::class, 'claim'])->name('repairs.claim');
    Route::get('/repairs/{repair}/complete', [RepairController::class, 'showComplete'])->name('repairs.complete.form');
    Route::post('/repairs/{repair}/complete', [RepairController::class, 'complete'])->name('repairs.complete');

    Route::resource('suppliers', App\Http\Controllers\SupplierController::class);
    Route::resource('parts', App\Http\Controllers\PartController::class);
    Route::resource('clients', ClientController::class)->middleware('auth');

    // Part requests (механик заявява, admin управлява)
    Route::resource('part_requests', PartRequestController::class)->only(['index', 'create', 'store', 'edit', 'update']);

    Route::resource('service_requests', App\Http\Controllers\ServiceRequestController::class)->only(['index', 'create', 'store']);
    Route::get('/service_requests_poll', [App\Http\Controllers\ServiceRequestController::class, 'poll'])->name('service_requests.poll');
    Route::resource('my_cars', App\Http\Controllers\ClientCarController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::patch('/service_requests/{serviceRequest}/approve', [App\Http\Controllers\ServiceRequestController::class, 'approve'])->name('service_requests.approve');
});

require __DIR__.'/auth.php';
