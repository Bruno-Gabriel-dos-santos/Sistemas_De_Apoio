@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Upload de Arquivos para a Página: {{ $pagina->titulo }}</h1>
    <form id="upload-form" enctype="multipart/form-data" class="mb-6">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Categoria:</label>
            <select name="categoria" id="categoria" class="border rounded px-2 py-1 w-full">
                <option value="imagem">Imagem</option>
                <option value="musica">Música</option>
                <option value="texto">Texto</option>
                <option value="asset">Asset</option>
                <option value="audio">Áudio</option>
                <option value="graficos">Gráficos</option>
                <option value="video">Vídeo</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Selecione os arquivos:</label>
            <input type="file" name="arquivos[]" id="arquivos" multiple class="border rounded px-2 py-1 w-full">
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Enviar</button>
    </form>
    <div class="w-full bg-gray-200 rounded h-4 mb-4">
        <div id="progress-bar" class="bg-blue-500 h-4 rounded" style="width: 0%"></div>
    </div>
    <div id="mensagem" class="mb-4"></div>
    <a href="{{ route('sistemas.show', $sistema->nome) }}" class="mt-4 inline-block text-gray-600">Voltar</a>
</div>
<script>
const form = document.getElementById('upload-form');
const progressBar = document.getElementById('progress-bar');
const mensagem = document.getElementById('mensagem');

form.onsubmit = async function(e) {
    e.preventDefault();
    mensagem.innerHTML = '';
    progressBar.style.width = '0%';

    const categoria = document.getElementById('categoria').value;
    const arquivos = document.getElementById('arquivos').files;
    if (!arquivos.length) {
        mensagem.innerHTML = '<span class="text-red-600">Selecione pelo menos um arquivo.</span>';
        return;
    }

    // Verificação prévia de arquivos existentes (AJAX)
    let nomes = Array.from(arquivos).map(f => f.name);
    let existe = await fetch(`{{ url('/api/paginas_sistemas/check-arquivos') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        },
        body: JSON.stringify({
            pagina_id: {{ $pagina->id }},
            categoria: categoria,
            nomes: nomes
        })
    }).then(r => r.json());
    if (existe && existe.existem && existe.existem.length > 0) {
        mensagem.innerHTML = `<span class='text-red-600'>Já existem arquivos com os nomes: <b>${existe.existem.join(', ')}</b>. Renomeie ou remova-os antes de enviar.</span>`;
        return;
    }

    // Upload dos arquivos
    let formData = new FormData();
    formData.append('categoria', categoria);
    formData.append('pagina_id', {{ $pagina->id }});
    for (let i = 0; i < arquivos.length; i++) {
        formData.append('arquivos[]', arquivos[i]);
    }
    formData.append('_token', document.querySelector('input[name=_token]').value);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', `/api/paginas_sistemas/upload-arquivos`, true);
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            let percent = (e.loaded / e.total) * 100;
            progressBar.style.width = percent + '%';
        }
    };
    xhr.onload = function() {
        if (xhr.status === 200) {
            mensagem.innerHTML = '<span class="text-green-600">Upload realizado com sucesso!</span>';
            progressBar.style.width = '100%';
        } else {
            mensagem.innerHTML = '<span class="text-red-600">Erro ao enviar arquivos.</span>';
        }
    };
    xhr.onerror = function() {
        mensagem.innerHTML = '<span class="text-red-600">Erro de conexão.</span>';
    };
    xhr.send(formData);
};
</script>
@endsection 