<?php
// namespace components\com_jrealtimeanalytics\models;
/**
 * Gestore dei messaggi e dei dati
 *
 * @package JREALTIMEANALYTICS::SERVERSTATS::components::com_jrealtimeanalytics
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Responsabilità classe gestore dei messaggi e dei dati
 *
 * @package JREALTIMEANALYTICS::SERVERSTATS::components::com_jrealtimeanalytics
 * @subpackage models
 * @since 1.0
 */
interface IServerstats {
	/**
	 * Metodo di interfaccia pubblica per lo storing dei dati server stats
	 * nel tracking per il dispatch corrente
	 *
	 * @access public
	 * @param string $customerName        	
	 * @return boolean
	 */
	public function storeServerStats($customerName);
}

/**
 *
 * @access public
 * @package SERVERSTATS.componentscom_jrealtimeanalytics.models
 */
class jfbcServerstats implements IServerstats {
	/**
	 * Pagina visitata in tracking
	 *
	 * @var string
	 * @access private
	 */
	private $visitedPage;
	
	/**
	 * DB Connector
	 *
	 * @var Object&
	 * @access private
	 */
	private $DBO;
	
	/**
	 * Session ID utente in refresh
	 *
	 * @var Object&
	 * @access private
	 */
	private $session;
	
	/**
	 * Component config
	 *
	 * @access private
	 * @var Object &
	 */
	private $config;
	
	/**
	 * Ottiene la nowpage visitata dall'utente nel tracking corrente in dispatch
	 *
	 * @access protected
	 * @return string
	 */
	protected function getVisitedPage() {
		return urldecode(JRequest::getVar('nowpage', null, 'POST'));
	}
	
	/**
	 * Ottiene l'header HTTP Accept/Language per lo storing
	 * della nazionalità dell'utente
	 *
	 * @access protected
	 * @return string
	 */
	protected function getLocationHeader() {
		$chunkHttpAcceptHeader = $_SERVER ['HTTP_ACCEPT_LANGUAGE'];
		
		// Patch per header HTTP Internet Explorer
		if(strlen($chunkHttpAcceptHeader) > 2) {
			$spliced = explode ( '-', $chunkHttpAcceptHeader );
			$code = substr ( $spliced [1], 0, 2 ); 
		} else {
			$code = $chunkHttpAcceptHeader;
		} 
		return strtoupper($code);
	}
 
	/**
	 * Ottiene il browser in uso dall'utente
	 *
	 * @access protected
	 * @return string
	 */
	protected function getBrowser() { 
		$browserName = 'N/A';
		$browsers = array (
				'firefox',
				'msie',
				'opera',
				'chrome',
				'safari',
				'mozilla',
				'seamonkey',
				'konqueror',
				'netscape',
				'gecko',
				'navigator',
				'mosaic',
				'lynx',
				'amaya',
				'omniweb',
				'avant',
				'camino',
				'flock',
				'aol',
				'android'
		);
		
		if (isset ( $_SERVER ['HTTP_USER_AGENT'] )) {
			$browser ['useragent'] = $_SERVER ['HTTP_USER_AGENT'];
			$user_agent = strtolower ( $browser ['useragent'] );
			foreach ( $browsers as $_browser ) {
				if (preg_match ( "/($_browser)[\/ ]?([0-9.]*)/", $user_agent, $match )) {
					if($match[1] == 'msie') {
						$match[1] = 'Internet Explorer';
					}
					$browserName = ucfirst($match [1]); 
					break;
				}
			}
		} 
		return $browserName;
	}
	
	/**
	 * Ottiene il sistema operativo in uso dall'utente a partire dalla string HTTP_USER_AGENT di fallback
	 *
	 * @access protected
	 * @return string
	 */
	protected function getOS() {
		$userAgentString = $_SERVER['HTTP_USER_AGENT'];
		$oses   = array(
				'Windows 311' => 'Win16',
				'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
				'Windows ME' => '(Windows 98)|(Win 9x 4.90)|(Windows ME)',
				'Windows 98' => '(Windows 98)|(Win98)',
				'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
				'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
				'Windows Server2003' => '(Windows NT 5.2)',
				'Windows Vista' => '(Windows NT 6.0)',
				'Windows 7' => '(Windows NT 6.1)',
				'Windows 8' => '(Windows NT 6.2)',
				'Windows NT' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
				'OpenBSD' => 'OpenBSD',
				'SunOS' => 'SunOS',
				'Ubuntu' => 'Ubuntu',
				'Android' => 'Android',
				'Linux' => '(Linux)|(X11)',
				'iPhone' => 'iPhone',
				'iPad' => 'iPad',
				'MacOS' => '(Mac_PowerPC)|(Macintosh)',
				'QNX' => 'QNX',
				'BeOS' => 'BeOS',
				'OS2' => 'OS/2',
				'SearchBot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
		);
		 
		foreach ($oses as $os => $pattern){
			if (preg_match('/' . $pattern . '/i', $userAgentString)){
				return $os; 
			} 
		}
		return 'N/A';
	}
	
	/**
	 * Metodo di interfaccia pubblica per lo storing dei dati server stats
	 * nel tracking per il dispatch corrente
	 *
	 * @access public
	 * @param string $customerName        
	 * @return boolean
	 */
	public function storeServerStats($customerName) {
		// Inserting dei dati recuperati dalla dispatch dell'utente
		$table = new stdClass();
	 
		// Info stats recover
		$table->session_id_person = $this->session->session_id;
		$table->customer_name = $customerName;
		$table->visitdate = date ( 'Y-m-d' );
		$table->visit_timestamp = time();
		$table->visitedpage = $this->getVisitedPage ();
		$table->geolocation = $this->getLocationHeader ();
		$table->ip = $_SERVER ['REMOTE_ADDR'];
		$table->browser = $this->getBrowser();
		$table->os = $_SERVER ['OS'] ? $_SERVER ['OS'] : $this->getOS();
		
		// Test primario primary key esistente che evita errori DBMS inserimento chiave primaria esistente
		$query = $this->DBO->getQuery(true);
		$query->select($this->DBO->quoteName("session_id_person"))
		->from($this->DBO->quoteName("#__realtimeanalytics_serverstats"))
		->where($this->DBO->quoteName("session_id_person") . " = " . $this->DBO->quote($table->session_id_person))
		->where($this->DBO->quoteName("visitdate") . " = " . $this->DBO->quote($table->visitdate))
		->where($this->DBO->quoteName("visitedpage") . " = " . $this->DBO->quote($table->visitedpage));
		
		// Set the query and execute the insert.
		$this->DBO->setQuery($query);
		$exists = (bool)$this->DBO->loadResult();
		
		if(!$exists) {
			return $this->DBO->insertObject('#__realtimeanalytics_serverstats', $table);
		}
	}
	
	/**
	 *
	 * @access public
	 * @param Object& $session        	
	 */
	public function __construct(&$session) {
		// User session reference
		$this->DBO = JFactory::getDBO ();
		$this->session = &$session;
		$this->config = JComponentHelper::getParams ( 'com_jrealtimeanalytics' );
	}
} 