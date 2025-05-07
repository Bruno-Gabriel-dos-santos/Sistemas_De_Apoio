<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 flex justify-end">
        <button class="bg-green-600 text-white px-4 py-2 rounded mb-4" onclick="abrirModalNovo()">Novo Post</button>
    </div>
</div>
<!-- Modal de cadastro -->
<div id="modal-novo" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-lg">
        <form id="form-novo-conteudo" method="POST" action="<?php echo e(route('interfaces-hiperprocessamento.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
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

<?php if(session('success')): ?>
    <div class="alert alert-success" id="alert-success"><?php echo e(session('success')); ?></div>
    <script>
        setTimeout(function() {
            var alert = document.getElementById('alert-success');
            if(alert) alert.style.display = 'none';
        }, 5000);
    </script>
<?php endif; ?>

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('interfaces-hiperprocessamento.show', $post->id)); ?>" class="block bg-white rounded-lg shadow p-4 hover:bg-blue-50 transition cursor-pointer">
                <img src="/storage/<?php echo e($post->capa); ?>" alt="Capa" class="w-full h-40 object-cover rounded mb-2">
                <h3 class="text-xl font-bold"><?php echo e($post->titulo); ?></h3>
                <p class="text-gray-600"><?php echo e($post->descricao); ?></p>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-sm text-gray-500"><?php echo e($post->autor); ?></span>
                    <span class="text-sm text-gray-400"><?php echo e(\Carbon\Carbon::parse($post->data)->format('d/m/Y')); ?></span>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="mt-6 flex justify-center gap-2">
        <?php echo e($posts->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bruno/composer_sites/Sistemas_de_Apoio_10/resources/views/interfaces_hiperprocessamento/index.blade.php ENDPATH**/ ?>