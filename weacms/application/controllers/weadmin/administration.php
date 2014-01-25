<?php
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @type 		controller
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Administration extends MY_AdminController
{
    public function __construct()
    {        
        parent::__construct();
        
		// User is admin ?
        if (! $this->ion_auth->is_admin()) 
        {
            redirect(base_url());
        }
    }
    
    // ------------------------------------------------------------------------
   
    /**
    * Load hook module and execute hook_name function
    * 
    * @access  	public
    * @return  	function
    */   
	public function index($tab = 'users')
    {
	    $tabs = array(
	       'users'		=> 'Utilisateurs',
	       'theme'		=> 'Thème',
	       'menu'		=> 'Menu du site',
	       'configuration'	=> 'Configuration',
	       'langs'		=> 'Langues',
	       'logs'		=> 'Logs et sessions',
        );
        
        $content = call_user_func(array('administration', $tab));
		
        $this->template->add_js('datatable');
		$this->template->write('title', 'Administration - '. $tabs[$tab]);
		$this->template->write_view('content', 'administration/index', array(
          'tabs' 	=> $tabs, 
          'content' => $content,
          'tab' 	=> $tab,               
      ));
	} 
    
    // ------------------------------------------------------------------------
    /* Mise à jour du fichier de sécurité, ré-écrit le fichier à chaque changement d'accès
     */    
    private function write_security_access()
    {
        $this->load->helper('file');
        
        $content = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n
\$config['access'] = array(\n";
        
        $access = $this->db->get('security_module')->result();
            
        foreach($access as $row) 
        {
            $content .= "\t'". $row->controller ."' => array(";
            
            foreach($this->db->get_where('security_access', array('id_sm' => $row->id_sm))->result() as $subrow) 
            {
                $content .= $subrow->id_group .', ';
            }
            
            $content .= "),\n";
        }                
        
        $content .= ');';        
        
        write_file(local_url('application/config/access.php'), $content);
    }
     
    // ------------------------------------------------------------------------

    /**
    * User crud
    * 
    * @access  	public
    */       
    public function users($action = '', $id = 0)
    {
        foreach($this->db->order_by('description')->get('groups')->result() as $service) 
        {
            $services[$service->id] = $service->description;            
        }
        
        $form = array(
                array('Nom complet', 'username', 'input'), 
                array('Photo associée', 'image', 'file'),           
                array('Email', 'email', 'input'),
                array('Job', 'company', 'input'),
                array('Service', 'group_id', 'select', $services),
            );
        $id_text = 'id';      
		
		$data = $this->db->select('g.description, ug.group_id AS group_id, u.*, u.id as image')
			->from('users u')
			->join('users_groups ug', 'ug.user_id = u.id')
			->join('groups g', 'g.id = ug.group_id');
		
		if ($action == 'edition') 
		{
			$this->db->where('u.id', $id);
		}
		
		$data = $this->db->get()->result();
		
        $params = array(
            'button' 	=> 'Ajouter un nouvel utilisateur',
            'title' 	=> 'Gestion des utilisateurs',
            'url' 		=> 'administration/users/',
            'id' 		=> $id_text,
            'data' 		=> $data,
			'image_url' => base_url('theme/assets/avatars') . '/',
            'fields' 	=> array(
				'image' 	=> 'image',
                'Nom' 		=> 'username',
                'Email' 	=> 'email',
                'Job' 		=> 'company',
                'Service' 	=> 'description',
                ),
            'form' => $form,
        );
            
        if ($action == 'edition') 
        {
            $data = $id ? $data[0] : FALSE;
			
            $this->template->write('title', 'Création / Modification d\'un utilisateur');            
            $this->template->write_view('content', 'generique/create', array(
                'params' 	=> $params,
                'data'      => $data,
            ));  
                         
        } 
        elseif ($action == 'check') 
        {
            $this->form_validation->set_rules('username', 'Nom complet', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|email');

            if(! $this->form_validation->run()) 
            {
            	response_json(FALSE, FALSE, validation_errors());
            }  
            else 
            {                 
                response_json(TRUE);
            }     
        } 
        elseif ($action == 'update') 
        {
            $post = $this->input->post();
			
            if ($id) 
            {                
                $this->ion_auth->update($post, array($id_text => $id));                
            } 
            else 
            {                                
                $username 	= $this->input->post('username');
                $email 		= $this->input->post('email');
                
                // Get random password
                $password = substr(str_shuffle(strtolower(sha1(rand() . time() . 'pass'))), 0, 6);                
                
                // Use array() for group in registrer ion_auth
                $group []= $this->input->post('group_id');
                                         
                $id = $this->ion_auth->register($username, $password, $email, array('company' => $this->input->post('company')), $group); 
            }
            
            if(!empty($_FILES['image']['name'])) 
            {                
                // Get avatar image
                $config['upload_path']		= local_url('web/images/avatars');
                $config['file_name'] 		= $id .'.jpg';
                $config['allowed_types'] 	= 'jpg|jpeg';
                $config['overwrite'] 		= TRUE;
                
                $this->load->library('upload', $config);
            
                if ( ! $this->upload->do_upload('image')) 
                {                    
                    die($this->upload->display_errors());
                }   
                else 
                {
                    // Image Resizing
                    $config['source_image'] = $this->upload->upload_path.$this->upload->file_name;
                    $config['maintain_ratio'] = TRUE;
                    $config['width'] = 120;
                    $config['height'] = 120;
                    $config['master_dim'] = 'height';
            
                    $this->load->library('image_lib', $config);    
                    
                    if (! $this->image_lib->resize())
                    {
                        $this->session->set_flashdata('message', $this->image_lib->display_errors('', ''));
                    }                              
                    $data = $this->upload->data();
                }   
            }         
            
            redirect('administration/index/users');               
        } 
        else if ($action == 'delete') 
        {            
            $this->ion_auth->delete_user($id);
            response_json(TRUE);
        }
            
        //$this->action($action, 'user', $id_text, $id, $this->input->post(), $form);  
        
        return $this->load->view(
            $this->config->item('admin_url') . 'generique/index', 
            array('params' => $params), 
            true);
    }   

    // ------------------------------------------------------------------------

    /**
    * Configuration crud
    * 
    * @access  	public
    */       
    public function configuration($action = '', $id = FALSE)
    {
		$id_text = 'name';      
		$table = 'configuration';
		
    	switch ($action)
		{
			case '': default:
				    	
		        $form = array(
		            array('Nom', 'name', 'input'), 
		            array('Description', 'description', 'input'), 
		            array('Valeur', 'value', 'input'), 
		        );

				$params = array(
		            'button' 	=> 'Ajouter une nouvelle variable de configuration',
		            'title' 	=> 'Gestion des variables de configuration',
		            'url' 		=> 'administration/configuration/',
		            'id' 		=> $id_text,
		            'data' 		=> $this->db->get('configuration')->result(),
		            'fields' 	=> array(
		                'Nom' 		=> 'name',
		                'Description' 	=> 'description',
		                'Valeur' 		=> 'value',
		                ),
		            'form' => $form,
		        );

				return $this->load->view(
		            $this->config->item('admin_url') . 'generique/crud', array(
		            	'params' => $params
					), TRUE);
				break;

			case 'get':
				
				response_json(TRUE, $this->db->get_where('configuration', array($id_text => $id))->row());
				break;

			case 'check':
				
	            $this->form_validation->set_rules('value', 'Valeur', 'required');

				if(! $this->form_validation->run()) 
	            {
	            	response_json(FALSE, FALSE, validation_errors());
				}  
	            else 
	            {                 
	                response_json(TRUE);
				}     
				break;

			case 'update':
	            $post = $this->input->post();

				if ($id) 
	            {          
	                $this->db->update($table, $post, array($id_text => $id));
				} 
	            else 
	            {        
	                $this->db->insert($table, $post);
				} 
	            
	            response_json(TRUE);  
				break;
				
			case 'delete':
			
	            $this->db->delete($table, array($id_text => $post['id']));
				response_json(TRUE);
				break;
		}
    }   


    // ------------------------------------------------------------------------

    /**
    * Langs crud
    * 
    * @access  	public
    */       
    public function langs($action = '', $id = FALSE)
    {
		$id_text = 'id_dl';      
		$table = 'langs';
		
    	switch ($action)
		{
			case '': default:
				
		        $form = array(
		            array('Nom', 'name', 'input'), 
		            array('Nom raccourci', 'letters', 'input'), 
		            array('Langue principale ?', 'main', 'select', array(0 => 'Non', 1 => 'Oui')), 
		        );

				$params = array(
		            'button' 	=> 'Ajouter une nouvelle langue',
		            'title' 	=> 'Gestion des langues de vos pages',
		            'url' 		=> 'administration/langs/',
		            'id' 		=> $id_text,
		            'data' 		=> $this->db->get('langs')->result(),
		            'fields' 	=> array(
		                'Nom' 		=> 'name',
		                'Nom raccourci'	=> 'letters',
		                'Langue principale' => 'main',
		                ),
		            'form' => $form,
		        );
				
				return $this->load->view(
		            $this->config->item('admin_url') . 'generique/crud', 
		            array('params' => $params), 
		            TRUE);
				break;

			case 'get':
				
				response_json(TRUE, $this->db->get_where($table, array($id_text => $id))->row());                         
				break;
				
			case 'check':

	            $this->form_validation->set_rules('name', 'Nom', 'required');
				$this->form_validation->set_rules('letters', 'Nom raccourci', 'required');

				if(! $this->form_validation->run()) 
	            {
	            	response_json(FALSE, FALSE, validation_errors());
				}  
	            else 
	            {                 
	                response_json(TRUE);
				}  
				break;
				
			case 'update': 

	            $post = $this->input->post();

				if ($id) 
	            {
	            	// If main, update others to have main = 0
	            	if ($post['main'] == 1)
					{
						$this->db->update($table, array('main' => 0));
					}
	            	          
	                $this->db->update($table, $post, array($id_text => $id));
				} 
	            else 
	            {        
	                $this->db->insert($table, $post);
				} 
	            
	            response_json(TRUE);
				break;

			case 'delete': 
      
	            $this->db->delete($table, array($id_text => $post['id']));
				response_json(TRUE);
			break;
		}
	}   
    
    // ------------------------------------------------------------------------

    /**
    * Return active sessions from CI
    * 
    * @access  	public
    */    
    public function sessions()    
    {
        $query = $this->db->where('LENGTH(user_data) > 0')->get('ci_sessions');
        
        $users = array(); /* array to store the user data we fetch */
        
        foreach ($query->result() as $row)
        {
            $udata = unserialize($row->user_data);
            
            if (isset($udata['username'])) {
                $module = end($udata['modules']);
                /* put data in array using username as key */
                $users []= (object)array(
                    'username'  => $udata['username'],
                    'user_id'   => $udata['user_id'],
                    'ip'        => $row->ip_address,
                    'module'    => isset($module->controller) ? $module->controller : '',
                    'last_activity' => date('d/m à H:i', $row->last_activity),
                    ); 
            }
        }
        
        return $this->load->view('administration/tabs/sessions', array(
            'users' => $users,
        ), TRUE);        
    }

	// ------------------------------------------------------------------------
	
    /**
    * Main menu crud
    * 
    * @access  	public
    */    
	public function menu($action = '', $lang = FALSE, $id = FALSE)
	{
		$this->load->model(array('page_model', 'lang_model'));
    	$table = 'pages_menus';	
		$id_text = 'id_pm';

    	switch ($action)
		{
			case '': case 'list': default:
				
				$lang = ! $lang ? $this->lang_model->get_main() : $lang;		
				
				// Build an array for positions select
				for ($i = 1; $i < 10; $i++)
				{
					$positions[$i] = $i;
				}
				
				$this->template->add_js('treeview');
				$this->template->add_css('treeview');		
				
		        return $this->load->view($this->config->item('admin_url') . 'administration/tabs/menu', array( 
					'menu' => $this->page_model->get_menu(FALSE, 0, $lang), 
					'menu_parent' => $this->page_model->get_menu_select($lang),
					'selectview'	=> $this->page_model->get_pages_select($lang),
					'positions' => $positions,
					'langs' => $this->lang_model->get(),
					'lang_main' => $lang,			
				), TRUE);
				break;
			
			case 'get':
				
				response_json(TRUE, $this->db->get_where($table, array($id_text => $this->input->post('id')))->row());					
				break;
			
			case 'update':
								
				$this->form_validation->set_rules('label', 'Label', 'required|xss_clean');
		
				if ($this->form_validation->run() == TRUE) 
				{					
					if (! $id) 
					{
						$this->db->insert($table, $this->input->post());
					}
					else 
					{
						$this->db->update($table, $this->input->post(), array($id_text => $id));
					}
					response_json(TRUE);
				}
				else 
				{					
					// Return some errors if some fields are missing 
					response_json(FALSE, FALSE, validation_errors());
				}						
				break;
		}
	}	

    // ------------------------------------------------------------------------
    /* Affichage de tous les logs de l'application
     */
    public function logs()
    {
        $this->load->helper('date_2');
        $this->load->model('log_model');
        
        return $this->load->view(
            'administration/tabs/logs', 
            array('logs' => $this->log_model->get(NULL, 100)),
            TRUE);
    }
}
