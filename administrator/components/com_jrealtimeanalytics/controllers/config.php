<?php
// namespace administrator\components\com_jrealtimeanalytics\controllers;
/**
 *
 * @package JREALTIMEANALYTICS::CONFIG::administrator::components::com_jrealtimeanalytics
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
 * @package JREALTIMEANALYTICS::CONFIG::administrator::components::com_jrealtimeanalytics
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
 * @package JREALTIMEANALYTICS::CPANEL::administrator::components::com_jrealtimeanalytics
 * @subpackage controllers
 * @since 1.0
 */
class JRealtimeAnalyticsControllerConfig extends JRealtimeAnalyticsControllerBase implements IConfigController {

	/**
	 * Show configuration
	 * @access public
	 * @return void
	 */
	public function display() {  
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
		$this->setRedirect( 'index.php?option=com_jrealtimeanalytics&task=config.display', $msg);
	}
}
?>