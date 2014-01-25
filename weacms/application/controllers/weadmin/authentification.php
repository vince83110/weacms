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
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentification extends MY_AdminController
{
	/**
	 * Index Page for this controller.
	 * Return login page or homepage
	 */	
	public function index()
	{
		if (! $this->ion_auth->logged_in())
		{
			$this->login();
		}
		else
		{			
			redirect($this->config->item('admin_url'));
		}
	}

	// ------------------------------------------------------------------------
	
	public function login()
	{
		$this->data = array();
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		
		// Get uri passed in get or post
		$this->data['uri'] = $this->input->get('uri') ? $this->input->get('uri') : ($this->input->post('uri') ? $this->input->post('uri') : $this->config->item('admin_url'));

		//var_dump($this->ion_auth->change_password('v.decaux@mutuelle-var.fr', 'admin', 'test'));

		if ($this->ion_auth->logged_in())
		{
			// Already logged in so no need to access this page
			redirect($this->config->item('admin_url'));
		}

		// Validate form input
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == TRUE)
		{	
			$remember = (bool) $this->input->post('remember');
			
			if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
			{
				// If the login is successful
				redirect($this->data['uri']);
			}
			else
			{ 
				// If the login was un-successful
				$this->session->set_flashdata('message', $this->ion_auth->errors());
			}
		}
		else
		{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			
			// Get value in post for email
			$this->data['email'] = $this->input->post('email') ? $this->input->post('email') : '';

			$this->load->view($this->config->item('admin_url') .'authentification/login', $this->data);
		}
	}

	// ------------------------------------------------------------------------
	
	function logout()
	{
		$this->data['title'] = "Logout";

		// Log the user out
		$logout = $this->ion_auth->logout();
		
		// Redirect them back to the page they came from
		redirect($this->config->item('admin_url') .'authentification', 'refresh');
	}

	// ------------------------------------------------------------------------
	
	function change_password()
	{
		$this->data = array();
		$this->form_validation->set_rules('old', 'Old password', 'required');
		$this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('authentification/login', 'refresh');
		}
		$user = $this->ion_auth->get_user($this->session->userdata('user_id'));

		if ($this->form_validation->run() == false)
		{ //display the form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['old_password'] = array('name' => 'old',
				'id' => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array('name' => 'new',
				'id' => 'new',
				'type' => 'password',
			);
			$this->data['new_password_confirm'] = array('name' => 'new_confirm',
				'id' => 'new_confirm',
				'type' => 'password',
			);
			$this->data['user_id'] = array('name' => 'user_id',
				'id' => 'user_id',
				'type' => 'hidden',
				'value' => $user->id,
			);

			//render
			$this->load->view($this->config->item('admin_url') .'authentification/change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{ //if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect($this->config->item('admin_url') .'authentification/change_password', 'refresh');
			}
		}
	}

	// ------------------------------------------------------------------------
	
	function forgot_password()
	{
		//get the identity type from config and send it when you load the view
		$identity = $this->config->item('identity', 'ion_auth');
		$identity_human = ucwords(str_replace('_', ' ', $identity)); //if someone uses underscores to connect words in the column names
		$this->form_validation->set_rules($identity, $identity_human, 'required');
		
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data[$identity] = array('name' => $identity,
				'id' => $identity, //changed
			);
			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['identity'] = $identity; $this->data['identity_human'] = $identity_human;
			$this->load->view($this->config->item('admin_url') .'authentification/forgot_password', $this->data);
		}
		else
		{
			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($this->input->post($identity));

			if ($forgotten)
			{ //if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect($this->config->item('admin_url') .'authentification/login', 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect($this->config->item('admin_url') .'authentification/forgot_password', 'refresh');
			}
		}
	}

	// ------------------------------------------------------------------------
	
	public function reset_password($code)
	{
		$reset = $this->ion_auth->forgotten_password_complete($code);

		if ($reset)
		{  //if the reset worked then send them to the login page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect($this->config->item('admin_url') .'authentification/login', 'refresh');
		}
		else
		{ //if the reset didnt work then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect($this->config->item('admin_url') .'authentification/forgot_password', 'refresh');
		}
	}

	// ------------------------------------------------------------------------
	
	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	// ------------------------------------------------------------------------
	 
	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
				$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}