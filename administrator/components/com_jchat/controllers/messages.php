<?php
// namespace administrator\components\com_jchat\controllers;
/**
 *
 * @package JCHAT::MESSAGES::administrator::components::com_jchat
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.controller' );

/**
 * User messages concrete implementation
 *
 * @package JCHAT::MESSAGES::administrator::components::com_jchat
 * @subpackage controllers
 * @since 1.0
 */
class JChatControllerMessages extends JChatController { 
	/**
	 * Setta il model state a partire dallo userstate di sessione
	 * @access protected
	 * @return void
	 */
	protected function setModelState($scope = 'default') {
		parent::setModelState('messages');
		
		// User state specific
		$app = JFactory::getApplication();
		$option= JRequest::getVar('option');
		  
		$fromPeriod = $app->getUserStateFromRequest( "$option.messages.fromperiod", 'fromperiod');
		$toPeriod = $app->getUserStateFromRequest( "$option.messages.toperiod", 'toperiod');
		$msgType = $app->getUserStateFromRequest( "$option.messages.msg_type", 'msg_type');
		$msgStatus = $app->getUserStateFromRequest( "$option.messages.msg_status", 'msg_status');
		
		// Get default model
		$defaultModel = $this->getModel();
		
		// Set model state  
		$defaultModel->setState('fromPeriod', $fromPeriod);
		$defaultModel->setState('toPeriod', $toPeriod);
		$defaultModel->setState('msgType', $msgType);
		$defaultModel->setState('msgStatus', $msgStatus);
	}
	
	/**
	 * Default listEntities
	 * 
	 * @access public
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		// Set model state 
		$this->setModelState();
		
		// Parent construction and view display
		parent::display();
	}

	/**
	 * Mostra il dettaglio dell'entity
	 * 
	 * @access public
	 * @return void
	 */
	public function showEntity() {
		$cid = JRequest::getVar('cid', array(0), 'method', 'array');
		$idEntity = (int) $cid[0];
		$model = $this->getModel();
		$record = $model->loadEntity($idEntity);
		
		$model->setState('option', JRequest::getVar('option'));
 
		$view = $this->getView();
		$view->setModel ( $model, true );
		$view->showEntity($record); 
	}
	
	/**
	 * Cancella una entity dal DB
	 *
	 * @access public
	 * @return void
	 */
	public function deleteEntity() {
		$cids = JRequest::getVar('cid', array(0), 'method', 'array');
		$option = JRequest::getVar('option');
		//Load della  model e checkin before exit
		$model = $this->getModel ( );
		$result = $model->deleteEntity($cids);
	
		$msgResult = $result ? 'MSG_SUCCESS_DELETE' : 'MSG_ERROR_DELETE';
			
		$this->setRedirect ( "index.php?option=$option&task=messages.display", JTEXT::_($msgResult) );
	}
	
	/**
	 * Avvia il processo di esportazione records
	 *
	 * @access public
	 * @return void
	 */
	public function exportMessages() { 
		// Set model state 
		$this->setModelState();
		// Mapping fields to load to column header
		$fieldsToLoadArray = array(	'a.actualfrom AS sender_name'=>JText::_('SENDER_NAME'),
									'a.actualto AS receiver_name'=>JText::_('RECEIVER_NAME'),
									'a.message'=>JText::_('MESSAGE'),
									'a.sent'=>JText::_('SENT'),
									'a.read'=>JText::_('READ'),
									'a.type'=>JText::_('TYPE')); 
		$fieldsFunctionTransformation = array();
		
		$model = $this->getModel();
		$data = $model->exportMessages($fieldsToLoadArray, $fieldsFunctionTransformation);
		
		if(!$data) {
			$this->setRedirect('index.php?option=com_jchat&task=messages.display', JText::_('NODATA_EXPORT'));
			return false;
		}
		
		// Get view
		$view = $this->getView();
		$view->sendCSVMessages($data, $fieldsFunctionTransformation);
	}  
}
?>