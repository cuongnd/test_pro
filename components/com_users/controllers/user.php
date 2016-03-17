<?php
require_once  JPATH_ROOT.'/libraries/facebook-php-sdk-v4-master/src/Facebook/autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;

/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Registration controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.6
 */
class UsersControllerUser extends UsersController
{
	/**
	 * Method to log in a user.
	 *
	 * @since   1.6
	 */
	public function login()
	{

		//JSession::checkToken('post') or jexit(JText::_('JInvalid_Token'));
		$app    = JFactory::getApplication();
		$input  = $app->input;
		$method = $input->getMethod();

		// Populate the data array:
		$data = array();

		$data['return']    = base64_decode($app->input->post->get('return', '', 'BASE64'));
		$data['username']  = $input->$method->get('username', '', 'USERNAME');
		$data['password']  = $input->$method->get('password', '', 'RAW');
		$data['secretkey'] = $input->$method->get('secretkey', '', 'RAW');


        // Set the return URL if empty.
		if (empty($data['return']))
		{
			$data['return'] = 'index.php?option=com_users&view=profile';
		}

		// Set the return URL in the user state to allow modification by plugins
		$app->setUserState('users.login.form.return', $data['return']);

		// Get the log in options.
		$options = array();
		$options['remember'] = $this->input->getBool('remember', false);
		$options['return']   = $data['return'];

		// Get the log in credentials.
		$credentials = array();
		$credentials['username']  = $data['username'];
		$credentials['password']  = $data['password'];
		$credentials['secretkey'] = $data['secretkey'];
		// Perform the log in.
		if (true === $app->login($credentials, $options))
		{

			// Success
			if ($options['remember'] = true)
			{
				$app->setUserState('rememberLogin', true);
			}

			$app->setUserState('users.login.form.data', array());
			$app->redirect(JRoute::_($app->getUserState('users.login.form.return'), false));
		}
		else
		{

			// Login failed !
			$data['remember'] = (int) $options['remember'];
			$app->setUserState('users.login.form.data', $data);
			$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
		}
	}
	public function ajax_set_key_of_params()
	{
		$app=JFactory::getApplication();
		$key_params=$app->input->get('key_params','','string');
		$value_key_params=$app->input->get('value_key_params','','string');
		$user=JFactory::getUser();
		$user->setParam($key_params,$value_key_params);
		$response=new stdClass();
		$response->e=0;
		if(!$user->save())
		{
			$response->e=1;
			$response->r=$user->getError();
		}else{
			$response->r="change params success";
		}
		echo json_encode($response);
		die;
	}
	public function ajax_get_list_group_user()
	{
		$app=JFactory::getApplication();
		$keyword=$app->input->get('keyword','','string');
		$db=JFactory::getDbo();
		$list_icon=JUtility::get_class_icon_font();
		$list_result=array();
		foreach($list_icon as $icon)
		{
			if(strpos($icon,$keyword)) {
				$item = new stdClass();
				$item->id = $icon;
				$item->text = $icon;
				$list_result[] = $item;
			}
		}
		header('Content-Type: application/json');
		echo json_encode($list_result,JSON_NUMERIC_CHECK);
		die;

	}


	/**
	 * Proxy for getModel
	 * @since   1.6
	 */
	public function getModel($name = 'login', $prefix = 'UsersModel', $config = array())
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
	public function aJaxGetFormLogin()
	{
		$modelLogin= $this->getModel();
		$view = &$this->getView('login', 'html', 'UsersView');
		$view->setModel($modelLogin , true );
		$view->display();
		$contents = ob_get_contents();
		ob_end_clean(); // get the callback function
		$respone_array[] = array(
			'key' => '.dialog_show_view_body',
			'contents' => $contents
		);
		echo json_encode($respone_array);
		exit();
	}
	/**
	 * Method to log out a user.
	 *
	 * @since   1.6
	 */
	public function logout()
	{
		JSession::checkToken('request') or jexit(JText::_('JInvalid_Token'));

		$app = JFactory::getApplication();

		// Perform the log in.
		$error  = $app->logout();
		$input  = $app->input;
		$method = $input->getMethod();

		// Check if the log out succeeded.
		if (!($error instanceof Exception))
		{
			// Get the return url from the request and validate that it is internal.
			$return = $input->$method->get('return', '', 'BASE64');
			$return = base64_decode($return);
			if (!JUri::isInternal($return))
			{
				$return = '';
			}

			// Redirect the user.
			$app->redirect(JRoute::_($return, false));
		}
		else
		{
			$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
		}
	}

	/**
	 * Method to register a user.
	 *
	 * @since   1.6
	 */
	public function register()
	{
		JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));

		// Get the application
		$app = JFactory::getApplication();

		// Get the form data.
		$data = $this->input->post->get('user', array(), 'array');

		// Get the model and validate the data.
		$model  = $this->getModel('Registration', 'UsersModel');
		$return	= $model->validate($data);

		// Check for errors.
		if ($return === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'notice');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'notice');
				}
			}

			// Save the data in the session.
			$app->setUserState('users.registration.form.data', $data);

			// Redirect back to the registration form.
			$this->setRedirect('index.php?option=com_users&view=registration');
			return false;
		}

		// Finish the registration.
		$return	= $model->register($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('users.registration.form.data', $data);

			// Redirect back to the registration form.
			$message = JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $model->getError());
			$this->setRedirect('index.php?option=com_users&view=registration', $message, 'error');
			return false;
		}

		// Flush the data from the session.
		$app->setUserState('users.registration.form.data', null);

		exit;
	}

	/**
	 * @throws Exception
     */
	public function facebook_login()
	{
		$session = JFactory::getSession();
		$user=$session->get('user');
		$user=JFactory::getUser();

		if($user->id!=0)
		{
			die('you are login');
		}
		$app = JFactory::getApplication();
		$input = $app->input;
		require_once JPATH_ROOT.'/components/com_users/helpers/facebook.php';
		$fb=Facebook_helper::get_facebook();
		$helper = $fb->getRedirectLoginHelper();

		try {
			$accessToken = $helper->getAccessToken();
		} catch (Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch (Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		try {
			// Returns a `Facebook\FacebookResponse` object
			$response = $fb->get('/me?fields=id,name,email', $accessToken->getValue());
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		if(!class_exists('JUserHelper')) require_once JPATH_ROOT.'/components/com_users/helpers/users.php';
		$facebook_user = $response->getGraphUser();


		$facebook_email=$facebook_user['email'];
		$user_by_email=JUserHelper::get_user_by_email($facebook_email);

		if(!$user_by_email)
		{
			$temp=new stdClass();
			$temp->id=0;
			$temp->useractivation=0;
			$temp->email1=$facebook_email;
			$temp->username=$facebook_email;
			$temp->name=$facebook_user['name'];
			$temp->password1=JUserHelper::genRandomPassword();

			// Finish the registration.
			$data=(array)$temp;
			$model_registration  = $this->getModel('Registration', 'UsersModel');
			$return	= $model_registration->register($data);

			// Check for errors.
			if ($return === false)
			{
				// Save the data in the session.
				$app->setUserState('users.registration.form.data', $data);

				// Redirect back to the registration form.
				$message = JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $model_registration->getError());
				die($message);
			}
			echo "heoosdfgsd";
		}else{
			$user=JFactory::getUser($user_by_email->id);

			$app    = JFactory::getApplication();
			$input  = $app->input;

			// Populate the data array:
			$data = array();

			$data['username']  =$user->username;
			$data['login_facebook']  =true;
			$data['secretkey'] = JSession::getFormToken();


			// Get the log in options.
			$options = array();
			$options['remember'] = true;

			// Get the log in credentials.
			$credentials = array();
			$credentials['username']  = $data['username'];
			$credentials['password']  = $data['password'];
			$credentials['secretkey'] = $data['secretkey'];
			$credentials['login_facebook'] = $data['login_facebook'];

			// Perform the log in.
			if (true === $app->login($credentials, $options))
			{
				// Success
				if ($options['remember'] = true)
				{
					$app->setUserState('rememberLogin', true);
				}

				$app->setUserState('users.login.form.data', array());
				$app->redirect(JUri::root().'index.php');
			}
			else
			{
				die('login error');
			}

		}
	}



	/**
	 * Method to login a user.
	 *
	 * @since   1.6
	 */
	public function remind()
	{
		// Check the request token.
		JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));

		$app   = JFactory::getApplication();
		$model = $this->getModel('User', 'UsersModel');
		$data  = $this->input->post->get('jform', array(), 'array');

		// Submit the username remind request.
		$return	= $model->processRemindRequest($data);

		// Check for a hard error.
		if ($return instanceof Exception)
		{
			// Get the error message to display.
			if ($app->get('error_reporting'))
			{
				$message = $return->getMessage();
			}
			else
			{
				$message = JText::_('COM_USERS_REMIND_REQUEST_ERROR');
			}

			// Get the route to the next page.
			$itemid = UsersHelperRoute::getRemindRoute();
			$itemid = $itemid !== null ? '&Itemid=' . $itemid : '';
			$route  = 'index.php?option=com_users&view=remind' . $itemid;

			// Go back to the complete form.
			$this->setRedirect(JRoute::_($route, false), $message, 'error');

			return false;
		}
		elseif ($return === false)
		{
			// Complete failed.
			// Get the route to the next page.
			$itemid = UsersHelperRoute::getRemindRoute();
			$itemid = $itemid !== null ? '&Itemid=' . $itemid : '';
			$route  = 'index.php?option=com_users&view=remind' . $itemid;

			// Go back to the complete form.
			$message = JText::sprintf('COM_USERS_REMIND_REQUEST_FAILED', $model->getError());
			$this->setRedirect(JRoute::_($route, false), $message, 'notice');

			return false;
		}
		else
		{
			// Complete succeeded.
			// Get the route to the next page.
			$itemid = UsersHelperRoute::getLoginRoute();
			$itemid = $itemid !== null ? '&Itemid=' . $itemid : '';
			$route	= 'index.php?option=com_users&view=login' . $itemid;

			// Proceed to the login form.
			$message = JText::_('COM_USERS_REMIND_REQUEST_SUCCESS');
			$this->setRedirect(JRoute::_($route, false), $message);

			return true;
		}
	}

	/**
	 * Method to login a user.
	 *
	 * @since   1.6
	 */
	public function resend()
	{
		// Check for request forgeries
		JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
	}
}
