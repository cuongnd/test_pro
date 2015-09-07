<?php
// namespace administrator\components\com_jrealtimeanalytics\controllers;
/**
 *
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.controller' );

/**
 * Responsibilities del realtime controller
 *
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage controllers
 * @since 1.0
 */
interface IRealstatsController { 
	/**
	 * Restituisce in view dispatch i dati per la JS APP in formato JSON
	 * per l'elaborazione e la generazione dei dati statistici
	 * 
	 * @access public
	 * @return void
	 */
	public function dataPoll();
}

/**
 * Statistiche realtime admin controller, responsabile della default display che
 * va ad iniettare la JS APP e di fornire in polling i dati in json format
 * alle richieste asincrone da parte della JS APP che renderizzerà i grafici e i dati
 *
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage controllers
 * @since 1.0
 */
class JRealtimeAnalyticsControllerRealstats extends JRealtimeAnalyticsControllerBase implements IRealstatsController {
	protected function setModelState() { 
		// User state specific 
		$option= JRequest::getVar('option'); 
		 
		// Get default model
		$defaultModel = $this->getModel();
	
		// Set model state
		$defaultModel->setState('option', $option); 
	}
	 
	/**
	 * Default task che anzichè richiamare una funzionalità di listEntities
	 * va ad iniettare la JS APP che in corso di esecuzione renderizzerà
	 * le statistiche realtime comunicando asincronamente con il dataPoll task
	 * 
	 * @access public
	 * @return void
	 */
	public function display() {
		// Set model state
		$this->setModelState();
		 
		parent::display();
	}

	/**
	 * Restituisce in view dispatch i dati per la JS APP in formato JSON
	 * per l'elaborazione e la generazione dei dati statistici
	 * 
	 * @access public
	 * @return void
	 */
	public function dataPoll() {
		$initRequest = JRequest::getVar('init', false);
		$pieRequest = JRequest::getVar('pie', false);
		
		$defaultModel = $this->getModel();
		// Calculate data from model
		$data = $defaultModel->getData($pieRequest, $initRequest);
		
		// Respond in JSON to JS APP
		$view = $this->getView(); 
		$view->setModel($defaultModel);
		$view->jsonView($data);
	}
}
?>