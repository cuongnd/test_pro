<?php
// namespace administrator\components\com_jrealtimeanalytics\views\cpanel;
/**
 *
 * @package JREALTIMEANALYTICS::CONFIG::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage config
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Config view
 *
 * @package JREALTIMEANALYTICS::CONFIG::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @since 1.0
 */
class JRealtimeAnalyticsViewConfig extends JViewLegacy{
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		JToolBarHelper::title( JText::_( 'CONFIG' ), 'jrealtimeanalytics' );
		JToolBarHelper::save('config.saveentity', 'SAVECONFIG');
		JToolBarHelper::custom('cpanel.display', 'config', 'config', 'CPANEL', false);
	}
	
	/**
	 * Effettua il rendering dei tabs di configurazione del componente
	 * @access public
	 * @return void
	 */
	public function display() {
		// Not yet implemented
		JHTML::_('behavior.tooltip');
		JHTML::_('bootstrap.framework');
		
		$doc = JFactory::getDocument(); 
		// Docs stylesheet overrides 
		$doc->addStyleDeclaration('dd.tabs label.hasTip{width:230px;}
								   img.jfbc_sysinfo{margin-top:8px;}
								   div.current{border:none; border-top:1px solid #CCC;}
								   div#element-box div.m{background-color:#FFF;}');
		$doc->addStyleSheet(JURI::root(true) . '/administrator/components/com_jrealtimeanalytics/css/generic.css');
		
		$params = $this->get('Data');
		$form = $this->get('form');
		
		// Bind the form to the data.
		if ($form && $params) {
			$form->bind($params);
		}
		
		$this->assignRef('params_form', $form);
		$this->assignRef('params', $params);
		$this->assignref('app', JFactory::getApplication());
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		// Output del template
		parent::display();
	}
}
?>