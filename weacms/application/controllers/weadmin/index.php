<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller 
{
	public function index()
	{
		$this->load->model('page_model');
		
		$this->template->addJS('tablesorter');
		$this->template->write('title', 'Bienvenue sur votre outil de gestion');
		$this->template->write_view('content', 'home', array(
				'pages' => $this->page_model->get(NULL, 6), 
				'pagesvalid' => array(), 
				'logs' => $this->log_model->get(NULL, 6)
			));
		$this->template->render();
	}
	
	// ------------------------------------------------------------------------
	
	public function statistiques()
	{

		$this->load->library('GoogleAnalytics');
		
		$ga = new GoogleAnalytics;
		$ga->connect('internetmdv@gmail.com', 'Stat=emoa83', '9314097', date('Y-m-d', time()));
		
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