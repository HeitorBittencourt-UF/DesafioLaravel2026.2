<?php

namespace App\View\Components;

use App\Models\Categoria;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Navbar extends Component
{
    public Collection $categorias;

    public function __construct()
    {
        $this->categorias = Categoria::query()
            ->select(['id', 'nome'])
            ->orderBy('nome')
            ->get();
    }

    public function render(): View|Closure|string
    {
        return view('components.navbar');
    }
}