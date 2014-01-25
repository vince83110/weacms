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
	</div>
	
	<?= $content ?>
	
	<section id="footer">
		<div class="container">
		    <div class="row-fluid">
		    	<div class="span1">
		    		<?= $hook_template['content_bottom_template'] ?>
		    	</div>
		    	<div class="span7">
		    		<h3 class="widget-title">Derniers événements</h3>
				    <ul class="recent-posts-list">
				        <li class="clearfix sticky-post">
				            <div class="entry-thumb">
				                <a class="more" href="#">
				                	<img src="http://agasm.weacms.com/themes/default/web/css/img/menu-2.jpg">
				                </a>
				            </div>
				            
				            <h4 class="entry-title">
				            	<a href="#" rel="bookmark">
						            Claritas est etiam processus dynamicus, qui sequitur
						            mutationem
						        </a>
						    </h4>
				
				            <div class="entry-meta dopinfo">
				                <span class="byline author vcard animated">Agasm</span> 
				                <span class="delim animated"></span> 
				                <span class="entry-date animated">Mai 18, 2013</span>
				            </div>
				        </li>
				
				        <li class="clearfix">
				            <div class="entry-thumb">
				                <a class="more" href="#">
				                	<img src="http://agasm.weacms.com/themes/default/web/css/img/menu-home.jpg">
				                </a>
				            </div>
				
				            <h4 class="entry-title">
				            	<a href="#" rel="bookmark">
						            Claritas est etiam processus dynamicus, qui sequitur
						            mutationem
						        </a>
						    </h4>
				
				            <div class="entry-meta dopinfo">
				                <span class="byline author vcard animated">Agasm</span> 
				                <span class="delim animated"></span> 
				                <span class="entry-date animated">Mai 18, 2013</span>
				            </div>
				        </li>
				    </ul>    		
		    	</div>
		    	<div class="span4">
		    		<h3 class="widget-title">Qui sommes-nous ?</h3>
		    		<p>
		    			"Il y en a qui veulent construire l’Europe par le haut... nous autres, sous-mariniers, nous commençons par le bas..."
		    		</p>
		    		<p>&nbsp;</p>
		    		<address>
		    			1947	 	Les évènements ne pouvant altérer le moral des sous-mariniers, Fouquet avec l’appui de l’Amiral Lacaze, constitue le premier bureau de l’A.G.A.A.S.M..
	16 Octobre 1951	 	Les statuts et le règlement intérieur (agréés par le ministre de la Marine ) sont enregistrés par la préfecture de police sous le n° 51909.
		    		</address>
		    	</div>
		    </div>
	    </div>
	</section>	
		
	<section id="sub-footer">
		<div class="container">
		    <div class="row-fluid">
		        <div class="span6 copyr">
					<img src="<?= theme_web_url('images/logo-agasm-footer.png') ?>" alt="Agasm" class="foot-logo">
					&copy; <?= date('Y') ?> - AGASM - Mentions légales
		        </div>
		        <div class="span6">
					<ul id="menu-footer" class="footer-menu">
						<li><a href="<?= base_url() ?>">Accueil</a></li>
						<li><a href="<?= base_url() ?>">Qui sommes-nous</a></li>
						<li><a href="<?= base_url() ?>">Nous contacter</a></li>
						<li><a href="<?= base_url() ?>">Espace privé</a></li>
						<li><a href="<?= base_url() ?>">Réalisé par Weacms</a></li>
					</ul>
		        </div>
		    </div>
	    </div>
	</section>
	
	<script src="<?= theme_web_url('js/app.js') ?>"></script>
    <?= $js ?>
</body>
</html>
