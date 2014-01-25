<?php
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @type 		widget
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Share_widget extends Widget
{
	// Main configuration
	public $title = 'Boutons de partage';
	public $description =  'Affiche les boutons de partage sur les réseaux sociaux';
	public $version	= '1.0';
	public $configure = FALSE;

	// ------------------------------------------------------------------------
		
	public function content_bottom()
	{
		$this->add_js('share');
		$this->add_css('share');

		return $this->view('content_bottom');
	}
}