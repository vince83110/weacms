<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta charset="UTF-8">
    <title>Bienvenue sur Weadmin - Authentification</title>
    <link rel="stylesheet" type="text/css" href="<?=base_url('web/css/bootstrap.css')?>" />
    <link rel="stylesheet" type="text/css" href="<?=base_url('web/css/metro.css')?>" />
    <link rel="stylesheet" type="text/css" href="<?=base_url('web/css/ui.css')?>" />
    <link rel="stylesheet" type="text/css" href="<?=base_url('web/css/app.css')?>" />
    <link rel="stylesheet" type="text/css" href="<?=base_url('web/css/login.css')?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    <link rel="shortcut icon" href="<?=base_url('favicon.ico')?>">
    
    <script> 
        var baseUrl = '<?=base_url()?>';
    </script>   
    <script type="text/javascript" src="<?=base_url('web/js/jquery.min.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('web/js/validate.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('web/js/backstretch.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('web/js/scripts.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('web/js/app.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('web/js/login.js')?>"></script>
    <!--[if lt IE 9]>  
        <script src="<?=base_url('web/js/html5.js')?>"></script>
    <![endif]-->          
</head><body class="login">
    <!-- BEGIN LOGO -->
    <div class="logo">
        <img src="<?=base_url('web/images/weacms-logo.png')?>" alt="Weacms" /> 
    </div>
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <div class="content">
        <!-- BEGIN LOGIN FORM -->
        <form class="form-vertical login-form" action="<?= base_url_admin('authentification/login') ?>" method="post">
            <h3 class="form-title">Connectez-vous</h3>
            
            <? if (strlen($message)) : ?>                 
                <div class="alert alert-error"><?= $message ?></div>                    
            <? endif; ?>    
            
            <input type="hidden" name="uri" value="<?=$uri?>" />
            <div class="control-group">
                <label class="control-label visible-ie8 visible-ie9">Nom d'utilisateur</label>
                <div class="controls">
                    <div class="input-icon left">
                        <i class="icon-user"></i>
                        <input class="m-wrap placeholder-no-fix" type="text" placeholder="Nom d'utilisateur" name="email" value="<?=$email?>" />
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label visible-ie8 visible-ie9">Mot de passe</label>
                <div class="controls">
                    <div class="input-icon left">
                        <i class="icon-lock"></i>
                        <input class="m-wrap placeholder-no-fix" type="password" placeholder="Mot de passe" name="password" />
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <label class="checkbox">
                <input type="checkbox" name="remember" value="1"/> Se souvenir de moi
                </label>
                <button type="submit" class="btn btn-info pull-right">
                Me connecter <i class="icon-chevron-sign-right icon-white"></i>
                </button>            
            </div>
            <div class="forget-password">
                <h4>Mot de passe oublié</h4>
                <p>
                    vous pouvez retrouvez votre mot de passe en <a href="javascript:;" class="" id="forget-password">cliquant ici.</a>
                </p>
            </div>
        </form>
        <!-- END LOGIN FORM -->        
        <!-- BEGIN FORGOT PASSWORD FORM -->
        <form class="form-vertical forget-form" action="<?= base_url_admin('authentification/forgot_password') ?>">
            <h3 class="">Mot de passe oublié ?</h3>
            <p>Entrez votre adresse email ci-dessous et validez pour recevoir un nouveau mot de passe.</p>
            <div class="control-group">
                <div class="controls">
                    <div class="input-icon left">
                        <i class="icon-envelope"></i>
                        <input class="m-wrap placeholder-no-fix" type="text" placeholder="Email" name="email" />
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" id="back-btn" class="btn">
                <i class="icon-angle-left"></i> Revenir
                </button>
                <button type="submit" class="btn btn-info pull-right">
                Valider <i class="icon-ok icon-white"></i>
                </button>            
            </div>
        </form>
    </div>
    
    <div class="copyright">
        <?= date('Y') ?> &copy; Weacms - Votre outil de gestion de contenus
    </div>
    
    <script>
        $(function() {     
			Login.init();
        });
    </script>
 </body>
</html>