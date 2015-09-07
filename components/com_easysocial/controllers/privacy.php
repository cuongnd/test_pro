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

class EasySocialControllerPrivacy extends EasySocialController
{

	/**
	 * to hide the stream
	 *
	 * @since	1.0
	 * @access	public
	 * @param
	 * @return	string
	 */
	public function store()
	{
		Foundry::requireLogin();

		$post       = JRequest::get( 'POST' );
		$my         = Foundry::user();

		$state	= false;

		if( isset( $post[ 'privacy'] ) )
		{
			$model 	= Foundry::model( 'Privacy' );
			$state = $model->updatePrivacy( $my->id , $post[ 'privacy' ], 'user' );
		}


		if(! $state )
		{
			// Foundry::getInstance( 'View' , 'Privacy' , false )->setErrors( JText::_( 'COM_EASYSOCIAL_PRIVACY_UPDATE_FAILED' ) );

			Foundry::getInstance( 'Info' )->set( JText::_( 'COM_EASYSOCIAL_PRIVACY_UPDATE_FAILED' ) , 'error' );
			return Foundry::getInstance( 'View' , 'Privacy' , false )->display();
		}

		Foundry::getInstance( 'Info' )->set( JText::_( 'COM_EASYSOCIAL_PRIVACY_UPDATED' ) , 'success' );
		return Foundry::getInstance( 'View' , 'Privacy' , false )->display();

		exit;
	}



	/**
	 * to update privacy on an object by current logged in user
	 *
	 * @since	1.0
	 * @access	public
	 * @param
	 * @return	string
	 */
	public function update()
	{
		Foundry::checkToken();

		Foundry::requireLogin();

		$my = Foundry::user();

		// get data from form post.
		$uid 		= JRequest::getInt( 'uid' );
		$utype 		= JRequest::getVar( 'utype' );
		$value		= JRequest::getVar( 'value' );
		$pid		= JRequest::getVar( 'pid' );
		$customIds 	= JRequest::getVar( 'custom', '' );

		$view 	= Foundry::view( 'Privacy', false );

		// If id is invalid, throw an error.
		if( !$uid )
		{
			//Internal error logging.
			Foundry::logError( __FILE__ , __LINE__ , 'Privacy Log: Unable to update privacy on item because id provided is invalid.' );

			$view->setError( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_ID' ) );
			return $view->call( __FUNCTION__ );
		}

		$model = Foundry::model( 'Privacy' );
		$state = $model->update( $my->id, $pid, $uid, $utype, $value, $customIds );

		// If there's an error, log this down.
		if( !$state )
		{
			//Internal error logging.
			Foundry::logError( __FILE__ , __LINE__ , 'Privacy Log: Unable to update privacy on item because model returned the error, ' . $model->getError() );

			$view->setError( $model->getError() );

			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );

	}

	public function browse()
	{
		Foundry::checkToken();
		Foundry::requireLogin();

		$pid 		= JRequest::getInt( 'pid', 1);
		$pItemId 	= JRequest::getInt( 'pItemId', 0);
		$userIds 	= JRequest::getString( 'userIds', '');

		$users = array();
		if( $pItemId )
		{
			$model = Foundry::model( 'Privacy' );
			$users = $model->getPrivacyCustom( $pItemId, 'item' );
		}
		else if( empty( $pItemId ) && !empty( $userIds ) )
		{
			$tmpData = explode( ',', $userIds );
			foreach( $tmpData as $data )
			{
				if( !empty( $data ) )
				{
					$user = new stdClass();
					$user->user_id = $data;

					$users[] = $user;
				}
			}
		}

		$view 	= Foundry::view( 'Privacy', false );
		return $view->call( __FUNCTION__, $users );
	}

}
