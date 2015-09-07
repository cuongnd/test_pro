<?php
// namespace administrator\components\com_jchat\views\cpanel;
/**
 *
 * @package JCHAT::CONFIG::administrator::components::com_jchat
 * @subpackage views
 * @subpackage config
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.view' );

/**
 * Config view
 *
 * @package JCHAT::CONFIG::administrator::components::com_jchat
 * @subpackage views
 * @since 1.0
 */
class JChatViewConfig extends JViewLegacy {

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		JToolBarHelper::title(JText::_( 'JCHAT_MAINTITLE_TOOLBAR' ) . JText::_( 'JCHAT_CONFIG' ), 'jchat' );
		JToolBarHelper::save('config.saveentity', 'SAVECONFIG');
		JToolBarHelper::custom('cpanel.display', 'config', 'config', 'JCHAT_CPANEL', false);
	}
	
	/**
	 * Effettua il rendering dei tabs di configurazione del componente
	 * @access public
	 * @return void
	 */
	public function display($tpl = null) {
		// Not yet implemented
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.switcher');
		
		$doc = JFactory::getDocument();
		// Docs stylesheet overrides 
		$doc->addStyleDeclaration('dd.tabs label.hasTip{width:230px;}
								   img.jfbc_sysinfo{margin-top:8px;}
								   div.current{border:none; border-top:1px solid #CCC;}
								   div#element-box div.m{background-color:#FFF;}');
		$doc->addStyleSheet(JURI::root(true) . '/administrator/components/com_jchat/css/generic.css');
		
		$params = $this->get('Data');
		$form = $this->get('form');
		
		// Bind the form to the data.
		if ($form && $params) {
			$form->bind($params);
		}
		
		$this->assignRef('params_form', $form);
		$this->assignRef('params', $params);
		$this->app = JFactory::getApplication();
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		// Output del template
		parent::display();
	}
}
?>