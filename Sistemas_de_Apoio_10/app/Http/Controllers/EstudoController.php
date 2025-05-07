<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstudoController extends Controller
{
    public function index()
    {
        $estudos = \App\Models\Estudo::orderBy('data', 'desc')->paginate(6, ['*'], 'estudos_page');
        $pesquisas = \App\Models\Pesquisa::orderBy('data', 'desc')->paginate(6, ['*'], 'pesquisas_page');
        return view('estudos.index', compact('estudos', 'pesquisas'));
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

        \App\Models\Estudo::create($data);

        return redirect()->route('estudos.index')->with('success', 'Estudo criado com sucesso!');
    }

    public function show($id)
    {
        $estudo = \App\Models\Estudo::findOrFail($id);
        return view('estudos.show', compact('estudo'));
    }

    public function destroy($id)
    {
        $estudo = \App\Models\Estudo::findOrFail($id);
        // \Storage::disk('public')->delete($estudo->capa); // Descomente se quiser deletar a imagem
        $estudo->delete();
        return redirect()->route('estudos.index')->with('success', 'Estudo deletado com sucesso!');
    }

    public function edit($id)
    {
        $estudo = \App\Models\Estudo::findOrFail($id);
        return view('estudos.edit', compact('estudo'));
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $estudo = \App\Models\Estudo::findOrFail($id);
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required',
        ]);
        $estudo->update($data);
        return redirect()->route('estudos.show', $estudo->id)->with('success', 'Estudo atualizado com sucesso!');
    }

    public function ajaxList(\Illuminate\Http\Request $request)
    {
        $estudos = \App\Models\Estudo::orderBy('data', 'desc')->paginate(6);
        return view('estudos._cards', compact('estudos'))->render();
    }

    public function ajaxListJson(\Illuminate\Http\Request $request)
    {
        $estudos = \App\Models\Estudo::orderBy('data', 'desc')->paginate(6);
        return response()->json([
            'data' => $estudos->items(),
            'current_page' => $estudos->currentPage(),
            'last_page' => $estudos->lastPage(),
            'next_page_url' => $estudos->nextPageUrl(),
            'prev_page_url' => $estudos->previousPageUrl(),
            'total' => $estudos->total(),
        ]);
    }
} 