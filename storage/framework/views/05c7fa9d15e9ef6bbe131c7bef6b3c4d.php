<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['skladchina', 'user' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['skladchina', 'user' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    // Текущий пользователь (если не передан явно в компонент, берём auth)
    $currentUser = $user ?? auth()->user();

    // Если пользователь авторизован - пытаемся получить запись (pivot) этого юзера в складчине
    $participant = null;
    if ($currentUser) {
        $participant = $skladchina->participants->first(function($u) use ($currentUser) {
            return $u->id === $currentUser->id;
        });
    }

    // Статус оплаты (null - не записан, false - записан но не оплатил, true - оплатил)
    $paidStatus = null;
    if ($participant) {
        // Предполагается, что в pivot-е есть булево поле 'paid'
        $paidStatus = (bool) $participant->pivot->paid;
    }
?>


<div
    class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg overflow-hidden flex flex-col"
    onclick="window.location='<?php echo e(route('skladchinas.show', $skladchina)); ?>'"
>
    
    <?php if($skladchina->image_path): ?>
        <div class="w-full h-48 overflow-hidden relative group">
            <img
                src="<?php echo e(url('img/' . $skladchina->image_path)); ?>"
                alt="<?php echo e($skladchina->title); ?>"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
            >
            <?php if($skladchina->images->first()): ?>
                <img
                    src="<?php echo e(url('img/'.$skladchina->images->first()->path)); ?>"
                    alt=""
                    class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                >
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
            <span class="text-gray-500 dark:text-gray-400">Нет изображения</span>
        </div>
    <?php endif; ?>

    
    <div class="flex-1 flex flex-col px-4 pt-3 pb-2">
        
        <div class="mb-2">
            <span
                class="inline-block px-3 py-1 text-sm font-semibold rounded-full <?php echo e($skladchina->status_badge_classes); ?>"
            >
                <?php echo e($skladchina->status_label); ?>

            </span>
        </div>

        
        <div class="mb-2 flex-1">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-snug">
                <a
                    href="<?php echo e(route('skladchinas.show', $skladchina)); ?>"
                    class="hover:underline"
                    onclick="event.stopPropagation();"
                >
                    <?php echo e($skladchina->title); ?>

                </a>
            </h3>
        </div>

        
        <div class="mb-2 text-sm text-gray-600 dark:text-gray-300">
            <span class="font-medium">Взнос:</span>
            <span class="text-gray-900 dark:text-gray-100">
                <?php echo e(number_format($skladchina->member_price, 0, '', ' ')); ?> ₽
            </span>
            <span class="mx-1">|</span>
            <span class="font-medium">Сбор:</span>
            <span class="text-gray-900 dark:text-gray-100">
                <?php echo e(number_format($skladchina->full_price, 0, '', ' ')); ?> ₽
            </span>
        </div>
    </div>

    
    <div class="px-4 pb-4">
        <?php if(auth()->guard()->check()): ?>
            
            <?php if( is_null($participant) ): ?>
                <form action="<?php echo e(route('skladchinas.join', $skladchina)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button
                        type="submit"
                        class="w-full bg-blue-600 dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-400 text-white dark:text-gray-100 font-semibold px-4 py-2 rounded-lg transition"
                        onclick="event.stopPropagation();"
                    >
                        Участвовать
                    </button>
                </form>
            <?php else: ?>
                
                <?php if( $paidStatus === false ): ?>
                    
                    <button
                        disabled
                        class="w-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 font-semibold px-4 py-2 rounded-lg cursor-default"
                        onclick="event.stopPropagation();"
                    >
                        Вы участвуете (Не оплачено)
                    </button>
                <?php else: ?>
                    
                    <button
                        disabled
                        class="w-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 font-semibold px-4 py-2 rounded-lg cursor-default"
                        onclick="event.stopPropagation();"
                    >
                        Вы участвуете (Оплачено)
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            
            <a
                href="<?php echo e(route('login')); ?>"
                class="w-full block text-center bg-gray-600 dark:bg-gray-500 hover:bg-gray-700 dark:hover:bg-gray-400 text-white dark:text-gray-100 font-semibold px-4 py-2 rounded-lg transition"
                onclick="event.stopPropagation();"
            >
                Войдите, чтобы участвовать
            </a>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\skladchina\resources\views/components/home-skladchina-card.blade.php ENDPATH**/ ?>