<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['items', 'class' => 'hidden']));

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

foreach (array_filter((['items', 'class' => 'hidden']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php if(!empty($items)): ?>
<nav aria-label="breadcrumb" class="<?php echo e($class); ?>">
    <ol class="flex flex-wrap items-center text-sm" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($index > 0): ?>
                <li aria-hidden="true" class="mx-2 text-gray-400">&#8250;</li>
            <?php endif; ?>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="flex items-center">
                <?php if(!empty($item['url'])): ?>
                    <a itemprop="item" href="<?php echo e($item['url']); ?>" class="text-blue-600 hover:underline">
                        <span itemprop="name"><?php echo e($item['label']); ?></span>
                    </a>
                <?php else: ?>
                    <span itemprop="name"><?php echo e($item['label']); ?></span>
                    <meta itemprop="item" content="<?php echo e(url()->current()); ?>" />
                <?php endif; ?>
                <meta itemprop="position" content="<?php echo e($index + 1); ?>" />
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
</nav>
<?php endif; ?>
<?php /**PATH C:\laragon\www\skladchina\resources\views/components/breadcrumbs.blade.php ENDPATH**/ ?>