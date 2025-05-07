<?php

namespace App\Http\Controllers;

use App\Models\ArquivosCategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArquivosCategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categorias = ArquivosCategoria::when($search, function($query, $search) {
            return $query->where('categoria', 'like', "%{$search}%");
        })->orderBy('categoria')->paginate(8);
        return response()->json($categorias);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoria' => 'required|string|max:255|unique:arquivos_categoria,categoria',
            'id_categoria' => 'nullable|exists:arquivos_categoria,id',
            'capa' => 'required|image',
        ]);
        $data = [
            'categoria' => $request->categoria,
            'id_categoria' => $request->id_categoria,
        ];
        if ($request->hasFile('capa')) {
            $data['capa'] = $request->file('capa')->store('capas_categorias', 'public');
        }
        $categoria = ArquivosCategoria::create($data);
        return response()->json($categoria, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ArquivosCategoria $arquivosCategoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ArquivosCategoria $arquivosCategoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ArquivosCategoria $arquivosCategoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoria = ArquivosCategoria::findOrFail($id);
        $categoria->delete();
        // Opcional: remover a pasta do storage
        // Storage::deleteDirectory('arquivos/' . $categoria->categoria);
        return response()->json(['success' => true]);
    }
}
