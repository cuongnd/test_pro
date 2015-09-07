<?php
//namespace components\com_jrealtimeanalytics\models;
/**  
 * Gestore dei messaggi e dei dati 
 * @package JREALTIMEANALYTICS::DATA::components::com_jrealtimeanalytics
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */ 
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 

/**
 * Responsabilità classe gestore dei messaggi e dei dati  
 * @package JRealtimeAnalyticsComponents::com_jrealtimeanalytics
 * @subpackage models 
 * @since 1.0
 */
interface IReceiver {

	/**
	 * Execute della app logic da controller
	 * @access public 
	 * @return void
	 */
	public function appDispatch();
}

/**
 * Classe gestore dei messaggi e dei dati  
 * @package JRealtimeAnalyticsComponents::com_jrealtimeanalytics
 * @subpackage models 
 * @since 1.0
 */
class jfbcReceiver implements IReceiver{
	/**
	 * Session Object
	 * @access private
	 * @var Object &
	 */
	private $session;
	
	/**
	 * Me user Object
	 * @access private
	 * @var Object &
	 */
	private $myUser;
	 
	/**
	 * Memorizza un reference all'oggetto database
	 * @access private
	 * @var Object &
	 */
	private $DBO;
	
	/**
	 * Component config
	 * @access private
	 * @var Object &
	 */
	private $config; 
	
	/**
	 * Realstats communication instance
	 * @access private
	 * @var Object &
	 */
	private $realStats;
	
	/**
	 * Serverstats tracking instance
	 * @access private
	 * @var Object &
	 */
	private $serverStats; 
	
	/**
	 * Array associativo della response HTTP
	 * @access protected
	 * @var array
	 */
	protected $response;
 
	/**
	 * Output response application/json
	 * @access private 
	 * @return void
	 */
	private function sendResponse() { 
		// Fondamentale per far arrivare prima la paramslist ad initialize = 1
		ksort($this->response);
		header ( 'Content-type: application/json; charset=utf-8' );
		echo json_encode ( $this->response );
		exit ();
	}
   
	/**
	 * Effettua un reverse reduce sul'ID di sessione MD5 per arrivare
	 * ad una stringa da appendere al prefix del name assegnato ai guest users
	 * @param string $sessionID
	 * @access private
	 * @return string
	 */
	private function generateRandomGuestNameSuffix($sessionID) {
		$appendHashSuffix = $this->config->get('guestprefix');
		// Conversione da base 16 a base 36 ovvero 10 digits + 26 chars. Mantiene 128 bit come l'MD5 ma permette di avere una stringa più corta
		$hash = base_convert($sessionID, 16, 36);
	
		// Recuperiamo la parte numerica dell'hash in base 36
		$numericHashArray = preg_match_all('/\d/i', $hash, $matches);
	
		if(is_array($matches[0]) && count($matches[0])) {
			$appendHashSuffix = '_' . implode('', $matches[0]);
			// Limitiamo a 4 cifre il numeric suffix
			$appendHashSuffix = $this->config->get('guestprefix') . substr($appendHashSuffix, 0, 5);
		}
	
		return $appendHashSuffix;
	}
	 
	/**
	 * Execute della app logic da controller
	 * @access public 
	 * @return void
	 */
	public function appDispatch() { 
		$initialize = JRequest::getBool ( 'initialize' );
		// Store server stats con dependency injected object
		$userName = $this->myUser->name;
		if(!$userName) {
			$userName = $this->generateRandomGuestNameSuffix($this->session->session_id);
		}
		
		// Refresh realtime stats con dependency injected object
		$this->realStats->refreshRealtime($userName); 
		// Se è l'initialize = 1 ovvero la prima ajax call store server stats
		if ( $initialize ) { 
			$this->serverStats->storeServerStats($userName); 
			 
			// Inject dei parametri che condizionano la restante parte nella JS APP
			if(!empty($this->config)) {
				$this->response['aparamslist'] = $this->config->toObject();  
			}
			 
			// Lazy loading initialization objects <<use>>
			// Sezione Garbage dipendenza generica
			if((bool)$this->config->get('gcenabled')){
				$gc = new jfbcGarbage();
				//Exec GC Probability
				$execGC = $gc->execGC();
			}
		} 
		// Final response send
		$this->sendResponse (); 
	}
	
	/**
	 * Class constructor
	 * @access public 
	 * @param Object& $session 
	 * @param Object& $realStats
	 * @param Object& $serverStats
	 * @return Object&
	 */
	public function __construct(&$session, &$realStats, &$serverStats) {
		// init
		$this->messages = array();
		$this->response = array();
		$this->session = &$session;
		$this->realStats = &$realStats;
		$this->serverStats = &$serverStats;
		$this->myUser = JFactory::getUser();
		$this->DBO = JFactory::getDBO();
		$this->config = JComponentHelper::getParams ( 'com_jrealtimeanalytics' );
	}
}

