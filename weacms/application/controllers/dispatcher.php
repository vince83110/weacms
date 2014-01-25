<?php 
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @type			controller
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dispatcher extends MX_Controller 
{
	/**
	 * Dispatch URI and open the cached file with the same MD5 signature
	 * Open the 404 page if no page or module exist
	 * 
	 * @return	str 
	 */	
	public function index()
	{
		$cache_path = APPPATH .'cache/';
		
		$uri = $this->uri->uri_string();
		
		// Get URI string
		$page = md5(str_replace('.html', '', $uri));
		$file_page = $cache_path . $page. '.gz';
		
		if (file_exists($file_page))
		{
			header('Content-Cache-Date : '. date('d F Y H:i:s.', filemtime($cache_path . $page. '.gz')));
			
			// Output cache file content
			die(readgzfile($file_page));
		}
		else
		{
			show_error('404');
		}
	}
}