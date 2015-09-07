<?php
// namespace administrator\components\com_jrealtimeanalytics\views\help;
/**
 *
 * @package JREALTIMEANALYTICS::CPANEL::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage help
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.view' );

/**
 * CPanel view
 *
 * @package JREALTIMEANALYTICS::CPANEL::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage help
 * @since 1.0
 */
class JRealtimeAnalyticsViewHelp extends JViewLegacy { 
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
	 * Effettua il rendering del pannello di controllo
	 * @access public
	 * @return void
	 */
	public function display() { 
		$doc = JFactory::getDocument ();
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jrealtimeanalytics/css/help.css' );
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		// Output del template
		parent::display ();
	} 

}
?>