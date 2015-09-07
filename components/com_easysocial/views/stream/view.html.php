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

// Necessary to import the custom view.
Foundry::import( 'site:/views/views' );

class EasySocialViewStream extends EasySocialSiteView
{
	/**
	 * Responsible to output a single stream item.
	 *
	 * @access	public
	 * @return	null
	 *
	 */
	public function item()
	{
		// Get the stream id from the request
		$id 	= JRequest::getInt( 'id' );

		if( !$id )
		{
			Foundry::info()->set( JText::_( 'COM_EASYSOCIAL_STREAM_INVALID_STREAM_ID' ) , SOCIAL_MSG_ERROR );

			return $this->redirect( FRoute::dashbaord( array() , false ) );
		}

		// Get the current logged in user.
		$user 	= Foundry::user();

		// Retrieve stream
		$streamLib 	= Foundry::stream();
		$stream		= $streamLib->getItem( $id );


		if( $stream === true || count( $stream ) <= 0 )
		{
			// this mean either the user do not have have permission to view the stream or user do not have the required app to generate the stream.
			$actor = $streamLib->getStreamActor( $id );

			$this->set( 'user' , $actor );
			parent::display( 'site/stream/restricted' );

			return;
		}

		$stream 	= $stream[0];

		$title		= strip_tags( $stream->title );

		// Set the page title
		Foundry::page()->title( $title );


		// Get stream actions
		$actions 	= $streamLib->getActions( $stream );

		$this->set( 'actions' , $actions );
		$this->set( 'user' 	, $user );
		$this->set( 'stream', $stream );

		echo parent::display( 'site/stream/item' );
	}
}
