<?php
//namespace components\com_jchat;
/**
 * Entrypoint dell'application di frontend
 * @package JCHAT::components::com_jchat
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html    
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
//ini_set('display_errors', false);
//ini_set('error_reporting', E_ERROR);

// Register model, framework, libraries classes
JLoader::register('JChatAvatar', JPATH_COMPONENT . '/models/avatar.php');
JLoader::register('JChatAttachments', JPATH_COMPONENT . '/models/attachments.php');
JLoader::register('JChatExport', JPATH_COMPONENT . '/models/export.php');
JLoader::register('JChatStream', JPATH_COMPONENT . '/models/stream.php');
JLoader::register('JChatGroupchat', JPATH_COMPONENT . '/models/groupchat.php');

JLoader::register('PhpThumbFactory', JPATH_COMPONENT . '/libraries/phpthumb/thumb.factory.php');
JLoader::register('JChatUsers', JPATH_COMPONENT . '/libraries/jchatusers.php');

// Entrypoint controller
$controller = JRequest::getVar ( 'controller', 'stream' ); 
// Dispatch controller
require_once 'controllers/' . $controller . '.php';               