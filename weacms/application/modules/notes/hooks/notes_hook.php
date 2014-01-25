<?php 
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @type 		hook
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notes_hook extends Hook
{
	public function hook_bottom()
	{
		//$this->template->add_css('jnote', FALSE, 'notes');
		
		$notes = $this->db->select('u.username, n.*')
			->from('module_note n')
			->join('users u', 'u.id = n.id_user', 'left')
			->where(array('url' => $this->uri->uri_string()))->get()->result();
	
		return $this->view('hook_bottom', array('notes' => $notes));
	}
	
	// ------------------------------------------------------------------------
	
	public function hook_nav()
	{
		return $this->view('hook_nav');
	}
}