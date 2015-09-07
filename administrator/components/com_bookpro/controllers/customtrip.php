<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  2-04-2014 6:16:16
 **/

// No direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.controller' );
AImporter::model ( 'passenger','order' );
AImporter::helper('request', 'controller');

class BookProControllerCustomtrip extends JControllerLegacy {
		var $_modelpassenger;
		var $_Omodel;
		function __construct($config = array()) {
			parent::__construct ( $config );
			$this->_Omodel =new BookProModelOrder();
			$this->_modelpassenger= new BookProModelPassenger() ;
		}
    /*
     * Functional: Save passenger from customtrip infor
     */
		function save($apply = false) {
			
			JRequest::checkToken() or jexit('Invalid Token');
			$mainframe = &JFactory::getApplication();
			$post = JRequest::get('post');
			$post['id'] = $post['order_id'];
			$post['notes'] = JRequest::getVar('notes', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$db=JFactory::getDbo();
			
			$id = $this->_Omodel->store($post);
		
			// notification
			$jinput = JFactory::getApplication()->input;
		
			if ($id) {
				if ($jinput->getBool('notify_customer', false)) {
					AImporter::helper('email');
					$mailer = new EmailHelper();
					$mailer->changeOrderStatus($id);
				}
			}
		
			if ($id !== false) {
				$mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
			} else {
				$mainframe->enqueueMessage(JText::_('Save failed'), 'error');
			}
			if ($apply) {
				ARequest::redirectEdit(CONTROLLER_ORDER, $id);
			} else {
				ARequest::redirectList(CONTROLLER_ORDER);
			}
		}
		
    /*
     * Send email update
     */
	function sendEmail(){
		
	}
	/*
	 * Update passenger infor
	*/
	
	function editPassenger(){
		/*
		 * Post: message
		*/
		
		$config = AFactory::getConfig ();
		$mainframe = &JFactory::getApplication ();
		/* @var $mainframe JApplication */
		$user = JFactory::getUser ();
		/* @var $user JUser */
		$config = AFactory::getConfig ();
		$params = JComponentHelper::getParams ( 'com_users' );
		$useractivation = $params->get ( 'useractivation' );
		
		$post = JRequest::get ( 'post' );
	
	   
		$id = $this ->_modelpassenger ->store($post);
		/*
		* 1. Successfull
		*/
		if ($id !== false) {
		// Save successfull ->send email
			$mainframe->enqueueMessage ( JText::_ ( 'Successfully saved ' ), 'message' );
			//send email to client
			AImporter::helper('email');
			$mail=new EmailHelper();
			$mail->sendCustomtripChangepassenger($post['order_id']);
		 } else {
				$mainframe->enqueueMessage ( JText::_ ( 'Save failed' ), 'error' );
				}
		$mainframe->redirect ( JURI::base () . 'index.php?option=com_bookpro&view=customtrip&cid[]='.$post['order_id'] );
		
	}
	
	function cancel(){
		$mainframe = &JFactory::getApplication ();
		$post = JRequest::get ( 'post' );
		if ($post['id']) $mainframe->redirect ( JURI::base () . 'index.php?option=com_bookpro&view=customtrip&cid[]='.$post['order_id'] );
		 else
		$mainframe->redirect ( JURI::base () . 'index.php?option=com_bookpro&view=orders' );
		
	}
	/*
	 * 1. Successfull
	*/
	function removerPassenger(){
		
		$jinput = JFactory::getApplication ()->input;
		$passenger_id = array($jinput ->get('id'));		
		$order_id =$jinput ->get('order_id');
		$kt = $this ->_modelpassenger ->trash($passenger_id);
		$this ->setRedirect("index.php?option=com_bookpro&view=customtrip&cid[]=".$order_id);
	}
	/*
	 * 1. Successfull
	*/
	function sendusermanual(){
		$file = JRequest::getVar( 'manual_file', '', 'files', 'array' );
		echo "<pre>";
        var_dump($file);
        die();
	}
	
}
