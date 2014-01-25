<?php 
/**
* Weacms
* An open source Content Managing System
*
* @package		Weacms
* @author       Vincent DECAUX
* @link         http://www.weacms.com
* @since        Version 1.0
* @category		controller
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MY_AdminController
{
	/**
	 * Constructor
	 * load page and lang model
	 */	
	public function __construct() 
	{
		parent::__construct();
		
		$this->load->helper('date_fr');
		$this->load->model(array('page_model', 'lang_model'));
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Index Page for this controller.
	 * Return list of active pages
	 */	
	public function index($lang = 0)
	{		
		// Getting the language 
		$lang = $lang == 0 ? $this->lang_model->get_main() : $lang;
		
		$this->template->write('title', 'Gestion des pages');
		$this->template->add_js('datatable');
		
		$this->template->write_view('content', 'pages/index', array(
			'pages' => $this->page_model->get_valid_pages($lang),
			'langs' => $this->lang_model->get(),
			'lang_main' 	=> $lang,
			'selectview'	=> $this->page_model->get_pages_select($lang),
		));
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Load the page form
	 * @param	int		page id
	 * @param	int		lang id
	 */
	public function edition($id = FALSE, $lang = FALSE)
	{
		// Get lang id
		$lang = $lang == 0 ? $this->lang_model->get_main() : $lang;
		
		$this->load->model(array('widget_model', 'form_model'));
		$this->config->load('widgets');
		$this->config->load('page_templates');
				
		// Get the page and manage url if needed 
		$page = $this->page_model->get($id);	
		
		if ($page) 
		{
			$page->content = htmlentities($page->content);
		}		
				
		$this->template->write('title', 'Modification de page');
		$this->template->add_js(array('ckeditor/ckeditor', 'ckeditor/adapters/jquery', 'ace/ace', 'vkbeautify', 'elfinder', 'i18n/elfinder.fr', 'treeview'));
		$this->template->add_css(array('treeview', 'elfinder'));
		
		$this->template->write_view('content', 'pages/create', array(
			'page' 			=> $page, 
			'templates' 	=> $this->config->item('page_templates'),
			'widgets' 		=> $this->widget_model->get_page_widgets_available(),
			'pages_widgets' => $this->page_model->get_widgets($id),
			'widgets_hooks' => $this->config->item('widgets_page_hooks'),
			'lang' 			=> $lang,
			'forms' 		=> $this->form_model->get(),
			'selectview'	=> $this->page_model->get_pages_select($lang, 0, $id),
			'treeview'		=> $this->page_model->get_treeview($lang),
			)
		);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Update page information from main form
	 * @param	int		lang id
	 * @return	json
	 */
	function update($lang_id)
	{	
		$this->form_validation->set_rules('title','Titre','required');
		$this->form_validation->set_rules('url','Titre','required');
		$this->form_validation->set_rules('content','Contenu','required');
		$this->form_validation->set_rules('name','Nom','required');
		
		// Run some validations
		if($this->form_validation->run()) 
		{
			$widgets 	= $this->input->post('widgets');
			
			// Delete widget array from post data
			unset($_POST['widgets']);
			
			$data = array_merge($this->input->post(), array('uid' => $this->session->userdata('user_id'), 'state' => 0));
			
			// The page is updated, add log entry and update database informations
			if ($this->input->post('id') != 0) 
			{
				$this->page_model->update($data);
				$id = $this->input->post('id');
				$this->log_model->add(2, $id);
			}
			else 
			{
				$id = $this->page_model->add($data);
				$this->log_model->add(1, $id);
			}
			
			// Update widgets attachments 
			$this->page_model->update_widgets($widgets, $id);
			
			response_json(TRUE, array('id' => $id));			
		} 
		else 
		{
			// In case of error
			response_json(FALSE, FALSE, validation_errors());
		}		
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Load page builder in iframe
	 * @param	int		page id
	 * @return	str		HTML view of page builder
	 */	
	public function page_builder($id)
	{
		// Get the page
		$page = $this->page_model->get($id);	
		
		echo $this->load->view($this->config->item('admin_url') .'pages/builder/content', array(
				'sidebar'	=> $this->load->view($this->config->item('admin_url') .'pages/builder/sidebar', array(), TRUE),
				'content'	=> isset($page) ? html_entity_decode($page->content) : '',
		), TRUE);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Open trash pages view
	 */
	public function trash() 
	{
		$this->template->write('title', 'Corbeille');
		$this->template->add_js('datatable');
		
		$this->template->write_view('content', 'pages/basket', array(
			'pages' => $this->page_model->get(NULL, NULL, array('p.state' => 2))
		));		
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Put a page in trash
	 * @param	post
	 * @return	json
	 */
	public function basket()
	{
		$this->page_model->basket($this->input->post('id'));
		
		// Add log row
		$this->log_model->add(4, $this->input->post('id'));
		
		response_json(TRUE);
	}
		
	// ------------------------------------------------------------------------
	
	/**
	 * Restore a page from trash
	 * @param	post
	 * @return	json
	 */	
	public function restore()
	{
		$this->page_model->restore($this->input->post('id'));
		
		// Add log row
		$this->log_model->add(5, $this->input->post('id'));
		
		response_json(TRUE);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Delete a page from database
	 * @param	post
	 * @return	json
	 */
	public function delete()
	{
		$id = $this->input->post('id');
		
		// Delete page row
		$this->page_model->delete($id);
		
		// Delete log row
		$this->log_model->delete($id);
		
		response_json(TRUE);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Valid one page and put file content into cache folder
	 * @param	int		page id
	 * @return	json
	 */
	public function valid_one($id = FALSE, $lang_main = FALSE)
	{
		$id = $id ? $id : $this->input->post('id');
		$lang_main = $lang_main ? $lang_main : $this->lang_model->get_main();
		
		// Get the page
		$page = $this->page_model->get($id);
	
		if ($page)
		{
			$this->page_model->update(array('id' => $id, 'state' => 1));
			
			// Add log status
			if (! $id) 
			{
				$this->log_model->add(3, $id);
			}

			// If page is homepage of lang group, its url = ''
			if ($page->homepage)
			{
				$page->url = '';
			}

			// If its not the main lang, we add preffix at url beginning
			if ($lang_main != $page->lang) 
			{
				$lang = $this->lang_model->get($page->lang);
				$page->url = strtolower($lang->letters) .'/'. $page->url;
			}
			
			// Put the page in cache file 
			$name = md5($page->url);
			$data = $this->view($id, $page->lang, TRUE);	

			if ($zp = gzopen(APPPATH .'/cache/' . $name.'.gz', 'w9'))
			{
				gzwrite($zp, $data);
				@chmod(APPPATH .'/cache/' . $name .'.gz', 0777);
				gzclose($zp);				
			}			
			
			if ($id) 
			{
				return;	
			}
			
			response_json(TRUE);
		}
	}
	
	// ------------------------------------------------------------------------	
	
	/**
	 * Valid all pages
	 * @return	json
	 */
	public function valid_all()
	{
		// Get lang main here to avoid multiple queries
		$lang_main = $this->lang_model->get_main();
		
		foreach ($this->page_model->get(NULL, NULL, array('p.state !=' => 2)) as $page)
		{
			$this->valid_one($page->id, $lang_main);
		}

		response_json(TRUE);
	}	
	
	// ------------------------------------------------------------------------	
	
	 /** 
	  * Return the content of a page
	  * @param	int 	page id
	  * @param	int		lang id
	  * @param	bool	if true return the output
	  * @return	str	or CI view
	 */	
	public function view($id, $lang = FALSE, $return = FALSE)
	{
		$lang = ! $lang ? $this->lang_model->get_main() : $lang;
		
		$modules = array();
		$modules_template = array();
		
		$this->load->model('widget_model');
		$this->load->library('widget');
		$this->config->load('widgets');
		
		// Get hooks widgets list
		$widgets_hooks = $this->config->item('widgets_page_hooks');
		
		// Get all widgets on the page
		foreach ($this->widget_model->get_page_widgets($id) as $widget)
		{
			$hook = isset($widgets_hooks[$widget->hook]) ? $widgets_hooks[$widget->hook] : FALSE;
		
			if ($hook)
			{
				// Get or concat widget result in $module var
				if (! isset($modules[$widget->hook])) 
				{
					$modules[$widget->hook] = Widget::run($widget->class, $hook[0]);
				} 
				else 
				{
					$modules[$widget->hook] .= Widget::run($widget->class, $hook[0]);
				}
			}
		}
		
		// Get hooks widgets list
		$widgets_hooks_template = $this->config->item('widgets_template_hooks');
		
		// Get all widgets on the page
		foreach ($this->widget_model->get_template_widgets_hooked() as $widget)
		{
			$hook = isset($widgets_hooks_template[$widget->hook]) ? $widgets_hooks_template[$widget->hook] : FALSE;
		
			if ($hook)
			{
				// Get or concat widget result in $module var
				if (! isset($modules_template[$widget->hook])) 
				{
					$modules_template[$widget->hook] = Widget::run($widget->class, $hook[0]);
				} 
				else 
				{
					$modules_template[$widget->hook] .= Widget::run($widget->class, $hook[0]);
				}
			}
		}
		
		// Get the page to display
		$page = $this->page_model->get($id);
		
		// Add css and js ressources
		$this->template->add_css($page->css, TRUE, theme_web_url());
		$this->template->add_js($page->js, TRUE, theme_web_url());
		
		// Find [FORM] syntax to replace with the correct form 
		$page->content = preg_replace_callback('#\[form\](.*)\[\/form\]#sU', array( &$this, 'get_form_view'), $page->content);
		
		if (! $return)
		{
			// Add Dom monster button to test your DOM efficience
			$page->content .= '<a style="position:fixed; left:10px; bottom:10px; padding:6px 10px; display:block; background:#222; font-size:14px; color:#fff; z-index:99999;"
				href="javascript:(function(){var%20script=document.createElement(\'script\');script.src=\''. web_url('js/dommonster.js?') .'\'+Math.floor((+new Date));document.body.appendChild(script);})()">Tester le site</a>';
		}
		
		// Load page view
		return $this->template->page_view('template', $page, $modules, $modules_template, $lang, $return);
	}
	
	// ------------------------------------------------------------------------	
	
	/** 
	 * Load a form view in the page
	 * @param	int		contains id of the form
	 * @return	str		HTML view of form
	 */
	public function get_form_view($f)
	{
		// Exit if no form in param
		if (! count($f) ) 
		{
			return '';
		}
		
		$this->load->model('form_model');
		$this->load->library('form');	
		
		return $this->load->view('site/form', array(
			'fid' 		=> $f[1], 
			'form' 		=> $this->form_model->get($f[1]), 
			'fields' 	=> $this->form_model->get_form_fields($f[1])
		), TRUE);		
	}
	
	// ------------------------------------------------------------------------		
	
	/** 
	 * List pages folders
	 * @param	int		id of lang
	 */
	public function arborescence($lang = 0) 
	{
		$lang_main = $lang == 0 ? $this->lang_model->get_main() : $lang;
		
		$this->template->write('title', 'Arborescence des pages');
		$this->template->add_js('treeview');
		$this->template->add_css('treeview');
		$this->template->write_view('content', 'pages/arborescence', array(
			'langs' => $this->lang_model->get(),
			'lang_main' => $lang_main,		
			'treeview' 	=> $this->page_model->get_menu(FALSE, FALSE, $lang_main),
			'total' 	=> count($this->page_model->get(FALSE, FALSE, 'p.state != 2' )) 
		));		
	}	
}