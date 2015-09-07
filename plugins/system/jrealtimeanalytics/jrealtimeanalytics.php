<?php
/** 
 * JS APP renderer system plugin
 * @package JREALTIMEANALYTICS::plugins::system
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.plugin.plugin' );
 
class plgSystemJRealtimeAnalytics extends JPlugin {
	/**
	 * Class Constructor 
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function plgSystemJRealtimeAnalytics(& $subject, $config) {
		parent::__construct ( $subject, $config );
	} 
	/**
	 * onAfterInitialise handler
	 *
	 * Aggiunge nel page document output la JS APP
	 *
	 * @access	public
	 * @return null
	 */
	function onAfterInitialise() {
		$app = JFactory::getApplication();  
		$notrack = JRequest::getVar('notrack', false);
		$component = JComponentHelper::getComponent('com_jrealtimeanalytics');
		$cParams = $component->params;
		// Execute solo nel frontend
		if(!$app->getClientId() && !$notrack && $cParams->get('includeevent') == 'afterinitialize') {
			// Ottenimento parametri componente
			$component = JComponentHelper::getComponent('com_jrealtimeanalytics');
			$cParams = $component->params;
			// Ottenimento document
			$doc = JFactory::getDocument (); 
			$this->injectApp ( $cParams, $doc );
		} 
	}
 
	/**
	 * onAfterInitialise handler
	 *
	 * Effettua un restore della versione di jQuery iniettata dall jrealtimeanalytics
	 * dopo quella effettuata dal Community Builder
	 *
	 * @access	public
	 * @return null
	 */
	function onAfterDispatch() {
		$app = JFactory::getApplication();  
		$notrack = JRequest::getVar('notrack', false);
		$component = JComponentHelper::getComponent('com_jrealtimeanalytics');
		$cParams = $component->params;
		// Execute solo nel frontend
		if(!$app->getClientId() && !$notrack && $cParams->get('includeevent') == 'afterdispatch') {
			// Ottenimento parametri componente
			$component = JComponentHelper::getComponent('com_jrealtimeanalytics');
			$cParams = $component->params;
			// Ottenimento document
			$doc = JFactory::getDocument ();  
			$this->injectApp ( $cParams, $doc );
		}
	}
	
	/**
	 * Effettua l'app js output
	 * 
	 * @param Object& $cParams
	 * @param Object& $doc
	 * @return boolean
	 */
	private function injectApp(&$cParams, &$doc) { 
		$option = JRequest::getvar('option');
		$base = JURI::base(); 
		
		// Output JS APP nel Document esclusione
		if($doc->getType() !== 'html' || JRequest::getCmd('tmpl') === 'component') {
			return false;
		}
		 
		// Output JS APP nel Document
		if($cParams->get('includejquery')) {
			JHtml::_('jquery.framework');
		}
		if($cParams->get('noconflict')) {
			$doc->addScript(JURI::root(true) . '/components/com_jrealtimeanalytics/js/jquery.noconflict.js');  
		}
		$doc->addScriptDeclaration("var jfbc_baseURI='$base';"); 
		$doc->addScript(JURI::root(true) . '/components/com_jrealtimeanalytics/js/jrealtimeanalytics.js');
	 	
		// Restore se overwrite jQuery prototype da parte di CB
		if($option === 'com_comprofiler' && $cParams->get('cbnoconflict')) {
			// Evitiamo fatal error nei controlli ajax del CB con JDocumentRaw che non ha addCustomTag
			if($doc instanceof JDocumentHTML) {
				$doc->addCustomTag('<script type="text/javascript">jQuery.noConflict(true);</script>');
			}
		} 
		return true;
	}
}