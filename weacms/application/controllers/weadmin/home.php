<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_AdminController
{
	public function index()
	{	echo 'oo';
		$this->load->model('page_model');
		$this->load->helper('date_fr');
	echo 'kk';
		$this->template->write('title', 'Bienvenue sur votre outil de gestion');
		
		$this->template->write_view('content', 'home/index', array(
			'portlet_pages' => 
				$this->load->view($this->config->item('admin_url') .'home/portlet/pages', array(
					'pages' => $this->page_model->get(NULL, 6, array('p.state' => 0))
				), TRUE), 
			'portlet_pages_valid' => 
				$this->load->view($this->config->item('admin_url') .'home/portlet/pages_valid', array(
					'pages' => $this->page_model->get(NULL, 3, array('p.state' => 1))
				), TRUE), 		
			'portlet_logs' => 
				$this->load->view($this->config->item('admin_url') .'home/portlet/logs', array(
					'logs' => $this->log_model->get(NULL, 6)
				), TRUE), 			 
			'hook_home'	=> $this->template->hook('hook_home'),		
		));
	}
	
	// ------------------------------------------------------------------------
	
	public function statistiques($period = 0)
	{
		$this->load->library('GoogleAnalytics');
		
		die('<div class="alert-message block-message warning"><h5>Identifiants nécessaire pour obtenir les statistiques en ligne.</h5></div>');
		
		$ga = new GoogleAnalytics;
		//$ga->connect('internetmdv@gmail.com', 'Stat=emoa83', '58478035', date('Y-m-d', time() - ($period*86400)),  date('Y-m-d', time()));
		$ga->connect('internetmdv@gmail.com', '', '58478035', date('Y-m-d', time() - ($period*86400)),  date('Y-m-d', time()));
		
		$keywords = array_slice($ga->getDimensionByMetric('pageviews', 'keyword'), 0, 6);
		$source = array_slice($ga->getDimensionByMetric('pageviews', 'source'), 0, 6);
		$pagePath = $ga->getDimensionByMetric('pageviews', 'pagePath');
		$visits = $ga->getMetric('visits');
		$unique_visits = $ga->getMetric('visitors');
		$page_views = $ga->getMetric('pageviews');
									
		echo '      
			<table class="zebra-striped sorter">
                    <thead>
                        <tr>
                            <th class="header yellow">Mots clés</th>
                            <th class="header green" width="130">Nombre</th>
                        </tr>
                    </thead>';
					
		foreach ($keywords as $o) {
			if ($o['label'] !== '(not set)') 
			{
				echo '<tr><td>'. $o['label'] .'</td><td>'. $o['value'] .'</td></tr>';
			}
		}
		echo '<br />';
		 
		echo '      
			<table class="zebra-striped sorter">
                    <thead>
                        <tr>
                            <th class="header yellow">Source de visite</th>
                            <th class="header green" width="130">Nombre</th>
                        </tr>
                    </thead>';
					
		foreach ($source as $o) {
			echo '<tr><td>'. $o['label'] .'</td><td>'. $o['value'] .'</td></tr>';
		}
		
		echo '
                <table class="zebra-striped sorter">
                    <thead>
                        <tr>
                            <th class="header red">Résumé</th>
                            <th class="header green" width="130">Valeur</th>
                        </tr>
                    </thead>
                    <tbody>
						<tr><td>Nombre de visites</td><td>'.$visits.'</td></tr>
						<tr><td>Nombre de visites uniques</td><td>'.$unique_visits.'</td></tr>
						<tr><td>Pages vues</td><td>'.$page_views.'</td></tr>
                    </tbody>
                </table>';
		die();
	}
}