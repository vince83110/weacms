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

class Page_model extends CI_Model
{		
	/** 
	 * Add a page object to database
	 * 
	 * @param	object	page informations
	 * @return	int		last db insert id
	 */		
	public function add($data)
	{
		// Get now() date and merge with data
		$data = array_merge(array('edited' => date('Y-m-d H:i:s')), $data);
		
		$this->db->insert('pages',$data);
		
		return $this->db->insert_id();
	}
	
	// ------------------------------------------------------------------------

	/** 
	 * Update page object in database
	 * 
	 * @param	object	page informations
	 * @return	bool	status of db update
	 */		
	public function update($data)
	{
		// Get now() date and merge with data
		$data = array_merge(array('edited' => date('Y-m-d H:i:s')), $data);
		
		return $this->db->update('pages', $data, array('id' => $data['id']));
	}
	
	// ------------------------------------------------------------------------

	/** 
	 * Delete page from database
	 * 
	 * @param	int		page id
	 * @return	bool	status of db delete
	 */	
	public function delete($id)
	{
		return $this->db->delete('pages', array('id' => $id));
	}	
	
	// ------------------------------------------------------------------------
	
	/** 
	 * Get a page or get all the pages
	 * 
	 * @param	int		page id
	 * @param	int		limit param
	 * @param	array 	where clause
	 * @return	object	db result
	 */		
	public function get($id = FALSE, $limit = FALSE, $where = FALSE)
	{
		if (! $id) 
		{			
			// Get all the pages 
			$this->db->select('p.*, u.username, p2.name AS category')
				->from('pages AS p')
				->join('users AS u', 'p.uid = u.id', 'left')
				->join('pages AS p2', 'p2.id = p.parent', 'left');
			
			if ($where) 
			{
				$this->db->where( $where );
			}		
			if ($limit) 
			{
				$this->db->limit( $limit );	
			}
			
			$this->db->order_by('category, name');
			
			return $this->db->get()->result();
			
		} 
		else 
		{			
			// Or get only one
			$this->db->select('p.*, u.username, p2.name AS category')
				->from('pages AS p')
				->join('users AS u', 'p.uid = u.id', 'left')
				->join('pages AS p2', 'p2.id = p.parent', 'left')
				->where(array('p.state !=' => 2, 'p.id' => $id));
			
			return $this->db->get()->row();
		}
	}
	
	// ------------------------------------------------------------------------
	
	/** 
	 * Get valid pages 
	 * 
	 * @param	int		lang id
	 * @param	int		limit param
	 * @return	object	db result
	 */		
	public function get_valid_pages($lang = FALSE, $limit = FALSE)
	{
		return $this->get(FALSE, $limit, array('p.state !=' => 2, 'p.lang' => $lang));
	}
	
	// ------------------------------------------------------------------------	
	
	/** 
	 * Get page widgets
	 * 
	 * @param	int		page id
	 * @return	object	db result
	 */		
	public function get_widgets($id)
	{
		$widgets = $this->db->select('pw.*')
			->from('pages_widgets AS pw')
			->join('widgets AS w', 'w.id = pw.id_widget', 'left')
			->where(array('pw.id_page' => $id))
			->get()->result();
		
		return $widgets;
	}
	
	// ------------------------------------------------------------------------	
	
	/** 
	 * Update page widgets
	 * 
	 * @param	str		widgets list
	 * @param	int		page id
	 * @return	bool	set to true by default
	 */		
	public function update_widgets($widgets, $id)
	{
		$data = array();
		
		// Delete old entries 
		$this->db->delete('pages_widgets', array('id_page' => $id));
		
		if (strlen($widgets)) 
		{
			// Build the data to insert
			foreach (explode('-', substr($widgets, 0, -1)) as $widget)
			{
				$widget_data = explode('|', $widget);
				
				$data []= array(
					'id_page' 	=> $id,
					'id_widget' => $widget_data[0],
					'hook'		=> $widget_data[1],
				);
			}
			
			// Insert the new widget configuration
			$this->db->insert_batch('pages_widgets', $data);
		}
						  
		return TRUE;
	}	
	
	// ------------------------------------------------------------------------
	
	/** 
	 * Return array containing menu entries
	 * 
	 * @param	int		lang id
	 * @return	array 	
	 */	
	public function get_menu_select($lang) 
	{
		$options = '<option value="0">Aucun</option>';
		$pages_menus = $this->db->get_where('pages_menus', array('lang' => $lang))->result();
		
		// Change variable names
		foreach ($pages_menus as $i => $page_menu) 
		{
			$pages_menus[$i]->id = $page_menu->id_pm;	
			$pages_menus[$i]->name = $page_menu->label;
		}
		
		return $options . $this->tree_select(0, 0, $pages_menus, FALSE, FALSE);
	}	
	
	// ------------------------------------------------------------------------
	
	/** 
	 * Return array containing all pages entries
	 * 
	 * @param	int		lang id
	 * @param	int		page parent id
	 * @param	int		page selected id
	 * @return	array 	
	 */	
	public function get_pages_select($lang, $id_parent = FALSE, $id = FALSE) 
	{
		return $this->tree_select(0, 0, $this->get_valid_pages($lang), $id_parent, $id);
	}	
	
	// ------------------------------------------------------------------------
	
	/** 
	 * Return select with indents
	 * 
	 * @param	int		parent of page
	 * @param	int		recursive level
	 * @param	array 	contain subpages
	 * @param	int		page parent id
	 * @param	int		page selected id
	 * @return	str		all options values
	 */		
	public function tree_select($parent, $level, $array, $id_parent, $id)
	{
		$html = '';
		$level_prev = 0;
		 
		// Loop over pages
		foreach ($array AS $entry) 
		{
			if ($parent == $entry->parent && $entry->id != $id) 
			{			 
				$separation = str_repeat('::::', $level);
				
			 	// Create tree entry
				$html .= '<option'. ($id ? ($entry->id == $id ? ' selected="selected"' : '') : '') .' value="'. $entry->id .'">' . $separation . ' '. $entry->name . '</option>';
			 
				$level_prev = $level;
			 
			 	// Recursive call
				$html .= $this->tree_select($entry->id, ($level + 1), $array, $id_parent, $id);
			}
		}
		
		return $html;
	}	
	
	// ------------------------------------------------------------------------
	
	/** 
	 * Return menu public function considering first param
	 * 
	 * @param	bool	true to have main template menu
	 * @param	int		page id
	 * @param	int		lang id
	 * @return	function
	 */		
	public function get_menu($site = FALSE, $id = 0, $lang)
	{
		// Get all the menus
		$pages = $this->db->select('pm.*, p.title, p.url, p.id')
			->from('pages_menus AS pm')
			->join('pages AS p', 'p.id = pm.id_page', 'left')
			->where(array('pm.lang' => $lang))
			->order_by('pm.position ASC')
			->get()->result();
		
		return $site ? 
			$this->menu_site(0, 0, $pages, $id) 
			: 
			$this->treeview(0, 0, $pages);
	}
	
	// ------------------------------------------------------------------------
	
	/** 
	 * Return treeview for all site pages considering lang
	 * 
	 * @param	int		lang id
	 * @return	function
	 */
	function get_treeview($lang)
	{
		$pages = $this->db->get_where('pages', array('lang' => $lang))->result();
		
		foreach ($pages as $i => $page)
		{
			$pages[$i]->id_pm = $page->id;
			$pages[$i]->label = $page->name;			
		}
		
		return $this->treeview(0, 0, $pages);
	}	
	
	// ------------------------------------------------------------------------
	
	/** 
	 * Return html of treeview menu
	 * 
	 * @param	int		parent of page
	 * @param	int		recursive level
	 * @param	array 	contain subpages
	 * @return	str		html view
	 */		
	public function treeview($parent, $level, $array)
	{
		$html = '';
		$level_prev = 0;
		 
		// Loop over pages
		foreach ($array AS $entry) 
		{
			if ($parent == $entry->parent ) 
			{			 
				if ($level_prev < $level) 
				{
					$html .= "\n<ul>\n";
				}
				
			 	// Create tree entry
				$html .= '<li class="open"><a data-url="'. $entry->url .'" data-id="'. $entry->id_pm .'"><span class="folder">' . $entry->label . '</span></a>';
			 
				$level_prev = $level;
			 
			 	// Recursive call
				$html .= $this->treeview($entry->id_pm, ($level + 1), $array);
			}
		}
		 
		if ($level_prev == $level && $level_prev != 0) 
		{
			$html .= "</ul>\n</li>\n";
		}
		else 
		{
			$html .= "</li>\n";
		}
		
		return $html;
	}		
	
	// ------------------------------------------------------------------------
	
	/** 
	 * Return html of main menu used in template
	 * 
	 * @param	int		parent of page
	 * @param	int		recursive level
	 * @param	array 	contain subpages
	 * @param	int		page id
	 * @return	str		html view
	 */		 
	public function menu_site($parent, $level, $array, $id)
	{
		$html = '';
		$level_prev = 0;
		 
		// Loop over pages
		foreach ($array AS $entry) 
		{
			if ($parent == $entry->parent ) 
			{
				if ($level_prev < $level)
				{ 
					$html .= ($level == 1 ? '<span class="sub-menu-arrow"></span>' : '') . '<ul id="sub-menu-id-'. $entry->parent .'" class="sub-menu-'. $level .'">';
				}
				
				$class = '';
				$class .= (strlen($entry->class) ? $entry->class : '');
				$class .= ($entry->id == $id ? ' active' : '');
				
			 	// Create tree entry
				$html .= '
				<li id="menu-li-'. $entry->id .'"'. (strlen($class) ? ' class="'. trim($class) .'"' : '') .'>
					'. ($entry->id != 0 ? ('<a href="'. base_url( $entry->url ) .'">
					'. $entry->label .'</a>') : '<span class="no-link">'. $entry->label .'</span>');
			 
				$level_prev = $level;
			 
			 	// Recursive call
				$html .= $this->menu_site($entry->id_pm, ($level + 1), $array, $id);		 
			}
		}
		 
		if (($level_prev == $level) && ($level_prev != 0)) 
		{
			$html .= '</ul></li>';
		}
		
		return $html;
	}	
	
	// ------------------------------------------------------------------------
	
	/** 
	 * Return breadcrumb
	 * 
	 * @param	object 	page 
	 * @return	str		breadcrumb view
	 */	
	public function get_breadcrumb($page)
	{
		$id = $page->parent;
		$breadcrumb = array();
		
		do 
		{
			$parent = $this->db->get_where( 'pages', array('id' => $id) )->row();

			if (count($parent)) 
			{
				$breadcrumb []= '<a href="'. base_url( $parent->url ). '.html">'. $parent->name .'</a> / ';	
				$id = $parent->parent;			
			}
			
		}
		while (count($parent));
		
		asort($breadcrumb);
		
		return implode('', $breadcrumb) . $page->title;
	}
	
	// ------------------------------------------------------------------------
		
	/** 
	 * Put a page in trash 
	 * Update state to 2 
	 * 
	 * @param	int		page id
	 * @return	bool	status of db update
	 */	
	public function basket($id)
	{
		$this->db->update('pages', array('state' => 2), array('id' => $id));
	}
	
	// ------------------------------------------------------------------------
		
	/** 
	 * Restore a page in valid one
	 * Update state to 0
	 * 
	 * @param	int		page id
	 * @return	bool	status of db update
	 */	
	public function restore($id)
	{
		$this->db->update('pages', array('state' => 0), array('id' => $id));
	}	
}