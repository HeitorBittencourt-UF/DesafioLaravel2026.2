

<?php

use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProdutoController::class, 'index'])->name('landing');

Route::view('/produtos', 'front.catalog')->name('products.index');
Route::view('/produtos/{produto}', 'front.product')->name('products.show');
Route::view('/comprar/{produto}', 'front.purchase')->name('purchase.show');

Route::view('/carrinho', 'front.cart')->name('cart.index');
Route::view('/checkout/endereco', 'front.checkout', [
    'etapa' => 'endereco',
])->name('checkout.address');

Route::view('/checkout/pagamento', 'front.checkout', [
    'etapa' => 'pagamento',
])->name('checkout.payment');

Route::view('/checkout/concluido', 'front.checkout', [
    'etapa' => 'concluido',
])->name('checkout.success');

Route::view('/meus-produtos', 'front.products-management')
    ->name('products.manage');

Route::view('/meus-produtos/novo', 'front.product-form', [
    'modo' => 'criar',
])->name('products.create');

Route::view('/meus-produtos/{produto}/editar', 'front.product-form', [
    'modo' => 'editar',
])->name('products.edit');

Route::view('/historico/compras', 'front.history', [
    'tipo' => 'compras',
])->name('history.purchases');

Route::view('/historico/vendas', 'front.history', [
    'tipo' => 'vendas',
])->name('history.sales');

Route::view('/relatorios/{tipo}', 'front.report')
    ->whereIn('tipo', ['compras', 'vendas'])
    ->name('reports.show');

Route::view('/admin/usuarios', 'front.people-management', [
    'tipo' => 'usuarios',
])->name('admin.users.index');

Route::view('/admin/usuarios/novo', 'front.person-form', [
    'tipo' => 'usuarios',
    'modo' => 'criar',
])->name('admin.users.create');

Route::view('/admin/usuarios/{usuario}/editar', 'front.person-form', [
    'tipo' => 'usuarios',
    'modo' => 'editar',
])->name('admin.users.edit');

Route::view('/admin/usuarios/{usuario}', 'front.person-form', [
    'tipo' => 'usuarios',
    'modo' => 'visualizar',
])->name('admin.users.show');

Route::view('/admin/administradores', 'front.people-management', [
    'tipo' => 'administradores',
])->name('admin.admins.index');

Route::view('/admin/administradores/novo', 'front.person-form', [
    'tipo' => 'administradores',
    'modo' => 'criar',
])->name('admin.admins.create');

Route::view('/admin/administradores/{administrador}/editar', 'front.person-form', [
    'tipo' => 'administradores',
    'modo' => 'editar',
])->name('admin.admins.edit');

Route::view('/admin/administradores/{administrador}', 'front.person-form', [
    'tipo' => 'administradores',
    'modo' => 'visualizar',
])->name('admin.admins.show');

Route::view('/admin/email', 'front.email')->name('admin.email');
Route::view('/ajuda', 'front.help')->name('help');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';