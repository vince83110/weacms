<?php 
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @category		widget
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Analytics extends Widget
{
	// Main configuration
	public $title = 'Google Analytics sur vos pages';
	public $description =  'Vous permet de configurer Google Analytics pour votre site';
	public $version	= '1.0';

	// ------------------------------------------------------------------------
	
	/**
	 * Return javascript Google Analytics code in your page bottom
	 */				
	public function content_bottom_template()
	{
		// Disabled widget in admin mode
		if ($this->uri->segment(1) == trim($this->config->item('admin_url'), '/')) 
		{
			return '';
		}
		
		return $this->view('content_bottom_template', array(
			'code_site'	=> $this->get_configuration('code_site')
		));
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Install function called at widget installation
	 * Add entry in configuration table for code_site analytics
	 */				
	public function install()
	{
		$this->add_configuration('code_site', '', 'Votre code Google Analytics pour votre site');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Remove function called when refreshing module installation
	 * Delete code_site entry from database
	 */				
	public function remove()
	{
		$this->remove_configuration('code_site');
	}
}