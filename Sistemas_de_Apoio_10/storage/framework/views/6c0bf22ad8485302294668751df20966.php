<?php $__env->startSection('content'); ?>
<div class="mx-auto py-8" style="width: 80%;">
    <div class="flex justify-end mb-4 gap-2">
        <a href="<?php echo e(route('interfaces-hiperprocessamento.edit', $post->id)); ?>" class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-2 px-4 rounded">Editar</a>
        <form id="form-deletar-<?php echo e($post->id); ?>" action="<?php echo e(route('interfaces-hiperprocessamento.destroy', $post->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="button" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="abrirModalSenha(<?php echo e($post->id); ?>)">Deletar</button>
        </form>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <img src="<?php echo e(asset('storage/' . $post->capa)); ?>" alt="Capa" class="w-full h-64 object-cover rounded mb-4">
        <h1 class="text-3xl font-bold mb-2"><?php echo e($post->titulo); ?></h1>
        <div class="flex justify-between text-sm text-gray-500 mb-4">
            <span>Autor: <?php echo e($post->autor); ?></span>
            <span><?php echo e(\Carbon\Carbon::parse($post->data)->format('d/m/Y')); ?></span>
            <?php if($post->tag): ?>
                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded"><?php echo e($post->tag); ?></span>
            <?php endif; ?>
        </div>
        <div class="prose max-w-none"><?php echo $post->conteudo; ?></div>
    </div>
</div>
<!-- Modal de senha -->
<div id="modal-senha" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-sm text-center">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Confirme a exclusão</h2>
        <p class="mb-4 text-gray-600">Digite a senha para deletar:</p>
        <input type="password" id="senha-confirmacao" class="border rounded px-3 py-2 w-full mb-4 text-center" placeholder="Senha">
        <div id="erro-senha" class="text-red-600 mb-2 hidden">Senha incorreta!</div>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="fecharModalSenha()" class="px-4 py-2 bg-gray-300 rounded">Cancelar</button>
            <button type="button" onclick="confirmarSenha()" class="px-4 py-2 bg-red-600 text-white rounded font-bold">Deletar</button>
        </div>
    </div>
</div>
<script>
    let formDeletarId = null;
    function abrirModalSenha(id) {
        formDeletarId = id;
        document.getElementById('modal-senha').classList.remove('hidden');
        document.getElementById('senha-confirmacao').value = '';
        document.getElementById('erro-senha').classList.add('hidden');
    }
    function fecharModalSenha() {
        document.getElementById('modal-senha').classList.add('hidden');
        formDeletarId = null;
    }
    function confirmarSenha() {
        const senha = document.getElementById('senha-confirmacao').value;
        if (senha === '123') {
            document.getElementById('form-deletar-' + formDeletarId).submit();
        } else {
            document.getElementById('erro-senha').classList.remove('hidden');
        }
    }
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bruno/composer_sites/Sistemas_de_Apoio_10/resources/views/interfaces_hiperprocessamento/show.blade.php ENDPATH**/ ?>