<?php 
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @category		core extension
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Extends MX_Controller
 * Test if user is logged and redirect him to login page
 */
class MY_AdminController extends MX_Controller 
{
    public function __construct()
    {
        parent::__construct();

        // List of accessible controllers 
        $controllers_free = array('authentification');
		
		// Get uri loaded for smart redirect
		$uri = $this->uri->uri_string();
				
		// Test 2nd segment of URI, 1st is admin_url
        if (! $this->ion_auth->logged_in() && (! in_array($this->uri->segment(2), $controllers_free))) 
        {
            redirect($this->config->item('admin_url') .'authentification/login?uri='. $uri); 	
			exit();
		}
    }
}

// ------------------------------------------------------------------------

/**
 * Extends MY_AdminController
 * Test URI and redirect him to base URL
 * Deny modules access by direct URL
 */
class WidgetController extends MY_AdminController
{
    public function __construct()
    {
        parent::__construct();
				
		// Test 1st segment of URI
        if (! $this->ion_auth->logged_in()) 
        {
            redirect($this->config->item('base_url')); 	
			exit();
		}
    }
}