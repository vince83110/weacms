<?php  
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @type			config file
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Page templates
|--------------------------------------------------------------------------
|
| HTML code to define different templates for a page
| html code is used for visual in your page administration
| Based on bootstrap 2.3.2 scaffholding : http://getbootstrap.com/2.3.2/scaffolding.html
| If you change templates in your theme, you must update this file
| 
| 1 : Template one column
| 2 : Template 2 columns - left column with 1/3 and 2/3
| 3 : Template 2 columns - right column with 2/3 and 1/3
| 4 : Template 3 columns - left and right with  1/6 - 4/6 - 1/6
| 
| @example => array(template name , HTML code for visual , hooks widgets allowed, technical name)
|
*/
$config['page_templates'] = array(
	1 => array(
		'1 colonne', 
		'<div class="span12"></div>',
		array(1, 2),
		'one_column'
	),
	2 => array(
		'2 colonnes gauche', 
		'<div class="span3"></div>
		<div class="span9"></div>',
		array(1, 2, 3),
		'left_column'
	),
	3 => array(
		'2 colonnes droite', 
		'<div class="span9"></div>
		<div class="span3"></div>',
		array(1, 2, 4),
		'right_column'
	),
	4 => array(
		'3 colonnes', 
		'<div class="span2"></div>
		<div class="span8"></div>
		<div class="span2"></div>',
		array(1, 2, 3, 4),
		'two_columns'
	),
	5 => array(
		'Toute la largeur', 
		'<div class="span12"><h3 class="text-center">&lt; 100 % &gt;</h3></div>',
		array(1, 2),
		'full_width'
	),
);
