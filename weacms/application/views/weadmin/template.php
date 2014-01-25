<!DOCTYPE html>
<!--[if IE 8]> <html lang="fr" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="fr" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="fr"> <!--<![endif]-->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">  
	<meta charset="utf-8" />
    <title><?= $title ?></title>
	<link rel="stylesheet" type="text/css" href="<?=base_url('web/css/bootstrap.css')?>" />
	<link rel="stylesheet" type="text/css" href="<?=base_url('web/css/metro.css')?>" />
	<link rel="stylesheet" type="text/css" href="<?=base_url('web/css/ui.css')?>" />
    <link rel="stylesheet" type="text/css" href="<?=base_url('web/css/app.css')?>" />
	<link rel="shortcut icon" href="<?=base_url('favicon.ico')?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    
    <script type="text/javascript" src="<?=base_url('web/js/jquery.min.js')?>"></script>
    <script> 
		var base_url = '<?=base_url_admin()?>';
		var site_url = '<?=base_url()?>';
	</script>
    <!--[if lt IE 9]>  
        <script src="<?=base_url('web/js/html5.js')?>"></script>
    <![endif]-->	
    <script type="text/javascript" src="<?=base_url('web/js/bootstrap.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('web/js/scripts.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('web/js/app.js')?>"></script>
    <?= $header ?>    
</head>
<body class="page-header-fixed <?= $this->uri->segment(1) . ($this->session->userdata('collapse') ? '  page-sidebar-closed': '') . ($this->ion_auth->is_admin() ? ' page-admin' : '') ?>">
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="<?= base_url() ?>">
					Votre outil de gestion en ligne
				</a>
				<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
				<i class="icon-align-justify"></i>
				</a>                    
				<ul class="nav pull-right">
					<!-- BEGIN INBOX DROPDOWN -->
					<?= $hook_nav ?>
					<li class="dropdown" id="header_inbox_bar">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-envelope"></i>
						<span class="badge count-messages">?</span>
						</a>
						<ul class="dropdown-menu extended inbox">
							<li>
								<p>Vous avez <span class="count-messages"></span> messages non traités</p>
							</li>
							<li>
								<h1><span class="count-messages-adherents"></span><small> d'adhérents</small></h1>
								    <hr />
                                <h1><span class="count-max-days"></span><small> jours d'attente</small></h1>
							</li>
							<li class="external">
								<a href="<?= base_url('support') ?>">Accédez aux messages<i class="m-icon-swapright"></i></a>
							</li>
						</ul>
					</li>
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<li class="dropdown user">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<span class="username"><?= $this->session->userdata('username') ?></span>
							<i class="icon-angle-down"></i>
						</a>
						<ul class="dropdown-menu">
							<li><a href="<?=base_url_admin('compte') ?>"><i class="icon-user icon-white"></i> Mon compte</a></li>
							<li class="divider"></li>
							<li><a href="<?=base_url_admin('authentification/logout')?>"><i class="icon-unlock-alt"></i> Se déconnecter</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="page-container">
		<!-- BEGIN SIDEBAR -->
		<div class="page-sidebar nav-collapse collapse">
			<img id="logo-mutuelle-var" src="<?= base_url('web/images/weacms-logo-medium.png') ?>" style="margin:26px 38px;" />
			<ul class="page-sidebar-menu">
				<li>
					<div class="sidebar-toggler hidden-phone"></div>
				</li>
				<li>
					<form class="sidebar-search">
						<div class="input-box">
							<a href="javascript:;" class="remove"></a>
							<input id="top-search" type="text" placeholder="Rechercher" />
							<input type="button" class="submit" value=" " />
						</div>
					</form>
				</li>
				<?= $sidebar ?>
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
		<!-- END SIDEBAR -->	

		<div class="page-content">
			<div class="container-fluid">	
                <div id="top-search-results" class="widget hide"></div>
				<h3 class="page-title lined"><?= $title ?></h3>
				
				<?= $hook_top ?>
				<?= $content ?>
				
                <ul class="breadcrumb gray-content">
                    <?= $breadcrumb ?>
                </ul>   
			</div>
			
		</div>      
		<div class="footer">
			<div class="footer-inner">
					&copy; <?= date('Y') ?> Weacms - Outil de gestion de contenus Web
					| Page rendue en <strong>{elapsed_time}</strong> secondes
					| Mémoire utilisée <strong>{memory_usage}</strong>
			</div>
			<div class="footer-tools">
				<span class="go-top">
				<i class="icon-angle-up"></i>
				</span>
			</div>
		</div>	
	
	<? // Manage flashdata message
	if ($this->session->flashdata('message') != '') : ?>
	
	<script>
		$(function() {
			$.pnotify('<?=$this->session->flashdata('message')?>');
		})
	</script>
		
	<? endif; ?>
	
	<?= $hook_bottom ?>
</body>
</html>