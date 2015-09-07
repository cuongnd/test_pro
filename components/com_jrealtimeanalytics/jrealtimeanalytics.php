<?php
//namespace components\com_jrealtimeanalytics;
/**
 * Entrypoint dell'application di frontend
 * @package JREALTIMEANALYTICS::components::com_jrealtimeanalytics
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html     
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
// Massima soppressione errori per json_encode response HTTP
ini_set('display_errors', false);
ini_set('error_reporting', E_ERROR);
// Task che funge da entrypoint controller
$controller = JRequest::getVar ( 'controller', 'receiver' ); 
// Dispatch controller
require_once 'controllers/' . $controller . '.php';               