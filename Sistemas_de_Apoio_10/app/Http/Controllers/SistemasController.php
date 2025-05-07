<?php
namespace App\Http\Controllers;

use App\Models\Sistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SistemasController extends Controller
{
    // Exibe todos os sistemas como cards
    public function index(Request $request)
    {
        $sistemas = \App\Models\Sistema::orderBy('ordem')->orderBy('id')->paginate(6);

        if ($request->ajax()) {
            return view('sistemas.partials.cards', compact('sistemas'))->render();
        }

        return view('sistemas.index', compact('sistemas'));
    }

    // Cadastra novo sistema e cria a pasta
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:sistemas,nome',
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'comandos' => 'nullable|string',
            'documentacao' => 'nullable|string',
            'rota' => 'nullable|string',
            'pasta' => 'nullable|string',
            'imagem_capa' => 'nullable|string',
            'tags' => 'nullable|string',
            'categoria' => 'nullable|string',
        ]);

        $sistema = Sistema::create(array_merge($validated, [
            'data_inicio' => now(),
            'ativo' => true,
        ]));

        // Cria a pasta sistemas/{id}
        $pasta = 'sistemas/' . $sistema->id;
        Storage::makeDirectory($pasta);
        $sistema->pasta = $pasta;
        $sistema->save();

        return redirect()->route('sistemas.index')->with('success', 'Sistema criado com sucesso!');
    }

    // Busca AJAX de sistemas com paginação
    public function busca(Request $request)
    {
        $query = $request->input('query');
        $sistemas = \App\Models\Sistema::where('nome', 'like', "%$query%")
            ->orWhere('titulo', 'like', "%$query%")
            ->orWhere('categoria', 'like', "%$query%")
            ->orderBy('ordem')->orderBy('id')
            ->paginate(6);
        return view('sistemas.partials.cards', compact('sistemas'))->render();
    }

    // Exibe a view do sistema pelo slug
    public function show($slug)
    {
        $sistema = Sistema::where('nome', $slug)->firstOrFail();
        $posts = $sistema->apiSistemas()->orderBy('ordem')->orderBy('id')->get();
        return view('sistemas.show', compact('sistema', 'posts'));
    }

    // Exclusão completa de sistema, páginas e pastas
    public function destroyCompleto($id)
    {
        $sistema = \App\Models\Sistema::findOrFail($id);
        // Excluir todas as páginas relacionadas
        $paginas = \App\Models\ApiSistema::where('sistema_id', $id)->get();
        foreach ($paginas as $pagina) {
            \Storage::deleteDirectory("paginaSistemas/{$pagina->id}");
            $pagina->delete();
        }
        // Excluir pasta do sistema
        \Storage::deleteDirectory("sistemas/{$id}");
        // Excluir o sistema
        $sistema->delete();
        return redirect()->route('sistemas.index')->with('success', 'Sistema e dados relacionados excluídos com sucesso!');
    }
} 