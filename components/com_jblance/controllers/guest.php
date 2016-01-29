<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	controllers/guest.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.controller');

class JblanceControllerGuest extends JControllerLegacy {
	
	function __construct(){
		parent :: __construct();
	}
/**
 ==================================================================================================================
 SECTION : AJAX Requests
 1.checkUser - Check Username & Email (ajax)
 2.processFeed - Hide feed/activity
 3.searchSuggest - Search suggestion for University
 4.listempaddressdata - Load address data
 5.Dashboard Decide
 ==================================================================================================================
 */	
	//1.Check Username & Email (ajax)
	function checkUser(){
		$app  = JFactory::getApplication();
		$db 	  =& JFactory::getDBO();
		$inputstr = $app->input->get('inputstr', '', 'string');
		$name 	  = $app->input->get('name', '', 'string');
	
		if($name == 'username'){
			$sql 	  = "SELECT COUNT(*) FROM #__users WHERE username='$inputstr'";
			$msg = 'COM_JBLANCE_USERNAME_EXISTS';
		}
		elseif($name == 'email'){
			$sql 	  = "SELECT COUNT(*) FROM #__users WHERE email='$inputstr'";
			$msg = 'COM_JBLANCE_EMAIL_EXISTS';
		}
	
		$db->setQuery($sql);
		if($db->loadResult()){
			echo JText::sprintf($msg, $inputstr);
		}
		else {
			echo 'OK';
		}
		exit;
	}
	
/**
 ==================================================================================================================
 SECTION : Registration & Login
 ==================================================================================================================
 */
	//1. grabUsergroupInfo
	function grabUsergroupInfo(){
		$app 	=& JFactory::getApplication();
		$user 	=& JFactory::getUser();
		$ugid 	= $app->input->get('ugid', 0, 'int');
		$Itemid = $app->input->get('Itemid', 0, 'int');
	
		$session =& JFactory::getSession();
		$session->set('ugid', $ugid, 'register');
	
		$freeMode = JblanceHelper::isFreeMode($ugid);
	
		if($freeMode){
			// if the user is not registered, direct him to registration page else to profile page.
			if($user->id == 0)
				$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=register&step=3', false);
			else
				$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=usergroupfield', false);
	
			$app->redirect($return);
			return;
		}
		else {
			$return	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd&Itemid='.$Itemid.'&step=2', false);
			$app->redirect($return);
			return;
		}
	}
	
	function grabPlanInfo(){
		$app 	=& JFactory::getApplication();
		//$db		=& JFactory::getDBO();
		$user 	=& JFactory::getUser();
		$post   = JRequest::get('post');
		$Itemid = $app->input->get('Itemid', 0, 'int');
	
		$session =& JFactory::getSession();
		$session->set('planid', $post['plan_id'], 'register');
		$session->set('gateway', $post['gateway'], 'register');
		$session->set('planChosen', $post, 'register');
	
		// if the user is not registered, direct him to registration page else to profile page.
		if($user->id == 0){
			$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=register&Itemid='.$Itemid.'&step=3', false);
		}
		else {
			$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=usergroupfield', false);
		}
		/* else {
			//skip the user group field layout in case the profile integration is not JoomBri
			$profileInteg = JblanceHelper::getProfile();
			if(!($profileInteg instanceof JoombriProfileJoombri)){
				//$url = $profileInteg->getEditURL();
				//if ($url) $app->redirect($url);
				//echo 'came here';exit;
				$return = JRoute::_('index.php?option=com_jblance&task=guest.saveusernew&'.JSession::getFormToken().'=1', false);
			}
			else {
				$return = JRoute::_('index.php?option=com_jblance&view=guest&layout=usergroupfield', false);
			}
		} */
	
		$app->redirect($return);
		return;
	}
	
	function grabUserAccountInfo(){
		$app =& JFactory::getApplication();
		$session =& JFactory::getSession();
		$Itemid = $app->input->get('Itemid', 0, 'int');
		$post = JRequest::get('post');
		
		$session->set('userInfo', $post, 'register');
		$link = JRoute::_('index.php?option=com_jblance&view=guest&layout=usergroupfield&Itemid='.$Itemid.'&step=4', false);
		$this->setRedirect($link);
		return false;
	}
	
	//1.Save new Employer
	function saveUserNew(){
		// Check for request forgeries
		JSession::checkToken('request') or jexit('Invalid Token');
	
		$app =& JFactory::getApplication();
		$jbmail = JblanceHelper::get('helper.email');		// create an instance of the class EmailHelper
	
		//get the user info from the session
		$session 	=& JFactory::getSession();
		$userInfo 	= $session->get('userInfo', null, 'register');
		$ugid 		= $session->get('ugid', null, 'register');
		$gateway 	= $session->get('gateway', '', 'register');
		//$session->clear('id', 'upgsubscr');
	
		$user =& JFactory::getUser();
		$post = JRequest::get('post');
	
		//if the user is already registered and setting his profile to be JoomBri, then ignore the steps below.
		if($user->id == 0){
			$userInfo['name'] = (!empty($userInfo['firstname'])) ? $userInfo['firstname'] : null;
			$userInfo['name'] .= (!empty($userInfo['name']) && !empty($userInfo['lastname'])) ? ' '.$userInfo['lastname'] : null;
	
			// Get required system objects
			$usern = clone(JFactory::getUser());
			$pathway =& $app->getPathway();
			$authorize =& JFactory::getACL();
			$document =& JFactory::getDocument();
	
			// If user registration is not allowed, show 403 not authorized.
			$usersConfig =& JComponentHelper::getParams('com_users');
			if($usersConfig->get('allowUserRegistration') == '0'){
				JError::raiseError(403, JText::_('COM_JBLANCE_ACCESS_FORBIDDEN'));
				return;
			}
	
			/* // Initialize new usertype setting
			$newUsertype = $usersConfig->get('new_usertype');
			if(!$newUsertype){
				$newUsertype = 'Registered';
			} */
	
			// Bind the post array to the user object
			if(!$usern->bind($userInfo, 'usertype')) {
				JError::raiseError(500, $usern->getError());
			}
	
			// Set some initial user values
			$usern->set('id', 0);
			
			//get the Joombri user group information
			$usergroup	=& JTable::getInstance('usergroup', 'Table');
			$usergroup->load($ugid);
			$jbrequireApproval = $usergroup->approval;
			$joomlaUserGroup = $usergroup->joomla_ug_id;
			
			$defaultUserGroup = explode(',', $joomlaUserGroup);
	
			//$defaultUserGroup = $usersConfig->get('new_usertype', 2);
			$usern->set('usertype', 'deprecated');
			//$usern->set('groups', array($defaultUserGroup));
			$usern->set('groups', $defaultUserGroup);
	
			$date =& JFactory::getDate();
			$usern->set('registerDate', $date->toSql());
			
			$jAdminApproval = ($usersConfig->get('useractivation') == '2') ? 1 : 0;	//require Joomla Admin approval
			
			$requireApproval = $jbrequireApproval | $jAdminApproval;	//approval is required either JoomBri or Joomla require approval
			
			if($requireApproval)
				$usern->set('block', '1');
	
			// If user activation is turned on, we need to set the activation information
			$useractivation = $usersConfig->get('useractivation');
			if(($useractivation == 1 || $useractivation == 2) && !$requireApproval){
				jimport('joomla.user.helper');
				$usern->set('activation', JApplication::getHash(JUserHelper::genRandomPassword()));
				$usern->set('block', '1');
			}
	
			// If there was an error with registration, set the message and display form
			if(!$usern->save()){
				$msg = JText::_($usern->getError());
				$link = JRoute::_('index.php?option=com_jblance&view=guest&layout=register');
				$this->setRedirect($link, $msg);
				return false;
			}
	
			$userid = $usern->id;
		}
		else {
			$userid = $user->id;
		}
	
		// Initialize variables
		$db		=& JFactory::getDBO();
		$row	=& JTable::getInstance('jbuser', 'Table');
		$row->user_id = $userid;
		$row->ug_id = $ugid;
		//$row->biz_name = isset($userInfo['biz_name']) ? $userInfo['biz_name'] : '';
		
		$id_category 	= $app->input->get('id_category', '', 'array');
		if(count($id_category) > 0 && !(count($id_category) == 1 && empty($id_category[0]))){
			$proj_categ = implode(',', $id_category);
		}
		elseif($id_category[0] == 0){
			$proj_categ = 0;
		}
		$post['id_category'] = $proj_categ;
	
		if(!$row->save($post)){
			JError::raiseError(500, $row->getError());
		}
	
		$fields = JblanceHelper::get('helper.fields');		// create an instance of the class fieldsHelper
		$fields->saveFieldValues('profile', $row->user_id, $post);
		
		//insert the user to notify table
		$obj = new stdClass();
		$obj->user_id = $userid;
		$db->insertObject('#__jblance_notify', $obj);
	
		// Send registration confirmation mail only to new registered user
		if($user->id == 0){
			$password = $userInfo['password2'];
			$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password); //Disallow control chars in the email
			
			$jbmail->sendRegistrationMail($usern, $password);
	
			if($requireApproval){
				$msg = JText::_('COM_JBLANCE_ACCOUNT_HAS_BEEN_CREATED_NEED_ADMIN_APPROVAL');
			}
			else {
				if($useractivation){
					$msg = JText::_('COM_JBLANCE_ACCOUNT_HAS_BEEN_CREATED_NEED_ACTIVATION');
				}
				else {
					$msg = JText::_('COM_JBLANCE_ACCOUNT_HAS_BEEN_CREATED_PLEASE_LOGIN');
				}
			}
		}
		else {
			$msg = JText::_('COM_JBLANCE_YOUR_PROFILE_HAS_BEEN_SUCCESSFULLY_CREATED');
		}
	
		$freeMode = JblanceHelper::isFreeMode($ugid);
		if(!$freeMode){
			include_once(JPATH_COMPONENT.'/controllers/membership.php');
			JblanceControllerMembership::addSubscription($userid);	//add user to the subscription Table
			$subscrid = $app->input->get('returnrowid', 0, 'int');	//this returnid is the subscr id from plan_subscr table
			$session =& JFactory::getSession();
			$session->set('id', $subscrid, 'upgsubscr');

			if($gateway == 'banktransfer'){
				//send alert to admin and user
				$jbmail->alertAdminSubscr($subscrid, $userid);
				$jbmail->alertUserSubscr($subscrid, $userid);
			}
			$app->enqueueMessage(JText::_('COM_JBLANCE_PROCEED_PAYMENT_AFTER_REGISTRATION'));
			$link = JRoute::_('index.php?option=com_jblance&view=membership&layout=check_out&type=plan', false);
		}
		else {
			$link = JRoute::_('index.php?option=com_jblance', false);
		}
	
		//clear the session variable of 'register'
		$session->clear('ugid', 'register');
		$session->clear('planid', 'register');
		$session->clear('gateway', 'register');
		$session->clear('userInfo', 'register');
	
		$this->setRedirect($link, $msg);
	}
	
	/* Misc Functions */
	
}