<?php
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @type 		library
*/
if (! defined('BASEPATH')) exit('No direct script access allowed');

class Form 
{
    var $ci;

    public function __construct()
    {
		$this->ci =& get_instance();
		$this->ci->load->model('form_model');
    }
	
	// ------------------------------------------------------------------------
	
	/**
	 * Return HTML code for fields
	 * This fonction is used in Form creation, called by AJAX
	 *
	 * @param	int $type 	- Type of field to return (input, textarea ...)
	 * @return	string 		- HTML code get by HTML Helper
	 */
	public function show_field($type, $label = NULL, $options = NULL, $class = NULL, $id = NULL) 
	{
		$title = $label ? $label : $this->ci->form_model->get_name($type);
		$options = $options ? $options :  array('1' => 'Option 1', '2' => 'Option 2');
		$id = $id ? $id : $type;
		
		switch( $type )
		{
			default: case 1: case 2:
				$field = input( $id, $title, NULL, NULL, NULL, 'text', $class);  
			break;
			case 3:
				$field = input_date( $id, $title, NULL, NULL);  
			break;
			case 4:
				$field = textarea( $id, $title, NULL, NULL);  
			break;
			case 5:
				$field = radio( $id, $title, NULL, NULL, $options);
			break;
			case 6:
				$field = checkbox( $id, $title, NULL, NULL, $options);
			break;
			case 7:
				$field = select( $id, $title, NULL, $options);
			break;
		}
		
		return $field;
	}
}