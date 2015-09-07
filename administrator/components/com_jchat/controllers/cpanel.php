<?php
// namespace administrator\components\com_jchat\controllers;
/**
 *
 * @package JCHAT::CPANEL::administrator::components::com_jchat
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
 * @package JCHAT::CPANEL::administrator::components::com_jchat
 * @subpackage controllers
 * @since 1.0
 */
class JChatControllerCpanel extends JChatController {
	/**
	 * Show Control Panel
	 * @access public
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		parent::display (); 
	}
	
	/**
	 * Purge file cache
	 * @access public
	 * @return void
	 */
	public function purgeFileCache() {
		$option = JRequest::getVar('option');
		//Load model
		$model = $this->getModel ();
		$result = $model->purgeFileCache();
	
		$msg = $result ? 'SUCCESS_DELETE_CACHE' : 'ERROR_DELETE_CACHE';
			
		$this->setRedirect ( "index.php?option=$option&task=cpanel.display", JTEXT::_($msg) );
	}
}
?>