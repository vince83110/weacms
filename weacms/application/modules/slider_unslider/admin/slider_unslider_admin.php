<?php 
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @category		controller
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider_unslider_admin extends WidgetController
{
	// Define table and id for the module
	private $table 	= 'widget_slider_unslider';
	private	$id		= 'id';
	
	/**
	 * Define params to build auto crud
	 * @return	array 	contain all params
	 */
	private function params()
	{
		return array(
            'button' => 'Ajouter une nouvelle actualité',
            'title' => 'Gestion des actualités',
            'id' => $this->id,
            'data' => $this->db->get($this->table)->result(),
			'image_url' => base_url('theme/assets/images') .'/',
            'fields' => array(
				'Image' => 'image',
                'Titre' => 'title',
                'Catégorie' => 'id_category',
                'Date de création' => 'created',
                ),
            'form' => array(
                array('Titre de l\'actualité', 'title', 'input'), 
                array('Catégorie', 'id_category', 'select', $categories),
                array('Image de présentation', 'image', 'file'),           
                array('Contenu', 'content', 'fulltext'),            
            )
		);
	}
	
	public function index()
	{
		
	}
	
	public function get()
	{
		echo 'ook';
	}
}
	