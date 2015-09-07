<?php
// namespace administrator\components\com_jrealtimeanalytics\models;
/** 
 * @package JREALTIMEANALYTICS::SERVERSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.model' );

/**
 * Server stats model responsibilities contract
 *
 * @package JREALTIMEANALYTICS::SERVERSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage models
 * @since 1.0
 */
interface IServerstatsModel {
	/**
	 * Dependency injection setter del graph instance object generator
	 *
	 * @param Object& $graphInstance
	 * @access public
	 * @return void
	 */
	public function setGraphRenderer(&$graphInstance);
	
	/**
	 * Get geolocation translations from DB
	 *
	 * @access public
	 * @return array[] &
	 */
	public function &getGeoTranslations();
	
	/**
	 * Main get data method
	 *
	 * @access public
	 * @return Object[]
	 */
	public function getData(); 
	
	/**
	 * Load details entity
	 *
	 * @access public
	 * @param string $identifier
	 * @param string $detailType
	 * @return Object[]
	 */
	public function loadEntity($identifier, $detailType);
	
	/**
	 * Cancella la DB cache delle server stats
	 *
	 * @access public 
	 * @return boolean
	 */
	public function deleteEntity();
}

/**
 * @package JREALTIMEANALYTICS::SERVERSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage models
 * @since 1.0
 */
class JRealtimeAnalyticsModelServerstats extends JModelLegacy implements IServerstatsModel {
	/**
	 * Data inizio periodo statistiche
	 *
	 * @access private
	 * @var string
	 */
	private $intervalFrom;
	
	/**
	 * Data termine periodo statistiche
	 *
	 * @access private
	 * @var string
	 */
	private $intervalTo;
	
	/**
	 * Data structure container
	 * 
	 * @access private
	 * @var array 
	 */
	private $data;
	
	/**
	 * Component configuration object
	 * 
	 * @access private
	 * @var Object
	 */
	private $config;
	
	/**
	 * Graph generator object
	 * 
	 * @access private
	 * @var Object
	 */
	private $graphGenerator;
	
	/**
	 * Query snippet WHERE in cui tutto viene filtrato in base al periodo
	 *
	 * @access private
	 * @var string
	 */
	private $whereQuery;

	/** 
	 * Numero di visite GROUPED BY visitedpage
	 * 
	 * @access private
	 * @return array
	 */
	private function visitsPerPage() {
		$query = "SELECT COUNT(*) AS numvisits, MAX(". $this->_db->quoteName('visit_timestamp') . "), " . $this->_db->quoteName('visitedpage') .  
				 "\n FROM  #__realtimeanalytics_serverstats" .
				 $this->whereQuery .
				 "\n GROUP BY " . $this->_db->quoteName('visitedpage') .
				 "\n ORDER BY numvisits DESC";
		$this->_db->setQuery($query);
		$results = $this->_db->loadRowList();
		
		return $results;
	}

	/**
	 * Numero di pagine visitate complessivamente nel periodo
	 * 
	 * @access private
	 * @return int 
	 */
	private function totalVisitedPages() {
		$query = "SELECT COUNT(*)" .  
				 "\n FROM  #__realtimeanalytics_serverstats" .
				 $this->whereQuery;
		$this->_db->setQuery($query);
		$result = (int) $this->_db->loadResult();
		
		return $result;
	}

	/**
	 * Array contenente il numero di pagine, last visit date, browser, os,  GROUP BY session_id_person
	 * 
	 * @access private
	 * @return array 
	 */
	private function totalVisitedPagesPerUser() {
		$query = "SELECT COUNT(*), " . $this->_db->quoteName('customer_name') . "," . 
				 "\n MAX(". $this->_db->quoteName('visit_timestamp') . ")," .
				 $this->_db->quoteName('browser') . "," .
				 $this->_db->quoteName('os') . "," .
				 $this->_db->quoteName('session_id_person') . "," . 
				 $this->_db->quoteName('ip') .
				 "\n FROM ( SELECT * FROM #__realtimeanalytics_serverstats ORDER BY  `visit_timestamp` DESC) AS INTABLE" .
				 $this->whereQuery .
				 "\n GROUP BY " . $this->_db->quoteName('session_id_person') .
				 "\n ORDER BY " . $this->_db->quoteName('customer_name');
		$this->_db->setQuery($query);
		$results = $this->_db->loadRowList();
		
		return $results;
	}
	
	/**
	 * Numero di utenti totali DISTINCT per il periodo
	 *
	 * @access private
	 * @return int
	 */
	private function totalVisitors() {
		$query = "SELECT COUNT(DISTINCT(" . $this->_db->quoteName('session_id_person') . "))" .  
				 "\n FROM  #__realtimeanalytics_serverstats" .
				 $this->whereQuery;
		$this->_db->setQuery($query);
		$result = (int)$this->_db->loadResult();
		
		return $result;
	}
	
	/**
	 * Durata media della visita per il singolo utente
	 *
	 * @access private
	 * @return int in secondi
	 */
	private function mediumVisitTime() {
		$query = "SELECT MAX(". $this->_db->quoteName('visit_timestamp') . "),MIN(". $this->_db->quoteName('visit_timestamp') . ")" . 
				 "\n FROM  #__realtimeanalytics_serverstats" .
				 $this->whereQuery .
				 "\n GROUP BY " . $this->_db->quoteName('session_id_person');
		$this->_db->setQuery($query);
		$results = $this->_db->loadRowList();
		$mediumVisitTime = 0;
		
		if(count($results)) {
			// Ciclo di calcolo per determinare il tempo medio di visita
			$totalVisitedTime = 0;
			foreach ($results as $result) {
				$parziale = $result[0] - $result[1];
				$totalVisitedTime += $parziale;
			}
			
			// Media di visita
			$totalVisitors = count($results);
			$mediumVisitTime = (int)$totalVisitedTime / $totalVisitors; 
			$mediumVisitTime = gmdate('H:i:s', $mediumVisitTime);
		}
		
		return $mediumVisitTime;
	}
	
	/**
	 * Numero medio di pagine viste per il singolo utente
	 *
	 * @access private
	 * @return int
	 */
	private function mediumVisitedPagesPerSingleUser() {
		$query = "SELECT COUNT(" . $this->_db->quoteName('visitedpage') . ")," . 
				 "\n COUNT(DISTINCT(" . $this->_db->quoteName('session_id_person') . "))" .
				 "\n FROM  #__realtimeanalytics_serverstats" .
				 $this->whereQuery;
		$this->_db->setQuery($query);
		$result = $this->_db->loadRow();
		
		// Calcolo pagine medie viste per utente
		$mediumVisistedPage = 0;
		if(count($result[0]) && $result[1]) {
			$mediumVisistedPage = sprintf('%.2f', $result[0] / $result[1]);
		}
		
		return $mediumVisistedPage;
	}

	/**
	 * Array contenente il numero di utenti GROUP BY geolocation
	 * 
	 * @access private
	 * @return array 
	 */
	private function numUsersGeoGrouped() {
		$query = "SELECT COUNT(DISTINCT(" . $this->_db->quoteName('session_id_person') . ")) AS numusers," .  
				 $this->_db->quoteName('geolocation') .
				 "\n FROM  #__realtimeanalytics_serverstats" .
				 $this->whereQuery .
				 "\n GROUP BY " . $this->_db->quoteName('geolocation') .
				 "\n ORDER BY " . $this->_db->quoteName('numusers') . " DESC";
		$this->_db->setQuery($query);
		$results = $this->_db->loadRowList();
		
		return $results;
	}

	/**
	 * Array contenente il numero di utenti GROUP BY browser
	 * 
	 * @access private
	 * @return array 
	 */
	private function numUsersBrowserGrouped() {
		$query = "SELECT COUNT(DISTINCT(" . $this->_db->quoteName('session_id_person') . "))  AS numusers," .     
				 $this->_db->quoteName('browser') .
				 "\n FROM  #__realtimeanalytics_serverstats" .
				 $this->whereQuery .
				 "\n GROUP BY " . $this->_db->quoteName('browser') .
				 "\n ORDER BY " . $this->_db->quoteName('numusers') . " DESC";
		$this->_db->setQuery($query);
		$results = $this->_db->loadRowList();
		
		return $results;
	}

	/**
	 * Array contenente il numero di utenti GROUP BY os
	 * 
	 * @access private
	 * @return array 
	 */
	private function numUsersOSGrouped() {
		$query = "SELECT COUNT(DISTINCT(" . $this->_db->quoteName('session_id_person') . "))  AS numusers," .    
				 $this->_db->quoteName('os') .
				 "\n FROM  #__realtimeanalytics_serverstats" .
				 $this->whereQuery .
				 "\n GROUP BY " . $this->_db->quoteName('os') .
				 "\n ORDER BY " . $this->_db->quoteName('numusers') . " DESC";
		$this->_db->setQuery($query);
		$results = $this->_db->loadRowList();
		
		return $results;
	}
	
	/**
	 * Classifica delle pagine più abbandonate, AKA le ultime
	 * pagine viste dagli utenti dalla più ultima visitata
	 * Leave off page - Necessita di una COUNT GROUPED BY session_id_customer
	 * delle pagine con MAX visit_timestamp
	 *
	 * @access private
	 * @return array
	 */
	private function hitLeaveOffPages() { 
		$query = "SELECT COUNT(" . $this->_db->quoteName('visitedpage') . ") AS mostleaved," . 
				$this->_db->quoteName('visitedpage') .
				"\n FROM  #__realtimeanalytics_serverstats" .
				"\n WHERE " . $this->_db->quoteName('visit_timestamp') . " IN (" .
				"\n SELECT MAX(" . $this->_db->quoteName('visit_timestamp') . ")" .
				"\n FROM  `#__realtimeanalytics_serverstats`" .
				$this->whereQuery .
				"\n	GROUP BY " . $this->_db->quoteName('session_id_person') . ")" . 
				"\n GROUP BY " . $this->_db->quoteName('visitedpage') .
				"\n ORDER BY " . $this->_db->quoteName('mostleaved') . " DESC";
		$this->_db->setQuery($query);
		$results = $this->_db->loadRowList();
	
		return $results;
	}

	/**
	 * Classifica delle landing page, AKA le prime
	 * pagine viste dagli utenti in arrivo 
	 *
	 * @access private
	 * @return array
	 */
	private function hitLandingPages() {
		$query = "SELECT COUNT(" . $this->_db->quoteName('visitedpage') . ") AS mostlanding," .
				$this->_db->quoteName('visitedpage') .
				"\n FROM  #__realtimeanalytics_serverstats" .
				"\n WHERE " . $this->_db->quoteName('visit_timestamp') . " IN (" .
				"\n SELECT MIN(" . $this->_db->quoteName('visit_timestamp') . ")" .
				"\n FROM  `#__realtimeanalytics_serverstats`" .
				$this->whereQuery .
				"\n	GROUP BY " . $this->_db->quoteName('session_id_person') . ")" .
				"\n GROUP BY " . $this->_db->quoteName('visitedpage') .
				"\n ORDER BY " . $this->_db->quoteName('mostlanding') . " DESC";
		$this->_db->setQuery($query);
		$results = $this->_db->loadRowList();
	
		return $results;
	}

	/**
	 * Esplica la generazione delle graph images successivamente richiamate
	 * e visualizzate dalla view templates preposta entro ogni stats box
	 * 
	 * @access private
	 * @return boolean
	 */
	private function graphRender() {   
		$this->graphGenerator->buildBars($this->data);
		$this->graphGenerator->buildPies($this->data, $this->getGeoTranslations());
	}

	/**
	 * Esplica il garbage collector delle immagini generate per i grafici
	 * contestualmente all'utente ad esempio 62_graphbar1.png, 62_pie1.png ecc
	 * 
	 * @access protected
	 * @return array Un array dei file immagini cancellati
	 */
	protected function imagesGarbage() {
		// Deleting all files in a directory older than latency = 24h
		$directory = JPATH_COMPONENT . '/cache';
	    $filenames = array();
	    $latencyTime = time() - ($this->config->get ( 'maxlifetime_file' )*24*60*60);
	    $iterator = new DirectoryIterator($directory);
	    foreach ($iterator as $fileinfo) {
	        if ($fileinfo->isFile()) {
	            $filenames[] = array($fileinfo->getMTime(), $fileinfo->getFilename());
	        }
	    }
 		
	    $deletedFiles = array();
	    if(sizeof($filenames) > 1) {
	        foreach ($filenames as $fileElem) {
	            if($fileElem[0] < $latencyTime){ 
	                if(unlink($directory."/".$fileElem[1])) {
	                	$deletedFiles[] = $fileElem[1];
	                } 
	            } 
	        }
	    } 
	    return $deletedFiles;
	}
	
	/**
	 * Dependency injection setter del graph instance object generator
	 *
	 * @param Object& $graphInstance
	 * @access public
	 * @return void
	 */
	public function setGraphRenderer(&$graphInstance) {
		$this->graphGenerator = $graphInstance;
	}
	
	/**
	 * Get geolocation translations from DB
	 * 
	 * @access public
	 * @return array[] &
	 */
	public function &getGeoTranslations() {
		static $resultTranslations;
		
		if($resultTranslations) {
			return $resultTranslations;
		}
		$query = "SELECT" .
				$this->_db->quoteName('iso1_code') . "," .
				$this->_db->quoteName('name') . 
				"\n FROM  #__realtimeanalytics_countries_map";
		$this->_db->setQuery($query);
		$resultTranslations = $this->_db->loadAssocList('iso1_code');
		
		return $resultTranslations;
	}
	
	/**
	 * Main get data method
	 *
	 * @access public
	 * @return Object[]
	 */
	public function getData() {
		// Images graph garbage collector start
		$randomNumber = rand(0, 100);
		if(0 < $randomNumber && $randomNumber <= (int) $this->config->get ( 'probability' )){
			$this->imagesGarbage();
		}
		
		// Store period nelle class properties
		$this->intervalFrom = $this->getState('fromPeriod');
		$this->intervalTo = $this->getState('toPeriod');
		$this->whereQuery = "\n WHERE " .  $this->_db->quoteName('visitdate') . " >= " . $this->_db->Quote($this->intervalFrom) .
		 					"\n AND " .  $this->_db->quoteName('visitdate') . " <= " . $this->_db->Quote( $this->intervalTo );
		
		// Calculation dei dati da presentare all'utente
		$this->data[] = $this->visitsPerPage();
	  	$this->data[] = $this->totalVisitedPages();
	  	$this->data[] = $this->totalVisitedPagesPerUser();  
	  	$this->data[] = $this->totalVisitors();
	  	$this->data[] = $this->mediumVisitTime();
	  	$this->data[] = $this->mediumVisitedPagesPerSingleUser();
	  	$this->data[] = $this->numUsersGeoGrouped();
	  	$this->data[] = $this->numUsersBrowserGrouped();
	  	$this->data[] = $this->numUsersOSGrouped();
	  	$this->data[] = $this->hitLeaveOffPages();
	  	$this->data[] = $this->hitLandingPages();
	  	
		// Generazione nuove immagini grafici su filesystem richiamabili dalla view in base all'id utente
		$this->graphRender();
		   
		return $this->data;
	}
	
	/**
	 * Load details entity
	 *
	 * @access public
	 * @param string $identifier
	 * @param string $detailType
	 * @return Object[]
	 */
	public function loadEntity($identifier, $detailType) {
		// Init query
		$query = null;
		// Where fixed query construction
		$this->whereQuery = "\n WHERE " .  $this->_db->quoteName('visitdate') . " >= " . $this->_db->Quote($this->getState('fromPeriod')) .
							"\n AND " .  $this->_db->quoteName('visitdate') . " <= " . $this->_db->Quote($this->getState('toPeriod'));
		
		// Switch load data in base al tipo di detail richiesto
		switch ($detailType){
			case 'user':
				$query = "SELECT " . $this->_db->quoteName('visitedpage') . "," . 
						 "\n ". $this->_db->quoteName('visit_timestamp') .  
						 "\n FROM " . $this->_db->quoteName('#__realtimeanalytics_serverstats') .
						 $this->whereQuery . 
						 "\n AND " . $this->_db->quoteName('session_id_person') . " = " . $this->_db->Quote($identifier) .
						 "\n ORDER BY " . 
						 $this->_db->quoteName('visit_timestamp') . " DESC";
				break;
				
			case 'page':
				$query = "SELECT " . $this->_db->quoteName('customer_name') . "," . 
						 "\n ". $this->_db->quoteName('visit_timestamp') .  
						 "\n FROM " . $this->_db->quoteName('#__realtimeanalytics_serverstats') .
						 $this->whereQuery . 
						 "\n AND " . $this->_db->quoteName('visitedpage') . " = " . $this->_db->Quote($identifier) .
						 "\n ORDER BY " . 
						 $this->_db->quoteName('visit_timestamp') . " DESC";
				break;
			
		}
		 
		$this->_db->setQuery($query);
		$results = $this->_db->loadObjectList();
		
		return $results;
	}
	
	/**
	 * Cancella la DB cache delle server stats
	 *
	 * @access public
	 * @return boolean
	 */
	public function deleteEntity() {
		$query = "DELETE FROM #__realtimeanalytics_serverstats";
		$this->_db->setQuery($query);
		return $this->_db->execute();
	}
	
	/**
	 * Class Constructor
	 * 
	 * @access public
	 * @return Object&
	 */
	public function __construct() { 
		$this->data = array();
		$this->config = &JComponentHelper::getParams('com_jrealtimeanalytics');
		parent::__construct();
	}
}
?>