<?php
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @category		config file
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Widgets page hooks
|--------------------------------------------------------------------------
|
| Define active areas to place widgets on page
| You can add some, don't forget to refresh your widget to hook them to your new hooks
|
*/
$config['widgets_page_hooks'] = array(
	1 => array('content_bottom', 'Pied de page du contenu'),
	2 => array('content_top', 'Haut de page'),
	3 => array('right_column', 'Colonne de droite'),
	4 => array('left_column', 'Colonne de gauche'),
	5 => array('main_content', 'Contenu de la page'),
);

/*
|--------------------------------------------------------------------------
| Widgets template hooks
|--------------------------------------------------------------------------
|
| Define active areas to place widgets on template
| You can add some, don't forget to refresh your widget to hook them to your new hooks
|
*/
$config['widgets_template_hooks'] = array(
	1 => array('content_bottom_template', 'Pied de page du template'),
	2 => array('content_top_template', 'Haut de page du template'),
	3 => array('menu_menu_template', 'Menu principal'),
	4 => array('sub_menu_template', 'Menu secondaire'),
);
