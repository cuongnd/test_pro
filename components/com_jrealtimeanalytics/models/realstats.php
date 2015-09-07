<?php
//namespace components\com_jrealtimeanalytics\models;
/**  
 * Gestore dei messaggi e dei dati 
 * @package JREALTIMEANALYTICS::REALSTATS::components::com_jrealtimeanalytics 
 * @subpackage models
 * @author Joomla! Extensions Store  
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html    
 */ 
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 

/**
 * Responsabilità classe gestore dei messaggi e dei dati  
 * @package JREALTIMEANALYTICS::REALSTATS::components::com_jrealtimeanalytics
 * @subpackage models 
 * @since 1.0
 */
interface IRealstats { 
	/**
	 * Si occupa di aggiornare il record contestuale alla sessione utente connesso
	 * nella tabella 1 a 1 #__realtimeanalytics_realstats -> #__session
	 * @access public
	 * @param string $userName
	 * @return boolean Unit testing Use
	 */
	public function refreshRealtime($currentName);
}

/**
 * Realstats class frontend implementation 
 * @package JREALTIMEANALYTICS::REALSTATS::components::com_jrealtimeanalytics
 * @subpackage models 
 * @since 1.0
 */
class jfbcRealstats implements IRealstats { 
	/**
	 * DB Connector
	 * @var Object&
	 * @access private
	 */
	private $DBO;
	
	/**
	 * Session ID utente in refresh
	 * @var Object&
	 * @access private
	 */
	private $session;

	/**
	 * Ottiene dalla POST AJAX il current URL dove si trova l'utente
	 * @access protected
	 * @return string 
	 */
	protected function getNowPage() {
		return urldecode(JRequest::getVar('nowpage', null, 'POST'));
	}
 
	/**
	 * Valuta se il tracking realstats è abilitato
	 * @access protected
	 * @return string
	 */
	protected function getTrackingIsOff() {
		$nowPagePosted = $this->getNowPage();
		if(preg_match('/notrack/i', $nowPagePosted)) {
			return true;
		}
	}
	
	/**
	 * Si occupa di aggiornare il record contestuale alla sessione utente connesso
	 * nella tabella 1 a 1 #__realtimeanalytics_realstats -> #__session
	 * @access public
	 * @param string $userName
	 * @return boolean Unit testing Use
	 */
	public function refreshRealtime($currentName) {
		// Se non è abilitato il tracking non si effettua nessuna operazione
		if($this->getTrackingIsOff()) {
			return false;
		}
		
		// Ottenimento pagina utente corrente
		$nowPage = $this->getNowPage();
		$field = null;
		$value = null;
		$update = null;
		if($currentName) {
			$field = ", \n " . $this->DBO->quoteName('current_name');
			$value =  "," . $this->DBO->Quote($currentName);
			$update = ", \n " . $this->DBO->quoteName('current_name') . "=" .  $this->DBO->Quote($currentName);
		}
		
		// Build query insert/update
		$insertUpdateQuery = "INSERT INTO #__realtimeanalytics_realstats" .
							 "\n ( " . $this->DBO->quoteName('session_id_person') . "," . 
							 "\n " . $this->DBO->quoteName('nowpage') . "," .
							 "\n " . $this->DBO->quoteName('lastupdate_time') . $field . " )" . 
							 "\n VALUES ( " . $this->DBO->Quote($this->session->session_id) . "," .
							 $this->DBO->Quote($nowPage) . "," .
							 $this->DBO->Quote(time()) .  $value . " )" .
							 "\n ON DUPLICATE KEY UPDATE" .
							 "\n " . $this->DBO->quoteName('nowpage') . " = " . $this->DBO->Quote($nowPage) . "," .
							 "\n " . $this->DBO->quoteName('lastupdate_time') . "=" .  $this->DBO->Quote(time()) . $update;
		$this->DBO->setQuery($insertUpdateQuery);
		if(!$this->DBO->execute()) {
			return false;
		}
		return true;
	}

	/**
	 * Class constructor
	 * @access public
	 * @return Object& 
	 */
	public function __construct(&$session) {
		// User session reference
		$this->DBO = JFactory::getDBO();
		$this->session = &$session; 
	}
}