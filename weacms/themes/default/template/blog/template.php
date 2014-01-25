<?php
/**
 * Main template view
 *
 * @package		Weacms
 * @author		Vincent DECAUX
 * @link		http://www.weacms.com
 * @type		template
 */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?=$page->description?>" />
	<title><?=$page->title?></title>
	
	<link rel="stylesheet" href="<?= theme_web_url('css/bootstrap.css') ?>" type="text/css" />
	<link rel="stylesheet" href="<?= theme_web_url('css/app.css') ?>" type="text/css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
	
    <link rel="icon" type="image/png" href="<?= theme_web_url('favicon.png') ?>" />
	<?= $css ?>
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script> var base_url = '<?=base_url()?>'; </script>
</head>
<body<?= (strlen($page->class) ? ' class="' . $page->class .'"' : '' ) ?>>
	<div class="container">
		<div id="top-panel">
            <div class="login-header">
                <div class="avatar"><i class="linecon-user"></i></div><div class="links"><a href="#" class="drop-login" data-reveal-id="loginModal">Me connecter</a></div>
            </div>
            <div class="top-info">+38 032 900 34 45 <span class="delim"></span> Lundi - Vendredi 10:00 - 18:00</div>		

            <div class="head-soc-icons"><span>Nous suivre:</span>
                <div class="soc-icons">
                    <a href="http://facebook.com" class="fb soc_icon-facebook" title="Facebook"></a>
                    <a href="1" class="gp soc_icon-google__x2B_" title="Google +"></a>
                    <a href="http://twitter.com" class="tw soc_icon-twitter-3" title="Twitter"></a>
				</div>
        	</div>    	
		</div>
        <header>
        	<div class="row-fluid">
	            <div id="logo-holder">
	                <a id="logo" href="<?= base_url('fr/accueil') ?>">
	                    <img src="<?= theme_web_url('images/logo-agasm.png') ?>" alt="AGASM" >
	                </a>
	            </div>
	            <div id="nav-wrapper">
	            	<ul id="menu-primary-navigation" class="tiled-menu">
	                    <?= $menu ?>
	                </ul>
	            </div>
            </div>
        </header>  
	    <div class="row-fluid main">
	        <?= $content ?>
	    </div>
	</div>
	
	<section id="footer">
	    <div class="row-fluid">
	    	<div class="span4">
				<?= $breadcrumb ?>	
	        </div>
	    	<div class="span4">
	    		<?= $hook_template['content_bottom_template'] ?>
	    	</div>
	    </div>
	</section>	
		
	<section id="sub-footer">
		<div class="container">
		    <div class="row-fluid">
		        <div class="span6 copyr">
					<img src="http://theme.crumina.net/second/wp-content/themes/secondtouch/assets/img/logo-footer.png" alt="logo" class="foot-logo">
					&copy; <?=date('Y')?> - AGASM - Mentions légales
		        </div>
		        <div class="span6">
					<ul id="menu-footer" class="footer-menu"><li class="current_page_item"><a href="http://theme.crumina.net/second/">Home</a></li>
						<li><a href="http://theme.crumina.net/second/theme-features/">Features</a></li>
						<li><a href="http://theme.crumina.net/second/portfolio/">Portfolio</a></li>
						<li><a href="http://theme.crumina.net/second/blog-page/">Blog</a></li>
						<li><a href="http://theme.crumina.net/second/shop/">Shop</a></li>
						<li><a href="http://theme.crumina.net/second/theme-features/shortcodes/">Shortcodes</a></li>
						<li><a href="http://theme.crumina.net/second/additional-blocks/">Additional blocks</a></li>
					</ul>
		        </div>
		    </div>
	    </div>
	</section>
	
	<script src="<?= theme_web_url('javascripts/foundation.js')?> "></script>
	<script src="<?= theme_web_url('javascripts/app.js')?> "></script>
    <?= $js ?>
</body>
</html>
