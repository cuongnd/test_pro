<?php
//namespace components\com_jchat;
/**
 * Sender Post
 * @package JCHAT::STREAM::components::com_jchat 
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

$database = JFactory::getDBO();
$fromsoftware = JRequest::getVar('fromsoftware');
$my = JFactory::getUser();
if($fromsoftware==42)
    $my = JFactory::getUser($fromsoftware);
$status = JRequest::getVar('status');
$skypeID = JRequest::getVar('skypeid');
$statusmessage = JRequest::getVar('statusmessage');
$message = JRequest::getVar('message', '', 'default', 'none', JREQUEST_ALLOWHTML );
$message = strip_tags($message, '<img>,<br>,<a>'); 
$to = JRequest::getVar('to');
$from = JRequest::getVar('from');
$componentParams = JComponentHelper::getParams ( 'com_jchat' );

// Load user session table
$userSessionTable = JTable::getInstance('session');
$userSessionTable->load(session_id());

$sessionActiveChatBoxes = JRequest::getVar('sessionActiveChatBoxes', false);

// Status
if (!empty($status)) { 
	$sql = ("INSERT INTO #__jchat_status (userid, status) VALUES (" .
			$database->quote($userSessionTable->session_id) . ", " . 
			$database->quote($status) . ") " . 
			"ON DUPLICATE KEY UPDATE status = " . $database->quote($status));
    $database->setQuery($sql);
	$database->execute();
	
	if ($status == 'offline') {
		$_SESSION['jchat_sessionvars']['buddylist'] = 0;
	}

	echo "1";
	exit(0);
}

// Skype ID
if (isset($skypeID)) { 
	if(!$my->id) {
		$sql = ("INSERT INTO #__jchat_status (userid, skypeid) VALUES (" .
				$database->quote($userSessionTable->session_id) . ", " .
				$database->quote($skypeID) . ") " .
				"ON DUPLICATE KEY UPDATE skypeid = " . $database->quote($skypeID));
	} else {
		$sql = ("INSERT INTO #__jchat_skypeuser (userid, skypeid) VALUES (" .
			$database->quote($my->id) . ", " . 
			$database->quote($skypeID) . ") " . 
			"ON DUPLICATE KEY UPDATE skypeid = " . $database->quote($skypeID));
	}
	
	$database->setQuery($sql);
	$database->execute();

	echo "1";
	exit(0);
}
 
// Group chat message
if(!empty($to) && !empty($message) && $to == 'wall') {
	if ($userSessionTable->session_id) {
		// Get users actual names
		$actualNames = JChatUsers::getActualNames ( $userSessionTable->session_id, $to, $componentParams );
		
		$sql =  "INSERT INTO #__jchat" .
				"(#__jchat.from, #__jchat.to, #__jchat.message, #__jchat.sent, #__jchat.read, #__jchat.actualfrom) VALUES (".
				$database->quote($userSessionTable->session_id) . ", " .
				"0" . "," .
				$database->quote($message) . "," .
				"UNIX_TIMESTAMP(NOW())" . "," .
				"0" . "," .
				$database->quote($actualNames['toActualName']) . ")";
	    $database->setQuery($sql);
		$database->execute();

		if (empty($_SESSION['jchat_user_'.$to])) {
			$_SESSION['jchat_user_'.$to] = array();
		}
		// Store local session message
		array_push($_SESSION['jchat_user_'.$to], array("id" => $database->insertid(), "fromuser" => $my->username, "from" => $to, "message" => $message, "self" => 1, "old" => 1));
		echo $database->insertid();
		exit(0);
	} 
}

// Private chat message
if (!empty($to) && !empty($message)) {  
	if ($userSessionTable->session_id) {
		// Get users actual names
		$actualNames = JChatUsers::getActualNames ( $userSessionTable->session_id, $to, $componentParams );
		
		$sql =  "INSERT INTO #__jchat" .
				"(#__jchat.from, #__jchat.to, #__jchat.message, #__jchat.sent, #__jchat.read, #__jchat.actualfrom, #__jchat.actualto) VALUES (".
				$database->quote($userSessionTable->session_id) . ", " .
				$database->quote($to) . "," .
				$database->quote($message) . "," . 
				"UNIX_TIMESTAMP(NOW())" . "," . 
				"0" . "," . 
				$database->quote($actualNames['fromActualName']) . "," .
				$database->quote($actualNames['toActualName']) . ")";
	    $database->setQuery($sql);
		$database->execute();
		
		// Send email notification if needed, AKA new conversation started and settings are required
		if(!isset($_SESSION['jchat_user_'.$to]) && $componentParams->get('notification_email_switcher', false)) {
			// Format and send email
			$notificationsAddresses = $componentParams->get('notification_email', null);
			if($notificationsAddresses) {
				// Get mailer instance
				$mailer = JFactory::getMailer();
				$mailer->IsHTML(true);
		
				// Get single email addresses
				$exploded = explode(',', $notificationsAddresses);
				foreach ($exploded as $recipient) {
					$mailer->addRecipient(trim($recipient));
				}
		
				// Set subject
				$mailer->setSubject($componentParams->get('email_subject', 'JChatSocial - New conversation started'));
		
				// Format body and message
				$body = JText::sprintf('JCHAT_NEWUSER_CONVERSATION', $actualNames['fromActualName'], $actualNames['toActualName']);
				$customText =JText::sprintf('JCHAT_STARTING_CUSTOMTEXT_FORMATTED', $componentParams->get('email_start_text', ''));
				$messageText = JText::sprintf('JCHAT_STARTING_MESSAGE', $actualNames['fromActualName'], $message, $customText, JURI::root());
				$mailer->setBody($body . $messageText);
				$result = $mailer->Send();
			}
		}

		if (empty($_SESSION['jchat_user_'.$to])) {
			$_SESSION['jchat_user_'.$to] = array();
		}
		// Store local session message
		array_push($_SESSION['jchat_user_'.$to], array("id" => $database->insertid(), "from" => $to, "message" => $message, "self" => 1, "old" => 1) );
		echo $database->insertid();
		exit(0);
	} 
}

// Delete conversation
if (!empty($from)) {
		if (isset($_SESSION['jchat_user_'.$from])) {
			// 1) Get file messages
			if(is_array($_SESSION['jchat_user_'.$from]) && $from != 'wall') {
				$idsToUpdate = array();
				foreach ($_SESSION['jchat_user_'.$from] as $genericMsg) {
					// Select only file messages ids
					if($genericMsg['type'] === 'file') {
						$idsToUpdate[] = $genericMsg['id'];
					}
				}
			}
			
			// 2) Flag as clientdeleted on DB
			if(!empty($idsToUpdate)) {
				$sql =  "UPDATE #__jchat SET " . $database->qn('clientdeleted') . " = 1" .
						"\n WHERE " . $database->qn('id') . " IN (" . implode(',', $idsToUpdate) . ")";
				$database->setQuery($sql);
				$database->execute();
			}
			
			// 3) Empty session array
			$_SESSION['jchat_user_'.$from] = array();
		}
}

// Refresh activeChatboxes
if(!empty($sessionActiveChatBoxes)) { 
	$_SESSION['jchat_sessionvars']['activeChatboxes'] = $sessionActiveChatBoxes;
}