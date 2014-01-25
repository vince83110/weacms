<?php 
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @type			library
*/
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Ion_security
{
	var $CI;
		
   /**
    * Redirect users to authenfication page if they are not logged
    * Define a list of logless controllers 
    */   	
    public function __construct()
    {
        $this->CI	=& get_instance();
		
        // List of accessible controllers 
        $controllers_free = array('authentification');
		
		// Get uri loaded for smart redirect
		$uri = $this->CI->uri->uri_string();
		
		// Get admin segment name
		$admin_segment = trim($this->CI->config->item('admin_url'), '/');
				
		// Test 2nd segment of URI, 1st is admin_url
		// and test if we are in admin controllers
        if ($this->CI->uri->segment(1) == $admin_segment && ! $this->CI->ion_auth->logged_in() && (! in_array($this->CI->uri->segment(2), $controllers_free))) 
        {
            redirect($this->CI->config->item('admin_url') .'authentification/login?uri='. $uri); 
        }
    }  
   
}