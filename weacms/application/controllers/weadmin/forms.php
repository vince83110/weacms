<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forms extends MY_AdminController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('form_model');
		$this->load->library('form');
	}

	// ------------------------------------------------------------------------
	
	public function index()
	{
		$this->template->write('title', 'Gestion des formulaires');
		$this->template->add_js('datatable');
		
		$this->template->write_view('content', 'forms/index', array(
			'forms' => $this->form_model->get()
		));
	}

	// ------------------------------------------------------------------------
	
	public function nouveau()
	{
		$this->template->write('title', 'Nouveau formulaire');
		$this->template->write_view('content', 'forms/create', array(
				'form' 			=> NULL,
				'form_fields'	=> array(),
				'fields' 		=> $this->form_model->get_fields()
			));
		$this->template->render();
	}

	// ------------------------------------------------------------------------
	
	public function edition($id)
	{
		$this->template->write('title', 'Modification de formulaire');
		$this->template->add_css('form');
		
		$this->template->write_view('content', 'forms/create', array(
			'form' 			=> $this->form_model->get($id),
			'form_fields'	=> $this->form_model->get_form_fields($id),
			'fields' 		=> $this->form_model->get_fields(),
			'form_html'		=> 'kk'//$this->get_form_view($id)
		));
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get all entries of a form
	 */	
	public function entries($id)
	{
		$this->load->helper('date_fr');
		$this->template->write('title', 'Entrées d\'un formulaire');
		$this->template->add_js('tablesorter');
		$this->template->write_view('content', 'forms/statistics', array(
			'entries'	=> $this->form_model->get_entries($id),
			'id'		=> $id,
			'fields'	=> $this->form_model->get_fields_order($id),
		));	
	}	
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get the HTML code of a field
	 * @param 	int		- type : type of field to generate
	 *
	 * @return	string	- HTML code of the field
	 */	
	public function generate()
	{
		die( $this->form->show_field( $this->input->post('type') ) );
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Save the form data in the database
	 * Insert or update informations if ID != 0
	 *
	 * @return	int 	- the insert ID
	 *			string	- errors if some fields are missing
	 */
	public function update()
	{
		$this->form_validation->set_rules('title', 'Titre', 'required');
		
		$user = $this->ion_auth->get_user();
		
		if($this->form_validation->run()) {
			
			$data = array(
				'title' 		=> $this->input->post('title'),
				'sending' 		=> $this->input->post('sending'),
				'description' 	=> $this->input->post('description'),
				'uid' 			=> $user->id);
			
			// Update the informations
			if ($this->input->post('id') != 0) {
				
				$id = $this->input->post('id');
				$this->form_model->update( $data, $id );
				$this->log_model->add(8, $id);
			}
			// Insert informations
			else {
				
				$id = $this->form_model->add( $data );
				$this->log_model->add(7, $id);
			}
			
			// Update the form fields
			$this->form_model->update_fields($this->input->post(), $id);
			
			die((string)$id);
			
		} else {
			// Some errors ... return to the view
			ob_clean();
			die(validation_errors());
		}			
	}
	
	// ------------------------------------------------------------------------
	
	/* Load a form view in the page
	 * param : $f - contains id of the form
	 */
	public function get_form_view($f_id)
	{				
		return $this->load->view('site/form', array('fid' => $f_id, 'form' => $this->form_model->get($f_id), 'fields' => $this->form_model->get_form_fields($f_id)), TRUE);		
	}		
	
	// ------------------------------------------------------------------------
	
	/* Export a CSV with all form entries
	 * param : $f - contains id of the form
	 */
	public function export_csv($id)
	{				
		$entries 	= (array)$this->form_model->get_entries($id);
		$fields		= (array)$this->db->get_where('forms_fields', array('fid' => $id))->result();	
		$form 		= $this->form_model->get($id);
		
		$data = array();
		$fields_buffer [0] = 'Date';
		foreach($fields as $o) {
			$fields_buffer []=  utf8_decode($o->name);	
		}
		$data[0] = $fields_buffer;
		
		$i = 1;
		foreach($entries as $o) {
			$entries_buffer = array();
			$entries_buffer []= $o[0]->date;
			
			foreach($o as $s) {
				
				$entries_buffer []= utf8_decode($s->real_value);
			}
			$data[$i++] = $entries_buffer;	
		}
		
		$this->load->helper('excel');
		
		echo array_to_csv($data, str_replace(' ', '', strtolower($form->title)).'_'.date('d_m_y').'.csv');
	
	}	
}