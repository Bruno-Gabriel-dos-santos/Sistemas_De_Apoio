<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Editar Post</h1>
        <form method="POST" action="<?php echo e(route('interfaces-hiperprocessamento.update', $post->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="mb-3">
                <label class="block mb-1 font-bold">Título</label>
                <input type="text" name="titulo" class="w-full border p-2 rounded" value="<?php echo e(old('titulo', $post->titulo)); ?>" required>
            </div>
            <div class="mb-3">
                <label class="block mb-1 font-bold">Conteúdo</label>
                <textarea name="conteudo" id="conteudo" class="w-full border p-2 rounded" rows="6" required oninput="atualizarPreview()"><?php echo e(old('conteudo', $post->conteudo)); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="block mb-1 font-bold">Preview</label>
                <div id="preview" class="border p-2 rounded bg-gray-50 min-h-[100px]">
                    <?php echo old('conteudo', $post->conteudo); ?>

                </div>
            </div>
            <div class="flex justify-end">
                <a href="<?php echo e(route('interfaces-hiperprocessamento.show', $post->id)); ?>" class="px-4 py-2 bg-gray-300 rounded mr-2">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Salvar</button>
            </div>
        </form>
    </div>
</div>
<script>
function atualizarPreview() {
    const conteudo = document.getElementById('conteudo').value;
    document.getElementById('preview').innerHTML = conteudo;
}
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bruno/composer_sites/Sistemas_de_Apoio_10/resources/views/interfaces_hiperprocessamento/edit.blade.php ENDPATH**/ ?>