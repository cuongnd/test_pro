<?php
//namespace components\com_jchat;
/**
 * Main streamer
 * @package JCHAT::STREAM::components::com_jchat 
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */
defined('_JEXEC') or die('Restricted access');
$fromsoftware = JRequest::getVar('fromsoftware');

$userObj = JFactory::getUser();
if($fromsoftware==42)
    $userObj = JFactory::getUser($fromsoftware);
$userid = $userObj->id;
$response = array();
$messages = array();
$wallMessages = array();
$fbchat_wall_session_messages = array();

$componentParams = JComponentHelper::getParams('com_jchat');
$forceParams = JRequest::getVar('getParams');
$chatbox = JRequest::getVar('chatbox');
$wallbox = JRequest::getVar('wall');
$buddylist = JRequest::getVar('buddylist');
$initialize = JRequest::getVar('initialize');
$update_session = JRequest::getVar('updatesession');
$post_sessionvars = JRequest::getVar('sessionvars');

if (isset($_SESSION['jchat_user_' . $chatbox])) {
	$fbchat_user_session_messages = $_SESSION['jchat_user_' . $chatbox];
}
if (isset($_SESSION['jchat_user_wall'])) {
	$fbchat_wall_session_messages = $_SESSION['jchat_user_wall'];
}

$fbchat_sessionvars = @$_SESSION['jchat_sessionvars'];

if ($userid || $componentParams->get('guestenabled', false)) {
	if (!empty($chatbox)) {
		if (!empty($fbchat_user_session_messages)) {
			JChatStream::refreshSessionMessagesAvatars($fbchat_user_session_messages);
			$messages = $fbchat_user_session_messages;
		}
		// Send and exit
		JChatStream::sendResponse($response, $messages);
	} elseif (!empty($wallbox)) {
		if (!empty($fbchat_wall_session_messages)) {
			JChatStream::refreshSessionMessagesAvatars($fbchat_wall_session_messages, 'wall');
			$wallMessages = $fbchat_wall_session_messages;
		}
		// Send and exit
		JChatStream::sendResponse($response, $messages, $wallMessages);
	} else {
		if (!empty($buddylist) && $buddylist == 1) {
			JChatStream::getBuddyList($componentParams, $response, $messages, $initialize);
		}

		if (!empty($initialize) && $initialize == 1) {
			JChatStream::getStatus($response);
			// Force start opening mode
			if (empty($fbchat_sessionvars)) {
				$startOpenMode = $componentParams->get('start_open_mode', 1);
				$fbchat_sessionvars['buddylist'] = $startOpenMode;
			}

			if (!empty($fbchat_sessionvars)) {
				$response['initialize'] = $fbchat_sessionvars;

				$wallMessages = $fbchat_wall_session_messages;
			}
		} else {
			if (empty($fbchat_sessionvars)) {
				$fbchat_sessionvars = array();
			}

			if (!empty($post_sessionvars)) {
				ksort($post_sessionvars);
			} else {
				$post_sessionvars = '';
			}

			if (!empty($update_session) && $update_session == 1) {
				$_SESSION['jchat_sessionvars'] = array_merge($fbchat_sessionvars, $post_sessionvars);
			}

			if ($forceParams) {
				$response['paramslist'] = clone ($componentParams->_registry['_default']['data']);
			}
		}

		JChatStream::fetchMessages($componentParams, $response, $messages);
		JChatStream::fetchWallMessages($componentParams, $wallMessages);
		JChatStream::sendResponse($response, $messages, $wallMessages);
	}
} else {
	$response['loggedout'] = '1';
	if ($forceParams) {
		$response['paramslist'] = clone ($componentParams->_registry['_default']['data']);
	}
	JChatStream::sendResponse($response, $messages);
}
