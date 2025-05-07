<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <button id="btn-estudos" class="bg-blue-600 text-white px-4 py-2 rounded-l hover:bg-blue-700 font-bold">Estudos</button>
            <button id="btn-pesquisas" class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700">Pesquisas</button>
            <button class="bg-green-600 text-white px-4 py-2 rounded ml-auto" onclick="abrirModalNovo()">Novo Conteúdo</button>
        </div>
        <div id="cards-area"></div>
        <div id="pagination-area" class="mt-6 flex justify-center gap-2"></div>
    </div>
</div>
<!-- Modal de cadastro (exemplo simples) -->
<div id="modal-novo" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-lg">
        <form id="form-novo-conteudo" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <h2 class="text-xl font-bold mb-4" id="modal-titulo">Novo Estudo</h2>
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
let abaAtual = 'estudos';

function carregarCards(tipo, page = 1) {
    abaAtual = tipo;
    let url = tipo === 'estudos' ? '<?php echo e(route('ajax.estudos')); ?>' : '<?php echo e(route('ajax.pesquisas')); ?>';
    url += '?page=' + page;
    fetch(url)
        .then(response => response.json())
        .then(json => {
            renderizarCards(json.data, tipo);
            renderizarPaginacao(json, tipo);
            // Atualiza o destaque visual das abas
            if (tipo === 'estudos') {
                document.getElementById('btn-estudos').classList.add('font-bold');
                document.getElementById('btn-pesquisas').classList.remove('font-bold');
            } else {
                document.getElementById('btn-pesquisas').classList.add('font-bold');
                document.getElementById('btn-estudos').classList.remove('font-bold');
            }
        });
}

function renderizarCards(cards, tipo) {
    let html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
    for (const card of cards) {
        html += `
            <a href="/${tipo}/${card.id}" class="block bg-white rounded-lg shadow p-4 hover:bg-blue-50 transition cursor-pointer">
                <img src="/storage/${card.capa}" alt="Capa" class="w-full h-40 object-cover rounded mb-2">
                <h3 class="text-xl font-bold">${card.titulo}</h3>
                <p class="text-gray-600">${card.descricao ?? ''}</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-sm text-gray-500">${card.autor}</span>
                    <span class="text-sm text-gray-400">${(card.data ?? '').split('-').reverse().join('/')}</span>
                </div>
            </a>
        `;
    }
    html += '</div>';
    document.getElementById('cards-area').innerHTML = html;
}

function renderizarPaginacao(json, tipo) {
    let html = '';
    if (json.prev_page_url) {
        html += `<button class="px-3 py-1 bg-gray-200 rounded" onclick="carregarCards('${tipo}', ${json.current_page - 1})">&laquo; Anterior</button>`;
    }
    html += `<span class="px-3 py-1">${json.current_page} / ${json.last_page}</span>`;
    if (json.next_page_url) {
        html += `<button class="px-3 py-1 bg-gray-200 rounded" onclick="carregarCards('${tipo}', ${json.current_page + 1})">Próxima &raquo;</button>`;
    }
    document.getElementById('pagination-area').innerHTML = html;
}

document.getElementById('btn-estudos').onclick = function() { carregarCards('estudos'); }
document.getElementById('btn-pesquisas').onclick = function() { carregarCards('pesquisas'); }

// Carrega estudos por padrão ao abrir a página
carregarCards('estudos');

function abrirModalNovo() {
    document.getElementById('modal-novo').classList.remove('hidden');
    atualizarActionModal();
}

function fecharModalNovo() {
    document.getElementById('modal-novo').classList.add('hidden');
}

function atualizarActionModal() {
    const form = document.getElementById('form-novo-conteudo');
    const titulo = document.getElementById('modal-titulo');
    if (abaAtual === 'estudos') {
        form.action = "<?php echo e(route('estudos.store')); ?>";
        titulo.innerText = 'Novo Estudo';
    } else {
        form.action = "<?php echo e(route('pesquisas.store')); ?>";
        titulo.innerText = 'Nova Pesquisa';
    }
}

function ativarAbaEstudos() {
    abaAtual = 'estudos';
    btnEstudos.classList.add('font-bold');
    btnPesquisas.classList.remove('font-bold');
    estudosArea.classList.remove('hidden');
    pesquisasArea.classList.add('hidden');
    atualizarActionModal();
}
function ativarAbaPesquisas() {
    abaAtual = 'pesquisas';
    btnPesquisas.classList.add('font-bold');
    btnEstudos.classList.remove('font-bold');
    pesquisasArea.classList.remove('hidden');
    estudosArea.classList.add('hidden');
    atualizarActionModal();
}

btnEstudos.onclick = ativarAbaEstudos;
btnPesquisas.onclick = ativarAbaPesquisas;

// Ativa a aba correta ao carregar a página
if (abaAtual === 'pesquisas') {
    ativarAbaPesquisas();
} else {
    ativarAbaEstudos();
}
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bruno/composer_sites/Sistemas_de_Apoio_10/resources/views/estudos/index.blade.php ENDPATH**/ ?>