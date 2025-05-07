<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InterfacesDeHiperprocessamento;

class InterfacesHiperprocessamentoController extends Controller
{
    public function index()
    {
        $posts = \App\Models\InterfacesDeHiperprocessamento::orderBy('data', 'desc')->paginate(6);
        return view('interfaces_hiperprocessamento.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'data' => 'required|date',
            'tag' => 'nullable|string|max:255',
            'conteudo' => 'required|string',
            'capa' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Upload da capa
        if ($request->hasFile('capa')) {
            $capaPath = $request->file('capa')->store('capas', 'public');
            $validated['capa'] = $capaPath;
        }

        $validated['user_id'] = auth()->id();
        InterfacesDeHiperprocessamento::create($validated);
        return redirect()->route('interfaces-hiperprocessamento.index')->with('success', 'Post criado com sucesso!');
    }

    public function show($id)
    {
        $post = \App\Models\InterfacesDeHiperprocessamento::findOrFail($id);
        return view('interfaces_hiperprocessamento.show', compact('post'));
    }

    public function edit($id)
    {
        $post = \App\Models\InterfacesDeHiperprocessamento::findOrFail($id);
        return view('interfaces_hiperprocessamento.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = \App\Models\InterfacesDeHiperprocessamento::findOrFail($id);
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required',
        ]);
        $post->update($data);
        return redirect()->route('interfaces-hiperprocessamento.show', $post->id)->with('success', 'Post atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $post = \App\Models\InterfacesDeHiperprocessamento::findOrFail($id);
        $post->delete();
        return redirect()->route('interfaces-hiperprocessamento.index')->with('success', 'Post deletado com sucesso!');
    }
} 