<?php
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProdutoController::class, 'index'])->name('landing');

Route::get('/produtos', [ProdutoController::class, 'catalogo'])->name('produtos.index');
Route::get('/produtos/{produto}',[ProdutoController::class, 'show'])->name('produtos.show');
Route::view('/comprar/{produto}', 'purchase')->name('purchase.show');

Route::view('/carrinho', 'cart')->name('cart.index');
Route::view('/checkout/endereco', 'checkout', ['etapa' => 'endereco'])->name('checkout.address');
Route::view('/checkout/pagamento', 'checkout', ['etapa' => 'pagamento'])->name('checkout.payment');
Route::view('/checkout/concluido', 'checkout', ['etapa' => 'concluido'])->name('checkout.success');

Route::get('/meus-produtos',[ProdutoController::class, 'gerenciar'])->middleware('auth')->name('produtos.manage');
Route::get('/meus-produtos/novo',[ProdutoController::class, 'criar'])->middleware('auth')->name('produtos.create');
Route::get('/meus-produtos/{produto}/editar',[ProdutoController::class, 'editar'])->middleware('auth')->name('produtos.edit');

Route::view('/historico/compras', 'history', ['tipo' => 'compras'])->name('history.purchases');
Route::view('/historico/vendas', 'history', ['tipo' => 'vendas'])->name('history.sales');
Route::view('/relatorios/{tipo}', 'report')
    ->whereIn('tipo', ['compras', 'vendas'])
    ->name('reports.show');

Route::get('/admin/usuarios',[UsuarioController::class, 'usuarios'])->middleware('auth')->name('admin.users.index');

Route::view('/admin/usuarios/novo','person-form',['tipo' => 'usuarios','modo' => 'criar'])->middleware('auth')->name('admin.users.create');
Route::get('/admin/administradores',[UsuarioController::class, 'administradores'])->middleware('auth')->name('admin.admins.index');
Route::view('/admin/usuarios/{usuario}/editar', 'person-form', ['tipo' => 'usuarios', 'modo' => 'editar'])->name('admin.users.edit');
Route::view('/admin/usuarios/{usuario}', 'person-form', ['tipo' => 'usuarios', 'modo' => 'visualizar'])->name('admin.users.show');

Route::view('/admin/administradores', 'usuario-management', ['tipo' => 'administradores'])->name('admin.admins.index');
Route::view('/admin/administradores/novo', 'person-form', ['tipo' => 'administradores', 'modo' => 'criar'])->name('admin.admins.create');
Route::view('/admin/administradores/{administrador}/editar', 'person-form', ['tipo' => 'administradores', 'modo' => 'editar'])->name('admin.admins.edit');
Route::view('/admin/administradores/{administrador}', 'person-form', ['tipo' => 'administradores', 'modo' => 'visualizar'])->name('admin.admins.show');

Route::view('/admin/email', 'email')->name('admin.email');
Route::view('/ajuda', 'help')->name('help');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
