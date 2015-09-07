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

class SocialSharesHelperStream
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
		$preview    = "";
		$content 	= "";
		$title 		= "";

		if( !$shareTextOnly )
		{
			$stream = Foundry::stream();
			$data 	= $stream->getItem( $this->share->uid );

			if( $data !== true && !empty( $data ) )
			{
				$title 		= $data[0]->title;
				$content 	= $data[0]->content;

				if( isset( $data[0]->preview ) && $data[0]->preview )
				{
					$preview = $data[0]->preview;
				}
			}
		}

		$theme 		= Foundry::get( 'Themes' );
		$theme->set( 'content' , $content );
		$theme->set( 'preview', $preview);
		$theme->set( 'title' , $title );
		$theme->set( 'sharetext', $sharetext );

		$html	= $theme->output( 'apps/user/shares/streams/preview' );

		return $html;
	}

	public function getTitle()
	{
		$actors = $this->item->actors;

		$names 	= Foundry::string()->namesToStream( $actors, true , 3 );

		// Get the source id
		$sourceId = $this->share->uid;

		// Load the stream
		$stream		= Foundry::table( 'Stream' );
		$stream->load( $sourceId );

		if( ! $stream->id )
		{
			return '';
		}

		// Get the target user.
		$target		= Foundry::user( $stream->actor_id );

		// Determine if I am reposting my own stuffs
		$isMe 		= false;

		$theme 		= Foundry::get( 'Themes' );
		$theme->set( 'names'	, $names );
		$theme->set( 'stream' 	, $stream );
		$theme->set( 'target'	, $target );

		$title		= $theme->output( 'apps/user/shares/streams/stream/title' );

		return $title;
	}


}
