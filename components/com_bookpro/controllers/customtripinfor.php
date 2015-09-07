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
AImporter::model ( 'passenger' );
class BookProControllerCustomtripinfor extends JControllerLegacy {
		var $_modelpassenger;
		
		function __construct($config = array()) {
			parent::__construct ( $config );
			
			$this->_modelpassenger= new BookProModelPassenger() ;
		}
    /*
     * Functional: Save passenger from customtrip infor
     */
	function savepassenger(){
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
		
		$passenger= array();
		// 
		foreach ($post['passenger'] as $key =>$value){
			$passenger = $value;
			$passenger['order_id'] = $post['order_id'];
			$passenger['birthday'] = $this ->changeDatefomat($passenger['birthday']);
			//Store passsenger
			$id = $this ->_modelpassenger ->store($passenger);
			//User id for...?
		}
		/*
		 * 1. Successfull
		*/
		if ($id !== false) {
			// Save successfull ->send email
			$mainframe->enqueueMessage ( JText::_ ( 'Successfully saved ' ), 'message' );
		} else {
			$mainframe->enqueueMessage ( JText::_ ( 'Save failed' ), 'error' );
		}
		$mainframe->redirect ( JURI::base () . 'index.php?option=com_bookpro&view=customtripinfor&order_id='.$post['order_id'] );
	}
	function changeDatefomat($date){
		return JFactory::getDate($date)->format('Y-m-d');
	}
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
		$post['birthday'] =$this ->changeDatefomat($post['birthday']);
		$post['passport_issue'] = $this ->changeDatefomat($post['passport_issue']);
		$post['passport_expiry'] = $this ->changeDatefomat($post['passport_expiry']);
		
		
		
		
	   
		$id = $this ->_modelpassenger ->store($post);
		/*
		* 1. Successfull
		*/
		if ($id !== false) {
		// Save successfull ->send email
			$mainframe->enqueueMessage ( JText::_ ( 'Successfully saved ' ), 'message' );
		} else {
					$mainframe->enqueueMessage ( JText::_ ( 'Save failed' ), 'error' );
					}
		$mainframe->redirect ( JURI::base () . 'index.php?option=com_bookpro&view=customtripinfor&order_id='.$post['order_id'] );
		
	}
	function removerPassenger(){
		$jinput = JFactory::getApplication ()->input;
		$passenger_id = array($jinput ->get('id'));		
		$order_id =$jinput ->get('order_id');
		$kt = $this ->_modelpassenger ->trash($passenger_id);
		$this ->setRedirect("index.php?option=com_bookpro&view=customtripinfor&order_id=".$order_id);
		}
}
