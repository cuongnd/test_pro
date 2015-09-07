<?php
// namespace administrator\components\com_jrealtimeanalytics\views\messages;
/** 
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage realstats
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );   
jimport ( 'joomla.application.component.view' ); 

/**
 * Realtime stats view contract
 *
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage realstats
 * @since 1.0
 */
interface IRealstatsView { 
	/**
	 * Inject dati to JS app response in JSON mime-type
	 * @access public
	 * @param Object[]& $data
	 * @return void
	 */
	public function jsonView(&$data);
}

/**
 * Realtime stats view
 *
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage realstats
 * @since 1.0
 */
class JRealtimeAnalyticsViewRealstats extends JViewLegacy implements IRealstatsView { 
	/**
	 * Inietta le costanti lingua nel JS Domain con il solito name mapping
	 * @access protected
	 * @param $translations Object&
	 * @param $document Object&
	 * @return void
	 */
	protected function injectJsTranslations(&$translations, &$document) {
		$jsInject = null;
 		// Do translations
		foreach ( $translations as $translation ) {
			$jsTranslation = strtoupper ( $translation );
			$translated = JText::_( $jsTranslation, true);
			$jsInject .= <<<JS
				jfbc$translation = '{$translated}'; 
JS;
		}
		$document->addScriptDeclaration($jsInject);
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		JToolBarHelper::title( JText::_( 'REALSTATS_GRAPH' ), 'jrealtimeanalytics' );
		JToolBarHelper::custom('cpanel.display', 'config', 'config', 'CPANEL', false);
	}

	/**
	 * Default inject JS APP
	 * @access public
	 * @return void
	 */
	public function display() {
		// Assign ref
		$this->assignref('option', $this->getModel()->getState('option'));
		$componentConfig = $this->get('Config');
		
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration(" 
					var jfbcGlobalStatsEndpoint = '" . JURI::root() . 'administrator/index.php?option=com_jrealtimeanalytics&task=realstats.dataPoll&format=raw' . "';  
					var jfbcIntervalRealStats = '" . $componentConfig->get('realtime_refresh') . "';
					jQuery(function(){
						new jQuery.jfbcRealstatsController(); 
					});
				");
		// Inject js translations
		$translations = array(	'PiegraphTitle', 
								'TextStatsTitle', 
								'BargraphTitle', 
								'Users',
								'UsersStatsTitle',
								'TitleName',
								'TitleUsername',
								'TitleType',
								'TitleTime',
								'TitleNowpage',
								'PerpageStatsTitle',
								'TitleNumUsers',
								'TitleLastVisit');
		$this->injectJsTranslations($translations, $doc);
		
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jrealtimeanalytics/css/realtime.css' );
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jrealtimeanalytics/js/libraries/fancybox/jquery.fancybox-1.3.4.css' );
		
		JHtml::_('bootstrap.framework');
		
		$doc->addScript(JURI::root(true) . '/administrator/components/com_jrealtimeanalytics/js/libraries/kendo/kendo.core.js');
		$doc->addScript(JURI::root(true) . '/administrator/components/com_jrealtimeanalytics/js/libraries/kendo/kendo.data.js');
		$doc->addScript(JURI::root(true) . '/administrator/components/com_jrealtimeanalytics/js/libraries/kendo/kendo.chart.js');
		$doc->addScript(JURI::root(true) . '/administrator/components/com_jrealtimeanalytics/js/libraries/kendo/jquery.stringify.js');
		
		$doc->addScript(JURI::root(true) . '/administrator/components/com_jrealtimeanalytics/js/views/realstats.view.js');
		$doc->addScript(JURI::root(true) . '/administrator/components/com_jrealtimeanalytics/js/models/realstats.model.js');
		$doc->addScript(JURI::root(true) . '/administrator/components/com_jrealtimeanalytics/js/controllers/realstats.controller.js');
		 
		$doc->addScript(JURI::root(true) . '/administrator/components/com_jrealtimeanalytics/js/libraries/fancybox/jquery.fancybox-1.3.4.pack.js');
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		// Display template
		parent::display();
	}

	/**
	 * Inject dati to JS app response in JSON mime-type
	 * @access public
	 * @param Object[]& $data
	 * @return void
	 */
	public function jsonView(&$data) {
		header('Content-type: application/json');
		echo json_encode($data);
		
		exit();
	}
}
?>