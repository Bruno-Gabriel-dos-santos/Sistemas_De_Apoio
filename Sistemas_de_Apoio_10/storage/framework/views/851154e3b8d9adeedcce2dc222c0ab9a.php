<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center mt-4">
        <ul class="inline-flex items-center space-x-1">
            
            <?php if($paginator->onFirstPage()): ?>
                <li>
                    <span class="px-3 py-1 rounded bg-gray-200 text-gray-400 cursor-not-allowed">&laquo;</span>
                </li>
            <?php else: ?>
                <li>
                    <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="px-3 py-1 rounded bg-white border border-gray-300 text-gray-700 hover:bg-indigo-100 hover:text-indigo-700 transition">&laquo;</a>
                </li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php if(is_string($element)): ?>
                    <li>
                        <span class="px-3 py-1 rounded bg-gray-100 text-gray-500"><?php echo e($element); ?></span>
                    </li>
                <?php endif; ?>

                
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li>
                                <span class="px-3 py-1 rounded bg-indigo-600 text-white font-bold shadow"><?php echo e($page); ?></span>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php echo e($url); ?>" class="px-3 py-1 rounded bg-white border border-gray-300 text-gray-700 hover:bg-indigo-100 hover:text-indigo-700 transition"><?php echo e($page); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li>
                    <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="px-3 py-1 rounded bg-white border border-gray-300 text-gray-700 hover:bg-indigo-100 hover:text-indigo-700 transition">&raquo;</a>
                </li>
            <?php else: ?>
                <li>
                    <span class="px-3 py-1 rounded bg-gray-200 text-gray-400 cursor-not-allowed">&raquo;</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
<?php /**PATH /home/bruno/composer_sites/Sistemas_de_Apoio_10/resources/views/vendor/pagination/tailwind.blade.php ENDPATH**/ ?>