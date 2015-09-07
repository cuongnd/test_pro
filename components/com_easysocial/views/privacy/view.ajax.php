<?php
/**
* @package		Social
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'site:/views/views' );

class EasySocialViewPrivacy extends EasySocialSiteView
{
	/**
	 * Returns an ajax chain.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The verb that we have performed.
	 */
	public function update()
	{
		// Load ajax lib
		$ajax	= Foundry::ajax();

		// Determine if there's any errors on the form.
		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		return $ajax->resolve();
	}


	public function browse( $users = array(), $options = array() )
	{
		$ajax = Foundry::ajax();

		// Get dialog
		$theme = Foundry::themes();
		$theme->set( 'options', $options );

		$friends = array();

		if( count( $users ) > 0 )
		{
			$arr = array();
			foreach( $users as $u )
			{
				$arr[] = $u->user_id;
			}

			// preload users.
			Foundry::user( $arr );

			foreach( $users as $u )
			{
				$friends[] = Foundry::user( $u->user_id );
			}
		}

		$theme->set( 'friends', $friends );

		$html = $theme->output( 'site/privacy/dialog.custom.form' );

		return $ajax->resolve( $html );

	}

	public function getfriends( $userid = '' )
	{
		// Check for valid tokens.
		Foundry::checkToken();

		// Only valid registered user has friends.
		Foundry::requireLogin();

		$query 		= JRequest::getVar( 'q' , '' );
		$uId 		= JRequest::getVar( 'userid', '' );
		$exclude 	= JRequest::getVar( 'exclude' );

		$ajax = Foundry::ajax();

		if( !$query )
		{
			$ajax->reject( JText::_( 'Empty query' ) );
			return $ajax->send();
		}


		if( empty( $userid ) )
		{
			$userid = $uId;
		}

		$my 	= Foundry::user( $userid );

		// Load friends model.
		$model 		= Foundry::model( 'Friends' );


		// Determine what type of string we should search for.
		$config 	= Foundry::config();
		$type 		= $config->get( 'users.displayName' );

		//check if we need to apply privacy or not.
		$options = array();

		if( $exclude )
		{
			$options[ 'exclude' ] = $exclude;
		}

		// Try to get the search result.
		$friends		= $model->search( $my->id , $query , $type, $options);

		$return 	= array();
		if( $friends )
		{
			foreach( $friends as $row )
			{
				$friend 		= new stdClass();
				$friend->id 	= $row->id;
				$friend->title 	= $row->getName();

				$return[] = $friend;
			}
		}

		return $ajax->resolve( $return );
	}

	public function getfriendsOld( $userid = '' )
	{

		$query 		= JRequest::getVar( 'q' , '' );
		$uId 		= JRequest::getVar( 'userid', '' );
		$exclude 	= JRequest::getVar( 'exclude', '' );

		if( empty( $userid ) )
		{
			$userid = $uId;
		}

		//$ajax 	= Foundry::getInstance( 'Ajax' );
		$ajax = Foundry::ajax();

		if( !$query )
		{
			$ajax->reject( JText::_( 'Empty query' ) );
			return $ajax->send();
		}

		$my 	= Foundry::user( $userid );

		$model 	 = Foundry::model( 'friends' );
		$friends = $model->getFriends( $my->id );

		$return 	= array();

		if( $friends )
		{
			foreach( $friends as $row )
			{
				$friend 		= new stdClass();
				$friend->id 	= $row->id;
				$friend->title 	= $row->getName();

				$return[] = $friend;
			}
		}

		// header('Content-type: text/x-json; UTF-8');
		// echo json_encode($return);
		// exit;

		return $ajax->resolve( $return );

	}

}
