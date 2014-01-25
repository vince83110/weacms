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

class Articles extends MY_AdminController
{
	public function __construct() 
	{
		parent::__construct();
		
		$this->load->helper('date_fr');
		$this->load->model(array('article_model', 'lang_model'));
	}
	
	// ------------------------------------------------------------------------
	
	public function index()
	{		
		$this->template->write('title', 'Gestion des articles');
		$this->template->add_jS('tablesorter');
		
		$this->template->write_view('content', 'articles/index', array(
			'articles' => $this->article_model->get(NULL, NULL, array('a.state !=' => 2))
		));
	}
	
	// ------------------------------------------------------------------------
	
	public function edition($id = NULL)
	{
		$this->load->model('template_model');
		$this->load->model('widget_model');
				
		$this->template->write('title', 'Modification d\'un article');
		$this->template->addJS(array('tinymce/tiny_mce', 'treeview', 'uploadify'));
		$this->template->addCSS('treeview');
		$this->template->write_view('content', 'articles/create', array(
					'article' => $this->article_model->get($id), 
					'categories' => $this->article_model->get_categories_treeview()));
		
		$this->template->render();
	}
	
	// ------------------------------------------------------------------------
	
	public function valider_dossier() 
	{
		$id_ar = $this->input->post('id');
		
		$this->db->update('articles', array('is_dossier' => 0));
		
		$this->db->update('articles', array('is_dossier' => 1), array('id_ar' => $id_ar));
	}
	
	// ------------------------------------------------------------------------
	
	/* Update or create an article in database
	 *
	 * @post : contains article informations
	 */
	function update()
	{
		/* Set some validations rules */
		$this->form_validation->set_rules('title','Titre','required');
		$this->form_validation->set_rules('url','Titre','required');
		$this->form_validation->set_rules('content','Contenu','required');
		
		if($this->form_validation->run()) {
			
			$user = $this->ion_auth->get_user();
			
			/* If id != 0, we update the article */
			if ($this->input->post('id_ar') != 0) {
				$this->article_model->update( array_merge( $this->input->post(), array('id_us' => $user->id, 'state' => 0)) );
				$id = $this->input->post('id_ar');
				$this->log_model->add(10, $id);
			}
			/* Else we create the entry in database */
			else {
				$id = $this->article_model->add( array_merge( $this->input->post(), array('id_us' => $user->id, 'state' => 0)) );
				$this->log_model->add(9, $id);
			}
			
			/* Return the id inserted */
			die((string)$id);
			
		} else {
			// Certains champs obligatoires sont manquants
			die(validation_errors());
		}		
	}
	
	// ------------------------------------------------------------------------
	
	public function corbeille() 
	{
		$this->template->write('title', 'Corbeille');
		$this->template->add_js('datatable');
		
		$this->template->write_view('content', 'articles/basket', array(
			'articles' => $this->article_model->get_delete()
		));
	}
	
	// ------------------------------------------------------------------------
	
	public function basket()
	{
		$this->article_model->basket($this->input->post('id'));
		
		response_json(TRUE);
	}
		
	// ------------------------------------------------------------------------
	
	public function restore()
	{
		$this->article_model->restore($this->input->post('id'));
		
		response_json(TRUE);
	}
	
	// ------------------------------------------------------------------------
	
	/* Create category article, contains every articles
	 * 
	 * @id : id of the category to generate
	 */	
	public function valider_category( $id = NULL )
	{
		$this->load->driver('cache');
		$id = $id ? $id : $this->input->post('id');

		/* Get the category */
		$category = $this->article_model->get_category($id);

		/* Put the article in cache file */
		$this->cache->file->save( md5($this->config->item('blog_url') . $category->url), $this->voir_category($id, TRUE), 36000);	
		
		/* Reload the main blog page */
		$this->cache->file->save( md5( rtrim($this->config->item('blog_url'), '/') ), $this->voir_accueil(TRUE), 36000);	
		
		return TRUE;
	}			
	
	// ------------------------------------------------------------------------
	
	/* Return the content of a article
	 * if $return = TRUE, return only the output
	 * 
	 * @id : id of the article to view
	 */	
	public function voir_accueil($return = FALSE)
	{
		/* Get the article to display */
		$categories = $this->article_model->get_category();
		$articles 	= $this->article_model->get(NULL, 6, array('a.state !=' => 2), 'a.is_dossier DESC, a.created', 'DESC');
	
		$accueil->title = 'Suivez les nouveautés des technologies Web';
		$accueil->description = 'Découvrez les derniers plugins, les dernières nouveautés concernant le développement Web. Plugin jQuery, astuces Prestashop, actualité...';
		$accueil->template = 3;
		
		return $this->load->view('site/template_en', array(
				'page'			=> $accueil,
				'content'		=> $this->content_accueil($articles, $categories),
				'breadcrumb' 	=> 'Actualités du Web',
				'comments'		=> FALSE,
				/* Display Analysis button if the article is displayed in admin view */
				'analysis'		=> !$return), 
		$return);	
	}		
	
	// ------------------------------------------------------------------------
	
	/* Return the content of the blog homepage
	 * list the last article and the last 5 articles
	 * 
	 * @articles : articles to list - array
	 * @category : category to view - object
	 */	
	public function content_accueil($articles, $categories)
	{
		return $this->load->view('site/blog/index', array(
				'articles' 		=> $articles,
				'categories'	=> $categories),
		TRUE);	
	}		
	
	// ------------------------------------------------------------------------
	
	/* Return the content of a article
	 * if $return = TRUE, return only the output
	 * 
	 * @id : id of the article to view
	 */	
	public function voir_category($id, $return = FALSE)
	{
		/* Get the article to display */
		$category = $this->article_model->get_category($id);
		$articles = $this->article_model->get(NULL, 20, array('a.state !=' => 2, 'a.id_ac' => $id));
		
		$category->title = $category->name;
		$category->template	= 3;
		
		return $this->load->view('site/template_en', array(
				'page' 			=> $category,
				'content'		=> $this->content_category($articles, $category),
				'breadcrumb' 	=> $this->article_model->get_breadcrumb($category, $category->parent),
				'menu'			=> '',
				'categories'	=> $this->article_model->get_category(),
				'comments'		=> FALSE,
				/* Display Analysis button if the article is displayed in admin view */
				'analysis'		=> !$return), 
		$return);	
	}	
		
	// ------------------------------------------------------------------------
	
	/* Return the content of a category article
	 * list every articles in a category
	 * 
	 * @articles : articles to list - array
	 * @category : category to view - object
	 */	
	public function content_category($articles, $category)
	{
		$this->load->helper('date_fr');
				
		return $this->load->view('site/blog/category', array(
				'articles' 		=> $articles,
				'category'		=> $category),
		TRUE);	
	}	
	
	// ------------------------------------------------------------------------
	
	/* Valide a article and put the content in cache file
	 * param : $id @post
	 */	
	public function valider_toutes()
	{
		foreach($this->article_model->get(NULL, NULL, array('a.state !=' => 2)) as $o) {
			$this->valider( $o->id_ar );	
		}
		
		die('done');
	}
	
	// ------------------------------------------------------------------------
	
	/* Valide a article and put the content in cache file
	 * param : $id @post
	 */	
	public function valider( $aid = NULL )
	{
		$this->load->driver('cache');
		$id = $aid ? $aid : $this->input->post('id');

		/* Get the article */
		$article = $this->article_model->get($id);
		
		$this->article_model->update(array('id_ar' => $id, 'state' => 1));
		
		/* Add log status */
		$this->log_model->add(11, $id);

		/* Put the article in cache file */
		$this->cache->file->save( md5($this->config->item('blog_url') . $article->url), $this->voir($id, 1, TRUE), 36000);	
		
		/* Reload category article */
		$this->valider_category( $article->id_ac );
		
		if ( $aid ) {
			return;	
		}
		
		die('done');
	}	
	
	// ------------------------------------------------------------------------
	
	/* Return the content of a article
	 * if $return = TRUE, return only the output
	 * 
	 * @id : id of the article to view
	 */	
	public function voir($id, $lang = 0, $return = FALSE)
	{
		/* Getting the language */
		$lang = $this->lang_model->get($lang == 0 ? $this->lang_model->get_main() : $lang);
		
		$modules = array();
		$this->load->model('widget_model');
		
		/* Get each widget of the page */
		foreach ($this->widget_model->get_page_widgets($id) as $o)
		{
			/* Load in an array all the widgets outputs */
			if (! isset($modules[$o->zone])) {
				$modules[$o->zone] = Modules::run($o->class);
				
			} else {
				$modules[$o->zone] .= Modules::run($o->class);
			}
		}
		
		/* Get the article to display */
		$article = $this->article_model->get($id);
		$article->template = 1;
		$article->article = TRUE;
		
		/* Get the right template */
		$template = 'site/template_'. strtolower($lang->letters);
		
		return $this->load->view($template, array(
				'page' 			=> $article, 
				'content'		=> $article->content,
				'modules' 		=> $modules,
				'breadcrumb' 	=> $this->article_model->get_breadcrumb($article),
				'css' 			=> $this->template->addCSS($article->css, TRUE),
				'js' 			=> $this->template->addJS($article->js, TRUE),
				'comments'		=> TRUE,
				'categories'	=> $this->article_model->get_category(),
				/* Display Analysis button if the article is displayed in admin view */
				'analysis'		=> !$return), 
		$return);		
	}
	
	// ------------------------------------------------------------------------
	
	/* Show categories for articles part
	 * Return a filetree view of the categories
	 */
	public function categories() 
	{
		$this->template->write('title', 'Catégories du blog');
		$this->template->addJS('treeview');
		$this->template->addCSS('treeview');
		$this->template->write_view('content', 'articles/categories', array(
				'categories' => $this->article_model->get_categories_treeview(),
				'categories_select' => $this->article_model->get_categories_select(),
				'total' => count($this->article_model->get_categories()) ));
		$this->template->render();				
	}
	
	// ------------------------------------------------------------------------
	
	public function creer_category($type) 
	{
		$this->article_model->add_category($this->input->post());
		
		die('done');
	}
}