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

class EasySocialViewComments extends EasySocialSiteView
{
	/**
	 * Post process after comment is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableComments	The comment table object.
	 */
	public function save( $comment = null )
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve( $comment->renderHTML() );
	}

	public function update( $comment = null )
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve( $comment->getComment() );
	}

	public function load( $comments = null )
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$htmls = array();

		foreach( $comments as $comment )
		{
			if( !$comment instanceof SocialTableComments )
			{
				continue;
			}

			$htmls[] = $comment->renderHTML();
		}

		return $ajax->resolve( $htmls );
	}

	public function like( $likes = null )
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$hasLiked = $likes->hasLiked();
		$likeCount = $likes->getCount();
		$likesText = $likes->toString( null, true );

		return $ajax->resolve( $hasLiked, $likeCount, $likesText );
	}

	public function likedUsers( $html = null )
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve( $html );
	}

	public function likesText( $string = null )
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve( $string );
	}

	public function delete()
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve();
	}

	public function getRawComment( $comment = null )
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve( $comment );
	}

	public function getUpdates( $data = null )
	{
		Foundry::ajax()->resolve( $data );
	}

	public function confirmDelete()
	{
		$theme = Foundry::themes();

		$dialog = $theme->output( 'site/comments/dialog.delete' );

		Foundry::ajax()->resolve( $dialog );
	}
}
