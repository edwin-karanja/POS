<?php $__env->startSection('title'); ?>
    Categories
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<categories></categories>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        noty({
            layout: 'topCenter',
            theme: 'defaultTheme',
            type: 'success',
            text: 'Success in loading',
            timeout: 3000,
            progressBar:true
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('laravel_dashboard.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>