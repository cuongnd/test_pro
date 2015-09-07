<?php
// namespace administrator\components\com_jrealtimeanalytics\views\messages;
/** 
 * @package JREALTIMEANALYTICS::SERVERSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage serverstats
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );   
define ( 'VISITSPERPAGE', 0 );
define ( 'TOTALVISITEDPAGES', 1 );
define ( 'TOTALVISITEDPAGESPERUSER', 2 );
define ( 'TOTALVISITORS', 3 );
define ( 'MEDIUMVISITTIME', 4 );
define ( 'MEDIUMVISITEDPAGESPERSINGLEUSER', 5 );
define ( 'NUMUSERSGEOGROUPED', 6 );
define ( 'NUMUSERSBROWSERGROUPED', 7 );
define ( 'NUMUSERSOSGROUPED', 8 );
define ( 'LEAVEOFF_PAGES', 9 );
define ( 'LANDING_PAGES', 10 );
 
jimport ( 'joomla.application.component.view' ); 

/**
 * Server stats view
 *
 * @package JREALTIMEANALYTICS::SERVERSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage realstats
 * @since 1.0
 */
class JRealtimeAnalyticsViewServerstats extends JViewLegacy { 
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		JToolBarHelper::title( JText::_( 'SERVERSTATS_PANEL' ), 'jrealtimeanalytics' );
		JToolBarHelper::custom('serverstats.displaypdf', 'pdf', 'pdf', 'EXPORTPDF', false);
		JToolBarHelper::deleteList(JText::_('DELETE_SERVERSTATS_CACHE'), 'serverstats.deleteEntity', 'CLEAN_CACHE');
		JToolBarHelper::custom('cpanel.display', 'config', 'config', 'CPANEL', false);
	}
	
	/**
	 * Default display delle stats
	 * 
	 * @access public
	 * @return void
	 */
	public function display() {
		// Javascript support
		JHTML::_('behavior.calendar'); 
		$task = array_pop(explode('.', JRequest::getVar('task', 'display')));
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jrealtimeanalytics/css/serverstats.css' );
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jrealtimeanalytics/js/libraries/fancybox/jquery.fancybox-1.3.4.css' );
		JHtml::_('bootstrap.framework');
		$doc->addScript(JURI::root(true) . '/administrator/components/com_jrealtimeanalytics/js/libraries/fancybox/jquery.fancybox-1.3.4.pack.js');
		$doc->addScriptDeclaration("jQuery(function($){jQuery('.preview').fancybox({'width':'85%','height':'90%','autoScale':false,'transitionOut':'none','type':'iframe'});});");
		
		// Get stats data
		$statsData = $this->get('Data');
		$geoTranslations = $this->get('GeoTranslations');
		
		// Enqueue user message se nel periodo selezionato non ci sono pagine visitate AKA statistiche da mostrare
		if(!$statsData[TOTALVISITEDPAGES]) {
			$app->enqueueMessage(JText::_('NO_STATS_IN_PERIOD'));
		}
		
		// Set reference in template
		$dates = array('start'=>$this->getModel()->getState('fromPeriod'), 'to'=>$this->getModel()->getState('toPeriod')); 
		$this->assignRef('data', $statsData);
		$this->assignRef('option', $this->getModel()->getState('option'));
		$this->assignRef('dates', $dates);
		$this->assignRef('geotrans', $geoTranslations);
		$this->assignRef('userid', JFactory::getUser()->id);
		$this->assign('nocache', '?time=' . time());
		
		// Call main template
		$prefixPath = null;
		if($task === 'displaypdf') {
			$prefixPath = 'pdf_';
		}
		$this->setLayout($prefixPath . 'graph');
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		parent::display('main');
	}
	
	/**
	 * Show entity details richiesto per visite utente e pagine
	 * 
	 * @access public
	 * @param Object& $detailData
	 * @param string $detailType
	 * @return void 
	 */
	public function showEntity(&$detailData, $detailType) { 
		$doc = JFactory::getDocument();
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jrealtimeanalytics/css/serverstats.css' );
		
		$this->assignRef('detailData', $detailData);
		// Call main template
		$this->setLayout('details');
		parent::display($detailType);
	}
}
?>