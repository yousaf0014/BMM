<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?php echo !empty($title_for_layout) ? 'TurboAnchor.com | Bmm - '.$title_for_layout:'TurboAnchor.com|Bmm - Login'; ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo asset('fav.png');; ?>"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700') !!}" rel="stylesheet">
    <link href="<?php echo asset('css/bootstrap.min.css" rel="stylesheet'); ?>" type="text/css" />
    <link href="<?php echo asset('css/plugins.css" rel="stylesheet'); ?>" type="text/css" />
    <link href="<?php echo asset('css/authentication/form-2.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/forms/theme-checkbox-radio.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('css/forms/switches.css'); ?>">
</head>
<body class="form">
    <div class="form-container outer">
        <div class="form-form">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo asset('js/scripts.js'); ?>"></script>
    <script type="text/javascript">
    </script>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="<?php echo asset('js/libs/jquery-3.1.1.min.js'); ?>"></script>
    <script src="<?php echo asset('js/popper.min.js'); ?>"></script>
    <script src="<?php echo asset('js/bootstrap.min.js'); ?>"></script>
    
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="<?php echo asset('js/authentication/form-2.js'); ?>"></script>

    <?php $__env->startSection('scripts'); ?>
    <?php echo $__env->yieldSection(); ?>
</body>
</html>
<?php /**PATH /opt/lampp/htdocs/bmmlatest/resources/views/layouts/login/app.blade.php ENDPATH**/ ?>