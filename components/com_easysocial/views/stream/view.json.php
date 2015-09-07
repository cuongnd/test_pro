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

Foundry::import( 'site:/views/views' );

class EasySocialViewStream extends EasySocialSiteView
{
	public function display()
	{
		// Get the stream library
		$stream 	= Foundry::stream();
		$stream->get();

		$result 		= $stream->toArray();
		$data 			= new stdClass();
		$data->items 	= array();

		if( !$result )
		{
			$this->set( 'items' , array() );
		}

		// Set the url to this listing
		$data->url 			= FRoute::stream();

		// Follows the spec of http://activitystrea.ms/specs/json/1.0/
		foreach( $result as $row )
		{
			$item 				= new stdClass();

			// Set the stream title
			$item->title 		= $row->title;

			// Set the publish date
			$item->published 	= $row->created->toMySQL();

			// Set the generator
			$item->generator 		= new stdClass();
			$item->generator->url	= JURI::root();

			// Set the generator
			$item->provider 		= new stdClass();
			$item->provider->url	= JURI::root();

			// Set the verb
			$item->verb 		= $row->verb;

			// Set the actor
			$item->actor 				= new stdClass();
			$item->actor->url 			= $row->actor->getPermalink();
			$item->actor->objectType	= 'person';

			// Set actors image
			$item->actor->image 		= new stdClass();
			$item->actor->image->url	= $row->actor->getAvatar();
			$item->actor->image->width	= SOCIAL_AVATAR_MEDIUM_WIDTH;
			$item->actor->image->height	= SOCIAL_AVATAR_MEDIUM_HEIGHT;

			// Set the actors name
			$item->actor->displayName	= $row->actor->getName();

			$data->items[]	= $item;
		}
		// dump( $items );

		$this->set( 'data' , $data );

		parent::display();
	}
}
