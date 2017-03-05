<ul class="menu submenu treeview-menu">
    <?php $__currentLoopData = $menu->getItems(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
        <?php echo $__env->make(\HieuLe\LaravelDashboard\Dashboard::PLUGIN_NAME.'::main_sidebar.menu_item', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
</ul>