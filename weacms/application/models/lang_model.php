<?php
/**
 * Model class for Pages part
 * Database tables used :
 *		- langs
 *
 * @category	Model
 * @version 	1.0
*/
class Lang_model extends CI_Model
{	
	/* Get langs from database
	 */ 
	function get($id = 0)
	{
		/* Get the langs */
		if (! $id) {
			
			return $this->db->get('langs')->result();
		} else {
			
			return $this->db->get_where('langs', array('id_dl' => $id))->row();
		}	
	}
	
	function get_main()
	{
		return $this->db->get_where('langs', array('main' => 1))->row()->id_dl;
	}
}