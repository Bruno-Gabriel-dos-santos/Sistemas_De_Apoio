<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;

class PesquisaLivroController extends Controller
{
    public function index()
    {
        $categorias = Livro::select('categoria')
            ->distinct()
            ->orderBy('categoria')
            ->pluck('categoria');

        return view('livros.pesquisa.index', compact('categorias'));
    }

    public function buscar(Request $request)
    {
        $query = Livro::query();

        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        if ($request->filled('autor')) {
            $query->where('autor', 'like', '%' . $request->autor . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        $livros = $query->orderBy('titulo')
            ->paginate(10)
            ->withQueryString();

        return view('livros.pesquisa.resultados', compact('livros'));
    }
} 