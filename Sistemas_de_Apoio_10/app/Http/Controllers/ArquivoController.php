<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArquivosCategoria;
use App\Models\Arquivo;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ArquivoController extends Controller
{
    public function index()
    {
        return view('arquivos.index');
    }

    public function show($id_categoria)
    {
        $categoria = \App\Models\ArquivosCategoria::findOrFail($id_categoria);
        return view('arquivos.show', compact('categoria'));
    }

    public function listar(Request $request)
    {
        $request->validate([
            'categoria' => 'required|integer|exists:arquivos_categoria,id',
            'filtro' => 'nullable|string',
        ]);
        $query = Arquivo::where('categoria', $request->categoria);
        if ($request->filled('filtro')) {
            $query->where('nome', 'like', '%' . $request->filtro . '%');
        }
        $arquivos = $query->orderByDesc('data')->get();
        return response()->json($arquivos);
    }

    public function visualizar(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:arquivos,id']);
        $arquivo = Arquivo::findOrFail($request->id);
        $path = 'arquivos/' . $arquivo->categoria . '/' . $arquivo->nome;
        $url = Storage::url($path);
        $tipo = Storage::mimeType($path);
        return response()->json([
            'url' => $url,
            'tipo' => $tipo,
            'nome' => $arquivo->nome,
        ]);
    }

    public function excluir(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:arquivos,id']);
        $arquivo = Arquivo::findOrFail($request->id);
        $path = 'arquivos/' . $arquivo->categoria . '/' . $arquivo->path;
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
        $arquivo->delete();
        return response()->json(['success' => true]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file',
            'categoria' => 'required|integer|exists:arquivos_categoria,id',
            'descricao' => 'nullable|string',
            'nome' => 'required|string',
            'chunk' => 'required|integer',
            'total_chunks' => 'required|integer',
            'path' => 'required|string',
        ]);
        $file = $request->file('arquivo');
        $categoria = $request->categoria;
        $nome = $request->nome;
        $chunk = $request->chunk;
        $totalChunks = $request->total_chunks;
        $pathRelativo = $request->path;

        // Criar registros de pastas no banco, se necessário
        $dirs = explode('/', $pathRelativo);
        array_pop($dirs); // Remove o nome do arquivo
        $acumulado = '';
        foreach ($dirs as $dir) {
            $acumulado = $acumulado ? $acumulado . '/' . $dir : $dir;
            $nomePasta = $dir; // O nome da pasta é sempre o segmento atual
            $existe = \App\Models\Arquivo::where('categoria', $categoria)
                ->where('path', $acumulado)
                ->where('tipo', 'pasta')
                ->exists();
            if (!$existe) {
                \App\Models\Arquivo::create([
                    'categoria' => $categoria,
                    'path' => $acumulado,
                    'nome' => $nomePasta,
                    'descricao' => null,
                    'data' => now(),
                    'tamanho_arquivo' => null,
                    'tipo' => 'pasta',
                ]);
            }
        }

        $tmpPath = storage_path('app/tmp_uploads/' . $categoria . '_' . md5($pathRelativo));
        if (!file_exists(dirname($tmpPath))) {
            mkdir(dirname($tmpPath), 0777, true);
        }
        // Salva o chunk no arquivo temporário
        $out = fopen($tmpPath, $chunk == 0 ? 'w' : 'a');
        $in = fopen($file->getRealPath(), 'r');
        while ($buffer = fread($in, 4096)) {
            fwrite($out, $buffer);
        }
        fclose($in);
        fclose($out);
        // Se for o último chunk, move para o destino final e registra no banco
        if ($chunk + 1 == $totalChunks) {
            $destino = 'arquivos/' . $categoria . '/' . $pathRelativo;
            Storage::put($destino, file_get_contents($tmpPath));
            $tamanho = filesize($tmpPath);
            unlink($tmpPath);
            $arquivo = \App\Models\Arquivo::create([
                'categoria' => $categoria,
                'path' => $pathRelativo,
                'nome' => $nome,
                'descricao' => $request->descricao,
                'data' => now(),
                'tamanho_arquivo' => $tamanho,
                'tipo' => 'arquivo',
            ]);
            return response()->json($arquivo);
        }
        return response()->json(['chunk' => $chunk, 'status' => 'ok']);
    }

    public function excluirPasta(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:arquivos,id']);
        $pasta = \App\Models\Arquivo::findOrFail($request->id);
        if ($pasta->tipo !== 'pasta') {
            return response()->json(['error' => 'Não é uma pasta'], 400);
        }
        $categoria = $pasta->categoria;
        $path = $pasta->path;
        // Buscar todos os arquivos e subpastas dentro da pasta
        $arquivos = \App\Models\Arquivo::where('categoria', $categoria)
            ->where(function($q) use ($path) {
                $q->where('path', $path)
                  ->orWhere('path', 'like', $path . '/%');
            })->get();
        foreach ($arquivos as $arq) {
            if ($arq->tipo === 'arquivo') {
                $destino = 'arquivos/' . $categoria . '/' . $arq->path;
                if (Storage::exists($destino)) {
                    Storage::delete($destino);
                }
            }
            $arq->delete();
        }
        // Exclui a própria pasta
        $pasta->delete();
        // Exclui o diretório físico
        Storage::deleteDirectory('arquivos/' . $categoria . '/' . $path);
        return response()->json(['success' => true]);
    }

    public function download($id)
    {
        $arquivo = Arquivo::findOrFail($id);

        if ($arquivo->tipo === 'arquivo') {
            $path = 'arquivos/' . $arquivo->categoria . '/' . $arquivo->path;
            if (!Storage::exists($path)) {
                abort(404);
            }
            return Storage::download($path, $arquivo->nome);
        } else if ($arquivo->tipo === 'pasta') {
            $categoria = $arquivo->categoria;
            $pastaPath = 'arquivos/' . $categoria . '/' . $arquivo->path;
            $zipFile = storage_path('app/tmp/' . $arquivo->nome . '.zip');

            // Garante que o diretório tmp existe
            if (!file_exists(storage_path('app/tmp'))) {
                mkdir(storage_path('app/tmp'), 0777, true);
            }

            $zip = new ZipArchive;
            if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                $files = Storage::allFiles($pastaPath);
                foreach ($files as $file) {
                    $relativeName = $arquivo->nome . '/' . substr($file, strlen($pastaPath) + 1);
                    $zip->addFile(storage_path('app/' . $file), $relativeName);
                }
                $zip->close();
            } else {
                abort(500, 'Não foi possível criar o arquivo zip.');
            }

            return response()->download($zipFile)->deleteFileAfterSend(true);
        }

        abort(404);
    }

    public function criarPasta(Request $request)
    {
        $request->validate([
            'categoria' => 'required|integer|exists:arquivos_categoria,id',
            'path' => 'required|string',
            'nome' => 'required|string',
        ]);
        $categoria = $request->categoria;
        $path = $request->path;
        $nome = $request->nome;
        $existe = \App\Models\Arquivo::where('categoria', $categoria)
            ->where('path', $path)
            ->where('tipo', 'pasta')
            ->exists();
        if (!$existe) {
            \App\Models\Arquivo::create([
                'categoria' => $categoria,
                'path' => $path,
                'nome' => $nome,
                'descricao' => null,
                'data' => now(),
                'tamanho_arquivo' => null,
                'tipo' => 'pasta',
            ]);
            // Cria o diretório físico no storage
            Storage::makeDirectory('arquivos/' . $categoria . '/' . $path);
        }
        return response()->json(['success' => true]);
    }

    public function visualizador($id)
    {
        $arquivo = \App\Models\Arquivo::findOrFail($id);
        $url = \Storage::url('arquivos/' . $arquivo->categoria . '/' . $arquivo->path);
        $tipo = \Storage::mimeType('arquivos/' . $arquivo->categoria . '/' . $arquivo->path);
        return view('arquivos.visualizador', compact('arquivo', 'url', 'tipo'));
    }

    public function preview($id)
    {
        $arquivo = \App\Models\Arquivo::findOrFail($id);
        $path = storage_path('app/arquivos/' . $arquivo->categoria . '/' . $arquivo->path);
        if (!file_exists($path)) abort(404);
        $mime = mime_content_type($path);
        // Para arquivos de texto, forçar visualização inline
        $disposition = in_array($mime, ['text/plain', 'application/pdf', 'text/html', 'text/csv']) ? 'inline' : 'inline';
        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => $disposition . '; filename="' . $arquivo->nome . '"'
        ]);
    }
} 