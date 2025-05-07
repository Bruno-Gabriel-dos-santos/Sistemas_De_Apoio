@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $sistema->nome }}</h1>
        <span class="text-lg text-gray-500">Categoria: <span class="font-bold text-indigo-700">{{ $sistema->categoria }}</span></span>
    </div>

    <!-- Tabs -->
    <div class="flex gap-4 mb-6">
        <button id="tab-paginas" class="tab-btn bg-indigo-600 text-white px-4 py-2 rounded-t font-bold shadow">Páginas do Sistema</button>
        <button id="tab-arquivos" class="tab-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-t font-bold shadow">Arquivos</button>
    </div>

    <!-- Quadro Páginas do Sistema -->
    <div id="quadro-paginas" class="bg-white rounded-b-xl shadow-lg p-6 block">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div class="flex items-center gap-4">
                <h2 class="text-lg font-bold text-indigo-700">Páginas do Sistema</h2>
                <div class="flex items-center gap-2">
                    <button type="button" id="btn-anterior" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-2 py-1 rounded">&larr;</button>
                    <span class="text-xs text-gray-500">Total: <span id="pagina-atual" class="font-bold">1</span> de <span id="total-paginas" class="font-bold">{{ $posts->count() }}</span></span>
                    <button type="button" id="btn-proxima" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-2 py-1 rounded">&rarr;</button>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('paginas_sistemas.create', $sistema->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded shadow text-sm">Nova Página</a>
                <a id="btn-editar" href="#" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow text-sm hidden">Editar</a>
                <form id="form-excluir" action="#" method="POST" class="inline hidden">
                    @csrf
                    @method('DELETE')
                    <input type="password" name="senha" placeholder="Senha" class="border rounded px-2 py-1 text-xs w-20" required>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow text-sm ml-1">Excluir</button>
                </form>
                <a id="btn-upload" href="#" onclick="irParaUpload()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded shadow text-sm">Upload</a>
            </div>
        </div>
        @if($posts->isEmpty())
            <div class="text-gray-500 text-center py-8">Nenhuma página de blog criada ainda.</div>
        @else
            @php $pagina = $posts[0]; @endphp
            <div id="visualizacao-pagina-blog" class="border rounded p-6 bg-gray-50">
                <h1 class="text-2xl font-bold text-indigo-800 mb-2">{{ $pagina->titulo }}</h1>
                <div class="text-xs text-gray-500 mb-2">{{ $pagina->data ? substr($pagina->data, 0, 10) : '' }}</div>
                <div class="text-base text-gray-700 mb-4">{{ $pagina->descricao }}</div>
                <hr class="mb-4">
                {!! $pagina->conteudo !!}
            </div>
        @endif
    </div>
</div>

<!-- Quadro Arquivos FORA do container -->
<div id="quadro-arquivos" class="hidden flex w-[90vw] mx-auto">
    @include('components.gerenciador-arquivos', ['sistema' => $sistema])
</div>

<script>
// Alternância entre abas
const tabPaginas = document.getElementById('tab-paginas');
const tabArquivos = document.getElementById('tab-arquivos');
const quadroPaginas = document.getElementById('quadro-paginas');
const quadroArquivos = document.getElementById('quadro-arquivos');

function mostrarAbaPaginas() {
    quadroPaginas.classList.remove('hidden');
    quadroPaginas.classList.add('block');
    quadroArquivos.classList.remove('block');
    quadroArquivos.classList.add('hidden');
    quadroArquivos.style.display = 'none';
    quadroPaginas.style.display = 'block';
    tabPaginas.classList.add('bg-indigo-600', 'text-white');
    tabPaginas.classList.remove('bg-gray-200', 'text-gray-700');
    tabArquivos.classList.remove('bg-indigo-600', 'text-white');
    tabArquivos.classList.add('bg-gray-200', 'text-gray-700');
}
function mostrarAbaArquivos() {
    quadroArquivos.classList.remove('hidden');
    quadroArquivos.classList.add('block');
    quadroArquivos.style.display = 'block';
    quadroPaginas.classList.remove('block');
    quadroPaginas.classList.add('hidden');
    quadroPaginas.style.display = 'none';
    tabArquivos.classList.add('bg-indigo-600', 'text-white');
    tabArquivos.classList.remove('bg-gray-200', 'text-gray-700');
    tabPaginas.classList.remove('bg-indigo-600', 'text-white');
    tabPaginas.classList.add('bg-gray-200', 'text-gray-700');
    // Rolar para o quadro de arquivos
    setTimeout(function() {
        quadroArquivos.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 100);
}

if(tabPaginas && tabArquivos && quadroPaginas && quadroArquivos) {
    tabPaginas.onclick = mostrarAbaPaginas;
    tabArquivos.onclick = mostrarAbaArquivos;
    // Garante que ao carregar a página, a aba de páginas está ativa
    mostrarAbaPaginas();
}

// Navegação entre páginas do sistema
const posts = @json($posts->values());
let idxAtual = 0;
const visualizacao = document.getElementById('visualizacao-pagina-blog');
const paginaAtualSpan = document.getElementById('pagina-atual');
const btnAnterior = document.getElementById('btn-anterior');
const btnProxima = document.getElementById('btn-proxima');
const btnEditar = document.getElementById('btn-editar');
const formExcluir = document.getElementById('form-excluir');

function renderizarPagina(idx) {
    if(!posts[idx]) return;
    const post = posts[idx];
    paginaAtualSpan.textContent = (idx+1);
    visualizacao.innerHTML = `
        <h1 class='text-2xl font-bold text-indigo-800 mb-2'>${post.titulo}</h1>
        <div class='text-xs text-gray-500 mb-2'>${post.data ? post.data.substring(0, 10) : ''}</div>
        <div class='text-base text-gray-700 mb-4'>${post.descricao}</div>
        <hr class='mb-4'>
        ${post.conteudo}
    `;
    btnEditar.classList.remove('hidden');
    btnEditar.href = `/sistemas/${post.sistema_id}/paginas_sistemas/${post.id}/edit`;
    formExcluir.classList.remove('hidden');
    formExcluir.action = `/sistemas/${post.sistema_id}/paginas_sistemas/${post.id}`;
    formExcluir.querySelector('input[name="senha"]').value = '';
}
if(posts.length > 0) {
    renderizarPagina(idxAtual);
}
if(btnAnterior) btnAnterior.onclick = function() {
    if(idxAtual > 0) {
        idxAtual--;
        renderizarPagina(idxAtual);
    }
};
if(btnProxima) btnProxima.onclick = function() {
    if(idxAtual < posts.length-1) {
        idxAtual++;
        renderizarPagina(idxAtual);
    }
};
if(formExcluir) formExcluir.addEventListener('submit', function(e) {
    const senha = formExcluir.querySelector('input[name="senha"]').value;
    if(senha !== '123') {
        alert('Senha incorreta!');
        e.preventDefault();
    }
});

function irParaUpload() {
    if(!posts[idxAtual]) return;
    const paginaId = posts[idxAtual].id;
    const sistemaId = posts[idxAtual].sistema_id;
    window.location.href = `/sistemas/${sistemaId}/paginas_sistemas/${paginaId}/upload`;
}
</script>
@endsection 