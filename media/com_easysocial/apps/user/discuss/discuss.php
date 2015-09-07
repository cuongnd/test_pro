<?php
/**
* @package		%PACKAGE%
* @subpackge	%SUBPACKAGE%
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'admin:/includes/apps/apps' );

require_once( dirname( __FILE__  ) . '/helper.php' );

/**
 * EasyDiscuss Application for EasySocial
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUserAppDiscuss extends SocialAppItem
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

	public function exists()
	{
		$file 	= JPATH_ROOT . '/components/com_easydiscuss/helpers/helper.php';

		if( !JFile::exists( $file ) )
		{
			return false;
		}

		require_once( $file );

		return true;
	}

	/**
	 * Prepares the stream item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStreamItem	The stream object.
	 * @param	bool				Determines if we should respect the privacy
	 */
	public function onPrepareStream( SocialStreamItem &$item, $includePrivacy = true )
	{
		if( $item->context != 'discuss' )
		{
			return;
		}

		if( !$this->exists() )
		{
			return;
		}

		// Attach our own stylesheet
		$this->getApp()->loadCss();

		// Determine the current action
		$verb 	= $item->verb;
		$method = $verb . 'Stream';

		if( !method_exists( $this , $method ) )
		{
			return;
		}

		// Define standard stream looks
		$item->display 	= SOCIAL_STREAM_DISPLAY_FULL;
		$item->color 	= '#589f64';

		$this->$method( $item );
	}

	/**
	 * Generates the activity stream for new discussion
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function createStream( SocialStreamItem &$item )
	{
		$actor 		= $item->actor;

		$params 	= array_values( $item->contextParams );
		$data 		= Foundry::json()->decode( $params[ 0 ] );

		$post 		= DiscussHelper::getTable( 'Post' );
		$post->bind( $data );

		// Get the category
		$category 	= $data->cat;

		$permalink 		= SocialDiscussHelper::getPermalink( $post->id );
		$catPermalink	= SocialDiscussHelper::getCategoryPermalink( $category->id );

		// Remove code blocks from the content
		$post->content 		= EasyDiscussParser::removeCodes( $post->content );
		$post->content_raw	= $post->content;

		$post->content 		= DiscussHelper::formatContent( $post );

		$this->set( 'catPermalink'	, $catPermalink );
		$this->set( 'permalink'		, $permalink );
		$this->set( 'post'			, $post );
		$this->set( 'category' 		, $category );
		$this->set( 'actor' 		, $actor );

		$item->title 	= parent::display( 'streams/create.title' );
		$item->content 	= parent::display( 'streams/create.content' );
	}

	/**
	 * Generates the activity stream when a reply is marked as answer.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStreamItem
	 * @return	
	 */
	public function acceptedStream( SocialStreamItem &$item )
	{
		$actor 		= $item->actor;

		$params 	= array_values( $item->contextParams );
		$obj 		= Foundry::json()->decode( $params[ 0 ] );
		$post 		= $obj->post;
		$question 	= $obj->question;

		$type 		= $post->id == $question->id ? 'question' : 'reply';
		
		$permalink 	= SocialDiscussHelper::getPermalink( $question->id ) . '#answer';

		$this->set( 'permalink' , $permalink );
		$this->set( 'type'		, $type );
		$this->set( 'post'		, $post );
		$this->set( 'actor' 	, $actor );
		$this->set( 'question'	, $question );

		$item->title 	= parent::display( 'streams/accepted.title' );
		$item->content 	= parent::display( 'streams/accepted.content' );
	}

	public function replyStream( SocialStreamItem &$item )
	{
		$actor 		= $item->actor;

		$params 	= array_values( $item->contextParams );
		$obj 		= Foundry::json()->decode( $params[ 0 ] );
		$post 		= $obj->post;
		$question 	= $obj->question;

		$type 		= $post->id == $question->id ? 'question' : 'reply';
		
		$permalink 		= SocialDiscussHelper::getPermalink( $question->id );

		// Remove code blocks from the content
		$post->content 		= EasyDiscussParser::removeCodes( $post->content );
		$post->content_raw	= $post->content;

		$post->content 		= DiscussHelper::formatContent( $post );


		$this->set( 'permalink' , $permalink );
		$this->set( 'type'		, $type );
		$this->set( 'post'		, $post );
		$this->set( 'actor' 	, $actor );
		$this->set( 'question'	, $question );

		$item->title 	= parent::display( 'streams/reply.title' );
		$item->content 	= parent::display( 'streams/reply.content' );
	}

	public function commentStream( SocialStreamItem &$item )
	{
		$actor 		= $item->actor;

		$params 	= array_values( $item->contextParams );
		$obj 		= Foundry::json()->decode( $params[ 0 ] );

		$post 		= $obj->post;
		$comment 	= $obj->comment;
		$question 	= $obj->question;

		$type 		= $post->id == $question->id ? 'question' : 'reply';
		
		$permalink 	= SocialDiscussHelper::getPermalink( $question->id );

		if( $type == 'reply' )
		{
			$permalink	 .= '#reply-' . $post->id;
		}

		$this->set( 'permalink' , $permalink );
		$this->set( 'type'		, $type );
		$this->set( 'post'		, $post );
		$this->set( 'actor' 	, $actor );
		$this->set( 'question'	, $question );
		$this->set( 'comment'	, $comment );

		$item->title 	= parent::display( 'streams/comment.title' );
		$item->content 	= parent::display( 'streams/comment.content' );
	}

	public function favouriteStream( SocialStreamItem &$item )
	{
		$actor 		= $item->actor;

		$params 	= array_values( $item->contextParams );
		$post 		= Foundry::json()->decode( $params[ 0 ] );

		$permalink 	= SocialDiscussHelper::getPermalink( $post->id );

		// Remove code blocks from the content
		$post->content 		= EasyDiscussParser::removeCodes( $post->content );
		$post->content_raw	= $post->content;

		$post->content 		= DiscussHelper::formatContent( $post );

		$this->set( 'permalink' , $permalink );
		$this->set( 'post'		, $post );
		$this->set( 'actor' 	, $actor );

		$item->title 	= parent::display( 'streams/favourite.title' );
		$item->content 	= parent::display( 'streams/favourite.content' );
	}

	public function likesStream( SocialStreamItem &$item )
	{
		$actor 		= $item->actor;

		$params 	= array_values( $item->contextParams );
		$obj 		= Foundry::json()->decode( $params[ 0 ] );
		$post 		= $obj->post;
		$question 	= $obj->question;

		$type 		= $post->id == $question->id ? 'question' : 'reply';

		// Remove code blocks from the content
		$post->content 		= EasyDiscussParser::removeCodes( $post->content );
		$post->content_raw	= $post->content;

		$post->content 		= DiscussHelper::formatContent( $post );

		$permalink 	= SocialDiscussHelper::getPermalink( $question->id );

		$this->set( 'permalink' , $permalink );
		$this->set( 'type'		, $type );
		$this->set( 'post'		, $post );
		$this->set( 'actor' 	, $actor );

		$item->title 	= parent::display( 'streams/likes.title' );
		$item->content 	= parent::display( 'streams/likes.content' );
	}

	public function voteStream( SocialStreamItem &$item )
	{
		$actor 	= $item->actor;

		$params 	= array_values( $item->contextParams );
		$post 		= Foundry::json()->decode( $params[ 0 ] );

		$permalink 	= SocialDiscussHelper::getPermalink( $post->parent_id );

		// Remove code blocks from the content
		$post->content 		= EasyDiscussParser::removeCodes( $post->content );
		$post->content_raw	= $post->content;

		$post->content 		= DiscussHelper::formatContent( $post );

		$this->set( 'permalink' , $permalink );
		$this->set( 'post'	, $post );
		$this->set( 'actor' , $actor );


		$item->title 	= parent::display( 'streams/vote.title' );
		$item->content 	= parent::display( 'streams/vote.content' );
	}
}
