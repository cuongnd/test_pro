<?php
//namespace components\com_jchat\controllers;
/**
 * Files controller
 * @package JCHAT::CONTATTI::components::com_jchat 
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

//Pseudo Controller execute 
$fbchatContacts = new JChatGroupchat ();
$task = JRequest::getCmd ( 'task' );
$id = JRequest::getCmd ( 'id' );
$functionName = $task ? $task : null;
//Execute function
if ($functionName) {
	call_user_func_array ( array ($fbchatContacts, $functionName ), array ($id ) );
}