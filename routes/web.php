<?php

use App\Http\Controllers\AdminEmailController;
use App\Http\Controllers\AjudaController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoricoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [ProdutoController::class, 'index'])->name('landing');
    Route::get('/produtos', [ProdutoController::class, 'catalogo'])->name('produtos.index');
    Route::get('/produtos/{produto}', [ProdutoController::class, 'show'])->name('produtos.show');
    Route::get('/compra/{produto}', [CompraController::class, 'show'])->name('compra.show');
});

Route::get('/meus-produtos',[ProdutoController::class, 'gerenciar'])->middleware('auth')->name('produtos.manage');
Route::get('/meus-produtos/novo',[ProdutoController::class, 'criar'])->middleware('auth')->name('produtos.create');
Route::get('/meus-produtos/{produto}/editar',[ProdutoController::class, 'editar'])->middleware('auth')->name('produtos.edit');

Route::get('/admin/usuarios',[UsuarioController::class, 'usuarios'])->middleware('auth')->name('admin.users.index');

Route::view('/admin/usuarios/novo','person-form',['tipo' => 'usuarios','modo' => 'criar'])->middleware('auth')->name('admin.users.create');
Route::get('/admin/administradores',[UsuarioController::class, 'administradores'])->middleware('auth')->name('admin.admins.index');
Route::view('/admin/usuarios/{usuario}/editar', 'person-form', ['tipo' => 'usuarios', 'modo' => 'editar'])->name('admin.users.edit');
Route::view('/admin/usuarios/{usuario}', 'person-form', ['tipo' => 'usuarios', 'modo' => 'visualizar'])->name('admin.users.show');

Route::view('/admin/administradores/novo', 'person-form', ['tipo' => 'administradores', 'modo' => 'criar'])->name('admin.admins.create');
Route::view('/admin/administradores/{administrador}/editar', 'person-form', ['tipo' => 'administradores', 'modo' => 'editar'])->name('admin.admins.edit');
Route::view('/admin/administradores/{administrador}', 'person-form', ['tipo' => 'administradores', 'modo' => 'visualizar'])->name('admin.admins.show');

Route::get('/admin/email', [AdminEmailController::class, 'create'])
    ->middleware('auth')
    ->name('admin.email');
Route::post('/admin/email', [AdminEmailController::class, 'send'])
    ->middleware('auth')
    ->name('admin.email.send');

Route::get('/ajuda', [AjudaController::class, 'index'])->name('ajuda');
Route::post('/ajuda', [AjudaController::class, 'send'])->name('ajuda.send');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('cart.index');
    Route::post('/carrinho/{produto}', [CarrinhoController::class, 'store'])->name('cart.store');
    Route::patch('/carrinho/item/{itemCarrinho}', [CarrinhoController::class, 'update'])->name('cart.update');
    Route::delete('/carrinho/item/{itemCarrinho}', [CarrinhoController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/carrinho', [CarrinhoController::class, 'clear'])->name('cart.clear');

    Route::get('/checkout/endereco', [CheckoutController::class, 'address'])->name('checkout.address');
    Route::post('/checkout/endereco', [CheckoutController::class, 'storeAddress'])->name('checkout.address.store');
    Route::get('/checkout/pagamento', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/finalizar', [CheckoutController::class, 'finish'])->name('checkout.finish');
    Route::get('/checkout/concluido', [CheckoutController::class, 'success'])->name('checkout.success');

    Route::get('/historico/compras', [HistoricoController::class, 'purchases'])->name('historico.compras');
    Route::get('/historico/vendas', [HistoricoController::class, 'sales'])->name('historico.vendas');
    Route::get('/relatorios/{tipo}', [ReportController::class, 'show'])
        ->whereIn('tipo', ['compras', 'vendas'])
        ->name('reports.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
