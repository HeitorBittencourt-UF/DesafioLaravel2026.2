<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Messages\MailMessage;

class Usuario extends Authenticatable implements CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'Usuarios';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'tipo',
        'cpf',
        'data_nascimento',
        'telefone',
        'saldo',
        'foto',
        'criador_id',
    ];

    protected $hidden = [
        'senha',
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
            'saldo' => 'decimal:2',
            'senha' => 'hashed',
        ];
    }

    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new class($token) extends ResetPasswordNotification {
            public function toMail($notifiable)
            {
                $url = url(route('password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false));

                return (new MailMessage)
                    ->subject('Redefinição de Senha')
                    ->greeting('Olá, ' . $notifiable->nome . '!')
                    ->line('Você está recebendo este e-mail porque solicitou a redefinição de senha da sua conta.')
                    ->action('Redefinir Senha', $url)
                    ->line('Se você não solicitou a redefinição de senha, nenhuma ação adicional é necessária... Esse negocio de ser hackeado é complicado...')
                    ->salutation('Atenciosamente, Equipe do Sistema');
            }
        });
    }
    
    public function criador()
    {
        return $this->belongsTo(Usuario::class, 'criador_id');
    }

    public function usuariosCriados()
    {
        return $this->hasMany(Usuario::class, 'criador_id');
    }

    public function enderecos()
    {
        return $this->belongsToMany(Endereco::class, 'Usuarios_Enderecos', 'UsuarioId', 'EnderecoId');
    }

    public function produtos()
    {
        return $this->hasMany(Produto::class, 'UsuarioId');
    }

    public function carrinho()
    {
        return $this->hasOne(Carrinho::class, 'UsuarioId');
    }

    public function cartoes()
    {
        return $this->hasMany(Cartao::class, 'UsuarioId');
    }

    public function compras()
    {
        return $this->hasMany(Venda::class, 'CompradorId');
    }

    public function vendas()
    {
        return $this->hasMany(ItemVenda::class, 'VendedorId');
    }
}