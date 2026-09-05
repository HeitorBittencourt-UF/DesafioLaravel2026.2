<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse  // chama quando pede pra trocar a senha
    {
        $validated = $request->validateWithBag('updatePassword', [  
            'current_password' => ['required', 'current_password'],     // pede a senha atual para a alteracao funcionar
            'password' => ['required', Password::defaults(), 'confirmed'],      //obriga a ter uma senha nova seguindo o padrao do laravel e iguais
        ]);     //guarda as infos pegas

        $request->user()->update([
            'senha' => ($validated['password']),      //N tem hash pq ja tem na Model
        ]);

        return back()->with('status', 'password-updated'); // retorna pra pag anterior e uma mensagem 
    }
}

