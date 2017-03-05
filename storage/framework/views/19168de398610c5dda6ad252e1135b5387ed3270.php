<?php if($item['item'] instanceof \HieuLe\LaravelMenu\Menu): ?>
    <li class="menuitem treeview <?php echo e(app('menu.manager')->isActive($item) ? 'active' : ''); ?>">
        <a href="#">
            <?php echo $item['before']; ?>

            <span><?php echo e($item['item']->getLabel()); ?></span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <?php echo $__env->make(\HieuLe\LaravelDashboard\Dashboard::PLUGIN_NAME.'::main_sidebar.sub_menu', ['menu' => $item['item']], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </li>
<?php else: ?>
    <li class="menuitem item <?php echo e(app('menu.manager')->isActive($item) ? 'active' : ''); ?>">
        <a href="<?php echo e($item['item']['url']); ?>">
            <?php echo $item['before']; ?>

            <span><?php echo e($item['item']['text']); ?></span>
            <?php echo $item['after']; ?>

        </a>
    </li>
<?php endif; ?>

