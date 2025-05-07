<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Monitor do Sistema</h2>

                <!-- Informações do Sistema -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Informações do Sistema</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700">Sistema Operacional</h4>
                            <p class="text-gray-600"><?php echo e($systemInfo['os']); ?></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700">Kernel</h4>
                            <p class="text-gray-600"><?php echo e($systemInfo['kernel']); ?></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700">Hostname</h4>
                            <p class="text-gray-600"><?php echo e($systemInfo['hostname']); ?></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700">Uptime</h4>
                            <p class="text-gray-600"><?php echo e($systemInfo['uptime']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Uso de Recursos -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Uso de Recursos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- CPU -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-lg font-medium mb-3">CPU</h4>
                            <div class="space-y-2">
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Load 1min</span>
                                        <span><?php echo e(number_format($cpuUsage['load_1'], 2)); ?></span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e(min($cpuUsage['load_1'] * 100, 100)); ?>%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Load 5min</span>
                                        <span><?php echo e(number_format($cpuUsage['load_5'], 2)); ?></span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e(min($cpuUsage['load_5'] * 100, 100)); ?>%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Load 15min</span>
                                        <span><?php echo e(number_format($cpuUsage['load_15'], 2)); ?></span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e(min($cpuUsage['load_15'] * 100, 100)); ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Memória -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-lg font-medium mb-3">Memória</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total</span>
                                    <span><?php echo e($memoryInfo['total']); ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Em uso</span>
                                    <span><?php echo e($memoryInfo['used']); ?> (<?php echo e($memoryInfo['usage_percent']); ?>%)</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Livre</span>
                                    <span><?php echo e($memoryInfo['free']); ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: <?php echo e($memoryInfo['usage_percent']); ?>%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Disco -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-lg font-medium mb-3">Disco</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total</span>
                                    <span><?php echo e($diskUsage['total']); ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Em uso</span>
                                    <span><?php echo e($diskUsage['used']); ?> (<?php echo e($diskUsage['usage_percent']); ?>%)</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Livre</span>
                                    <span><?php echo e($diskUsage['free']); ?></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: <?php echo e($diskUsage['usage_percent']); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Processos -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Processos em Execução</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPU %</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MEM %</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comando</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $processes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $process): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($process['user']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($process['pid']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($process['cpu']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($process['mem']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($process['command']); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Atualiza as informações a cada 5 segundos
    setInterval(function() {
        location.reload();
    }, 5000);
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bruno/composer_sites/Sistemas_de_Apoio_10/resources/views/monitor/index.blade.php ENDPATH**/ ?>