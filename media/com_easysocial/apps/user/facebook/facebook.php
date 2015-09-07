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

// Include apps interface.
Foundry::import( 'admin:/includes/apps/apps' );

/**
 * Facebook application for EasySocial.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUserAppFacebook extends SocialAppItem
{
	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Responsible to process cron items for oauth items
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onCronExecute()
	{
		// We'll temporarily disable this.
		return;

		$model 		= Foundry::model( 'OAuth' );

		// Load up facebook client
		$facebookClient 	= Foundry::oauth( 'facebook' );

		// Get a list of pullable items
		$oauthUsers			= $model->getPullableClients();

		if( !$oauthUsers )
		{
			return;
		}

		// Go through each of the pullable users
		foreach( $oauthUsers as $oauthUser )
		{
			// Simulate the user now by passing in their valid token.
			$facebookClient->setAccess( $oauthUser->token );

			// Get the stream items from Facebook
			$items 			= $facebookClient->pull();

// echo '<pre>';
// print_r( $items );
// echo '</pre>';
// exit;
			foreach( $items as $item )
			{
				// Store this into the stream now.
				$stream 		= Foundry::stream();

				// Get the stream template
				$template 		= $stream->getTemplate();
				$template->setActor( $oauthUser->uid , $oauthUser->type );
				$template->setContext( $item->get( 'id' ) , SOCIAL_TYPE_FACEBOOK );
				$template->setContent( $item->get( 'content' ) );
				$template->setVerb( 'update' );

				// Create the new stream item.
				$streamTable 		= $stream->add( $template );

				// Store into the stream assets table as the app needs this.
				$assets 			= Foundry::table( 'StreamAsset' );
				$assets->stream_id 	= $streamTable->id;
				$assets->type		= SOCIAL_TYPE_FACEBOOK;
				$assets->data 		= $item->toString();
				$assets->store();

				// Store into the import history.
				$history				= Foundry::table( 'OAuthHistory' );
				$history->remote_id		= $item->get( 'id' );
				$history->remote_type	= $item->get( 'type' );
				$history->local_id 		= $streamTable->id;
				$history->local_type 	= SOCIAL_TYPE_STREAM;
				$history->store();
			}

			// Update the last pulled item datetime.
			$oauthTable 	= Foundry::table( 'OAuth' );
			$oauthTable->bind( $oauthUser );

			$oauthTable->last_pulled	= Foundry::date()->toMySQL();
			$state 	= $oauthTable->store();

		}
	}

	/**
	 * Automatically pushes to Facebook when user creates a new story.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @param	SocialTableStreamItem	The stream item.
	 * @return
	 */
	public function onAfterStorySave( $stream , $streamItem )
	{
		// Get the current user's object.
		$my 	= Foundry::user();

		// Get the current user's oauth object.
		$oauth	= $my->getOAuth( SOCIAL_TYPE_FACEBOOK );

		if( !$oauth )
		{
			return;
		}

		// If user disabled this, we shouldn't push to the server.
		if( !$oauth->push )
		{
			return;
		}

		$photos = JRequest::getVar( 'photos' );
		$photo	= null;

		if( $photos )
		{
			// Get the first picture
			$photoId	= $photos[0];

			$photo 	= Foundry::table( 'Photo' );
			$photo->load( $photoId );
		}

		// Get the single stream object
		$streamObj 		= $stream->getItem( $streamItem->uid );
		$streamObj 		= $streamObj[0];

		$latitude 		= JRequest::getVar( 'locations_lat' , '' );
		$longitude 		= JRequest::getVar( 'locations_lng' , '' );
		$placeId 		= null;

		$client 	= Foundry::OAuth( 'Facebook' );
		$client->setAccess( $oauth->token );

		if( $latitude && $longitude )
		{
			$options 		= array( 'type' => 'place' , 'center' => $latitude . ',' . $longitude );

			$places 		= $client->api( '/search' , $options );

			// Get the first item
			$place 			= $places['data'][0];
			$placeId 		= $place[ 'id' ];
		}

		$content 	= strip_tags( $streamObj->content_raw );
		$id			= $client->push( $content , $placeId , $photo );

		// Once the push was successfull, we need to store this in the history table to prevent infinite loops.
		if( !$id )
		{
			return false;
		}

		// Store this into our history table to prevent infinite looping
		$history 	= Foundry::table( 'OAuthHistory' );
		$history->oauth_id		= $oauth->oauth_id;
		$history->remote_id		= $id;
		$history->remote_type	= 'status';
		$history->local_id 		= $streamItem->uid;
		$history->local_type 	= 'story';
		$history->store();
	}

	/**
	 * Trigger for onPrepareStream
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onPrepareStream( SocialStreamItem &$item, $includePrivacy = true )
	{
		// We only want to process related items
		if( $item->context != SOCIAL_TYPE_FACEBOOK )
		{
			return;
		}

		// Get current logged in user.
		$my         = Foundry::user();

		// Get user's privacy.
		$privacy 	= Foundry::privacy( $my->id );

		// Load the assets based on the stream id.
		$asset 		= Foundry::table( 'StreamAsset' );
		$asset->load( array( 'stream_id' => $item->uid ) );
		$params 	= Foundry::registry( $asset->data );

		// If "type" is not supplied, skip this
		if( !$params->get( 'type' ) ) 
		{
			return;
		}

		// var_dump( $params->toArray() );
		// Set the content
		$item->content 	= $this->formatMessage( $params );

		$this->set( 'item'	, $item );
		$this->set( 'params', $params );

		$file 		= 'streams/content.import.' . $params->get( 'type' );
		$contents 	= parent::display( $file );

		// Decoratre the stream item
		$item->display	= 'full';
		$item->title 	= parent::display( 'streams/title.import' );
		$item->content 	= $contents;
	}

	/**
	 * Formats the message
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function formatMessage( $params )
	{
		$withData	= $params->get( 'with_data' );

		if( $withData )
		{
			// dump( $withData );
		}

		$storyTags 	= $params->get( 'story_tags' );

		$message 	= $params->get( 'content' );

		if( $storyTags )
		{
			foreach( $storyTags as $tag )
			{
				$message 		= JString::substr_replace( $message , '<a href="' . $tag->link . '">' . $tag->name . '</a>', $tag->offset , $tag->length );
			}
		}

		return $message;
	}
}
