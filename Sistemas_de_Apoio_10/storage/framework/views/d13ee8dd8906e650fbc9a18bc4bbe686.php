<?php $__env->startSection('content'); ?>
<div class="mx-auto py-8" style="width: 80%;">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Editar Pesquisa</h1>
        <form action="<?php echo e(route('pesquisas.update', $pesquisa->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Título</label>
                <input type="text" name="titulo" value="<?php echo e(old('titulo', $pesquisa->titulo)); ?>" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Conteúdo</label>
                <textarea name="conteudo" id="conteudo" rows="8" class="w-full border rounded p-2" required><?php echo e(old('conteudo', $pesquisa->conteudo)); ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Preview em tempo real:</label>
                <div id="preview" class="prose border rounded p-4 bg-gray-50"><?php echo old('conteudo', $pesquisa->conteudo); ?></div>
            </div>
            <div class="flex justify-end gap-2">
                <a href="<?php echo e(route('pesquisas.show', $pesquisa->id)); ?>" class="px-4 py-2 bg-gray-300 rounded">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Salvar</button>
            </div>
        </form>
    </div>
</div>
<script>
    const textarea = document.getElementById('conteudo');
    const preview = document.getElementById('preview');
    textarea.addEventListener('input', function() {
        preview.innerHTML = textarea.value;
    });
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bruno/composer_sites/Sistemas_de_Apoio_10/resources/views/pesquisas/edit.blade.php ENDPATH**/ ?>