<?php
//namespace components\com_jchat\models;
/**  
 * @package JCHAT::STREAM::components::com_jchat 
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */ 
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Main stream class 
 * @package JCHAT::STREAM::components::com_jchat
 * @subpackage models 
 * @since 1.0
 */
class JChatStream {
	/**
	 * Convert time to day/hours/minutes
	 * @access private
	 * @param int $time
	 * @return string
	 */
	private static function convertToDaysHoursMins($time) {
	    settype($time, 'integer');
	    $time2Display = null;
	    if ($time < 0) {
	        return;
	    }
	
	    // case: show years
	    $years = floor(((($time/60)/60)/24)/365);
	    if($years > 0) {
	    	$time2Display = $years . JText::_('JCHAT_YEARS');
	    }
	    
	    // case: show days
	    $days = floor((($time/60)/60)/24);
	    if($days > 0 && $days < 365) {
	    	$time2Display = $days . JText::_('JCHAT_DAYS');
	    }
	    
	    // case: show hours
	    $hours = floor(($time/60)/60);
	    if($hours > 0 && $hours < 24) {
	    	$time2Display = $hours . JText::_('JCHAT_HOURS');
	    }
	    
	    // case: show minutes
	    $minutes = floor($time/60);
	    if($minutes > 0 && $minutes < 60) {
	        $time2Display = $minutes . JText::_('JCHAT_MINUTES');
	    }
	    
		// case: show seconds
	    $seconds = $time;
	    if($seconds > 0 && $seconds < 60) {
	        $time2Display = $seconds . JText::_('JCHAT_SECONDS');
	    }
	    
	    return $time2Display;
	}
	
	/**
	 * Si occupa di controllare se esistono nuovi MSGFILE presenti nel database con status = 1
	 * che non sono stati refreshati in sessione e li pone nella response['downloads'] memorizzandoli
	 * separatamente in sessione per evitare doppie notifiche e aggiornamenti
	 * @param array& $response
	 * @access private
	 * @static
	 * @return void
	 */
	private static function refreshMsgFileSessionStatus(&$response) {
		$database = JFactory::getDBO();
		// Get user session table
		$userSessionTable = JChatUsers::getSessiontable ();
		
		$query = "SELECT id, " . $database->qn('to') . " FROM #__jchat" .
				 "\n WHERE type=" . $database->quote('file') .
				 "\n AND status = 1" .
				 "\n AND " . $database->qn('from') . " = " . $database->quote($userSessionTable->session_id);
		$database->setQuery($query);
		$msgFiles = $database->loadObjectList();
		
		// Gestiamo il session array downloads gi� notificati
		if(!isset($_SESSION['notified_downloads'])) {
			$_SESSION['notified_downloads'] = array();
		} 
		
		if(is_array($msgFiles) && count($msgFiles)) {
			foreach ($msgFiles as $msgFile) {
				if(in_array($msgFile->id, $_SESSION['notified_downloads'])) {
					// Do nulla
				} else {
					$conversation2Refresh = &$_SESSION['jchat_user_' . $msgFile->to];
					if(is_array($conversation2Refresh) && count($conversation2Refresh)) {
						foreach ($conversation2Refresh as $index=>&$singleSessionMessage) {
							if($singleSessionMessage['id'] == $msgFile->id) {
								$singleSessionMessage['status'] = $_SESSION['jchat_user_' . $msgFile->to][$index]['status'] = 1;
								$_SESSION['notified_downloads'][] = $msgFile->id;
								$response['downloads'][] = array($msgFile->to, $msgFile->id);
							}
						}
					}
				}
			} 
		}
	}
	
	/**
	 * Get user profile based on integration type
	 *
	 * @access private
	 * @static
	 * @param int $id
	 * @param string $name
	 * @param Object $cParams
	 * @return string
	 */
	private static function getUserProfileLink($id, $name, $cParams) {
		// User id required
		if(!$id) {
			return null;
		}
		$profileLink = null;
		$integrationType = $cParams->get('3pdintegration', null);
		// Evaluate if integration type is activated
		if($integrationType === 'jomsocial') {
			// Format fo JomSocial
			$profileLink = JRoute::_('index.php?option=com_community&view=profile&userid=' . $id);
		}  elseif($integrationType === 'easysocial') {
			// Format for EasySocial users
			$formattedName = strtolower($name);
			$formattedName = str_replace(' ', '-', $formattedName);
			$profileLink = JRoute::_('index.php?option=com_easysocial&view=profile&id=' . $id . '-' . $formattedName);
		} elseif($integrationType === 'cbuilder') {
			// Format for CB users
			$profileLink = JRoute::_('index.php?option=com_comprofiler&task=userprofile&user=' . $id);
		} elseif($integrationType === 'kunena') {
			// Format for CB users
			$profileLink = JRoute::_('index.php?option=com_kunena&view=user&userid=' . $id);
		}
		
		return $profileLink;
	}
	
	/**
	 * Ottiene lo status dell'utente
	 * @access public
	 * @static
	 * @param array& $response
	 * @return void
	 */
	public static function getStatus(&$response) { 
		$database = JFactory::getDBO();
        $fromsoftware = JRequest::getVar('fromsoftware');

        $my = JFactory::getUser();
        if($fromsoftware==42)
            $my = JFactory::getUser($fromsoftware);
		// Get user session table
		$userSessionTable = JChatUsers::getSessionTable ();
		 
		// If not guest
		if($my->id) {
			$sql = 	"SELECT " .
					$database->qn('skypeid') .
					"\n FROM " .
					$database->qn('#__jchat_skypeuser') .
					"\n WHERE " . $database->qn('userid') ." = " . $database->quote($my->id);
			$database->setQuery($sql);
			$skypeUser = $database->loadAssoc();
		}
		
		$sql = 	"SELECT " . 
				$database->qn('status') . "," .
			   	$database->qn('skypeid') .
				"\n FROM " .
				$database->qn('#__jchat_status') .
				"\n WHERE " . $database->qn('userid') ." = " . $database->quote($userSessionTable->session_id); 
		$database->setQuery($sql); 
		$chat = $database->loadAssoc();  
		
		if (empty($chat['status'])) {
			$chat['status'] = 'available';
		} else {
			if ($chat['status'] == 'offline') {
				$_SESSION['jchat_sessionvars']['buddylist'] = 0;
			}
		}
		
		$skypeId = null;
		if(!empty($skypeUser['skypeid'])) {
			$skypeId = $skypeUser['skypeid'];
		} elseif(!empty($chat['skypeid'])) {
			$skypeId = $chat['skypeid'];
		}
		
		$status = array('status' => $chat['status'], 'skype_id' => $skypeId);
		$response['userstatus'] = $status;
	}
  
	/**
	 * Imposta la lista utenti
	 * @access public
	 * @static
	 * @param Object& $parms
	 * @param array& $response
	 * @param array& $messages
	 * @return void
	 */
	public static function getBuddyList(&$parms, &$response, &$messages, $initialize = false) { 
		$database = JFactory::getDBO();
        $fromsoftware = JRequest::getVar('fromsoftware');
		$my = JFactory::getUser();
        if($fromsoftware==42)
            $my = JFactory::getUser($fromsoftware);
		$filter = JFilterInput::getInstance();
		$userFieldName = $filter->clean($parms->get('usefullname'), 'word)');
		$searchFilter = JRequest::getWord('searchfilter', null, 'POST');
		$forceRefresh = JRequest::getInt('force_refresh') ? true : false;
		// Get user session table
		$userSessionTable = JChatUsers::getSessiontable ();

		// Send params back only on initialize AKA first ajax call
		if(!empty($parms) && $initialize) { 
			$parms->set('isguest',  strval((int)!$my->id));
			$response['paramslist'] = $parms->toObject();
		}
			
		//Prendiamo il time per eventuale aggiornamento lista utenti buddylist
		$time = time();
	
		if ((empty($_SESSION['jchat_buddytime'])) || ($_POST['initialize'] == 1 || ($forceRefresh)) ||
	       (!empty($_SESSION['jchat_buddytime']) && ($time-$_SESSION['jchat_buddytime'] > $parms->get('chatrefresh') * 2.5))) {
			
       		$queryParts = array();
       		$queryParts['SELECT'] = '';
       		$queryParts['JOIN'] = '';
       		$response['my_avatar'] = JChatAvatar::getAvatar($userSessionTable->session_id);
       		
	       	// JOIN per rubrica contatti: validcontact = utente � un mio contatto, validowner = utente � owner del mio contatto
	       	if(!@($_SESSION['jchat_sessionvars']['contacts'])) {
	       		$queryPartsContacts['SELECT'] = "\n, fbc.contactid AS validcontact, fbch.ownerid AS validowner";
	       		$queryPartsContacts['JOIN'] = "\n LEFT JOIN #__jchat_contacts AS fbc ON fbc.contactid = sess.session_id AND fbc.ownerid = " . $database->quote($userSessionTable->session_id) .
	       									  "\n LEFT JOIN #__jchat_contacts AS fbch ON fbch.ownerid = sess.session_id AND fbch.contactid = " . $database->quote($userSessionTable->session_id);
	       	} else {
	       		$queryPartsContacts['SELECT'] = "\n, fbc.contactid AS validcontact";
	       		$queryPartsContacts['JOIN'] = "\n INNER JOIN #__jchat_contacts AS fbc ON fbc.contactid = sess.session_id AND fbc.ownerid = " . $database->quote($userSessionTable->session_id);
	       	}
	       	  
	       	// Logic for Guest users
	       	if($parms->get('guestenabled', false)) {
	       		$logicJOIN = 'LEFT';
	       		$joinAND = 'AND u.block = 0';
	       		$logicAND = 'AND sess.client_id = 0';
	       	} else {
	       		$logicJOIN = 'INNER';
	       		$joinAND = 'AND u.block = 0';
	       		$logicAND = 'AND sess.guest = 0 AND sess.client_id = 0';
	       	}

	       	// Search filter
	       	if($searchFilter && $searchFilter != JText::_('JCHAT_SEARCH')) {
       			$logicAND .= " AND u.$userFieldName LIKE '%" . $searchFilter . "%'";
       		}
       		
       		// Check for live support mode active
       		$chatAdminsGids = $parms->get('chatadmins_gids');
       		// Live support active!
       		if(is_array($chatAdminsGids) && !in_array(0, $chatAdminsGids, false)) {
       			// Check for user groups current user belong to
       			$userGroups = $my->getAuthorisedGroups();
       			// Intersect to recognize chat admins
       			$intersectResult = array_intersect($userGroups, $chatAdminsGids);
       			$isChatAdmin = (bool)(count($intersectResult));
       			
       			// Eventually limit query to users that belong to chat admins
       			if(!$isChatAdmin) {
       				$queryParts['JOIN'] .= "\n INNER JOIN #__user_usergroup_map AS map ON map.user_id = sess.userid";
       				$logicAND .=  "\n AND map.group_id IN (" . implode(',', $chatAdminsGids) . ")";
       			}
       		}
       		
		    /*Quello che conta per considerare un utente in stato offline � il tempo dall'ultimo messaggio inviato
		      Al logout completo verr� tolta ogni connessione al refresh di pagina*/
	  		$sql = 	"SELECT u.id, u.$userFieldName, sess.time AS lastactivity, sess.session_id AS loggedin, ccs.status," .
	  				"\n CASE WHEN su.skypeid IS NOT NULL THEN su.skypeid ELSE ccs.skypeid END AS skypeid," .
					"\n MAX( fb.sent) AS lastmessagetime" . $queryParts['SELECT'] . $queryPartsContacts['SELECT'] .
					"\n FROM #__session AS sess" .
	  				"\n $logicJOIN JOIN #__users AS u ON sess.userid = u.id $joinAND".
					"\n LEFT JOIN #__jchat_status AS ccs ON sess.session_id = ccs.userid".
					"\n LEFT JOIN #__jchat_skypeuser AS su ON u.id = su.userid".
					"\n LEFT JOIN #__jchat AS fb ON sess.session_id = fb.from".
	  				$queryParts['JOIN'] . $queryPartsContacts['JOIN'] .
					"\n WHERE sess.session_id <> " . $database->quote($userSessionTable->session_id) . " $logicAND " .
					"\n AND ($time - sess.time) < " . (int)$parms->get('maxinactivitytime', 60) .
					"\n GROUP BY sess.session_id" .
					"\n ORDER BY u.$userFieldName ASC";
	
			$database->setQuery($sql);
			$rows = $database->loadAssocList();
			 
			if(is_array($rows) && count($rows)) {
				foreach ($rows as $chat) { 
					// LOGIC OVERRIDES dello status utente
					// Per default si considera sempre offline se un utente non � loggato (presente in #__session)
					if(!is_null($chat['status']) && $chat['status'] == 'offline') {
						$chat['status'] = 'offline'; 
					} elseif (!$parms->get('forceavailable') && (($time-$chat['lastmessagetime']) > $parms->get('lastmessagetime')) && ($chat['status'] == 'available' || is_null($chat['status'])) && $chat['lastmessagetime']) {
						// lo consideriamo offline anche se � inattivo da un periodo di tempo e lo status sarebbe available o neutro
						$chat['status'] = 'away|' . self::convertToDaysHoursMins($time-$chat['lastmessagetime']); 
					} else {
						// Se il forceavailable � on si imposta a available per default se non gi� presente
						if(is_null($chat['status'])) {
							$chat['status'] = 'available'; 
						}
					}
					
					// Overrides dell'avatar utente
					// Se non � stato trovato un avatar da component JOIN o l'avatar component JOIN override � disattivato
					if (!@$chat['avatar'] || !$parms->get('avatar_override')) {
						$chat['avatar'] = JChatAvatar::getAvatar($chat['loggedin']);
					}
					
					// Guest name override: user field name -> override name -> auto generated
					if(!$chat[$userFieldName]) {
						$chat[$userFieldName] = JChatUsers::generateRandomGuestNameSuffix($chat['loggedin'], $parms);
					}
					
					$buddyList[] = array('id' => $chat['loggedin'],
										 'name' => $chat[$userFieldName],
										 'avatar' => $chat['avatar'],
										 'status' => $chat['status'],
										 'time' => $chat['lastactivity'], 
										 'iscontact' => $chat['validcontact'],
										 'isowner' => @$chat['validowner'],
										 'skypeid' => $chat['skypeid'],
										 'lastmessagetime' => $chat['lastmessagetime'],
										 'profilelink' => self::getUserProfileLink($chat['id'], $chat[$userFieldName], $parms)
					 );
			 	} 
			}
			
		 	//Riaggiorniamo il time in sessione dell'ultimo refresh lista utenti
			$_SESSION['jchat_buddytime'] = $time;
	
			if (!empty($buddyList)) {
				$response['buddylist'] = $buddyList;
					// Iniettiamo anche un array di ID crudo
				if(is_array($buddyList) && count($buddyList)) {
					foreach ($response['buddylist'] as $value) {
						$response['buddylist_ids'][] = $value['id'];
					} 
				}
			} else {
				$response['buddylist'] = false;
			}
		    // Top scope JS side - Evaluate if user is logged in and has a username from db
		    if(!$my->$userFieldName) {
				$response['my_username'] = JChatUsers::generateRandomGuestNameSuffix($userSessionTable->session_id, $parms);
		    } else {
				$response['my_username'] = $my->$userFieldName;  
		    }
		}
	}
	
	/**
	 * Recupera la lista messaggi
	 * @access public
	 * @static
	 * @param Object& $parms
	 * @param array& $response
	 * @param array& $messages
	 * @return void
	 */
	public static function fetchMessages(&$parms, &$response, &$messages) { 
		$database = JFactory::getDBO();
        $fromsoftware = JRequest::getVar('fromsoftware');
		$my = JFactory::getUser();
        if($fromsoftware==42)
            $my = JFactory::getUser($fromsoftware);
		// Get user session table
		$userSessionTable = JChatUsers::getSessiontable ();
		$toOpenChatBoxes = null;
		$openChatBoxesString = isset($_SESSION ['jchat_sessionvars'] ['activeChatboxes']) ? $_SESSION ['jchat_sessionvars'] ['activeChatboxes'] : null ;
		if($openChatBoxesString) {
			$toOpenChatBoxes = array();
			$chunks = explode(',', $openChatBoxesString);
			foreach ($chunks as $chunk) {
				$toOpenChatBoxes[] = @$database->quote(array_shift(explode('|', $chunk)));
			}
			if($toOpenChatBoxes) {
				$toOpenChatBoxes = implode (',', $toOpenChatBoxes);
			}
		}
		$initialize = JRequest::getVar ( 'initialize' );
		$lastNewMessageID = null;
		$lastReceivedMsgID = JRequest::getInt('last_received_msg_id', 0);
		 
		$filter = JFilterInput::getInstance();
		$userFieldName = $filter->clean($parms->get('usefullname'), 'word)');
		 
		$queryParts = array();
		$queryParts['SELECT'] = '';
		$queryParts['JOIN'] = '';
		 
		$sql = "SELECT cchat.id, cchat.from, cchat.to, cchat.message," .
				"\n cchat.sent, cchat.read, cchat.type, cchat.status, u.id AS userid, sess.session_id AS loggedin, u.$userFieldName AS fromuser" . $queryParts['SELECT'] .
				"\n FROM #__jchat AS cchat" .
				"\n LEFT JOIN #__session AS sess ON cchat.from = sess.session_id" .
				"\n LEFT JOIN #__users AS u ON sess.userid = u.id" .
				$queryParts['JOIN'] .
				"\n WHERE (cchat.to = ". $database->quote($userSessionTable->session_id) . " AND cchat.read != 1)";
				if (!$initialize && $toOpenChatBoxes && $lastReceivedMsgID > 0) {
					$sql .= "\n OR (cchat.from = ". $database->quote($userSessionTable->session_id) . " AND cchat.to IN ( " . $toOpenChatBoxes . " ) " .
							"\n AND cchat.id > $lastReceivedMsgID AND cchat.type='file' AND cchat.clientdeleted = 0)";
				}
				$sql .="\n ORDER BY cchat.id";
	 
		$database->setQuery($sql);
	 	$rows = $database->loadAssocList();
	   
	 	// Ciclo su ogni messaggio gi� precedentemente in sessione per controllare se gli avatar sono ancora esistenti o sono stati cancellati nel frattempo
	 	self::refreshSessionMessagesAvatars($messages);
	 	
	 	if(is_array($rows) && count($rows)) {
		 	// Aggiunta nuovi messaggi
		 	foreach ($rows as $chatmessage) {
		 		$self = 0;
				$old = 0;
				if ($chatmessage['from'] == $userSessionTable->session_id) {
					$chatmessage['from'] = $chatmessage['to'];
					$self = 1;
					$old = 1;
				}
					
				// Get user avatar
				$chatmessage['avatar'] = JChatAvatar::getAvatar($chatmessage['loggedin']);
				
				// Get profile link
				$chatmessage['profilelink'] = self::getUserProfileLink($chatmessage['userid'], $chatmessage['fromuser'], $parms);
				
				// Guest name override: user field name -> override name -> auto generated
				if(!$chatmessage['fromuser']) {
					$chatmessage['fromuser'] = JChatUsers::generateRandomGuestNameSuffix($chatmessage['loggedin'], $parms);
				}
				
				$messages[] = array( 'id' => $chatmessage['id'],
									 'from' => $chatmessage['from'], 
									 'fromuser' => @$chatmessage['fromuser'], 
									 'avatar' => $chatmessage['avatar'],
									 'profilelink' => @$chatmessage['profilelink'],
									 'message' => stripslashes($chatmessage['message']),
									 'type' => @$chatmessage['type'],
									 'status' => @$chatmessage['status'],
									 'self' => $self,
									 'old' => $old);
				
				//Mette i nuovi messaggi provenienti dal mittente in sessione se non propri, vecchi e gi� letti
				if ($self == 0 && $old == 0 && $chatmessage['read'] != 1) {
					$_SESSION['jchat_user_'.$chatmessage['from']][] = array('id' => $chatmessage['id'],
																			'from' => $chatmessage['from'], 
																			'fromuser' => @$chatmessage['fromuser'], 
								 											'avatar' => $chatmessage['avatar'],
																			'userid' => @$chatmessage['loggedin'],
																			'profilelink' => @$chatmessage['profilelink'],
																			'message' => stripslashes($chatmessage['message']),
																			'type' => @$chatmessage['type'],
								 											'status' => @$chatmessage['status'],
																			'self' => 0,
																			'old' => 1);
				}
		
				$lastNewMessageID = $chatmessage['id'];
		 	}
	 	}
	 
	 	// Adesso aggiorna lo stato dei messaggi come letti
		if ($lastNewMessageID) {
			$sql = "UPDATE #__jchat SET `read` = '1' WHERE `to` = " .
				    $database->quote($userSessionTable->session_id) . " and `id` <= " . $database->quote($lastNewMessageID); 
				 
			$database->setQuery($sql); 
			$database->execute();
		}
		
		// Esplica l'autoresfresh realtime dello status messaggi type=file
		self::refreshMsgFileSessionStatus($response);
	}
	
	
	/**
	 * Recupera la lista messaggi del WALL senza to
	 * @access public
	 * @static
	 * @param Object& $parms
	 * @param array& $response
	 * @param array& $messages
	 * @return void
	 */
	public static function fetchWallMessages(&$parms, &$wallMessages) {
		$database = JFactory::getDBO();
		// Get user session table
		$userSessionTable = JChatUsers::getSessiontable ();
		$filter = JFilterInput::getInstance();
		$userFieldName = $filter->clean($parms->get('usefullname'), 'word)');
		  
		$queryParts = array();
		$queryParts['SELECT'] = '';
		$queryParts['JOIN'] = '';
		
		$AND = " AND cchat.from IN (SELECT contactid FROM #__jchat_contacts" .
				"\n WHERE ownerid = " . $database->quote($userSessionTable->session_id) . ")" .
				" AND cchat.from IN (SELECT ownerid FROM #__jchat_contacts" .
				"\n WHERE contactid = " . $database->quote($userSessionTable->session_id) . ")";
		
		$sql = "SELECT cchat.id, cchat.from, cchat.to, cchat.message," .
				"\n cchat.sent, cchat.read, u.id AS userid, sess.session_id AS loggedin, u.$userFieldName AS fromuser" . $queryParts['SELECT'] .
				"\n FROM #__jchat AS cchat" .
				"\n LEFT JOIN #__session AS sess ON cchat.from = sess.session_id" .
				"\n LEFT JOIN #__users AS u ON sess.userid = u.id" . $queryParts['JOIN'] .
				"\n WHERE cchat.to = " . $database->quote(0) . " AND cchat.from != " . $database->quote($userSessionTable->session_id) . $AND .
				"\n AND cchat.sent > " . (time() - $parms->get('maxtimeinterval_groupmessages', 12)) .
				"\n AND cchat.id NOT IN" .
				"\n (SELECT messageid FROM #__jchat_wall WHERE userid = " . $database->quote($userSessionTable->session_id) . ")" .
				"\n ORDER BY cchat.id"; 
		$database->setQuery($sql);
	 	$rows = $database->loadAssocList();
	  
	 	// Ciclo su ogni messaggio gi� precedentemente in sessione per controllare se gli avatar sono ancora esistenti o sono stati cancellati nel frattempo
	 	self::refreshSessionMessagesAvatars($wallMessages, 'wall');
	 	
	 	if(is_array($rows) && count($rows)) {
		 	// Aggiunta nuovi messaggi
		 	foreach ($rows as $chatmessage) {
		 		$self = 0;
				$old = 0; 
				
				// Get user avatar
				$chatmessage['avatar'] = JChatAvatar::getAvatar($chatmessage['loggedin']);
				
				// Get profile link
				$chatmessage['profilelink'] = self::getUserProfileLink($chatmessage['userid'], $chatmessage['fromuser'], $parms);
				
				// Guest name override: user field name -> override name -> auto generated
				if(!$chatmessage['fromuser']) {
					$chatmessage['fromuser'] = JChatUsers::generateRandomGuestNameSuffix($chatmessage['loggedin'], $parms);
				}
				
				$wallMessages[] = array( 'id' => $chatmessage['id'],
										 'from' => 'wall',
										 'fromuserid' => @$chatmessage['from'], 
										 'fromuser' => @$chatmessage['fromuser'], 
										 'avatar' => @$chatmessage['avatar'],
										 'profilelink' => @$chatmessage['profilelink'],
										 'message' => stripslashes($chatmessage['message']), 
										 'self' => $self,
										 'old' => $old); 
				
				$_SESSION['jchat_user_wall'][] = array ( 'id' => $chatmessage['id'],
															'from' => 'wall',
				 											'fromuserid' => @$chatmessage['from'], 
															'fromuser' => @$chatmessage['fromuser'], 
															'avatar' => @$chatmessage['avatar'], 
															'userid' => @$chatmessage['loggedin'],
															'profilelink' => @$chatmessage['profilelink'],
															'message' => stripslashes($chatmessage['message']), 
															'self' => 0,
															'old' => 1);
				//Inseriamo i messaggi pubblici come scaricati/letti da questo utente in sessione
				$sql = "INSERT INTO #__jchat_wall VALUES(" . (int)$chatmessage['id'] . "," . $database->quote($userSessionTable->session_id) . ")"; 
				$database->setQuery($sql); 
				$database->execute();  
		 	} 
	 	}
	}
	
	/**
	 * Refresh dell'avatar nelle liste messaggi in base a successivi cambiamenti di avatar
	 * gestiti da frontend lato utente, siano essi cambiamenti o cancellazioni
	 * @access public
	 * @static
	 * @param array& $messages
	 * @param string $messageType Decide se valutare i wall o private messages
	 * @return void
	 */
	public static function refreshSessionMessagesAvatars(&$messages, $messageType = 'user') {
		$avatarCache = array();
		$destination = null;
		if(is_array($messages) && count($messages)) { 
			// Ciclo su tutti i messaggi
			foreach ($messages as $msgIndex=>&$sessionMessage) {
				// Forcing e smistamento in base al message type
				if($messageType == 'wall') {
					// Messagetype uguale a private user
					if($sessionMessage['from'] != 'wall') {
						continue;
					}
					$destination = 'wall';
				} else {
					// Messagetype uguale a private user
					if($sessionMessage['from'] == 'wall') {
						continue;
					}
					if(isset($sessionMessage['userid'])) {
						$destination = $sessionMessage['userid'];
					} 
				}
				
				// Ignoriamo self message o messaggi senza to
				if(!is_null($destination) && $sessionMessage['self'] != 1) {
					// Controllo esistenza immagine SOLO SE c'� un'immagine avatar
					if(isset($sessionMessage['avatar'])) {
						if(!isset($avatarCache[$sessionMessage['userid']])) {
							if(!@file_get_contents($sessionMessage['avatar'])) {
								$sessionMessage['avatar'] = JChatAvatar::getAvatar($sessionMessage['userid']);
								// Update $_SESSION
								$_SESSION ['jchat_user_' . $destination][$msgIndex]['avatar'] = $sessionMessage['avatar'];
							} 
							// Cache storing
							$avatarCache[$sessionMessage['userid']] = $sessionMessage['avatar'];
						} else {
							if($sessionMessage['avatar'] != $avatarCache[$sessionMessage['userid']]) {
								$sessionMessage['avatar'] = $avatarCache[$sessionMessage['userid']];
								// Update $_SESSION
								$_SESSION ['jchat_user_' . $destination][$msgIndex]['avatar'] = $avatarCache[$sessionMessage['userid']]; 
							}
						}
					} else { 
						if(!isset($avatarCache[$sessionMessage['userid']])) {
							// Perform di un controllo dei messaggi in sessione inviati senza avatar, ma adesso con avatar aggiunto dall'utente
							$isNowNewAvatar = JChatAvatar::getAvatar($sessionMessage['userid']);
							$sessionMessage['avatar'] = $isNowNewAvatar;
							// Update $_SESSION
							$_SESSION ['jchat_user_' . $destination][$msgIndex]['avatar'] = $sessionMessage['avatar'];
							// Cache storing
							$avatarCache[$sessionMessage['userid']] = $sessionMessage['avatar'];
						} else {
							if($sessionMessage['avatar'] != $avatarCache[$sessionMessage['userid']]) {
								$sessionMessage['avatar'] = $avatarCache[$sessionMessage['userid']];
								// Update $_SESSION
								$_SESSION ['jchat_user_' . $destination][$msgIndex]['avatar'] = $avatarCache[$sessionMessage['userid']]; 
							}
						}  
					} 
				}
			}
		}
	}
	  
	/**
	 * Output response application/json
	 * @access public
	 * @static 
	 * @param array& $response
	 * @param array& $messages
	 * @return void
	 */
	public static function sendResponse( &$response, &$messages, &$wallMessages = array()) { 
		if (! empty ( $messages )) {
			$response ['messages'] = $messages;
		}
		
		if(! empty ( $wallMessages)) {
			$response ['wallmessages'] = $wallMessages;
		}
		header ( 'Content-type: application/json; charset=utf-8' );
		echo json_encode ( $response );
		exit ();
	} 
}

