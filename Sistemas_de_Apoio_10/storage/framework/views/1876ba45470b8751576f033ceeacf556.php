<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-b-xl shadow-lg p-6">
        <!-- Breadcrumb -->
        <div class="mb-4">
            <span class="text-gray-600">Caminho: </span>
            <a href="<?php echo e(route('sistemas.arquivos.index', ['id' => $id])); ?>" class="text-indigo-600 hover:underline">/</a>
            <?php
                $parts = $currentPath ? explode('/', $currentPath) : [];
                $accum = '';
            ?>
            <?php $__currentLoopData = $parts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $accum .= ($i > 0 ? '/' : '') . $part; ?>
                / <a href="<?php echo e(route('sistemas.arquivos.index', ['id' => $id, 'path' => $accum])); ?>" class="text-indigo-600 hover:underline"><?php echo e($part); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Criar nova pasta -->
        <form action="<?php echo e(route('sistemas.arquivos.createFolder', ['id' => $id, 'path' => $currentPath])); ?>" method="POST" class="mb-4 flex gap-2">
            <?php echo csrf_field(); ?>
            <input type="text" name="folder_name" placeholder="Nova pasta" class="border rounded px-2 py-1">
            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded">Criar Pasta</button>
        </form>

        <!-- Upload de arquivo -->
        <form action="<?php echo e(route('sistemas.arquivos.upload', ['id' => $id, 'path' => $currentPath])); ?>" method="POST" enctype="multipart/form-data" class="mb-4 flex gap-2">
            <?php echo csrf_field(); ?>
            <input type="file" name="arquivo" class="border rounded px-2 py-1">
            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Upload</button>
        </form>

        <!-- Listagem de pastas -->
        <h3 class="font-bold text-gray-700 mt-4 mb-2">Pastas</h3>
        <ul>
            <?php $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $folderName = basename($folder); $folderPath = ltrim(str_replace('sistemas/' . $id, '', $folder), '/'); ?>
                <li class="flex items-center gap-2">
                    <a href="<?php echo e(route('sistemas.arquivos.index', ['id' => $id, 'path' => $folderPath])); ?>" class="text-indigo-700 font-semibold"><?php echo e($folderName); ?></a>
                    <form action="<?php echo e(route('sistemas.arquivos.destroy', ['id' => $id, 'path' => $folderPath])); ?>" method="POST" onsubmit="return confirm('Excluir esta pasta e todo o conteúdo?')" class="inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="text-red-600 hover:underline text-xs">Excluir</button>
                    </form>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>

        <!-- Listagem de arquivos -->
        <h3 class="font-bold text-gray-700 mt-4 mb-2">Arquivos</h3>
        <ul>
            <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $fileName = basename($file); $filePath = ltrim(str_replace('sistemas/' . $id, '', $file), '/'); ?>
                <li class="flex items-center gap-2">
                    <span><?php echo e($fileName); ?></span>
                    <a href="<?php echo e(route('sistemas.arquivos.download', ['id' => $id, 'path' => $filePath])); ?>" class="text-blue-600 hover:underline text-xs">Download</a>
                    <form action="<?php echo e(route('sistemas.arquivos.destroy', ['id' => $id, 'path' => $filePath])); ?>" method="POST" onsubmit="return confirm('Excluir este arquivo?')" class="inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="text-red-600 hover:underline text-xs">Excluir</button>
                    </form>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bruno/composer_sites/Sistemas_de_Apoio_10/resources/views/sistemas/arquivos.blade.php ENDPATH**/ ?>