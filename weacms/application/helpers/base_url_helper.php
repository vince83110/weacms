<?php
/**
 * Weacms
 *
 * @package		Weacms
 * @author		Vincent DECAUX
 * @link		http://www.weacms.com
 * @since		Version 1.0
 * @category	helper
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * Local_url
 * Return the local URL 
 *
 * @param	string
 * @param   boolean force CLI execution
 * @return	str
 */
 
function local_url($uri = '', $cli = false)
{
	$ci =& get_instance();	
	$cli = $ci->input->is_cli_request() ? true : $cli;
	
	return ($cli ? '/var/www' : $_SERVER['DOCUMENT_ROOT']) .'/'. $uri;
}
    
// ------------------------------------------------------------------------

/**
 * File_url
 * Return the URL to file folder
 *
 * @param	string
 * @param   boolean force relative path
 * @param   boolean allow to sanitize the filename
 * @return	str
 */
 
function assets_url($uri = '', $relative = false, $sanitize = true, $date = false)
{
	$ci =& get_instance();	
	$date_folder = $date ? date('Y', $date) .'/'. date('m', $date) .'/' : '';
	
	return $relative ? 
		base_url('web/assets/'. $date_folder . ($sanitize ? sanitize_url($uri) : $uri)) :
		local_url('web/assets/'. $date_folder . ($sanitize ? sanitize_url($uri) : $uri));
}

// ------------------------------------------------------------------------

/**
 * Theme_web_url
 * Return the URL to web theme folder
 *
 * @param	string
 * @return	str
 */
 
function theme_web_url($uri = '')
{
	$ci =& get_instance();	
	
	return base_url('themes/'. $ci->config->item('theme') .'/web/'. $uri);
}
        
// ------------------------------------------------------------------------

/**
 * Sanitize_url
 * Return a string sanitized of any wrong character
 *
 * @param	string
 * @param   boolean force to just convert accents
 * @return	str
 */
 
function sanitize_url($str, $name = false) 
{
	setlocale(LC_ALL, 'en_US.UTF8'); 
	$str = !mb_detect_encoding($str, 'UTF-8', true) ? utf8_encode($str) : $str;
	$str = @iconv('UTF-8', 'UTF-8//IGNORE', $str);
	
	if ($name) 
	{
		return $str;
	}
	
	include(APPPATH.'config/foreign_chars.php');
	$str = preg_replace(array_keys($foreign_characters), array_values($foreign_characters), $str);
			
	$ext_point = strripos($str,'.');
	if ($ext_point===false) return false;
	$ext = substr($str,$ext_point,strlen($str));
	$str = substr($str, 0, $ext_point);        
	
	$str = url_title($str, '_');
	
	return $str . $ext;
}
        
// ------------------------------------------------------------------------

/**
 * Response_json
 * Return JSON response with given array
 *
 * @param	int		1 to success
 * @param   object 	response object 
 * @param   str 	message to show 
 * @return	str
 */
 
function response_json($success, $items = false, $message = '')
{
	$response['success'] = $success;            
	$response['message'] = $message;
	
	if ($items) {
		$response['items'] = $items;
	}
	
	die( json_encode($response) );
}

// ------------------------------------------------------------------------

/**
 * Base_url_admin
 * Return the admin URL
 * 
 * @param	string
 * @return	str
 */
 
function base_url_admin($uri = '')
{
	$ci =& get_instance();
	
	return $ci->config->item('base_url') . $ci->config->item('admin_url') . $uri;
}

// ------------------------------------------------------------------------

/**
 * Web_url
 * Return the web ressources URL
 * 
 * @param	string
 * @return	str
 */
 
function web_url($uri = '')
{
	$ci =& get_instance();
	
	return $ci->config->item('base_url') . 'web/' . $uri;
}