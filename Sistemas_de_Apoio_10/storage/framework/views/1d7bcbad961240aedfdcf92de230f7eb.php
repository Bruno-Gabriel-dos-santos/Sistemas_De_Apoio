<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-8">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-2xl flex flex-col items-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Visualizando: <?php echo e($arquivo->nome); ?></h2>
        <div class="mb-6 text-gray-500 text-sm">Tipo: <?php echo e($tipo); ?> | Tamanho: <?php echo e($arquivo->tamanho_arquivo ? number_format($arquivo->tamanho_arquivo/1024, 1) . ' KB' : '-'); ?></div>
        <div class="w-full flex flex-col items-center justify-center">
            <?php if(Str::startsWith($tipo, 'image/')): ?>
                <img src="<?php echo e(route('arquivos.preview', $arquivo->id)); ?>" alt="Imagem" class="max-w-full max-h-[60vh] rounded shadow" />
            <?php elseif(Str::startsWith($tipo, 'video/')): ?>
                <video src="<?php echo e(route('arquivos.preview', $arquivo->id)); ?>" controls class="max-w-full max-h-[60vh] rounded shadow"></video>
            <?php elseif(Str::startsWith($tipo, 'audio/')): ?>
                <audio src="<?php echo e(route('arquivos.preview', $arquivo->id)); ?>" controls class="w-full"></audio>
            <?php elseif($tipo === 'application/pdf'): ?>
                <iframe src="<?php echo e(route('arquivos.preview', $arquivo->id)); ?>" class="w-full min-h-[60vh] rounded shadow" frameborder="0"></iframe>
            <?php elseif(Str::startsWith($tipo, 'text/')): ?>
                <iframe src="<?php echo e(route('arquivos.preview', $arquivo->id)); ?>" class="w-full min-h-[60vh] rounded shadow bg-gray-100" frameborder="0"></iframe>
            <?php else: ?>
                <a href="<?php echo e(route('arquivos.preview', $arquivo->id)); ?>" target="_blank" class="text-blue-600 underline text-lg">Baixar/Visualizar arquivo</a>
            <?php endif; ?>
        </div>
        <a href="<?php echo e(url()->previous()); ?>" class="mt-8 px-4 py-2 rounded bg-gray-300 text-gray-800 hover:bg-gray-400">Voltar</a>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bruno/composer_sites/Sistemas_de_Apoio_10/resources/views/arquivos/visualizador.blade.php ENDPATH**/ ?>