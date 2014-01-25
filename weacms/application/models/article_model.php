<?php
/**
 * Model class for Articles part
 * Database tables used :
 *		- articles
 *		- articles_widgets
 *		- articles_folder
 *
 * @author  	Mutuelle du Var
 * @category	Model
 * @version 	1.0
*/
class Article_model extends CI_Model
{	
	/* Add a article into the database
	 */ 
	function add($data)
	{
		/* Get the NOW() date for column 'edited' */
		$data = array_merge(array('edited' => date('Y-m-d H:i:s')), $data);
		$this->db->insert('articles',$data);
		
		/* Return the last inserted ID */
		return $this->db->insert_id();
	}
	
	// ------------------------------------------------------------------------
	
	/* Add a blog category into the database
	 */ 	
	function add_category($data)
	{
		$this->db->insert('articles_categories',$data);
		
		/* Return the last inserted ID */
		return $this->db->insert_id();		
	}
	
	// ------------------------------------------------------------------------

	/* Update a article in the database
	 */
	function update($data)
	{
		/* Get the NOW() date for column 'edited' */
		$data = array_merge(array('edited' => date('Y-m-d H:i:s')), $data);
		
		return $this->db->update('articles', $data, array('id_ar' => $data['id_ar']));
	}
	
	// ------------------------------------------------------------------------

	/* Delete a article from the database
	 */
	function delete($id)
	{
		return $this->db->delete('articles', array('id' => $id));
	}	
	
	// ------------------------------------------------------------------------
	
	/* Get an article or get all the articles if $id = NULL
	 *
	 * @id : id of the article to get
	 * @limit : number max of results - int
	 * @where : where clause for the request - array
	 */
	function get($id = NULL, $limit = NULL, $where = NULL, $order = NULL, $order_type = 'ASC')
	{
		if (!$id) {
			
			/* Get all the articles */
			$this->db->select('a.*, u.username, c.name AS category, i.filename AS image');
			$this->db->from('articles AS a');
			$this->db->join('users AS u', 'a.id_us = u.id', 'left');
			$this->db->join('asset AS i', 'a.id_as = i.id', 'left');
			$this->db->join('articles_categories AS c', 'c.id_ac = a.id_ac', 'left');
			if ($where) {
				$this->db->where( $where );
			}
			if ($order) {
				$this->db->order_by( $order, $order_type );
			}			
			if ($limit) {
				$this->db->limit( $limit );
			}			
			
			$query = $this->db->get();
			
			if($query->num_rows() > 0){
				return $query->result();
			}
			
		} else {
			
			/* Or get only one article */
			$this->db->select('a.*, u.username, c.name AS category, i.filename AS image');
			$this->db->from('articles AS a');
			$this->db->join('users AS u', 'a.id_us = u.id', 'left');
			$this->db->join('asset AS i', 'a.id_as = i.id', 'left');
			$this->db->join('articles_categories AS c', 'c.id_ac = a.id_ac', 'left');
			$this->db->where(array('a.id_ar' => $id));
			
			$query = $this->db->get();
			if($query->num_rows() > 0){
				return $query->row();
			}
		}
		
		return array();
	}	
	
	// ------------------------------------------------------------------------

	/* Get a category
	 */
	function get_category($id = NULL)
	{
		if ($id) {
			$query = $this->db->get_where('articles_categories', array('id_ac' => $id));
			
			if($query->num_rows() > 0){
				return $query->row();
			}		
			
		} else {
			$this->db->select('ac.*, COUNT(a.id_ar) AS articles');
			$this->db->from('articles_categories AS ac');
			$this->db->join('articles AS a', 'a.id_ac = ac.id_ac', 'left');
			$this->db->group_by('ac.id_ac');
			$query = $this->db->get();
			
			if($query->num_rows() > 0){
				return $query->result();
			}
			
		}
		
		return array();
	}	
	
	// ------------------------------------------------------------------------
	
	/* Return articles treeview
	 */
	function get_categories()
	{
		/* Get all the articles */
		$query = $this->db->get_where('articles_categories');
		
		$this->db->select('ac.*, COUNT(a.id_ar) AS articles');
		$this->db->from('articles_categories AS ac');
		$this->db->join('articles AS a', 'a.id_ac = ac.id_ac', 'left');
		$this->db->where(array('a.id_ar' => $id));
		$this->db->group_by('ac.id_ac');
		
		if($query->num_rows() > 0){
			return $query->result();
		}
		
		return array();
	}
	
	// ------------------------------------------------------------------------
	
	/* Return menu entries 
	 */	
	function get_categories_select() 
	{
		$results = array('0' => 'Accueil');
		
		/* Return all the menus */
		foreach ($this->db->get_where('articles_categories')->result() as $o) {
			$results [ $o->id_ac ]= $o->name;	
		}
		
		return $results;
	}
	
	// ------------------------------------------------------------------------
	
	/* Return articles treeview
	 */
	function get_categories_treeview()
	{
		/* Get all the articles categories */
		$categories = $this->get_category();
		
		return $this->treeview( 0, 0, $categories );
	}
	
	// ------------------------------------------------------------------------
	
	/* Return HTML code of the treeview
	 *
	 * @TODO : this is not really MVC ... 
	 */ 
	function treeview( $parent, $niveau, $array )
	{
		$html = '';
		$niveau_precedent = 0;
		 
		/* Loop all the articles */
		foreach ($array AS $o) 
		{
			if ($parent == $o->parent) {
			 
				if ($niveau_precedent < $niveau) $html .= "\n<ul>\n";
			 
			 	/* Create the tree entries */
				$html .= '<li class="'. ($niveau == 0 ? 'open' : 'closed') .'" ><a data-id="'. $o->id_ac .'" data-url="'. $o->url .'"><span class="folder">' . $o->name . '</span></a>';
			 
				$niveau_precedent = $niveau;
			 
			 	/* Repeat the operation, increment the level */
				$html .= $this->treeview($o->id_ac, ($niveau + 1), $array);
		 
			}
		}
		 
		if (($niveau_precedent == $niveau) && ($niveau_precedent != 0)) $html .= "</ul>\n</li>\n";
		else $html .= "</li>\n";
		 
		return $html;
	}
	
	// ------------------------------------------------------------------------
	
	/* Return the total breadcrumb of a article
	 * 
	 * @id : id of the article
	 */	
	public function get_breadcrumb($article, $parent = FALSE)
	{
		$id_ac = $parent !== FALSE ? $parent : $article->id_ac;
		$breadcrumb = array();
		
		$breadcrumb []= '<a href="'. base_url( $this->config->item('blog_url') ).'">Actualités du Web</a> / ';	
		
		do {
			$category = $this->db->get_where( 'articles_categories', array('id_ac' => $id_ac) )->row();
			
			if ( count($category) ) {
				$breadcrumb []= '<a href="'. base_url( $this->config->item('blog_url') . $category->url ). '">'. $category->name .'</a> / ';	
				$id_ac = $category->parent;			
			}
			
		} while ( count($category) );
		
		asort( $breadcrumb );
		
		return implode('', $breadcrumb) . $article->title;
	}
	
	// ------------------------------------------------------------------------
	
	function basket($id)
	{
		$this->db->update('articles', array('state' => 2), array('id_ar' => $id));
	}
	
	// ------------------------------------------------------------------------
	
	function restore($id)
	{
		$this->db->update('articles', array('state' => 0), array('id_ar' => $id));
	}	
}