<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;

class LivroPesquisaController extends Controller
{
    public function index()
    {
        $pesquisas = \App\Models\Pesquisa::orderBy('data', 'desc')->get();
        return view('pesquisas.index', compact('pesquisas'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'capa' => 'required|image',
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'conteudo' => 'required',
            'data' => 'required|date',
            'tag' => 'nullable|string|max:255',
            'autor' => 'required|string|max:255',
        ]);

        if ($request->hasFile('capa')) {
            $data['capa'] = $request->file('capa')->store('capas', 'public');
        }

        \App\Models\Pesquisa::create($data);

        return redirect()->route('estudos.index')->with('success', 'Pesquisa criada com sucesso!');
    }

    public function buscar(Request $request)
    {
        $query = Livro::query();

        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('autor')) {
            $query->where('autor', 'like', '%' . $request->autor . '%');
        }

        $livros = $query->where('status', 'completo')->get();

        return view('livros.pesquisa.resultados', compact('livros'));
    }

    public function show($id)
    {
        $pesquisa = \App\Models\Pesquisa::findOrFail($id);
        return view('pesquisas.show', compact('pesquisa'));
    }

    public function destroy($id)
    {
        $pesquisa = \App\Models\Pesquisa::findOrFail($id);
        // \Storage::disk('public')->delete($pesquisa->capa); // Descomente se quiser deletar a imagem
        $pesquisa->delete();
        return redirect()->route('estudos.index')->with('success', 'Pesquisa deletada com sucesso!');
    }
} 