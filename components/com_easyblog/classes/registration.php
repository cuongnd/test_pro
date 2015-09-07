<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class ERegistration
{

	function ERegistration()
	{
		//constructor
	}

	function validate($options = array())
	{
		$db = EasyBlogHelper::db();

		$returnStr  = '';
		$query 		= '';

		foreach($options as $opt => $val)
		{
			$query  = 'SELECT COUNT(1) FROM `#__users` WHERE UPPER(' . $db->nameQuote($opt) . ') = UPPER(' . $db->Quote($val) . ')';
			$db->setQuery($query);

			$result = $db->loadResult();

			if($result > 0)
			{
				switch(strtolower($opt))
				{
					case 'username':
						$returnStr  = JText::sprintf('COM_EASYBLOG_REGISTRATION_USERNAME_ALREADY_EXISTS', $val);
						break;
					case 'email':
						$returnStr  = JText::sprintf('COM_EASYBLOG_REGISTRATION_EMAIL_ALREADY_EXISTS', $val);
						break;
					default:
						$returnStr  = JText::sprintf('COM_EASYBLOG_REGISTRATION_SOMETHING_ALREADY_EXISTS', $opt);
						break;
				}

				return $returnStr;
			}
		}

		return true;
	}

	function addUser16( $values , $source = 'subscribe' )
	{
		$config			= EasyBlogHelper::getConfig();
		$usersConfig	= JComponentHelper::getParams( 'com_users' );

		$canRegister    = ($source == 'comment') ?   $config->get('comment_registeroncomment', 0) :  $config->get('main_registeronsubscribe', 0);
		if (($usersConfig->get('allowUserRegistration') == '0') || (!$canRegister))
		{
			return JText::_('COM_EASYBLOG_REGISTRATION_DISABLED');
		}

		$username	= $values['username'];
		$email 		= $values['email'];
		$fullname 	= $values['fullname'];


		$mainframe  = JFactory::getApplication();
		$jConfig	= EasyBlogHelper::getJConfig();
		$authorize	= JFactory::getACL();
		$document	= JFactory::getDocument();

		$user 	      = new JUser();
		//$pathway 	      = & $mainframe->getPathway();

		$newUsertype = $usersConfig->get( 'new_usertype' );
		if (!$newUsertype)
		{
			$newUsertype = 'Registered';
		}

		$pwdClear	= $username . '123';

		$userArr = array(
						'username'	=> $username,
						'name'		=> $fullname,
						'email'		=> $email,
						'password'	=> $pwdClear,
						'password2'	=> $pwdClear,
						'gid'       => '0',
						'groups'	=> array( $usersConfig->get('new_usertype', 2) ),
						'id'        => '0'
					);

		if (!$user->bind( $userArr ))
		{
			return $user->getError();
		}


		//check if user require to activate the acct
		$useractivation = $usersConfig->get( 'useractivation' );
		if ($useractivation == '1')
		{
			jimport('joomla.user.helper');
			$user->set('activation', md5( JUserHelper::genRandomPassword()) );
			$user->set('block', '1');
		}

		JPluginHelper::importPlugin('user');

		$user->save();

		// Send registration confirmation mail
		$password = $pwdClear;
		$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password); //Disallow control chars in the email

		//load com_user language file
		$lang = JFactory::getLanguage();
		$lang->load('com_users');

		//UserController::_sendMail($user, $password);

		return $user->id;
	}

	function addUser( $values, $source = 'subscribe' )
	{
		$userComponent  = ( EasyBlogHelper::getJoomlaVersion() >= '1.6' ) ? 'com_users' : 'com_user';

		$config			= EasyBlogHelper::getConfig();
		$usersConfig	= JComponentHelper::getParams( $userComponent );


		$canRegister    = ($source == 'comment') ?   $config->get('comment_registeroncomment', 0) :  $config->get('main_registeronsubscribe', 0);
		if (($usersConfig->get('allowUserRegistration') == '0') || (!$canRegister))
		{
			return JText::_('COM_EASYBLOG_REGISTRATION_DISABLED');
		}

		$username	= $values['username'];
		$email 		= $values['email'];
		$fullname 	= $values['fullname'];


		$mainframe  = JFactory::getApplication();
		$jConfig	= EasyBlogHelper::getJConfig();
		$authorize	= JFactory::getACL();
		$document	= JFactory::getDocument();

		$user 	      = clone(JFactory::getUser());
		$pwdClear	= $username . '123';

		if( EasyBlogHelper::getJoomlaVersion() >= '1.6' )
		{
			$newUsertype = $usersConfig->get( 'new_usertype', 2 );
			$userArr = array(
							'username'	=> $username,
							'name'		=> $fullname,
							'email'		=> $email,
							'password'	=> $pwdClear,
							'password2'	=> $pwdClear,
							'groups'	=> array( $newUsertype ),
							'gid'       => '0',
							'id'        => '0'
						);

			if (!$user->bind( $userArr, 'usertype' ))
			{
				return $user->getError();
			}
		}
		else
		{
			$newUsertype = $usersConfig->get( 'new_usertype' );

			if (!$newUsertype)
			{
				$newUsertype = 'Registered';
			}

			$userArr = array(
							'username'	=> $username,
							'name'		=> $fullname,
							'email'		=> $email,
							'password'	=> $pwdClear,
							'password2'	=> $pwdClear,
							'gid'       => '0',
							'id'        => '0'
						);

			if (!$user->bind( $userArr, 'usertype' ))
			{
				return $user->getError();
			}

			// Initialize user default values
			$user->set('id', 0);
			$user->set('usertype', $newUsertype );
			$user->set('gid', $authorize->get_group_id( '', $newUsertype, 'ARO' ));
		}



		$date = EasyBlogHelper::getDate();
		$user->set('registerDate', $date->toMySQL());

		//check if user require to activate the acct
		$useractivation = $usersConfig->get( 'useractivation' );

		if( $useractivation == '1' || $useractivation == '2' )
		{
			jimport('joomla.user.helper');
			$user->set('activation', md5( JUserHelper::genRandomPassword()) );
			$user->set('block', '1');
		}


		JPluginHelper::importPlugin('user');
		$user->save();

		// Send registration confirmation mail
		$password = $pwdClear;
		$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password); //Disallow control chars in the email

		//load com_user language file
		$lang = JFactory::getLanguage();
		$lang->load( $userComponent );

		// Get the user id.
		$userId 	= $user->id;

		$this->sendMail( $user , $password );

		return $userId;
	}

	private function sendMail(&$user, $password)
	{
		$userComponent  = ( EasyBlogHelper::getJoomlaVersion() >= '1.6' ) ? 'com_users' : 'com_user';

		$mainframe	= JFactory::getApplication();
		$db			= EasyBlogHelper::db();
		$name 		= $user->get('name');
		$email 		= $user->get('email');
		$username 	= $user->get('username');

		$usersConfig 	= JComponentHelper::getParams( $userComponent );
		$sitename 		= $mainframe->getCfg( 'sitename' );
		$useractivation = $usersConfig->get( 'useractivation' );
		$mailfrom 		= $mainframe->getCfg( 'mailfrom' );
		$fromname 		= $mainframe->getCfg( 'fromname' );
		$siteURL		= JURI::base();

		$subject 	= JText::sprintf( 'COM_EASYBLOG_REGISTER_MAIL_ACCOUNT_DETAILS', $name, $sitename );
		$subject 	= html_entity_decode($subject, ENT_QUOTES);

		if ( $useractivation == 1 )
		{
			$task   = '';
			$key    = '';

			if( EasyBlogHelper::getJoomlaVersion() >= '1.6' )
			{
				$task	= 'registration.activate';
				$key    = 'token';
			}
			else
			{
				$task	= 'activate';
				$key    = 'activation';
			}
			$message = sprintf ( JText::_( 'COM_EASYBLOG_REGISTER_MAIL_ACTIVATE' ), $name, $sitename, $siteURL."index.php?option=".$userComponent."&task=".$task."&".$key."=".$user->get('activation'), $siteURL, $username, $password);
		}
		else
		{
			$message = sprintf ( JText::_( 'COM_EASYBLOG_REGISTER_MAIL' ), $name, $sitename, $siteURL, $username, $password);
		}

		$message = html_entity_decode($message, ENT_QUOTES);

		$ids 	= EasyBlogHelper::getSAUsersIds();
		$rows 	= array();

		foreach( $ids as $id )
		{
			$row	= new stdClass();

			$user 		= JFactory::getUser( $id );
			$row->name 	= $user->name;
			$row->email	= $user->email;
			$row->sendEmail = $user->sendEmail;

			$rows[] 	= $row;
		}

		// Send email to user
		if ( ! $mailfrom  || ! $fromname ) {
			$fromname = $rows[0]->name;
			$mailfrom = $rows[0]->email;
		}

		if( EasyBlogHelper::getJoomlaVersion() >= '3.0' )
		{
			$mail 	= JMail::getInstance();
			$mail->sendMail($mailfrom, $fromname, $email, $subject, $message, true);
		}
		else
		{
			JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);
		}
		

		// Send notification to all administrators
		$subject2 = sprintf ( JText::_( 'Account details for' ), $name, $sitename);
		$subject2 = html_entity_decode($subject2, ENT_QUOTES);

		// get superadministrators id
		foreach ( $rows as $row )
		{
			if ($row->sendEmail)
			{
				$message2 = sprintf ( JText::_( 'COM_EASYBLOG_REGISTER_MAIL_ADMIN' ), $row->name, $sitename, $name, $email, $username);
				$message2 = html_entity_decode($message2, ENT_QUOTES);

				if( EasyBlogHelper::getJoomlaVersion() >= '3.0' )
				{
					$mail 	= JMail::getInstance();
					$mail->sendMail($mailfrom, $fromname, $row->email, $subject2, $message2 , true );
				}
				else
				{
					JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2);
				}
			}
		}
	}
}
