<?php
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @type 		model
*/
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Log_model extends CI_Model
{	
	function add($action, $src)
	{
		$this->db->delete('logs', array('uid' => $this->session->userdata('user_id'), 'src' => $src, 'created >' => ' DATE_SUB( curdate(), INTERVAL 2 HOURS )' )); 
		
		$data = array(
					'uid' => $this->session->userdata('user_id'),
					'action' => $action,
					'src' => $src);
		
		return $this->db->insert('logs', $data);
	}	
	
	// ------------------------------------------------------------------------
	
	function garbage_collector($table, $id)
	{
		$query = $this->db->query('DELETE l FROM '. $this->db->dbprefix('logs') .' l LEFT JOIN '. $this->db->dbprefix($table) .' t ON l.src = t.'. $id .' WHERE t.'. $id .' IS NULL');	
	}
	
	// ------------------------------------------------------------------------
	
	function get($id = NULL, $limit = NULL)
	{
		if (!$id){
			
			$this->db->select('logs.*, users.username');
			$this->db->from('logs');
			$this->db->join('users', 'users.id = logs.uid', 'left');
			$this->db->limit($limit);
			$this->db->order_by('logs.created', 'desc');

			$query = $this->db->get();
			
			if($query->num_rows() > 0) {
				$rows = $query->result();
				$i = 0;
				
				foreach ($rows as $o)
				{
					$rows[$i++]->action = $this->display($o);
				}
				return $rows;
			}	
			
		} else {
			
			$query = $this->db->get_where('logs', array('id' => $id));
			if($query->num_rows()>0){
				return $query->row();
			}			
		}
		
		return array();
	}
	
	// ------------------------------------------------------------------------
	
	function delete($src)
	{
		return $this->db->delete('logs', array('src' => $src));	
	}
	
	// ------------------------------------------------------------------------
	
	function display($log)
	{
		$actions = array(
			1 => array('a créé une page', 'pages/voir/', 'pages'),	
			2 => array('a modifié une page', 'pages/voir/', 'pages'),	
			3 => array('a validé une page', 'pages/voir/', 'pages'),
			4 => array('a effacé une page', 'pages/voir/', 'pages'),
			5 => array('a restauré une page', 'pages/voir/', 'pages'),
			6 => array('a écrit un article', 'articles/voir/', 'articles'),
			7 => array('a créé un formulaire', 'formulaires/voir/', 'forms'),
			8 => array('a modifié un formulaire', 'formulaires/voir/', 'forms'),
			9 => array('a créé un article', 'articles/voir/', 'articles', 'id_ar'),	
			10 => array('a modifié un article', 'articles/voir/', 'articles', 'id_ar'),	
			11 => array('a validé un article', 'articles/voir/', 'articles', 'id_ar'),
		);
		
		$id = isset($actions[$log->action][3]) ? $actions[$log->action][3] : 'id';
		
		$this->db->select('title');
		$this->db->from($actions[$log->action][2]);
		$this->db->where(array($id => $log->src));

		$query = $this->db->get();	

		if (! count($query->row()) )
		{
			$this->garbage_collector( $actions[$log->action][2], $id );
			return;
		}
		$title = $query->row()->title;
		
		return ' ' . $actions[$log->action][0] . ' <blockquote><small><a href="'. base_url_admin() . $actions[$log->action][1] . $log->src .'" target="_blank">'. $title .'</a></small></blockquote>';
	}
}