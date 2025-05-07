<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div class="w-full md:w-auto flex justify-start">
            <h1 class="text-2xl font-bold text-gray-800 mb-0">Hub de Sistemas</h1>
        </div>
        <div class="w-full md:w-auto flex justify-center">
            <input type="text" id="search-sistemas" placeholder="Pesquisar sistemas..." class="border rounded px-3 py-2 w-full max-w-md text-center" />
        </div>
        <div class="w-full md:w-auto flex justify-end">
            <button onclick="document.getElementById('modal-add-sistema').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow flex items-center gap-2">
                <i class="fa fa-plus"></i> Novo Sistema
            </button>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div id="success-alert" class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow"><?php echo e(session('success')); ?></div>
        <script>
            setTimeout(function() {
                var alert = document.getElementById('success-alert');
                if(alert) alert.style.display = 'none';
            }, 5000);
        </script>
        <?php session()->forget('success'); ?>
    <?php endif; ?>

    <div id="sistemas-cards-container">
        <?php echo $__env->make('sistemas.partials.cards', ['sistemas' => $sistemas], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>

<!-- Modal de cadastro -->
<div id="modal-add-sistema" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-lg relative">
        <button onclick="document.getElementById('modal-add-sistema').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
        <h2 class="text-xl font-bold mb-4">Cadastrar Novo Sistema</h2>
        <form method="POST" action="<?php echo e(route('sistemas.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" name="nome" class="w-full border rounded px-3 py-2" required maxlength="255">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" name="titulo" class="w-full border rounded px-3 py-2" required maxlength="255">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Descrição</label>
                <textarea name="descricao" class="w-full border rounded px-3 py-2" required></textarea>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Comandos/Instruções</label>
                <textarea name="comandos" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Documentação</label>
                <textarea name="documentacao" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Rota (opcional)</label>
                <input type="text" name="rota" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Imagem de capa (URL, opcional)</label>
                <input type="text" name="imagem_capa" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Tags (opcional)</label>
                <input type="text" name="tags" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Categoria</label>
                <input type="text" name="categoria" class="w-full border rounded px-3 py-2" maxlength="255">
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('modal-add-sistema').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded">Cancelar</button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded font-bold">Salvar</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Busca e paginação AJAX com JS puro
(function() {
    var searchInput = document.getElementById('search-sistemas');
    var container = document.getElementById('sistemas-cards-container');

    function buscarSistemas(page) {
        var query = searchInput.value.trim();
        var url = '/sistemas';
        if (query) {
            url = '/sistemas/busca?query=' + encodeURIComponent(query) + '&page=' + page;
        } else {
            url = '/sistemas?page=' + page;
        }
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                container.innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    // Busca instantânea
    searchInput.addEventListener('input', function() {
        buscarSistemas(1);
    });

    // Delegação para paginação customizada e links
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-paginacao');
        if (btn) {
            e.preventDefault();
            var page = btn.getAttribute('data-page');
            buscarSistemas(page);
            return;
        }
        var link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            var url = new URL(link.href, window.location.origin);
            var page = url.searchParams.get('page') || 1;
            buscarSistemas(page);
        }
    });
})();
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bruno/composer_sites/Sistemas_de_Apoio_10/resources/views/sistemas/index.blade.php ENDPATH**/ ?>