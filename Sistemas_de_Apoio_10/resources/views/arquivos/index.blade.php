@extends('layouts.app')

@section('content')
<div class="py-12 flex flex-col items-center">
    <!-- Botões de abas -->
    <div class="flex w-4/5 mb-8">
        <button id="btn-arquivos" class="mr-4 px-10 py-1.5 rounded-lg font-semibold bg-blue-600 text-white shadow hover:bg-blue-700 transition-all">Arquivos</button>
        <button id="btn-backup" class="px-10 py-1.5 rounded-lg font-semibold bg-gray-300 text-gray-800 shadow hover:bg-gray-400 transition-all">Backup</button>
    </div>
    <!-- Bloco Arquivos -->
    <div id="bloco-arquivos" class="w-4/5 mx-auto" style="display: block;">
        <div class="relative mb-6 flex items-center">
            <input id="pesquisa-categoria" type="text" placeholder="Pesquisar categoria..." class="border rounded-lg px-4 py-1.5 w-1/3 focus:ring-2 focus:ring-blue-400" />
            <button id="btn-adicionar-categoria" class="absolute right-0 px-6 py-1.5 rounded-lg font-semibold bg-green-600 text-white shadow hover:bg-green-700 transition-all">Adicionar Categoria</button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="categorias-lista">
            <!-- Cards de categorias -->
        </div>
    </div>
    <!-- Bloco Backup -->
    <div id="bloco-backup" class="w-4/5 mx-auto" style="display: none;">
        <div class="bg-gray-50 rounded-lg p-8 text-center shadow">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Backups do Sistema</h3>
            <p class="text-gray-500 mb-6">Área de gerenciamento de backups (bancos de dados e configurações do sistema).</p>
            <div class="flex justify-center mb-6">
                <button id="btn-novo-backup" class="px-6 py-2 rounded bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 transition-all">
                    Novo Backup
                </button>
            </div>
            <div id="mensagem-backup" class="mb-4 text-green-600 font-semibold" style="display:none"></div>
            <table class="min-w-full bg-white rounded-lg shadow mb-4">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="px-4 py-2">Data/Hora</th>
                        <th class="px-4 py-2">Arquivo</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela-backups">
                    <!-- Linhas de backups via JS -->
                </tbody>
            </table>
            <div id="sem-backups" class="text-gray-400" style="display:none">Nenhum backup encontrado.</div>
        </div>
    </div>
</div>
<!-- Modal Adicionar Categoria -->
<div id="modal-adicionar-categoria" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50" style="display:none;">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Adicionar Categoria</h3>
        <form id="form-adicionar-categoria" enctype="multipart/form-data">
            <input type="text" id="input-nome-categoria" class="border rounded-lg px-4 py-2 w-full mb-4" placeholder="Nome da categoria" required />
            <input type="file" id="input-capa-categoria" class="border rounded-lg px-4 py-2 w-full mb-4" accept="image/*" required />
            <div class="flex justify-end gap-2">
                <button type="button" id="btn-cancelar-modal" class="px-4 py-2 rounded bg-gray-300 text-gray-800 hover:bg-gray-400">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Salvar</button>
            </div>
        </form>
    </div>
</div>
<script>
// Alternância de abas
const btnArquivos = document.getElementById('btn-arquivos');
const btnBackup = document.getElementById('btn-backup');
const blocoArquivos = document.getElementById('bloco-arquivos');
const blocoBackup = document.getElementById('bloco-backup');

btnArquivos.onclick = function() {
    blocoArquivos.style.display = 'block';
    blocoBackup.style.display = 'none';
    btnArquivos.className = 'mr-4 px-10 py-1.5 rounded-lg font-semibold bg-blue-600 text-white shadow hover:bg-blue-700 transition-all';
    btnBackup.className = 'px-10 py-1.5 rounded-lg font-semibold bg-gray-300 text-gray-800 shadow hover:bg-gray-400 transition-all';
};
btnBackup.onclick = function() {
    blocoArquivos.style.display = 'none';
    blocoBackup.style.display = 'block';
    btnArquivos.className = 'mr-4 px-10 py-1.5 rounded-lg font-semibold bg-gray-300 text-gray-800 shadow hover:bg-gray-400 transition-all';
    btnBackup.className = 'px-10 py-1.5 rounded-lg font-semibold bg-blue-600 text-white shadow hover:bg-blue-700 transition-all';
};
// Função para buscar e renderizar categorias
function carregarCategorias(filtro = '', pagina = 1) {
    fetch(`/categorias-arquivos?search=${encodeURIComponent(filtro)}&page=${pagina}`)
        .then(resp => resp.json())
        .then(res => {
            const categorias = res.data;
            const lista = document.getElementById('categorias-lista');
            lista.innerHTML = '';
            if (!categorias || categorias.length === 0) {
                lista.innerHTML = '<div class="col-span-full text-gray-400 text-center">Nenhuma categoria encontrada.</div>';
            } else {
                categorias.forEach(cat => {
                    const card = document.createElement('div');
                    card.className = 'bg-white rounded-lg shadow p-4 flex flex-col items-center hover:shadow-lg transition cursor-pointer';
                    let imgSrc = cat.capa ? `/storage/${cat.capa}` : '/img/default.png';
                    card.innerHTML = `
                        <img src="${imgSrc}" alt="Categoria" class="mb-3 w-full aspect-[16/9] object-cover bg-gray-200 rounded-lg">
                        <div class="font-bold text-lg text-gray-800 mb-1">${cat.categoria}</div>
                    `;
                    card.onclick = function() {
                        window.location.href = '/arquivos/show/' + cat.id;
                    };
                    lista.appendChild(card);
                });
            }
            // Adicionar paginação
            let paginacao = document.getElementById('paginacao-categorias');
            if (!paginacao) {
                paginacao = document.createElement('div');
                paginacao.id = 'paginacao-categorias';
                paginacao.className = 'flex justify-center mt-6';
                lista.parentNode.appendChild(paginacao);
            }
            paginacao.innerHTML = '';
            if (res.last_page > 1) {
                if (res.current_page > 1) {
                    const btnPrev = document.createElement('button');
                    btnPrev.innerText = 'Anterior';
                    btnPrev.className = 'px-3 py-1 mx-1 rounded bg-gray-200 hover:bg-gray-300';
                    btnPrev.onclick = () => carregarCategorias(filtro, res.current_page - 1);
                    paginacao.appendChild(btnPrev);
                }
                paginacao.appendChild(document.createTextNode(` Página ${res.current_page} de ${res.last_page} `));
                if (res.current_page < res.last_page) {
                    const btnNext = document.createElement('button');
                    btnNext.innerText = 'Próxima';
                    btnNext.className = 'px-3 py-1 mx-1 rounded bg-gray-200 hover:bg-gray-300';
                    btnNext.onclick = () => carregarCategorias(filtro, res.current_page + 1);
                    paginacao.appendChild(btnNext);
                }
            } else {
                paginacao.innerHTML = '';
            }
        });
}
// Pesquisa dinâmica
const pesquisaInput = document.getElementById('pesquisa-categoria');
pesquisaInput.addEventListener('input', function() {
    carregarCategorias(this.value, 1);
});
// Modal adicionar categoria
const btnAddCategoria = document.getElementById('btn-adicionar-categoria');
const modalAddCategoria = document.getElementById('modal-adicionar-categoria');
const btnCancelarModal = document.getElementById('btn-cancelar-modal');
const formAddCategoria = document.getElementById('form-adicionar-categoria');
const inputNomeCategoria = document.getElementById('input-nome-categoria');

btnAddCategoria.onclick = function() {
    modalAddCategoria.style.display = 'flex';
    inputNomeCategoria.value = '';
    inputNomeCategoria.focus();
};
btnCancelarModal.onclick = function() {
    modalAddCategoria.style.display = 'none';
};
formAddCategoria.onsubmit = function(e) {
    e.preventDefault();
    const nome = inputNomeCategoria.value.trim();
    const capa = document.getElementById('input-capa-categoria').files[0];
    if (!nome || !capa) return;

    const formData = new FormData();
    formData.append('categoria', nome);
    formData.append('capa', capa);

    fetch('/categorias-arquivos', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(resp => resp.json())
    .then(data => {
        modalAddCategoria.style.display = 'none';
        carregarCategorias('', 1);
    });
};
// Inicializar lista ao carregar página
carregarCategorias('', 1);

// === BACKUP - JS ===
function carregarBackups() {
    fetch('/backup/listar')
        .then(resp => resp.json())
        .then(backups => {
            const tabela = document.getElementById('tabela-backups');
            const semBackups = document.getElementById('sem-backups');
            tabela.innerHTML = '';
            if (!backups || backups.length === 0) {
                semBackups.style.display = '';
                return;
            }
            semBackups.style.display = 'none';
            backups.forEach(backup => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-4 py-2">${new Date(backup.created_at).toLocaleString('pt-BR')}</td>
                    <td class="px-4 py-2">${backup.nome_arquivo}</td>
                    <td class="px-4 py-2">
                        <button class="px-3 py-1.5 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm" onclick="baixarBackup(${backup.id}, '${backup.nome_arquivo}')">Download</button>
                    </td>
                `;
                tabela.appendChild(tr);
            });
        });
}
function baixarBackup(id, nome) {
    fetch(`/backup/download/${id}`)
        .then(resp => {
            if (!resp.ok) throw new Error('Erro ao baixar backup');
            return resp.blob();
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = nome;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        })
        .catch(() => alert('Erro ao baixar backup.'));
}
document.getElementById('btn-novo-backup').onclick = function() {
    const btn = this;
    btn.disabled = true;
    btn.innerText = 'Processando...';
    fetch('/backup/criar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(resp => resp.json())
    .then(res => {
        const msg = document.getElementById('mensagem-backup');
        msg.innerText = res.message;
        msg.style.display = '';
        if (res.success) {
            msg.className = 'mb-4 text-green-600 font-semibold';
            carregarBackups();
        } else {
            msg.className = 'mb-4 text-red-600 font-semibold';
        }
        setTimeout(() => { msg.style.display = 'none'; }, 4000);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerText = 'Novo Backup';
    });
};
// Carregar backups ao abrir a aba
btnBackup.addEventListener('click', carregarBackups);
</script>
@endsection 