@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-b-xl shadow-lg p-6">
        <!-- Breadcrumb -->
        <div class="mb-4">
            <span class="text-gray-600">Caminho: </span>
            <a href="{{ route('sistemas.arquivos.index', ['id' => $id]) }}" class="text-indigo-600 hover:underline">/</a>
            @php
                $parts = $currentPath ? explode('/', $currentPath) : [];
                $accum = '';
            @endphp
            @foreach($parts as $i => $part)
                @php $accum .= ($i > 0 ? '/' : '') . $part; @endphp
                / <a href="{{ route('sistemas.arquivos.index', ['id' => $id, 'path' => $accum]) }}" class="text-indigo-600 hover:underline">{{ $part }}</a>
            @endforeach
        </div>

        <!-- Criar nova pasta -->
        <form action="{{ route('sistemas.arquivos.createFolder', ['id' => $id, 'path' => $currentPath]) }}" method="POST" class="mb-4 flex gap-2">
            @csrf
            <input type="text" name="folder_name" placeholder="Nova pasta" class="border rounded px-2 py-1">
            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded">Criar Pasta</button>
        </form>

        <!-- Upload de arquivo -->
        <form action="{{ route('sistemas.arquivos.upload', ['id' => $id, 'path' => $currentPath]) }}" method="POST" enctype="multipart/form-data" class="mb-4 flex gap-2">
            @csrf
            <input type="file" name="arquivo" class="border rounded px-2 py-1">
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Upload</button>
        </form>

        <!-- Listagem de pastas -->
        <h3 class="font-bold text-gray-700 mt-4 mb-2">Pastas</h3>
        <ul>
            @foreach($folders as $folder)
                @php $folderName = basename($folder); $folderPath = ltrim(str_replace('sistemas/' . $id, '', $folder), '/'); @endphp
                <li class="flex items-center gap-2">
                    <a href="{{ route('sistemas.arquivos.index', ['id' => $id, 'path' => $folderPath]) }}" class="text-indigo-700 font-semibold">{{ $folderName }}</a>
                    <form action="{{ route('sistemas.arquivos.destroy', ['id' => $id, 'path' => $folderPath]) }}" method="POST" onsubmit="return confirm('Excluir esta pasta e todo o conteúdo?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-xs">Excluir</button>
                    </form>
                </li>
            @endforeach
        </ul>

        <!-- Listagem de arquivos -->
        <h3 class="font-bold text-gray-700 mt-4 mb-2">Arquivos</h3>
        <ul>
            @foreach($files as $file)
                @php $fileName = basename($file); $filePath = ltrim(str_replace('sistemas/' . $id, '', $file), '/'); @endphp
                <li class="flex items-center gap-2">
                    <span>{{ $fileName }}</span>
                    <a href="{{ route('sistemas.arquivos.download', ['id' => $id, 'path' => $filePath]) }}" class="text-blue-600 hover:underline text-xs">Download</a>
                    <form action="{{ route('sistemas.arquivos.destroy', ['id' => $id, 'path' => $filePath]) }}" method="POST" onsubmit="return confirm('Excluir este arquivo?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-xs">Excluir</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection 