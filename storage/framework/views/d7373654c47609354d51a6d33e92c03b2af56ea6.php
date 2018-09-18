<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(trans('others.company_name_label')); ?></title>

    <link href="<?php echo e(asset("css/app.css")); ?>" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><?php echo e(trans('others.company_name')); ?></a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <?php if(Auth::guest()): ?>
                        <li><a href="<?php echo e(Route('login')); ?>"><?php echo e(trans('others.login_label')); ?></a></li>
                        <li><a href="<?php echo e(Route('register')); ?>"><?php echo e(trans('others.register_label')); ?></a></li>
                    <?php else: ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo e(Auth::user()->first_name); ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/logout"><?php echo e(trans('others.logout_label')); ?></a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php $languages = App\Http\Controllers\Trans\TranslationController::getLanguageList();?>
                    <div class="col-md-6 col-md-offset-6">
                        <select name="languageSwitcher" id="languageSwitcher" class="btn btn-primary form-control"  type="button">

                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option class="Sbutton" value="<?php echo e($language->lan_code); ?>"
                                <?php echo e(($language->lan_code == Session::get('locale')) ? 'selected' : ''); ?>>
                                <?php echo e($language->lan_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php echo e(csrf_field()); ?>

                        </select>
                    </div>
                </ul>
            </div>
        </div>
    </nav>

    <?php echo $__env->yieldContent('content'); ?>

    <!-- Scripts -->
    <!--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>-->
    <script src="<?php echo e(asset("js/jquery.min.js")); ?>"></script>
    <script src="<?php echo e(asset("js/bootstrap.min.js")); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset("js/custom.js")); ?>"></script>
</body>
</html>




