<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(), // envia o usuario para a pag(guarda na variavel)
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());  // pega as alteracoes e guardam( nao no banco)

        if ($request->user() instanceof MustVerifyEmail && $request->user()->isDirty('email')) { //isDirty é para ver se teve alteração desde q o banco foi carregado
            $request->user()->email_verified_at = null; //trata q o emial n foi verificado
        }

        $request->user()->save(); // salva as alterações 

        return Redirect::route('profile.edit')->with('status', 'profile-updated');  // retorna pra pagina e da um sinal pra falar que foi alterado
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'], //pedir a senha 
        ]);

        $user = $request->user();

        Auth::logout(); // desloga ele 

        $user->delete(); // apaga com o usuario da variavel

        $request->session()->invalidate();  // desautentica a sessao, afinal vc apagou a parada
        $request->session()->regenerateToken(); // regenera o token 

        return Redirect::to('/');   // volta pra landing
    }
}
