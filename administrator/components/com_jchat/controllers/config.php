<?php
// namespace administrator\components\com_jchat\controllers;
/**
 *
 * @package JCHAT::CONFIG::administrator::components::com_jchat
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.controller' );

/**
 * Config controller responsibilities
 *
 * @package JCHAT::CONFIG::administrator::components::com_jchat
 * @subpackage controllers
 * @since 1.0
 */
interface IConfigController {

	/**
	 * Save config entity
	 * @access public
	 * @return void
	 */
	public function saveEntity();
}


/**
 * Config controller concrete implementation
 *
 * @package JCHAT::CPANEL::administrator::components::com_jchat
 * @subpackage controllers
 * @since 1.0
 */
class JChatControllerConfig extends JChatController implements IConfigController {

	/**
	 * Show configuration
	 * @access public
	 * @return void
	 */
	public function display($cachable = false, $urlparams = array()) { 
		parent::display();
	}

	/**
	 * Save config entity
	 * @access public
	 * @return void
	 */
	public function saveEntity() {
		$model = $this->getModel();
		$result = $model->storeEntity();
		$msg = null;  
		$msg = $result ? JText::_('SAVED_PARAMS') : JText::_ ( 'SAVE_PARAMS_ERROR' );
		$this->setRedirect( 'index.php?option=com_jchat&task=config.display', $msg);
	}
}
?>