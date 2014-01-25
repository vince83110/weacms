<?php
/**
 * Model class for Forms part
 * Database tables used :
 *		- forms
 *		- fields
 *		- forms_fields
 *		- forms_fields_value
 *		- form_entries
 *
 * @author  	Mutuelle du Var
 * @category	Model
 * @version 	1.0
*/
class Form_model extends CI_Model
{
	function add($data)
	{
		$this->db->insert('forms',$data);
		
		return $this->db->insert_id();
	}
	
	// ------------------------------------------------------------------------

	function update($data, $id)
	{
		return $this->db->update('forms', $data, array('id' => $id));
	}
	
	// ------------------------------------------------------------------------
	
	function get($id = NULL, $limit = NULL)
	{
		if (!$id) {
			
			$this->db->select('f.*, COUNT(fs.id) AS nombre, MAX(fs.date) AS last')
				->from('forms AS f')
				->join('forms_sender AS fs', 'fs.fid = f.id', 'left')
				->group_by('f.id');
				
			$query = $this->db->get();
			
			if($query->num_rows() > 0){
				return $query->result();
			}
			
		} else {
			
			$query = $this->db->get_where('forms', array('id' => $id));
			if($query->num_rows()>0){
				return $query->row();
			}
		}
		
		return array();
	}
	
	// ------------------------------------------------------------------------	

	function get_fields()
	{
		$query = $this->db->get('fields');
		
		if($query->num_rows() > 0){
			return $query->result();
		}
		
		return array();
	}
	
	// ------------------------------------------------------------------------	

	function get_form_fields($id)
	{
		$query = $this->db->get_where('forms_fields', array('fid' => $id));
		
		if($query->num_rows() > 0) {
			foreach ($query->result() as $o)
			{
				/* Get the options values */
				$options = $this->db->get_where('forms_fields_values', array('ffid' => $o->id));
				
				$opts = array();
				if($options->num_rows() > 0) {
					
					/* We get all options to generale the full field */
					foreach ($options->result() as $opt)
					{
						$opts []= $opt->value;	
					}
				}
				
				/* $result contain forms_fields values 
				 * if we have options, it's contains an array 'options' 
				 */
				$result [] = (object) array_merge((array)$o, array('options' => $opts));
			}
			
			return $result;
		}
		
		return array();		
	}
	
	// ------------------------------------------------------------------------	

	function get_fields_order($fid)
	{
		$this->db->order_by('order');
		$query = $this->db->get_where('forms_fields', array('fid' => $fid));
		 
		return $query->result();
	}
	
	// ------------------------------------------------------------------------	

	function update_fields($fields, $id)
	{
		$data = array();
		
		/* Delete old values */
		$query = $this->db->query('DELETE ff, ffv FROM forms_fields AS ff LEFT JOIN forms_fields_values AS ffv ON ffv.ffid = ff.id WHERE ff.fid = ?', $id);

		foreach ( $fields as $k => $v )
		{
			if ( is_int($k) ) {
				
				/* Let's start the order at 1 */
				$k++;
				
				$v = explode('_', $v);
					
				/* Building of primary key :
				 * 		id = Order . FormId . 0 . FieldId
				 */
				$primary = $k . $id . '0' . $v[0];
				
				$data []= array(
					'id'		=> $primary,
					'fid' 		=> $id,
					'fdid' 		=> $v[0],
					'required' 	=> $v[2],
					'name'		=> $v[1],
					'order'		=> $k);
				
				/* If we have some options associated
				 * Explode $v[3] to get all options
				 * Then insert in the table 'forms_fields_values' 
				 * ffid is the foreign key
				 */
				if (strlen($v[3]) > 0) {
					
					$opts = explode('|', substr($v[3], 0, -1));
					$i = 0;
					
					foreach ($opts as $o)
					{
						$options []= array(
							'ffid'	=> $primary,
							'order'	=> $i++,
							'value'	=> $o);
					}
					
					$this->db->insert_batch('forms_fields_values', $options);
				}
			}
		}
		
		/* Insert new values, if we have fields */
		if (count( $data )) {
			
			return $this->db->insert_batch('forms_fields', $data);
		}
		
		return FALSE;
	}
	
	// ------------------------------------------------------------------------	
	
	/* Get all entries of a form */
	function get_entries($fid, $fsid = NULL)
	{
		$this->db->select('fs.id, fs.ip, fs.date, fe.value as real_value, ff.name, ffv.value, ff.order, ff.fdid')
			->from('forms_fields AS ff')
			->join('forms_entries as fe', 'ff.id = fe.ffid', 'left')
			->join('forms_sender as fs', 'fe.fsid = fs.id', 'left')
			->join('forms_fields_values as ffv', 'ffv.ffid = fe.ffid AND ffv.order = fe.value', 'left')
			->where('fs.fid = '. $fid . ($fsid ? ' AND fs.id = '. $fsid : ''))
			->order_by('fs.date DESC, ff.order ASC');
	
		$query = $this->db->get();
		
		if($query->num_rows()>0) {
			
			if ($fsid) 
			{
				return $query->result();	
			}
			
			foreach ($query->result() as $o)
			{
				$results[$o->id] []= $o;	
			}
			
			return $results;
		}
		
		return array();
	}	
	
	// ------------------------------------------------------------------------	
	
	/* Save some informations about form sender */
	function save_sender($data)
	{
		$this->db->insert('forms_sender', $data);
		
		return $this->db->insert_id();
	}	
	
	// ------------------------------------------------------------------------	
	
	/* Save fields values in database */
	function save_values($fields, $fsid)
	{
		$data = array();
		
		foreach ($fields as $k => $v) 
		{
			/* In case of checkboxes, the values are concatened */
			if (is_array($v)) {
				$buffer_v = '';
				
				foreach ($v as $o) {
					$buffer_v .= $o .' - '; 	
				}
			}
			
			$data []= array(
				'ffid' 	=> $k,
				'fsid'	=> $fsid,
				'value'	=> is_array($v) ? substr($buffer_v, 0, -2) : $v);
		}
		
		return $this->db->insert_batch('forms_entries', $data);
	}	
	
	// ------------------------------------------------------------------------	

	function get_name($id)
	{
		$query = $this->db->get_where('fields', array('id' => $id));
		
		if($query->num_rows() > 0){
			return $query->row()->name;
		}
		
		return '';
	}
}