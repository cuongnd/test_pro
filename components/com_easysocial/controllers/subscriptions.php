<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'site:/controllers/controller' );

class EasySocialControllerSubscriptions extends EasySocialController
{

	/**
	 * subscription toggle.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function toggle()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user needs to be logged in.
		Foundry::requireLogin();

		$uid  	= JRequest::getInt('uid');
		$type 	= JRequest::getVar('type');
		$group  = JRequest::getVar('group', SOCIAL_APPS_GROUP_USER);
		$notify = JRequest::getVar('notify', '0');

		$my 		= Foundry::user();
		$view 		= Foundry::view( 'Subscriptions' , false );
		$subscribe  = Foundry::get( 'Subscriptions');

		$isFollowed	= $subscribe->isFollowing( $uid, $type, $group, $my->id );

		$verb		= $isFollowed ? 'unfollow' : 'follow';
		$state		= '';

		if( $isFollowed )
		{
			// unsubscribe user.
			$state = $subscribe->unfollow( $uid, $type, $group, $my->id );
		}
		else
		{
			$state = $subscribe->follow( $uid, $type, $group, $my->id, $notify );
		}

		if( !$state )
		{

			Foundry::logError( __FILE__ , __LINE__ , 'Subscription: Unable to ' . $verb . ' the stream item because of the error message ' . $subscribe->getError() );

			// Set the view with error
			$view->setMessage( $subscribe->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ , $verb );
		}

		return $view->call( __FUNCTION__ , $verb );

	}

	public function remove()
	{
		$sId		= JRequest::getVar( 'id' );

		if( empty($sId) )
		{
			Foundry::getInstance( 'View' , 'Subscriptions' , false )->setErrors( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_ID' ) );
			return Foundry::getInstance( 'View' , 'Subscriptions' , false )->remove();
		}


		$state 		= Foundry::get('Subscriptions')->remove($sId);

		if( ! $state )
		{
			Foundry::getInstance( 'View' , 'Subscriptions' , false )->setErrors( JText::_( 'COM_EASYSOCIAL_SUBSCRIPTION_FAILED_TO_UNSUBSCRIBE' ) );

			return Foundry::getInstance( 'View' , 'Subscriptions' , false )->remove();
		}

		return Foundry::getInstance( 'View' , 'Subscriptions' , false )->remove();

	}

	private function formKeys( $element , $group )
	{
		return $element . '.' . $group;
	}

}
