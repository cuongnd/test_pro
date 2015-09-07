<?php
//namespace components\com_jrealtimeanalytics\models; 
/** 
 * @package JRealtimeAnalyticsComponents::com_jrealtimeanalytics
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Responsabilità classe jfbcGarbage
 * @package JRealtimeAnalyticsComponents::com_jrealtimeanalytics
 * @subpackage models
 * @since 1.0
 */
interface IGarbage {
	/**
	 * Esegue il garbage collector process dando avvio solo se il calcolo probabilistico ha esito positivo
	 * @access public
	 * @return Boolean
	 */
	public function execGC();
}

/**
 * Classe che gestisce il garbage collector dei messaggi obsoleti nel database 
 * @package JRealtimeAnalyticsComponents::com_jrealtimeanalytics
 * @subpackage models 
 * @since 1.0
 */
class jfbcGarbage implements IGarbage{
	
	/**
	 * Memorizza se il garbage è attivato 
	 * @access private
	 * @var Boolean
	 */
	private $enabled; 
	/**
	 * Rappresenta il massimo tempo oltre cui considerare un utente come non più online per le statistiche realtime
	 * @access private
	 * @var int
	 */
	private $maxRealstatsTime;  
	/**
	 * Decide la probabilità che il garbage collector venga avviato
	 * @access private
	 * @var int
	 */
	private $probability;
	/**
	 * @property Boolean $divisor - Il divisore probabilistico 
	 * @access private
	 * @var int
	 */
	private $divisor;
	/**
	 * Memorizza un reference all'oggetto database
	 * @access private
	 * @var Object &
	 */
	private $DBO;
	
	/**
	 * Esegue il calcolo probabilistico vero e proprio
	 * @access private
	 * @return Boolean
	 */
	private function probabilityFn() {
		$randomNumber = rand ( 0, $this->divisor );
		if (0 < $randomNumber && $randomNumber <= $this->probability) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Costruisce la query di DELETE dal DB in caso di match
	 * @access private
	 * @return String
	 */
	private function buildQuery() {
		$queries = array();
		$time_attuale = time ();
		$soglia = $time_attuale - $this->maxLifeTime;
		$sogliaRealtime = $time_attuale - $this->maxRealstatsTime;
		  
		// Garbage query
		$queries['realtime'] = "DELETE FROM #__realtimeanalytics_realstats WHERE " . $this->DBO->quoteName ( 'lastupdate_time' ) .
							   "< " . $this->DBO->Quote ( $sogliaRealtime );
 
		return $queries;
	}
	  
	/**
	 * Esegue il garbage collector process dando avvio solo se il calcolo probabilistico ha esito positivo 
	 * @access public
	 * @return Boolean
	 */
	public function execGC() {
		$match = $this->probabilityFn ();
		if ($match && $this->enabled) {
			// Garbage messaggi su DB
			$queries = $this->buildQuery ();
			 
			// Garbage status session id records realtime obsoleti
			$this->DBO->setQuery ( $queries['realtime'] );
			if (! $this->DBO->execute ()) {
				$errorMsg = $this->DBO->getErrorMsg ();
				return $errorMsg;
			} 
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Setta i parametri di configurazione nelle private properties 
	 * @access public
	 * @return Object&
	 */
	public function __construct() {
		/** 
		 * Inizializzazione oggetto garbage collector 
		 */
		$this->DBO = JFactory::getDBO ();
		//Get dei parametri del componente
		$configParams = JComponentHelper::getParams ( 'com_jrealtimeanalytics' );
		$this->probability = ( int ) $configParams->get ( 'probability' );
		$this->maxRealstatsTime = ( int ) $configParams->get ( 'maxlifetime_session' );
	 	$this->enabled = ( int ) $configParams->get ( 'gcenabled' );
		$this->divisor = 100;
	}
} 