<?php
class Ws_model extends CI_Model
{
	function search($q)
	{
		$this->db->select('pages.id, pages.title, pages.description, CONCAT("pages/edition/", pages.id ) AS link');
		$this->db->from('pages');
		$this->db->like('title', $q); 
		$this->db->limit(10);
		
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$rows = $query->result();
			return $rows;
		}
		
		return array();
	}
	
	// ------------------------------------------------------------------------

	function online()
	{
		$this->db->select('distinct(u.id), u.username as u');
		$this->db->from('ci_sessions s');
		$this->db->join('users u', 'u.id = s.user_id');
		$this->db->where(array('s.user_id !=' => $this->session->userdata('user_id'), 's.last_activity > ' => time() - 120));
		
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$rows = $query->result();
			return $rows;
		}
		
		return array();	
	}
}