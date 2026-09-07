<?php

use App\Http\Controllers\AdminEmailController;
use App\Http\Controllers\AjudaController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoricoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Página inicial / Catálogo
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get(
        '/',
        [ProdutoController::class, 'index']
    )->name('landing');

    Route::get(
        '/produtos',
        [ProdutoController::class, 'catalogo']
    )->name('produtos.index');

    Route::get(
        '/produtos/{produto}',
        [ProdutoController::class, 'show']
    )->name('produtos.show');

    Route::get(
        '/compra/{produto}',
        [CompraController::class, 'show']
    )->name('compra.show');
});


/*
|--------------------------------------------------------------------------
| Gerenciamento de produtos
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('meus-produtos')
    ->name('produtos.')
    ->group(function () {

        Route::get(
            '/',
            [ProdutoController::class, 'gerenciar']
        )->name('manage');

        Route::get(
            '/novo',
            [ProdutoController::class, 'criar']
        )->name('create');

        Route::post(
            '/',
            [ProdutoController::class, 'store']
        )->name('store');

        Route::get(
            '/{produto}/editar',
            [ProdutoController::class, 'editar']
        )->name('edit');

        Route::put(
            '/{produto}',
            [ProdutoController::class, 'update']
        )->name('update');

        Route::delete(
            '/{produto}',
            [ProdutoController::class, 'destroy']
        )->name('destroy');
    });


/*
|--------------------------------------------------------------------------
| Administração
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('admin')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | CRUD de usuários
        |--------------------------------------------------------------------------
        */

        Route::prefix('usuarios')
            ->name('admin.users.')
            ->group(function () {

                // Listar usuários
                Route::get(
                    '/',
                    [UsuarioController::class, 'usuarios']
                )->name('index');

                // Formulário para criar usuário
                Route::get(
                    '/novo',
                    [UsuarioController::class, 'createUsuario']
                )->name('create');

                // Salvar usuário
                Route::post(
                    '/',
                    [UsuarioController::class, 'storeUsuario']
                )->name('store');

                // Visualizar usuário
                Route::get(
                    '/{usuario}',
                    [UsuarioController::class, 'showUsuario']
                )->name('show');

                // Formulário para editar usuário
                Route::get(
                    '/{usuario}/editar',
                    [UsuarioController::class, 'editUsuario']
                )->name('edit');

                // Atualizar usuário
                Route::put(
                    '/{usuario}',
                    [UsuarioController::class, 'updateUsuario']
                )->name('update');

                // Excluir usuário
                Route::delete(
                    '/{usuario}',
                    [UsuarioController::class, 'destroyUsuario']
                )->name('destroy');
            });


        /*
        |--------------------------------------------------------------------------
        | CRUD de administradores
        |--------------------------------------------------------------------------
        */

        Route::prefix('administradores')
            ->name('admin.admins.')
            ->group(function () {

                // Listar administradores
                Route::get(
                    '/',
                    [UsuarioController::class, 'administradores']
                )->name('index');

                // Formulário para criar administrador
                Route::get(
                    '/novo',
                    [UsuarioController::class, 'createAdministrador']
                )->name('create');

                // Salvar administrador
                Route::post(
                    '/',
                    [UsuarioController::class, 'storeAdministrador']
                )->name('store');

                // Visualizar administrador
                Route::get(
                    '/{administrador}',
                    [UsuarioController::class, 'showAdministrador']
                )->name('show');

                // Formulário para editar administrador
                Route::get(
                    '/{administrador}/editar',
                    [UsuarioController::class, 'editAdministrador']
                )->name('edit');

                // Atualizar administrador
                Route::put(
                    '/{administrador}',
                    [UsuarioController::class, 'updateAdministrador']
                )->name('update');

                // Excluir administrador
                Route::delete(
                    '/{administrador}',
                    [UsuarioController::class, 'destroyAdministrador']
                )->name('destroy');
            });


        /*
        |--------------------------------------------------------------------------
        | Envio de e-mail administrativo
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/email',
            [AdminEmailController::class, 'create']
        )->name('admin.email');

        Route::post(
            '/email',
            [AdminEmailController::class, 'send']
        )->name('admin.email.send');
    });


/*
|--------------------------------------------------------------------------
| Ajuda / Contato
|--------------------------------------------------------------------------
*/

Route::get(
    '/ajuda',
    [AjudaController::class, 'index']
)->name('ajuda');

Route::post(
    '/ajuda',
    [AjudaController::class, 'send']
)->name('ajuda.send');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Rotas autenticadas
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Carrinho
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/carrinho',
        [CarrinhoController::class, 'index']
    )->name('cart.index');

    Route::post(
        '/carrinho/{produto}',
        [CarrinhoController::class, 'store']
    )->name('cart.store');

    Route::patch(
        '/carrinho/item/{itemCarrinho}',
        [CarrinhoController::class, 'update']
    )->name('cart.update');

    Route::delete(
        '/carrinho/item/{itemCarrinho}',
        [CarrinhoController::class, 'destroy']
    )->name('cart.destroy');

    Route::delete(
        '/carrinho',
        [CarrinhoController::class, 'clear']
    )->name('cart.clear');


    /*
    |--------------------------------------------------------------------------
    | Checkout
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/checkout/endereco',
        [CheckoutController::class, 'address']
    )->name('checkout.address');

    Route::post(
        '/checkout/endereco',
        [CheckoutController::class, 'storeAddress']
    )->name('checkout.address.store');

    Route::get(
        '/checkout/pagamento',
        [CheckoutController::class, 'payment']
    )->name('checkout.payment');

    Route::post(
        '/checkout/finalizar',
        [CheckoutController::class, 'finish']
    )->name('checkout.finish');

    Route::get(
        '/checkout/concluido',
        [CheckoutController::class, 'success']
    )->name('checkout.success');


    /*
    |--------------------------------------------------------------------------
    | Histórico
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/historico/compras',
        [HistoricoController::class, 'purchases']
    )->name('historico.compras');

    Route::get(
        '/historico/vendas',
        [HistoricoController::class, 'sales']
    )->name('historico.vendas');


    /*
    |--------------------------------------------------------------------------
    | Relatórios
    |--------------------------------------------------------------------------
    */
    Route::get(
        '/relatorios/vendas/xlsx',
        [ReportController::class, 'salesXlsx']
    )->name('reports.sales.xlsx');

    Route::get(
        '/relatorios/{tipo}',
        [ReportController::class, 'show']
    )
        ->whereIn('tipo', ['compras', 'vendas'])
        ->name('reports.show');


    /*
    |--------------------------------------------------------------------------
    | Perfil
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Autenticação
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
