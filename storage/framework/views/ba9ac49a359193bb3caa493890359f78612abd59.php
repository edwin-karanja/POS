<!-- app.css  -->
<link rel="stylesheet" type="text/css" href="/css/app.css">

<!-- Font-Awesome CSS -->
<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">

<!-- app.css  -->
<link rel="stylesheet" type="text/css" href="/css/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" href="/css/parsley.css">

<!-- Admin LTE CSS -->
<link rel="stylesheet"
      href="<?php echo e(asset('vendor/' . \HieuLe\LaravelDashboard\Dashboard::PLUGIN_NAME . '/dist/css/AdminLTE.css')); ?>"/>

<!-- Admin LTE skin CSS -->
<link rel="stylesheet"
      href="<?php echo e(asset('vendor/' . \HieuLe\LaravelDashboard\Dashboard::PLUGIN_NAME . '/dist/css/skins/skin-' . (app(\HieuLe\LaravelDashboard\Dashboard::PLUGIN_NAME)->getSkin()) . '.css')); ?>"/>

<!-- Modernizr script -->
<script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>



<!-- Main CSS Files  -->
<link rel="stylesheet" type="text/css" href="/css/main.css">



<script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>
</script>