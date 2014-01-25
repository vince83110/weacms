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

class Widget_model extends CI_Model
{
	/** 
	 * Get widget list from database
	 * 
	 * @param	int		widget id
	 * @param	int		db limit
	 * @param	array 	where clause
	 * @return	object	db result
	 */		
	public function get($id = NULL, $limit = NULL)
	{
		if (! $id) 
		{			
			$this->db->select('w.*')->from('widgets AS w')->order_by('w.id');

			if ($limit) 
			{
				$this->db->limit( $limit );
			}
			
			$query = $this->db->get();
			
			if($query->num_rows()) 
			{				
				foreach($query->result() as $widget) 
				{				
					$pages = $this->db->select('p.name')
						->from('pages_widgets AS pw')
						->join('pages AS p', 'p.id = pw.id_page', 'left')
						->where('pw.id_widget', $widget->id)
						->get()->result();				
					
					$widget->pages = array();
					
					foreach ($pages as $page) 
					{
						$o->pages []= $page->name;
					}
					// Get hooks for each widget
					$widget->hooks = $this->db->get_where('widgets_hooks', array('id_widget' => $widget->id))->result();
					
					$results[] = $widget;
				}
				
				return $results;
			}
		} 
		else 
		{
			return $this->db->get_where('widgets', array('id' => $id))->row();
		}
	}

	// ------------------------------------------------------------------------	

	/** 
	 * Get page hooked widgets list
	 * 
	 * @param	int		page id
	 * @return	object	db result
	 */	
	public function get_page_widgets($id)
	{
		$this->db->select('pw.*, w.class')
			->from('pages_widgets AS pw')
			->join('widgets AS w', 'pw.id_widget = w.id', 'left')
			->where(array('pw.id_page' => $id));

		return $this->db->get()->result();
	}
	
	// ------------------------------------------------------------------------	
	
	/** 
	 * Get page available widget list
	 * 
	 * @param	bool	true if you want all widgets available on template hooks
	 * @return	object	db result
	 */	
	public function get_page_widgets_available()
	{
		$query = $this->db->select('w.*')
			->from('widgets AS w')
			->where('EXISTS (SELECT 1 FROM '. $this->db->dbprefix .'widgets_hooks wht WHERE wht.id_widget = w.id)', '', FALSE);
	
		$results = array();
			
		foreach($query->get()->result() as $widget) 
		{				
			// Get hooks for each widget
			$widget->hooks = $this->db->get_where('widgets_hooks', array('id_widget' => $widget->id))->result();
			
			$results[] = $widget;
		}
		return $results;
	}
	
	// ------------------------------------------------------------------------	
	
	/** 
	 * Get template available widget list
	 * 
	 * @param	bool	true if you want all widgets available on template hooks
	 * @return	object	db result
	 */	
	public function get_template_widgets_available()
	{
		$query = $this->db->select('w.*')
			->from('widgets AS w')
			->where('EXISTS (SELECT 1 FROM '. $this->db->dbprefix .'widgets_hooks_template wht WHERE wht.id_widget = w.id)', '', FALSE);
	
		$results = array();
			
		foreach($query->get()->result() as $widget) 
		{				
			// Get hooks for each widget
			$widget->hooks = $this->db->get_where('widgets_hooks_template', array('id_widget' => $widget->id))->result();
			
			$results[] = $widget;
		}
		return $results;
	}
	
	// ------------------------------------------------------------------------	
	
	/** 
	 * Get template hooked widget list
	 * 
	 * @param	bool	true if you want all widgets available on template hooks
	 * @return	object	db result
	 */	
	public function get_template_widgets_hooked()
	{
		$this->db->select('pw.*, w.*')
			->from('template_widgets AS pw')
			->join('widgets AS w', 'pw.id_widget = w.id', 'left');
	
		return $this->db->get()->result();
	}
	
	// ------------------------------------------------------------------------	
	
	/** 
	 * Update template widgets
	 * 
	 * @param	str		widgets list
	 * @return	bool	set to true by default
	 */		
	public function update_template_widgets($widgets)
	{
		$data = array();
		
		// Delete old entries 
		$this->db->truncate('template_widgets');
		
		if (strlen($widgets)) 
		{
			// Build the data to insert
			foreach (explode('-', substr($widgets, 0, -1)) as $widget)
			{
				$widget_data = explode('|', $widget);
				
				$data []= array(
					'id_widget' => $widget_data[0],
					'hook'		=> $widget_data[1],
				);
			}
			
			// Insert the new widget configuration
			$this->db->insert_batch('template_widgets', $data);
		}
						  
		return TRUE;
	}	
	
	// ------------------------------------------------------------------------	
	
	/** 
	 * Refresh widget list from module folder
	 * Update or create widget with widgets/file.php informations
	 * 
	 * @param	str		allow to refresh only one widget
	 */		
	public function refresh($widget_name = FALSE)
	{
    	$this->load->helper('file');
		$this->load->config('widgets');
		$widgets = array();	

	    $files = get_dir_file_info(APPPATH. 'modules', TRUE);
		
	    // Loop through modules folders
	    foreach (array_keys($files) as $file)
	    {
	    	if (is_dir(APPPATH. 'modules/'. $file .'/widgets'))
			{
				$widget 	= array_keys(get_dir_file_info(APPPATH. 'modules/'. $file .'/widgets', TRUE));	
	        	$widgets[] 	= str_replace(EXT, '', $widget[0]);
			}
	    }
		
		$db_widgets = $this->get();
		$db_widgets_name = array();
		
		// Delete old widgets
		foreach($db_widgets as $widget)
		{
			if (! in_array($widget->class, $widgets)) 
			{
				// Delete widget with no file associated
				$this->db->delete('widgets', array('id' => $widget->id));
			}
			
			$db_widgets_name[] = $widget->class;
		}

		// Get methods in Widget library
		$methods_library = get_class_methods('Widget');
		
		// Get widgets possible hooks
		$widgets_hooks = array();
		$widgets_template_hooks = array();
		
		foreach($this->config->item('widgets_page_hooks') as $key => $hook)
		{
			$widgets_hooks[$hook[0]] = $key;
		}
		foreach($this->config->item('widgets_template_hooks') as $key => $hook)
		{
			$widgets_template_hooks[$hook[0]] = $key;
		}
		
		// Update and create widgets
		foreach($widgets as $widget)
		{
			// Get folder name
			$folder = str_replace('_widget', '', $widget);
			
			// Load the class in memory
			if(! class_exists($widget)) 
			{
				$this->load->file(APPPATH .'modules/'. $folder .'/widgets/'. $widget . EXT);
			}  
								
            // Get widget methods array
            $list_methods = get_class_methods($widget);
			$hooks = array();
			$hooks_template = array();
			
			// List all methods
            if(is_array($list_methods))
            {
                foreach($list_methods as $method) 
                {
                    if($method != '__construct' && $method != $widget && ! in_array($method, $methods_library)) 
                    {
						$r = new ReflectionMethod($widget, $method);
						$params = array();
						
						foreach ($r->getParameters() as $param) 
						{
						    // Get method params
						    $params[] = $param->getName();
						}                    	
						
						// Get params in serialize mode to store in database
						$params = json_encode($params);
						
                    	// Get page or template hook index from config file
                    	if (strpos($method, '_template') !== FALSE)
						{
							if (isset($widgets_template_hooks[$method]))
							{
								$hooks_template[] = array(
									'hook'		=> $widgets_template_hooks[$method],
									'params'	=> $params,
								);
							}								
						}	
						else 
						{				
							if (isset($widgets_hooks[$method]))
							{
								$hooks[] = array(
									'hook'		=> $widgets_hooks[$method],
									'params'	=> $params,
								);
							}	
						}
                    }
                }
            }
			$_widget = new $widget();
			
			// Set widget name
			$_widget->set_name($folder);
			
			// Data to insert in database
			$data_widget = array(
				'title'			=> isset($_widget->title) ? $_widget->title : str_replace('_', '', $widget),
				'description'	=> isset($_widget->description) ? $_widget->description : '',
				'version'		=> isset($_widget->version) ? $_widget->version : '1.0',
				'configure'		=> isset($_widget->configure) ? $_widget->configure : FALSE,
				'class' 		=> $widget,
			);
								
			if (! in_array($widget, $db_widgets_name))
			{
				// Install function
				if (method_exists($_widget,'install'))
				{
					$_widget->install();
				}
				
				// Create widget in database
				$this->db->insert('widgets', $data_widget);			
				
				$id = $this->db->insert_id();
			}
			else
			{
				// Get widget id
				$id = $this->db->get_where('widgets', array('class' => $widget))->row()->id;
				
				// Update widget in database
				$this->db->update('widgets', array('id' => $id), $data_widget);
			}
			
			// Update widgets hooks in database
			$this->db->delete('widgets_hooks', array('id_widget' => $id));
			$this->db->delete('widgets_hooks_template', array('id_widget' => $id));
			
			// Build array for insert batch data
			$db_hooks = array();
			$db_hooks_template = array();
			
			foreach ($hooks as $hook)
			{
				$db_hooks[] = array(
					'id_widget'	=> $id,
					'hook'		=> $hook['hook'],
					'params'	=> $hook['params']
				);
			}
			foreach ($hooks_template as $hook_template)
			{
				$db_hooks_template[] = array(
					'id_widget'	=> $id,
					'hook'		=> $hook_template['hook'],
					'params'	=> $hook_template['params']
				);
			}
			
			if (count($db_hooks))
			{
				$this->db->insert_batch('widgets_hooks', $db_hooks);
			}
			if (count($db_hooks_template))
			{
				$this->db->insert_batch('widgets_hooks_template', $db_hooks_template);				
			}
		}
	}
}