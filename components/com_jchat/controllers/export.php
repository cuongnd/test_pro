<?php
//namespace components\com_jchat;
/**
 * Files controller
 * @package JCHAT::EXPORT::components::com_jchat 
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Lazy loading user session
$chatID = JRequest::getCmd('chatid');  
$userSessionTable = JTable::getInstance('session');
//Pseudo Controller execute 
$fbchatExporter = new JChatExport($chatID, $userSessionTable); 

$task = JRequest::getCmd ( 'task' );
$functionName = $task ? $task : 'exportFile'; 
//Execute function
call_user_func ( array ($fbchatExporter, $functionName ) ); 