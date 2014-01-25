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

class Medias extends MY_AdminController
{        	
	// ------------------------------------------------------------------------

	public function index()
	{		
		$this->load->helper('path');
		
		$this->template->add_css(array('elfinder'));
		$this->template->add_js(array('elfinder', 'i18n/elfinder.fr'));
		$this->template->write('title', 'Gestion de la bibliothèque');
		$this->template->write_view('content', 'medias/index');
		$this->template->render();
	}
	
	// ------------------------------------------------------------------------

	public function elfinder()
	{
		$this->load->helper('path');
		
		$opts = array(
			'debug' => true, 
			'roots' => array(
				array( 
					'driver' => 'MySQL',
					'host'   => $this->db->hostname,
					'user'   => $this->db->username,
					'pass'   => $this->db->password,
					'db'     => $this->db->database,
					'path'   => 1,
					'files_table'   => $this->db->dbprefix . 'file',
					'tmpPath'       => assets_url(),
				)
			)
		);
        $this->load->library('ElFinder_lib', $opts);    
	}

    // ------------------------------------------------------------------------
	
	/**
	 * Return readable informations about file
	 * 
	 * @return 	json
	 */	    
    public function get_attachs() 
    {
        $attachs = FALSE;
        if ($this->input->post('attachs')) 
		{
            $attachs = explode('|', $this->input->post('attachs'));
            $ids = array();
            
            foreach ($attachs AS $row) 
			{
                // ID decode as described in Elfinder library
                $h = substr($row, 3);            
                $ids []= base64_decode(strtr($h, '-_.', '+/='));     
            }
            
            $attachs = $this->db->where_in('id', $ids)->from('file')->get()->result();
            
            if ($attachs) 
			{                
                $i = 0;
                foreach ($attachs as $row) 
				{
                    $attachs[$i]->url = assets_url($attachs[$i]->name, TRUE, TRUE, $attachs[$i]->mtime);
                    $i++;
                }
                
                response_json(1, $attachs);
            } 
			else 
			{                
                response_json(0, NULL, 'Un souci a été détecté dans la récupération des pièces jointes.');
            }
        }        
    }	
}
