<?php
//namespace components\com_jrealtimeanalytics;
/**
 * Receiver/Responder principale delle richieste AJAX 
 * @package JREALTIMEANALYTICS::DATA::components::com_jrealtimeanalytics
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
// Require model and dependencies
require_once JPATH_COMPONENT . '/models/receiver.php';
require_once JPATH_COMPONENT . '/models/garbage.php';
require_once JPATH_COMPONENT . '/models/realstats.php';
require_once JPATH_COMPONENT . '/models/serverstats.php';
jimport('joomla.database.table.session');

//Instance degli oggetti <<mockable_di>> di cui si effettua Dependency Injection

// Fondamentale valutare il proprio oggetto session anzichè user
$userSessionTable = JTable::getInstance('Session', 'JTable');
$userSessionTable->load(session_id());
// Gestore tracking statistiche realtime
$realStats = new jfbcRealstats($userSessionTable);
// Gestore agents e assegnamenti
$serverStats = new jfbcServerstats($userSessionTable);

// Instance and execute dell'application
$app = new jfbcReceiver($userSessionTable, $realStats, $serverStats);
$app->appDispatch();
 

 