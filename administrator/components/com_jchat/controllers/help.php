<?php
// namespace administrator\components\com_jmap\controllers;
/**
 *
 * @package JMAP::CPANEL::administrator::components::com_jmap
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.controller' );

/**
 * CPanel controller
 *
 * @package JMAP::CPANEL::administrator::components::com_jmap
 * @subpackage controllers
 * @since 1.0
 */
class JChatControllerHelp extends JChatController {
	/**
	 * Show Control Panel
	 * @access public
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		parent::display (); 
	}
}
?>