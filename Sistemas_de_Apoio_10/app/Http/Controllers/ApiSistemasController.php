<?php
namespace App\Http\Controllers;

use App\Models\ApiSistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiSistemasController extends Controller
{
    // Listar páginas do blog
    public function index($sistema_id)
    {
        $paginas = ApiSistema::where('sistema_id', $sistema_id)->orderBy('ordem')->orderBy('id')->get();
        return view('blog.index', compact('paginas', 'sistema_id'));
    }

    // Formulário de nova página
    public function create($sistema_id)
    {
        return view('blog.create', compact('sistema_id'));
    }

    // Salvar nova página
    public function store(Request $request, $sistema_id)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'conteudo' => 'required|string',
            'tipo' => 'required|string',
        ]);
        $pagina = ApiSistema::create(array_merge($validated, [
            'sistema_id' => $sistema_id,
            'data' => now(),
        ]));
        // Cria as subpastas para arquivos do blog
        $base = "blog/{$pagina->id}";
        foreach(['imagens','videos','musicas','graficos','assets'] as $sub) {
            \Storage::makeDirectory("$base/$sub");
        }
        return redirect()->route('blog.edit', [$sistema_id, $pagina->id]);
    }

    // Formulário de edição
    public function edit($sistema_id, $id)
    {
        $pagina = ApiSistema::findOrFail($id);
        return view('blog.edit', compact('pagina', 'sistema_id'));
    }

    // Salvar edição
    public function update(Request $request, $sistema_id, $id)
    {
        $pagina = ApiSistema::findOrFail($id);
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'conteudo' => 'required|string',
        ]);
        $pagina->update($validated);
        return redirect()->route('blog.edit', [$sistema_id, $pagina->id])->with('success', 'Página atualizada!');
    }

    // Excluir página
    public function destroy($sistema_id, $id)
    {
        $pagina = ApiSistema::findOrFail($id);
        $pagina->delete();
        // Remove arquivos da pasta
        Storage::deleteDirectory("blog/$id");
        return redirect()->route('blog.index', $sistema_id)->with('success', 'Página excluída!');
    }

    // Upload de arquivos
    public function upload(Request $request, $sistema_id, $id)
    {
        $request->validate(['file' => 'required|file']);
        $file = $request->file('file');
        $tipo = $request->input('tipo', 'arquivos');
        $subpasta = in_array($tipo, ['imagens','videos','musicas','arquivos']) ? $tipo : 'arquivos';
        $path = "blog/$id/$subpasta/" . $file->getClientOriginalName();

        if (\Storage::exists($path)) {
            return response()->json(['error' => 'Arquivo já existe!'], 409);
        }

        $file->storeAs("blog/$id/$subpasta", $file->getClientOriginalName());
        return response()->json(['path' => $path]);
    }

    // Listar arquivos
    public function files($sistema_id, $id)
    {
        $arquivos = Storage::files("blog/$id");
        return response()->json($arquivos);
    }

    // Remover arquivo
    public function deleteFile(Request $request, $sistema_id, $id)
    {
        $request->validate(['filename' => 'required|string']);
        Storage::delete("blog/$id/" . $request->filename);
        return response()->json(['success' => true]);
    }

    // View exclusiva para upload de arquivos
    public function uploadView($sistema_id)
    {
        $paginas = ApiSistema::where('sistema_id', $sistema_id)->orderBy('ordem')->orderBy('id')->get();
        return view('blog.upload', compact('sistema_id', 'paginas'));
    }

    public function visualizar($sistema_id, $id)
    {
        $pagina = ApiSistema::findOrFail($id);
        return view('blog.visualizar', compact('pagina', 'sistema_id'));
    }
} 