<?php
// namespace administrator\components\com_jrealtimeanalytics\controllers;
/** 
 * @package JREALTIMEANALYTICS::SERVERSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.controller' );
 
/**
 * Suggestions controller responsibilities contract
 *
 * @package JREALTIMEANALYTICS::SUGGESTIONS::administrator::components::com_jrealtimeanalytics
 * @subpackage controllers
 * @since 1.0
 */
interface IServerstatsController {
	/**
	 * Details show entity
	 *
	 * @access public
	 * @return void
	 */
	public function showEntity();
	
	/**
	 * Cancella una entity dal DB
	 *
	 * @access public
	 * @return void
	*/
	public function deleteEntity();
}

/**
 * Concrete class serverstats controller
 *
 * @package JREALTIMEANALYTICS::SERVERSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage controllers
 * @since 1.0
 */
class JRealtimeAnalyticsControllerServerstats extends JRealtimeAnalyticsControllerBase implements IServerstatsController{ 
	/**
	 * Setta il model state a partire dallo userstate di sessione
	 * @access protected
	 * @return void
	 */
	protected function setModelState() {  
		// User state specific
		$app = JFactory::getApplication();
		$option= JRequest::getVar('option');
		  
		//Filtro Data DA... Data A... - Valori di default
		$primodelmese = date ( "Y-m-01", strtotime ( date ( "Y-m-d" ) ) );
		$ultimodelmese = date ( "Y-m-d", strtotime ( "-1 day", strtotime ( "+1 month", strtotime ( date ( "Y-m-01" ) ) ) ) );
		$fromPeriod = $app->getUserStateFromRequest( "$option.serverstats.fromperiod", 'fromperiod', strval($primodelmese));
		$toPeriod = $app->getUserStateFromRequest( "$option.serverstats.toperiod", 'toperiod', strval($ultimodelmese));  
		
		// Get default model
		$defaultModel = $this->getModel();
		
		// Set model state  
		$defaultModel->setState('fromPeriod', $fromPeriod);
		$defaultModel->setState('toPeriod', $toPeriod); 
		$defaultModel->setState('option', $option);
	}
	
	/**
	 * Default show stats
	 * 
	 * @access public
	 * @return void
	 */
	public function display() {
		// Set model state 
		$this->setModelState();
		$task = array_pop(explode('.', JRequest::getVar('task', 'display')));
		// Instance dependencies
		require_once JPATH_COMPONENT . '/libraries/jpgraph/generators/graphgenerator.php';
		 
		$graphGenerator = new jfbcGraphGenerator();
		
		$model = $this->getModel();
		$model->setGraphRenderer($graphGenerator);
		 
		//Creazione buffer output
		ob_start (); 
		// Parent construction and view display
		parent::display();
		$bufferContent = ob_get_contents ();
		ob_end_clean ();
		 
		
		//Decidiamo come mandare in php stdout i dati
		switch ($task) {
			case 'displaypdf':
				require_once JPATH_COMPONENT . '/libraries/renderers/pdf.php';
				$pdfRenderer = new PDFRenderer();
				$pdfRenderer->renderContent ( $bufferContent, $model );
				break;  
			default:
				echo $bufferContent; 
		} 
	}
	
	/**
	 * Details show entity
	 *
	 * @access public
	 * @return void
	 */
	public function showEntity() {
		// Set model state
		$this->setModelState();
		
		$identifier = JRequest::getvar('identifier', null);
		$detailType = JRequest::getvar('details');
		
		$model = $this->getModel();
		$detailData = $model->loadEntity($identifier, $detailType);
		
		$view = $this->getView();
		$view->showEntity($detailData, $detailType);
	}
	
	/**
	 * Cancellazione dati statistici nella DB cache
	 *
	 * @access public
	 * @return void
	 */
	public function deleteEntity() {  
		$option = JRequest::getVar('option');
		$model = $this->getModel();
		$result = $model->deleteEntity();
		
		$msgResult = $result ? 'SUCCESS_CLEANED_CACHE' : 'ERROR_CLEANED_CACHE';  
			
		$this->setRedirect ( "index.php?option=$option&task=serverstats.display", JTEXT::_($msgResult) );
	}
	
	/**
	 * Overloaded class constructor
	 * 
	 * @access public
	 * @return Object&
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
		$this->registerTask('displaypdf', 'display');
	}
}