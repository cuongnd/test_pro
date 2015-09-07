<?php
//namespace components\com_jchat;
/**
 * Files controller
 * @package JCHAT::FILES::components::com_jchat 
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Pseudo Controller execute  
$task = JRequest::getCmd('task');  

$from = session_id();
$to = JRequest::getCmd('to');
// Istanzazione con IDs injection
$fbchatMsgFile = new JChatAttachments($from, $to); 

$functionName = $task ? $task : 'showForms'; 
//Execute function
call_user_func ( array ($fbchatMsgFile, $functionName ) );
 