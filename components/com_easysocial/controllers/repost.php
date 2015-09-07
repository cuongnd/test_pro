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

class EasySocialControllerRepost extends EasySocialController
{

	/**
	 * Retrieves a list of user that shared a particular item
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getSharers()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// User needs to be logged in
		Foundry::requireLogin();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the current stream property.
		$id 		= JRequest::getInt( 'id' );
		$element 	= JRequest::getString( 'element' );

		// If id is invalid, throw an error.
		if( !$id || !$element)
		{
			//Internal error logging.
			Foundry::logError( __FILE__ , __LINE__ , 'Likes: Unable to process because id or element provided is invalid.' );

			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_ID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$model 		= Foundry::model( 'Repost' );
		$sharers	= $model->getRepostUsers( $id , $element , false );

		return $view->call( __FUNCTION__ , $sharers );
	}

	/**
	 * Toggle the likes on an object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param
	 * @return	string
	 */
	public function share()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// User needs to be logged in.
		Foundry::requireLogin();

		// Get the stream id.
		$id 		= JRequest::getInt( 'id' );
		$element 	= JRequest::getString( 'element' );
		$group 		= JRequest::getString( 'group', SOCIAL_APPS_GROUP_USER );
		$content 	= JRequest::getVar( 'content', '' );


		if( $content == JText::_( 'COM_EASYSOCIAL_REPOST_FORM_DIALOG_MSG') )
		{
			$content = '';
		}

		// Get the view.
		$view 	= $this->getCurrentView();

		// If id is invalid, throw an error.
		if( !$id || !$element)
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_ERROR_UNABLE_TO_LOCATE_ID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get current logged in user.
		$my 	= Foundry::user();

		// Load likes library.
		$share 	= Foundry::get( 'Repost', $id, $element, $group );
		$state  = $share->add( $my->id, $content );

		// If there's an error, log this down here.
		if( $state === false )
		{
			// Set the view with error
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_REPOST_ERROR_REPOSTING' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ , $id , $element, $group );
		}

		// now lets determine if we need to add the stream or not.
		$streamId = 0;
		if( $state !== true )
		{
			// this is an new share object.
			// lets add this share into stream.
			$stream				= Foundry::stream();
			$streamTemplate		= $stream->getTemplate();

			// Set the actor.
			$streamTemplate->setActor( $state->user_id , SOCIAL_TYPE_USER );

			// Set the context.
			$streamTemplate->setContext( $state->id , SOCIAL_TYPE_SHARE );

			// set the target. photo / stream
			$streamTemplate->setTarget( $id );

			// Set the verb.
			$streamTemplate->setVerb( 'add' . '.' . $element );

			$streamTemplate->setType( 'full' );


			$streamTemplate->setPublicStream( 'core.view' );


			// Create the stream data.
			$streamItem = $stream->add( $streamTemplate );
			$streamId = $streamItem->uid;
		}


		return $view->call( __FUNCTION__ , $id , $element, $group, $streamId );
	}
}
