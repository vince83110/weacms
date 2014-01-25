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

class Form extends CI_Controller 
{
	/**
	 * Insert form value in database
	 * Send email to administrator if receiver has been set
	 * 
	 * @return	json_response 
	 */	
	public function index()
	{			
		if (count($_POST) == 0) 
		{			
			redirect(base_url());
		}
	
		$this->load->model('form_model');
		
		$fid = $this->input->post('fid');
		$fsid = $this->form_model->save_sender( array('ip' => $this->input->ip_address(), 'fid' => $fid) );
		$form = $this->form_model->get( $fid );

		/* Delete index 'fid' of the Post array */
		unset( $_POST['fid'] );
		
		/* Get the receiver */
		$receiver = isset($_POST['receiver']) ? $this->input->post('receiver') : $form->sending;
		unset( $_POST['receiver'] );
		
		/* Save all values in database */
		$this->form_model->save_values($this->input->post(), $fsid );
		
		/* Send email information */
		if (strlen($receiver))
		{
			$data = $this->form_model->get_entries($fid, $fsid);

			/* Define the sender */
			$sender = 'ne-pas-repondre@mutuelle-emoa.fr';
            foreach ($data as $o) 
            {
				if (isset($o->real_value) && strlen($o->real_value)) 
				{
					if (filter_var($o->real_value, FILTER_VALIDATE_EMAIL)) 
					{
						$sender = $o->real_value;
					}
				}
            } 
			
			$message = $this->load->view('site/form_mail', array('data' => $data, 'title' => $form->title), true);

			$this->email->clear();
			$config['mailtype'] = 'html';
			$this->email->initialize( $config );
			$this->email->from($sender, 'Emoacube');
			$this->email->to($receiver);
			$this->email->subject('Site internet - ' . $form->title);
			$this->email->message($message);
	
			if ($this->email->send() == TRUE)
			{
				response_json(TRUE);
			}
		}
	}
}