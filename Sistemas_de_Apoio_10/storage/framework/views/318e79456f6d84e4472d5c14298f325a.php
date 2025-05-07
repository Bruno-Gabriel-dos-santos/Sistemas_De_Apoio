<div id="estudos-cards-wrapper">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__currentLoopData = $estudos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('estudos.show', $estudo->id)); ?>" class="block bg-white rounded-lg shadow p-4 hover:bg-blue-50 transition cursor-pointer">
                <img src="<?php echo e(asset('storage/' . $estudo->capa)); ?>" alt="Capa" class="w-full h-40 object-cover rounded mb-2">
                <h3 class="text-xl font-bold"><?php echo e($estudo->titulo); ?></h3>
                <p class="text-gray-600"><?php echo e($estudo->descricao); ?></p>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-sm text-gray-500"><?php echo e($estudo->autor); ?></span>
                    <span class="text-sm text-gray-400"><?php echo e(\Carbon\Carbon::parse($estudo->data)->format('d/m/Y')); ?></span>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="mt-6">
        <?php echo $estudos->links(); ?>

    </div>
</div> <?php /**PATH /home/bruno/composer_sites/Sistemas_de_Apoio_10/resources/views/estudos/_cards.blade.php ENDPATH**/ ?>