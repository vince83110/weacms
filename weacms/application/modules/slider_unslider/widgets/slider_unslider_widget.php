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

class Slider_unslider extends Widget
{
	// Main configuration
	public $title = 'Slider d\'images - Unslider';
	public $description =  'Affiche une galerie d\'images sur votre page sur toute la longueur';
	public $version	= '1.0';
	public $configure = TRUE;

	// ------------------------------------------------------------------------
		
	public function main_content($gallery_name = '')
	{
		$this->add_js('share');
		$this->add_css('share');

		return $this->view('main_content');
	}

	// ------------------------------------------------------------------------
			
	/**
	 * Install tables for the module
	 */
	public function install_db()
	{
		// Install table for galleries
		$this->table[] = array(
			'slider_unslider_gallery' => array(
                'id_gallery' => array(
					'type' => 'INT',
					'constraint' => 5, 
					'unsigned' => TRUE,
					'auto_increment' => TRUE,
					'primary'	=> TRUE,
				),
                'name' => array(
					'type' => 'VARCHAR',
					'constraint' => '100',
				),
				'id_lang' => array(
					'type'	=> 'INT',
					'constraint' => 2,
				)	
			)
		);		

		// Install table for pictures
		$this->table[] = array(
			'slider_unslider_image' => array(
                'id_image' => array(
					'type' => 'INT',
					'constraint' => 5, 
					'unsigned' => TRUE,
					'auto_increment' => TRUE,
					'primary'	=> TRUE
				),
                'name' => array(
					'type' => 'VARCHAR',
					'constraint' => '100',
				)
			)
		);
	}
}