<?php
// namespace components\com_jchat\controllers;
/**
 * Files controller
 * 
 * @package JCHAT::AVATAR::components::com_jchat
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Pseudo Controller execute
$fbchatAvatar = new JChatAvatar ();
$task = JRequest::getCmd ( 'task' );
$functionName = $task ? $task : null;
// Execute function
if ($functionName) {
	call_user_func ( array ($fbchatAvatar, $functionName ) );
}