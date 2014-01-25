<?php
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @category		library
*/
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Widget
{
    public $widget_path;
	public $widget_name;
	public $js = '';
	public $css = '';

	// ------------------------------------------------------------------------
	
   /**
    * Load widget module and execute zone function
    * 
    * @access  	public
    * @return  	function
    */   	
    public function run($file, $zone) 
    {    	        
        $args = func_get_args();
		$name = strtolower(str_replace('_widget', '', $file));
		
		// We keep "_widget" in controller name in case of a controller exist in module
		$path =  APPPATH .'modules/' . $name .'/widgets/';

        $module = '';
		
        Modules::load_file($file, $path);

		// Ucfirst controller name
		$file = ucfirst($file);
        $widget = new $file();

        $widget->widget_path = $path;
		$widget->widget_name = $name;

        return call_user_func_array(array($widget, $zone), array_slice($args, 1));    
    }

	// ------------------------------------------------------------------------
	
   /**
    * Render widget view
    * 
    * @access  	public
    * @return	str
    */   
    public function view($view, $data = array()) 
    {
		ob_start();
		
        extract($data);
        include str_replace('/widgets', '', $this->widget_path) . 'views/'. $view . EXT;

		$buffer = ob_get_contents();
		@ob_end_clean();
		
		return $buffer;		
    }

	// ------------------------------------------------------------------------
	
   /**
    * Enables the use of CI super-global without having to define an extra variable.
    * 
    * @access	public
    * @return  	CI instance
    */      	
    function __get($var) 
    {
        return get_instance()->$var;
    }
	
	// ------------------------------------------------------------------------
	
   /**
    * Add js widget file in js template list
    * 
    * @access	public
    */   	
	public function add_js($file)
	{
		$path = base_url('application/modules/' . $this->widget_name .'/web/js');
		
		$this->js .= $this->template->add_js($file, TRUE, $path);
	}
	
	// ------------------------------------------------------------------------
	
   /**
    * Add css widget file in css template list
    * 
    * @access	public
    */   	
	public function add_css($file)
	{
		$path = base_url('application/modules/' . $this->widget_name .'/web/css');
		
		$this->css .= $this->template->add_css($file, TRUE, $path);
	}
	
	// ------------------------------------------------------------------------
	
   /**
    * Add widget configuration in database
    * 
    * @access	public
    */  	
	public function add_configuration($name, $value, $description)
	{
		$this->db->insert('configuration', array(
			'name' => $this->widget_name .'_'. $name,
			'value'=> $value,
			'description'	=> $description
		));
	}
	
	// ------------------------------------------------------------------------
	
   /**
    * Remove widget configuration from database
    * 
    * @access	public
    */   	
	public function remove_configuration($name)
	{
		$this->db->delete('configuration', array(
			'name' => $this->widget_name .'_'. $name,
		));
	}
	
	// ------------------------------------------------------------------------
	
   /**
    * Return configuration value
    * 
    * @access	public
    * @param	str		configuration name
    * @return	str		configuration value
    */   	
	public function get_configuration($name)
	{
		return $this->db->get_where('configuration', array('name' => $this->widget_name .'_'. $name))->row()->value;
	}
	
	// ------------------------------------------------------------------------
	
   /**
    * Set name
    * 
    * @access	public
    */       
    public function set_name($name)
	{
		$this->widget_name = $name;
	}
} 