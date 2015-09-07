<?php
//namespace components\com_jchat\models; 
/** 
 * @package JCHAT::CONTATTI::components::com_jchat
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Permette la gestione degli avatar personalizzati permettendone l'upload e la cancellazione,
 * nonchè la visualizzazione renderizzata in un iframe popup da javascript side 
 * @package JCHAT::CONTATTI::components::com_jchat
 * @subpackage models
 * @since 1.0
 */ 
class JChatGroupchat {
	/**
	 * ID utente dell'owner tabella molti a molti
	 * @access private
	 * @var int
	 */
	private $ownerID;
	
	/**
	 * ID utente del contact tabella molti a molti
	 * @access private
	 * @var int
	 */
	private $contactID;
	
	/**
	 * DB object connector
	 * @access private
	 * @var Object&
	 */
	private $DBO; 
	
	/**
	 * SESSION reference
	 * @access private 
	 * @var array&
	 */
	private $session;
	
	/**
	 * Plugin configuration object
	 * @access private
	 * @var Object
	 */
	private $config;
	
	/**
	 * Aggiorna
	 * @param int $state
	 * @access public
	 * @return void
	 */
	public function refreshSessionVars($state) {
		// Settaggio in sessione
		if(is_numeric($state)) {
			$this->session['jchat_sessionvars']['contacts'] = $state; 
		}
		// Output buddylist aggiornata
		$response = array();
		$messages = array();
		$_POST['initialize'] = 1;
		JChatStream::getBuddyList($this->config, $response, $messages); 
		JChatStream::sendResponse($response, $messages);
	}
	 
	/**
	 * Memorizza l'ID utente come un contact nella
	 * tabella molti a molti per l'owner corrente
	 * @param int $contactID
	 * @access public
	 * @return boolean
	 */
	public function storeContact($contactID) {
		$query = "INSERT INTO #__jchat_contacts (ownerid, contactid)" .
				 "\n VALUES(" . $this->DBO->quote($this->ownerID) . ',' . $this->DBO->quote($contactID) . ')';
		$this->DBO->setQuery($query);
		echo ($this->DBO->execute()); 
	}
 
	/**
	 * Elimina l'ID utente come un contact nella
	 * tabella molti a molti per l'owner corrente
	 * @param int $contactID
	 * @access public
	 * @return boolean
	 */
	public function deleteContact($contactID) {
		$query = "DELETE FROM #__jchat_contacts" . 
				 "\n WHERE (ownerid = " . $this->DBO->quote($this->ownerID) .
				 "\n AND contactid = " . $this->DBO->quote($contactID) . ")" .
				 "\n OR  (contactid = " . $this->DBO->quote($this->ownerID) .
				 "\n AND ownerid = " . $this->DBO->quote($contactID) . ")";
		$this->DBO->setQuery($query);
		echo ($this->DBO->execute()); 
	}
	
	/**
	 * Class constructor
	 * @access public
	 * @param Object& $wpdb
	 * @param Object& $userObject
	 * @return Object &
	 */
	public function __construct() {
		// DB DAL
		$this->DBO = JFactory::getDBO();
		// Session reference
		$this->session = $_SESSION;
		// $ownerID init
		// Lazy loading user session
		$userSessionTable = JTable::getInstance('session');
		$userSessionTable->load(session_id());
		$this->ownerID = $userSessionTable->session_id;
		
		// Component config
		$this->config = JComponentHelper::getParams('com_jchat');
	}
}