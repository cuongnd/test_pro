<?php
// namespace administrator\components\com_jmap\views\cpanel;
/**
 *
 * @package JMAP::CPANEL::administrator::components::com_jmap
 * @subpackage views
 * @subpackage cpanel
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.view' );

/**
 * CPanel view
 *
 * @package JMAP::CPANEL::administrator::components::com_jmap
 * @subpackage views
 * @subpackage cpanel
 * @since 1.0
 */
class JChatViewHelp extends JViewLegacy {
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-jchat{background-image:url("components/com_jchat/images/icon-48-help.png")}');
		JToolBarHelper::title( JText::_( 'JCHAT_MAINTITLE_TOOLBAR' ) . JText::_( 'JCHAT_HELP' ), 'jchat' );
		JToolBarHelper::custom('cpanel.display', 'config', 'config', 'JCHAT_CPANEL', false);
	}
	
	/**
	 * Effettua il rendering del pannello di controllo
	 * @access public
	 * @return void
	 */
	public function display($tpl = null) {
		$doc = JFactory::getDocument ();
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jchat/css/generic.css' );
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jchat/css/help.css' );
	 
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		// Output del template
		parent::display ();
	}  
}