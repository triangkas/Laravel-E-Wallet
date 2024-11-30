<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\UpdateWalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect(route('login'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/transaction_history', [TransactionHistoryController::class, 'show'])->name('transaction_history');
    Route::get('/update_wallet', [UpdateWalletController::class, 'show'])->name('update_wallet');
    Route::post('/update_wallet_queue', [UpdateWalletController::class, 'updateWallet'])->name('update_wallet.queue');
});

require __DIR__.'/auth.php';
