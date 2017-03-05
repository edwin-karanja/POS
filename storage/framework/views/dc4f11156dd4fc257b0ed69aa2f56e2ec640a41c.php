<!DOCTYPE html>
<html class="no-js">
<?php
$dashboard = app(\HieuLe\LaravelDashboard\Dashboard::PLUGIN_NAME);
?>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo e($dashboard->getPageTitle()); ?></title>

    <?php echo $__env->make('laravel_dashboard.partials..head_assets', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <script>
        var AdminLTEOptions = <?php echo $dashboard->getAdminLteJsOptions(); ?>;
    </script>
</head>
<body class="<?php echo e($dashboard->getBodyClasses()->getClasses()); ?> skin-<?php echo e($dashboard->getSkin()); ?> <?php echo e($dashboard->getLayout()); ?> <?php echo e($dashboard->isSidebarCollapse() ? 'sidebar-collapse' : ''); ?> <?php echo e($dashboard->useMiniSidebar() ? 'sidebar-mini' : ''); ?>">
<div id="app" class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <?php echo $__env->make('laravel_dashboard.partials.logo', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <?php echo $__env->make('laravel_dashboard.partials.top_nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <?php echo $__env->make('laravel_dashboard.partials.main_sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <?php echo $__env->yieldContent('title'); ?>
            </h1>
            <?php if(array_key_exists('DaveJamesMiller\Breadcrumbs\ServiceProvider', app()->getLoadedProviders())): ?>
                <?php echo app('breadcrumbs')->renderIfExistsArray($dashboard->getBreadcrumbName(), $dashboard->getBreadcrumbParams()); ?>

            <?php endif; ?>
        </section>

        <section class="content">
            <div class='app-alerts'>
                <?php echo app('alert')->dump($errors->all()); ?>

            </div>
            <?php echo $__env->yieldContent('content'); ?>
        </section>
    </div>


    <footer class="main-footer">
        <?php echo $__env->make('laravel_dashboard.partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </footer>

    <!-- The Right Sidebar -->
    <aside class="control-sidebar control-sidebar-<?php echo e($dashboard->getControlSidebarTheme()); ?>">
        <?php echo $__env->make('laravel_dashboard.partials.control_sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </aside>
    <div class="control-sidebar-bg"></div>
</div>

    <!-- Scripts -->

    <?php echo $__env->make('laravel_dashboard.partials.foot_assets', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->yieldContent('scripts'); ?>

</body>
</html>