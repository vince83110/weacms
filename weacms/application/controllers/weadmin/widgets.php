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
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Widgets extends MY_AdminController
{
	/**
	 * Constructor
	 * load widget model
	 */	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('widget_model');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * List all widgets from database
	 * @return	view
	 */		
	public function index()
	{
		$this->template->write('title', 'Gestion des widgets');
		$this->template->add_js('datatable');
		
		$this->template->write_view('content', 'widgets/index', array(
			'widgets' => $this->widget_model->get()
		));
	}
	
	// ------------------------------------------------------------------------
	
	public function configurer($id)
	{
		$widget = $this->widget_model->get($id);
		$values = $this->widget_model->get_values($widget->class);
		$file = strtolower($widget->class);
		
		// Load the Controller file of the widget 
		Modules::load_file($file, APPPATH . 'modules/'. strtolower( $widget->class ) .'/config.php');
		
		$this->template->write('title', 'Configuration widget - ' . $widget->title);
		
		// Create the controller object 
		$controller = new $widget->class;
		
		// Test if widget override configuration view
		if (class_exists($widget->class . '_configure')) 
		{	
			$config = $widget->class . '_configure';
			$configurator = new $config;
			$this->template->write_view('content', 'widgets/configure', array('configure' => $configurator->configure('../../modules/'. strtolower( $widget->class ) .'/')));				
		} 
		else 
		{
			$this->template->AddJS(array('tablesorter', 'tinymce/tiny_mce'));
			
			// Get the get_fields method of the controller to show the fields
			$this->template->write_view('content', 'widgets/create', array('widget' => $widget, 'values' => $values, 'fields' => $controller->get_fields()));	
		}
		
		$this->template->render();	
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Refresh widgets list
	 * And redirect to index()
	 */		
	public function template()
	{
		$this->template->write('title', 'Gestion des widgets sur le template');
		$this->config->load('widgets');
		
		$this->template->write_view('content', 'widgets/template_widgets', array(
			'widgets' => $this->widget_model->get_template_widgets_available(),
			'widgets_hooks' => $this->config->item('widgets_template_hooks'),
			'template_widgets'	=> $this->widget_model->get_template_widgets_hooked(),
		));
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Update template widgets list in database
	 * @return	json
	 */			
	public function update_widgets_template()
	{
		if ($this->widget_model->update_template_widgets($this->input->post('widgets')))
		{
			response_json(TRUE);
		}
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Refresh widgets list
	 * And redirect to index()
	 */		
	public function refresh()
	{
		$this->widget_model->refresh();
		$this->session->set_flashdata('message', 'La liste des widgets a bien été mise à jour.');
		
		redirect($this->config->item('admin_url') . 'widgets', 'refresh');
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Display admin config for widget
	 * @param	int		widget id
	 * @param	str		action to call
	 */	
	public function admin($id_widget, $action = 'index')
	{
		$this->load->library('admin');	
		
		$widget = $this->widget_model->get($id_widget);
		
		Admin::run($widget->class, $action);
	}
}