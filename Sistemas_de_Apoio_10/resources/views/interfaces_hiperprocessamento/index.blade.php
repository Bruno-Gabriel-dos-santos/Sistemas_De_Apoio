@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 flex justify-end">
        <button class="bg-green-600 text-white px-4 py-2 rounded mb-4" onclick="abrirModalNovo()">Novo Post</button>
    </div>
</div>
<!-- Modal de cadastro -->
<div id="modal-novo" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-lg">
        <form id="form-novo-conteudo" method="POST" action="{{ route('interfaces-hiperprocessamento.store') }}" enctype="multipart/form-data">
            @csrf
            <h2 class="text-xl font-bold mb-4">Novo Post</h2>
            <input type="text" name="titulo" placeholder="Título" class="w-full mb-2 border p-2 rounded" required>
            <input type="text" name="descricao" placeholder="Descrição" class="w-full mb-2 border p-2 rounded" required>
            <input type="text" name="autor" placeholder="Autor" class="w-full mb-2 border p-2 rounded" required>
            <input type="date" name="data" class="w-full mb-2 border p-2 rounded" required>
            <input type="text" name="tag" placeholder="Tag (opcional)" class="w-full mb-2 border p-2 rounded">
            <input type="file" name="capa" class="w-full mb-2 border p-2 rounded" required>
            <textarea name="conteudo" placeholder="Conteúdo" class="w-full mb-2 border p-2 rounded" rows="4" required></textarea>
            <div class="flex justify-end">
                <button type="button" onclick="fecharModalNovo()" class="mr-2 px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Salvar</button>
            </div>
        </form>
    </div>
</div>
<script>
function abrirModalNovo() {
    document.getElementById('modal-novo').classList.remove('hidden');
}
function fecharModalNovo() {
    document.getElementById('modal-novo').classList.add('hidden');
}
</script>

@if(session('success'))
    <div class="alert alert-success" id="alert-success">{{ session('success') }}</div>
    <script>
        setTimeout(function() {
            var alert = document.getElementById('alert-success');
            if(alert) alert.style.display = 'none';
        }, 5000);
    </script>
@endif

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($posts as $post)
            <a href="{{ route('interfaces-hiperprocessamento.show', $post->id) }}" class="block bg-white rounded-lg shadow p-4 hover:bg-blue-50 transition cursor-pointer">
                <img src="/storage/{{ $post->capa }}" alt="Capa" class="w-full h-40 object-cover rounded mb-2">
                <h3 class="text-xl font-bold">{{ $post->titulo }}</h3>
                <p class="text-gray-600">{{ $post->descricao }}</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-sm text-gray-500">{{ $post->autor }}</span>
                    <span class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($post->data)->format('d/m/Y') }}</span>
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-6 flex justify-center gap-2">
        {{ $posts->links() }}
    </div>
</div>
@endsection 