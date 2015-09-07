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

Foundry::import( 'admin:/includes/themes/themes' );
Foundry::import( 'admin:/includes/apps/apps' );

class SocialSharesHelperAlbums extends SocialAppItem
{
	private $item 	= null;
	private $share 	= null;

	public function __construct( SocialStreamItem &$item, $share )
	{
		$this->item 	= $item;
		$this->share 	= $share;
	}

	public function getContent( $shareTextOnly = false )
	{
		$source 	= explode( '.', $this->share->element );
		$element 	= $source[0];
		$group 		= $source[1];

		$sharetext  = $this->share->content;
		$sourceId 	= $this->share->uid;

		$album = Foundry::table( 'Album' );
		$album->load( $sourceId );

		// Get user's privacy.
		$my         = Foundry::user();
		$privacy 	= Foundry::privacy( $my->id );

		if( !$privacy->validate( 'albums.view' , $album->id, SOCIAL_TYPE_ALBUM , $album->uid ) )
		{
			return false;
		}


		$theme 		= Foundry::get( 'Themes' );
		$theme->set( 'album' , $album );
		$theme->set( 'sharetext', $sharetext );
		$theme->set( 'textonly', $shareTextOnly);


		$html	= $theme->output( 'apps/user/shares/streams/albums/content' );

		return $html;
	}

	public function getTitle()
	{
		$actors = $this->item->actors;
		$names  = Foundry::string()->namesToStream( $actors, true, 3 );

		// Load the album
		$album = Foundry::table( 'Album' );
		$album->load( $this->share->uid );
		$albumCreator	= Foundry::user( $album->uid );

		$theme 		= Foundry::get( 'Themes' );
		$theme->set( 'names'		, $names );
		$theme->set( 'album' 	, $album );
		$theme->set( 'creator'	, $albumCreator );

		$html	= $theme->output( 'apps/user/shares/streams/albums/title' );

		return $html;
	}
}
