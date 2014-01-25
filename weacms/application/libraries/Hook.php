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

class Hook
{
    public $module_path;

	// ------------------------------------------------------------------------
	
   /**
    * Load hook module and execute hook_name function
    * 
    * @access  	public
    * @return  	function
    */   
    public function run($file, $hook_name) 
    {    	        
        $args = func_get_args();
		
		$_path =  'modules/' . $file .'/hooks/';
		$file .= '_hook';

        $module = '';
		$path = APPPATH . $_path;

        Modules::load_file($file, $path);

        $file = ucfirst($file);
        $widget = new $file();

        $widget->module_path = $path;

        return call_user_func_array(array($widget, $hook_name), array_slice($args, 1));    
    }

	// ------------------------------------------------------------------------
	
   /**
    * Render hook view
    * 
    * @access  	public
    * @return	str
    */   
    public function view($view, $data = array()) 
    {
		ob_start();
		
        extract($data);
        include str_replace('/hooks', '', $this->module_path) . 'views/'. $view . EXT;
		
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
    public function __get($var) 
    {
        return get_instance()->$var;
    }
} 