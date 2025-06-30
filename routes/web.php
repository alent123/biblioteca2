<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\FavoritoController;
use App\Http\Controllers\CompraController;
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['es', 'en'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
        
        if (auth()->check()) {
            auth()->user()->update(['idioma_preferencia' => $locale]);
        }
    }
    return redirect()->back()->with('success', 'Idioma cambiado exitosamente');
})->name('language.switch');

Route::get('/theme/{theme}', function ($theme) {
    if (in_array($theme, ['claro', 'oscuro'])) {
        session(['theme' => $theme]);
        
        if (auth()->check()) {
            auth()->user()->update(['tema_preferencia' => $theme]);
        }
    }
    return redirect()->back()->with('success', 'Tema cambiado exitosamente');
})->name('theme.switch');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/login/user', [AuthController::class, 'showUserLogin'])->name('login.user');
Route::post('/login/user', [AuthController::class, 'userLogin']);
Route::get('/login/admin', [AuthController::class, 'showAdminLogin'])->name('login.admin');
Route::post('/login/admin', [AuthController::class, 'adminLogin']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/books', [LibroController::class, 'catalog'])->name('books.catalog');
Route::get('/books/{libro}', [LibroController::class, 'show'])->name('books.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/loans', [UserController::class, 'loans'])->name('user.loans');
    Route::get('/reservations', [UserController::class, 'reservations'])->name('user.reservations');
    Route::get('/notifications', [UserController::class, 'notifications'])->name('user.notifications');
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.update-profile');
    
    Route::post('/books/{libro}/favorite', [UserController::class, 'toggleFavorite'])->name('books.favorite');
    Route::post('/books/{libro}/borrow', [PrestamoController::class, 'borrow'])->name('books.borrow');
    Route::post('/books/{libro}/reserve', [ReservaController::class, 'reserve'])->name('books.reserve');
    
    Route::post('/books/{libro}/toggle-favorite', [FavoritoController::class, 'toggle'])->name('books.toggle-favorite');
    Route::get('/favorites', [FavoritoController::class, 'index'])->name('user.favorites');
    Route::delete('/favorites/{favorito}', [FavoritoController::class, 'destroy'])->name('favorites.destroy');
    Route::get('/books/{libro}/check-favorite', [FavoritoController::class, 'check'])->name('books.check-favorite');
    
    Route::get('/books/{libro}/purchase', [CompraController::class, 'show'])->name('books.purchase');
    Route::post('/books/{libro}/purchase/fisica', [CompraController::class, 'compraFisica'])->name('books.purchase.fisica');
    Route::post('/books/{libro}/purchase/virtual', [CompraController::class, 'compraVirtual'])->name('books.purchase.virtual');
    Route::post('/books/{libro}/prestamo', [CompraController::class, 'prestamo'])->name('books.prestamo');
    Route::get('/books/{libro}/read', [CompraController::class, 'read'])->name('books.read');
    Route::get('/purchase/{compra}/success', [CompraController::class, 'success'])->name('books.purchase.success');
    Route::get('/purchase/{compra}/download', [CompraController::class, 'downloadPdf'])->name('books.purchase.download');
    Route::get('/historial', [CompraController::class, 'historial'])->name('user.historial');
    
    Route::post('/loans/{prestamo}/return', [PrestamoController::class, 'return'])->name('loans.return');
    Route::post('/loans/{prestamo}/renew', [PrestamoController::class, 'renew'])->name('loans.renew');
    
    Route::delete('/reservations/{reserva}', [ReservaController::class, 'cancel'])->name('reservations.cancel');
    
    Route::post('/notifications/{id}/mark-read', [NotificacionController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificacionController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificacionController::class, 'destroy'])->name('notifications.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/books', [LibroController::class, 'index'])->name('books.index');
    Route::get('/books/create', [LibroController::class, 'create'])->name('books.create');
    Route::post('/books', [LibroController::class, 'store'])->name('books.store');
    Route::get('/books/{libro}/edit', [LibroController::class, 'edit'])->name('books.edit');
    Route::put('/books/{libro}', [LibroController::class, 'update'])->name('books.update');
    Route::delete('/books/{libro}', [LibroController::class, 'destroy'])->name('books.destroy');
    
    Route::get('/loans', [PrestamoController::class, 'adminIndex'])->name('loans.index');
    Route::post('/loans', [PrestamoController::class, 'create'])->name('loans.create');
    Route::put('/loans/{prestamo}', [PrestamoController::class, 'update'])->name('loans.update');
    Route::delete('/loans/{prestamo}', [PrestamoController::class, 'destroy'])->name('loans.destroy');
    
    Route::get('/reservations', [ReservaController::class, 'adminIndex'])->name('reservations.index');
    Route::put('/reservations/{reserva}', [ReservaController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{reserva}', [ReservaController::class, 'destroy'])->name('reservations.destroy');
    
    Route::get('/users', [UserController::class, 'adminIndex'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'adminShow'])->name('users.show');
    Route::put('/users/{user}', [UserController::class, 'adminUpdate'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'adminDestroy'])->name('users.destroy');
});
