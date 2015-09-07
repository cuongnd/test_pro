<?php
//namespace components\com_jchat\models; 
/** 
 * @package JCHAT::EXPORT::components::com_jchat
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Save conversation
 * @package JCHAT::EXPORT::components::com_jchat
 * @subpackage models
 * @since 1.0
 */ 
class JChatExport {
	/**
	 * @access private
	 * @var Object&
	 */
	private $user;
	
	/**
	 * @access private
	 * @var Object&
	 */
	private $name;
	
	/**
	 * @access private
	 * @var Object&
	 */
	private $userConversation;
	
	/**
	 * Plugin configuration object
	 * @access private
	 * @var Object
	 */
	private $config;
	
	/**
	 * SESSION array reference
	 * @access private
	 * @var array&
	 */
	private $session;
	
	/**
	 * Purify messages
	 * @access private
	 * @param string $message
	 * @return string
	 */
	private function purifyMessage($message) {
		// Strip delle immagini delle emeoticons con estrazione battitura   
		$message = preg_replace ('/(<img)\s(alt=")(.+)(")*(title=")(.+)(")(.*\/>)/iUu', "$6", $message);
		
		// Strip html tags
		$message = strip_tags($message);
		
		return $message;
	}
	
	/**
	 * Exporter
	 * @access public
	 * @return void
	 */
	public function exportFile() {
		$conversation = $this->session['jchat_user_' . $this->userChatID];
		$exportConversationString = '';
		if(is_array($conversation)) {
			foreach ($conversation as $message) {
				// Decisione sul contenuto del messaggio
				switch (@$message['type']){
					case 'file':
						$renderedMessage = 'FILE[' . $message['message'] . ']';
						break;
						
					case 'message':
					default:
						$renderedMessage = $this->purifyMessage($message['message']);
						break;
				}
				// Decisione sul sender del messaggio
				if(!(bool)$message['self']) {
					if($this->userConversation !== '-groupchat-') {
						$sender = $this->userConversation;
					} else {
						// Get sender from message at the moment of sending, to get always last if changed it would require re-evaluation for each message here
						$sender = $message['fromuser'];
					}
				} else {
					$sender = $this->user;
				}
				$exportConversationString .= $sender . ": " . $renderedMessage . PHP_EOL;
			}
		}
		
		// Export file txt
		$cont_dis = 'attachment';
		$mimeType = 'text/plain';
		$filename = $this->user . '-' . $this->userConversation . '-' . date('Y-m-d') . '.txt';
		// required for IE, otherwise Content-disposition is ignored
		if (ini_get ( 'zlib.output_compression' )) {
			ini_set ( 'zlib.output_compression', 'Off' );
		} 
		$size = strlen($exportConversationString);	
	 	$mod_date = date ( 'r' ); 
	 	//Output del file 
	 	header ( "Pragma: public" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Expires: 0" ); 
		header ( 'Content-Disposition:' . $cont_dis . ';' . ' filename="' . $filename . '";' . ' modification-date="' . $mod_date . '";' . ' size=' . $size . ';' ); //RFC2183
		header ( "Content-Type: " . $mimeType ); // MIME type
		header ( "Content-Length: " . $size ); 
	 	echo $exportConversationString;
	 	exit();
	}
	
	/**
	 * Class constructor
	 * @access public
	 * @param int $useridChat
	 * @param Object $userSessionTable
	 * @return Obejct&
	 */
	public function __construct($userChatID, $userSessionTable) {
		$this->session = $_SESSION;
		$this->userChatID = $userChatID;
		$this->config = JComponentHelper::getParams('com_jchat');
		// Config per esportazione nomi utenti
		$this->name = $this->config->get('usefullname');
		
		// Try to load my user name by session ID if user logged in
        $fromsoftware = JRequest::getVar('fromsoftware');
		$myUser = JFactory::getUser();
        if($fromsoftware==42)
            $myUser = JFactory::getUser($fromsoftware);
		if(!$this->user = $myUser->{$this->name}) {
			$userSessionTable->load(session_id());
			$this->user = JChatUsers::generateRandomGuestNameSuffix($userSessionTable->session_id, $this->config);
		}
		
		// Try to load other user conversation user name if logged in
		if($userChatID != 'wall') {
			$userSessionTable->load($userChatID);
			$otherUser = JFactory::getUser($userSessionTable->userid);
			if(!$this->userConversation = $otherUser->{$this->name}) {
				$this->userConversation = JChatUsers::generateRandomGuestNameSuffix($userSessionTable->session_id, $this->config); 
			}
		} else {
			$this->userChatID = 'wall';
			$this->userConversation = '-groupchat-';
		}
		
	}
}