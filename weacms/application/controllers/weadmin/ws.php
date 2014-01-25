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

class Ws extends MY_AdminController
{
	/**
	 * Constructor
	 * load ws model
	 */	
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('ws_model');
	}
	
	// ------------------------------------------------------------------------
	
	public function search()
	{
		die(json_encode($this->ws_model->search($this->input->get('q'))));
	}
	
	// ------------------------------------------------------------------------
	
	public function online()
	{
		die(json_encode($this->ws_model->online()));
	}
}