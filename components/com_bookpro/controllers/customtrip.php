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
AImporter::model ( 'customer', 'order' );
AImporter::model ( 'customtrip' );
class BookProControllerCustomtrip extends JControllerLegacy {
	var $_modelCustomer;
	var $_modelCustomtrip;
	var $_modeOrder;
	function __construct($config = array()) {
		parent::__construct ( $config );
		if (! class_exists ( 'BookProModelCustomer' )) {
			AImporter::model ( 'customer' );
		}
		$this->_modelCustomer = new BookProModelCustomer ();
		$this->_modeOrder = new BookProModelOrder ();
		$this->_modelCustomtrip = new BookProModelCustomTrip ();
	}
	function test(){
		echo "ok cutomtrip";
		die();
	}
	/*
	 *
	 */
	function generateRandomPassword() {
		// Initialize the random password
		$password = '';
		
		// Initialize a random desired length
		$desired_length = rand ( 8, 12 );
		
		for($length = 0; $length < $desired_length; $length ++) {
			// Append a random ASCII character (including symbols)
			$password .= chr ( rand ( 32, 126 ) );
		}
		return $password;
	}
	/*
	 *
	 */
	function getCustomerInfor($arrPost) {
		$customer = array ();
		/*
		 * generate Username: email, password: gen
		 */
		$customer ['username'] = substr ( $arrPost ['email'], 0, strpos ( $arrPost ['email'], '@' ) );
		$customer ['password'] = $this->generateRandomPassword ();
		
		$customer ['email'] = $arrPost ['email'];
		$customer ['address'] = $arrPost ['address'];
		$customer ['telephone'] = $arrPost ['telephone'];
		$customer ['mobile'] = $arrPost ['mobile'];
		$customer ['firstname'] = $arrPost ['firstname'];
		
		$customer ['name'] = $arrPost ['name'];
		
		$customer ['gender'] = $arrPost ['gender'];
		$customer ['country_id'] = $arrPost ['country'];
		$customer ['id'] = $arrPost ['id'];
		$customer ['activation'] = $arrPost ['activation'];
		$customer ['block'] = $arrPost ['block'];
		return $customer;
	}
	/*
	 * user_id = customer_id
	 */
	function getCustomtrip($arrPost, $user_id) {
		$customtrip = array ();
		$customtrip ['type'] = "CUSTOMIZE";
		$notes;
		
		$notes .= "<b>Date of anticipated travel: </b> " . $arrPost ['traveldate'];
		$notes .= "<br><b>How many people are traveling ?:  </b> " . $arrPost ['number1'] . " Adults | " . $arrPost ['number1'] . " Child";
		$notes .= "<br><b>How many days do you plan to travel ?: </b> " . $arrPost ['day'];
		$notes .= "<br><b>Where would you like to start your trip ?: </b> " . $arrPost ['select01'] . " - " . $arrPost ['select02'];
		$notes .= "<br><b>Where would you like to end your trip ?: </b> " . $arrPost ['select001'] . " - " . $arrPost ['select002'];
		
		foreach ( ($this->_modelCustomtrip->getListCountries ()) as $key => $value ) {
			$notes .= "<br><b>Where would you like to visit in " . $value . " ?: </b> " . implode ( ", ", $arrPost [strtolower ( $value )] ) . "/Other: " . $arrPost ['other' . $key];
		}
		
		$notes .= "<br><b>What type of travel?: </b>". implode ( ", ", $arrPost ['traveltype']);
		$notes .= "<br><b>What modes of transportation do you prefer for in country travel ?: </b> ". implode ( ", ", $arrPost ['transport']);
		$notes .= "<br><b>What type of programme are you interested in ?: </b> ". implode ( ", ", $arrPost ['program']);
		$notes .= "<br><b>What type of accommodation do you prefer ?: </b> ". implode ( ", ", $arrPost ['hotel']);
		$notes .= "<br><b>Would you like us to include any meals in your itinerary ?: </b> ". implode ( ", ", $arrPost ['meal']);
		$notes .= "<br><b>What is your approximate budget per person for the trip?:  </b> ".$arrPost['budget']." /Other: ".$arrPost['other8'];
		
		$notes .= "<br><br><b>Other requirement: </b> " . $arrPost ['comment'];
		$notes .= "<br><b>How did you hear about us ?: </b> " . $arrPost ['search'];
		
		$customtrip ['notes'] = $notes;
		$customtrip ['user_id'] = $user_id;
		$customtrip ['pay_status']= 'PENDING';
		$customtrip ['order_status'] ='NEW';
		$customtrip ['pay_method'] = 'UNDEFINED';//change 16/4
		return $customtrip;
	}
	/*
	 * Created customer -> Exception : not login
	 */
	function createCustomer($customer) {
		// create user login ->return id
		// create customer
		// $userCreate = $model ->createUserSystem($customer);
		$customerinfor = $this->_modelCustomer->store ( $customer );
		echo '<pre>';
		var_dump ( $customerinfor );
	}
	function createOrder($customtrip) {
	}
	/*
	 * To add customtrip 1. Add customer infor 2. Add customtrip infor to order
	 */
	function addcustomtrip() {
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
		
		if ($user->id) {
			$this->_modelCustomer->setIdByUserId ();
			$post ['id'] = $this->_modelCustomer->getId ();
		} else
			$post ['id'] = 0;
		
		$post ['name'] = $post ['firstname'] . ' ' . $post ['lastname'];
		$isNew = $post ['id'] == 0;
		if (($useractivation == 1) || ($useractivation == 2)) {
			$post ['activation'] = JApplication::getHash ( JUserHelper::genRandomPassword () );
			$post ['block'] = 1;
		}
		/*
		 * Store customer -> Store new user -> store new customer
		 */
		$customer = $this->getCustomerInfor ( $post );
		$customerid = $this->_modelCustomer->store ( $customer );
		if ($customerid) {
			$customtrip = $this->getCustomtrip ( $post, $customerid );
			$customtripid = $this->_modeOrder->store ( $customtrip );
			$mainframe->enqueueMessage ( JText::_ ( 'Successfully saved ' ), 'message' );
			//Send email
			AImporter::helper('email');
			$mail=new EmailHelper();

			$mail->sendCustomtripEmail($customtripid,$customtrip);
			//is login
			if ($user->id)
			//$mainframe->redirect ( JURI::base () . 'index.php?option=com_bookpro&view=customtripinfor&order_id='.$customtripid );
			   $mainframe->redirect ( JURI::base () . 'index.php?option=com_bookpro&view=mailcustomtrip');
				else
				$mainframe->redirect ( JURI::base () . 'index.php?option=com_bookpro&view=mailcustomtrip');
		} else {
			$mainframe->enqueueMessage ( JText::_ ( 'Save failed' ), 'error' );
			$mainframe->redirect ( JURI::base () . 'index.php?');
		}
		}
		function ajaxcheckemail(){
			//get user id
			//get email;
			$respone_array= array();
			$input = JFactory::getApplication ();
			$input = $app->input;
			$email = $input->getString('email');
			$cModel = new BookProModelCustomer();
			if ( $cModel ->getUserSystemByEmail('dungphan307@gmail.com')){
				$respone_array[]= '6';
			}
			echo $respone_array[] ='5';
			echo json_encode($respone_array);
		}
}
