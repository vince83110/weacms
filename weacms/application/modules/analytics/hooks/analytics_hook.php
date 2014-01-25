<?php 
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @category		hook
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Analytics_hook extends Hook
{
	public function hook_home()
	{
		return 'ANALYTICS HOME';
	}
}