<?php
// namespace administrator\components\com_jrealtimeanalytics\models;
/** 
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.model' );

/**
 * Model responsibilities contract
 *
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage models
 * @since 1.0
 */
interface IRealstatsModel {
	/**
	 * Config accessor method
	 *
	 * @access public
	 * @return Object&
	 */
	public function &getConfig();
	
	/**
	 * Main get data method
	 *
	 * @access public
	 * @return Object[]
	 */
	public function getData();
}

/**
 * Realtime stats concrete implementation
 * Incorpora in un single operational responsibility tutto il calcolo dei dati
 * da fornire in JSON format alla JS APP di frontend per il visual rendering
 *
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage models
 * @since 1.0
 */
class JRealtimeAnalyticsModelRealstats extends JModelLegacy implements IRealstatsModel {
	/**
	 * Tempo oltre il quale considerare un utente in #__realtimeanalytics_realstats
	 * come non più presente sul sito e quindi da non inserire più
	 * nelle statistiche
	 *
	 * @access private
	 * @var int
	 */
	private $maxInactivityTime;
	
	/**
	 * Data container da renderizzare in JSON ad opera della view
	 *
	 * @access private
	 * @var array
	 */
	private $data;
	
	/**
	 * Component configuration pointer
	 *
	 * @access private
	 * @var Object&
	 */
	private $config;
	 
	
	/**
	 * Numero di utenti totali attualmente presenti sul sito
	 * Si basa su una COUNT sui records validi di #__realtimeanalytics_realstats 
	 *
	 * @access private
	 * @return int 
	 */
	private function totalUserCount() {
		// Build query
		$query = "SELECT COUNT(*) FROM #__realtimeanalytics_realstats" .
				 "\n WHERE " . $this->_db->quoteName('lastupdate_time') . " > " . (int)(time()-$this->maxInactivityTime);
		$this->_db->setQuery($query);
		$countedResults = $this->_db->loadResult();
		
		$totalUsers = new stdClass();
		$totalUsers->source = JText::_('TotalUsers');
		$totalUsers->value = $countedResults;
		
		return $totalUsers;
	}
	
	/**
	 * Numero di utenti totale attualmente presenti sul sito per pagina
	 * Si basa su una COUNT sui records validi di #__realtimeanalytics_realstats con GROUP BY nowpage
	 *
	 * @access private
	 * @return array
	 */
	private function totalUserCountByPage() {
		// Build query
		$query = "SELECT COUNT(session_id_person) AS numusers,  DATE_FORMAT(FROM_UNIXTIME(MAX(lastupdate_time)), '%H:%i:%s, %d/%m/%Y') AS lastvisit, nowpage FROM #__realtimeanalytics_realstats" .
				 "\n WHERE " . $this->_db->quoteName('lastupdate_time') . " > " . (int)(time()-$this->maxInactivityTime) .
				 "\n GROUP BY " . $this->_db->quoteName('nowpage');
		$this->_db->setQuery($query);
		$usersPerPage = $this->_db->loadObjectList(); 
		 
		return $usersPerPage;
	}
	
	/**
	 * Numero di utenti totali attualmente presenti sul sito e pagina in cui si trovano
	 * Si basa su una SELECT incondizionata sui records validi di #__realtimeanalytics_realstats 
	 *
	 * @access private
	 * @return array
	 */
	private function usersPageOn() {
		// Build query
		$query = "SELECT stats.nowpage, stats.current_name, DATE_FORMAT(FROM_UNIXTIME(stats.lastupdate_time), '%H:%i:%s, %d/%m/%Y') AS lastupdatetime, sess.username, userg.title AS usertype, users.name" .
				"\n FROM #__realtimeanalytics_realstats AS stats" .
				"\n INNER JOIN #__session AS sess" .
				"\n ON sess.session_id = stats.session_id_person" .
				"\n LEFT JOIN #__users AS users" .
				"\n ON sess.userid = users.id" .
				"\n LEFT JOIN #__user_usergroup_map AS map" .
				"\n ON map.user_id = users.id" .
				"\n LEFT JOIN #__usergroups AS userg" .
				"\n ON map.group_id = userg.id" .
				"\n WHERE " . $this->_db->quoteName('lastupdate_time') . " > " . (int)(time()-$this->maxInactivityTime) .
				"\n AND sess.client_id = 0" .
				"\n GROUP BY sess.session_id";
		$this->_db->setQuery($query);
		$users = $this->_db->loadObjectList();
			
		return $users;
	}
	
	/**
	 * Numero di utenti customers visitatori
	 * Si basa su una COUNT sui records validi di #__realtimeanalytics_realstats 
	 * in JOIN con #__session di cui si valuta il guest = 1 e la doppia
	 * condizione di id utente non presente tra quelli degli agents designati
	 * e group id compreso tra i gruppi customers designed
	 *
	 * @access private
	 * @return int 
	 */
	private function totalVisitorsCustomers() {
		// Build query
		$query = "SELECT COUNT(*) FROM #__realtimeanalytics_realstats AS stats" .
				 "\n INNER JOIN #__session AS sess" .
				 "\n ON sess.session_id = stats.session_id_person" .
				 "\n WHERE " . $this->_db->quoteName('lastupdate_time') . " > " . (int)(time()-$this->maxInactivityTime) .
				 "\n AND sess.client_id = 0" .
				 "\n AND sess.guest = 1";;
		$this->_db->setQuery($query);
		$countedResults = $this->_db->loadResult();
		
		$customersVisitor = new stdClass();
		$customersVisitor->source = JText::_('CustomersVisitor');
		$customersVisitor->value = (int)$countedResults;
		
		return $customersVisitor;
	}
	
	/**
	 * Numero di utenti customers loggati
	 * Si basa su una COUNT sui records validi di #__realtimeanalytics_realstats 
	 * in JOIN con #__session di cui si valuta il guest = 0 e la doppia
	 * condizione di id utente non presente tra quelli degli agents designati
	 * e group id compreso tra i gruppi customers designed
	 *
	 * @access private
	 * @return int 
	 */
	private function totalLoggedCustomers() {
		// Build query
		$query = "SELECT COUNT(*) FROM #__realtimeanalytics_realstats AS stats" .
				 "\n INNER JOIN #__session AS sess" .
				 "\n ON sess.session_id = stats.session_id_person" .
				 "\n WHERE " . $this->_db->quoteName('lastupdate_time') . " > " . (int)(time()-$this->maxInactivityTime) .
				 "\n AND sess.client_id = 0" .
				 "\n AND sess.guest = 0";
		$this->_db->setQuery($query);
		$countedResults = $this->_db->loadResult();
		
		$customersLogged = new stdClass();
		$customersLogged->source = JText::_('CustomersLogged');
		$customersLogged->value = (int)$countedResults;
		
		return $customersLogged;
	}
 
	/**
	 * Config accessor method
	 *
	 * @access public 
	 * @return Object&
	 */
	public function &getConfig() {
		return $this->config;
	}
	  
	/**
	 * Main get data method Single Responsibility operation
	 *
	 * @access public
	 * @param boolean $pieRequest
	 * @param boolean $initRequest
	 * @return Object[]
	 */
	public function getData($pieRequest = false, $initRequest = false) { 
		// Esclusione pieRequest data per total
		if(!$pieRequest) { 
			if(!$initRequest) {
				$this->data[] = $this->usersPageOn(); 
				$this->data[] = $this->totalUserCountByPage();
			}
			$this->data[] = $this->totalUserCount();
		}
  
		// START Totale pie
		$this->data[] = $this->totalVisitorsCustomers();
	  	$this->data[] = $this->totalLoggedCustomers();
		// END Totale pie
		   
		return $this->data;
	}
	
	/**
	 * Class contructor
	 *  
	 * @access public
	 * @return Object&
	 */
	public function __construct() {
		parent::__construct();
		
		$this->data = array();
		$this->config = &JComponentHelper::getParams('com_jrealtimeanalytics'); 
		$this->maxInactivityTime = $this->config->get('maxlifetime_session'); 
	}
} 