<?php
/**
* @package		Social
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Jason Rey <jasonrey@stackideas.com>
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( 'JPATH_BASE' ) or die( 'Unauthorized Access' );

Foundry::import( 'admin:/tables/table' );

class SocialTableComments extends SocialTable
{
	public $id			= null;
	public $element		= null;
	public $uid			= null;
	public $comment		= null;
	public $created_by	= null;
	public $created		= null;
	public $params		= null;

	public function __construct( $db )
	{
		parent::__construct('#__social_comments', 'id', $db);
	}

	public function store( $updateNulls = false )
	{
		if( !$this->params instanceof SocialRegistry )
		{
			$this->params = Foundry::registry( $this->params );
		}

		$this->params = $this->params->toString();

		$isNew = false;

		if( empty( $this->id ) )
		{
			$isNew = true;
		}

		if( $isNew )
		{
			// Get the dispatcher object
			$dispatcher 	= Foundry::dispatcher();
			$args 			= array( &$this );

			// @trigger: onBeforeCommentSave
			$dispatcher->trigger( SOCIAL_APPS_GROUP_USER , 'onBeforeCommentSave' , $args );
		}

		$state = parent::store();

		if( !$state )
		{
			Foundry::logError( __FILE__, __LINE__, $this->getError() );
			return false;
		}

		if( $isNew )
		{
			// @trigger: onAfterCommentSave
			$dispatcher->trigger( SOCIAL_APPS_GROUP_USER , 'onAfterCommentSave' , $args );
		}

		return $state;
	}

	// No chainability
	public function update( array $newData )
	{
		// Specific data that requires processing to update
		if( array_key_exists( 'comment', $newData ) )
		{
			$stringLib		= Foundry::get( 'string' );
			$this->comment	= $stringLib->escape( $newData['comment'] );

			unset( $newData['comment'] );
		}

		// General loop to update the rest of the new data
		foreach( $newData as $key => $value )
		{
			if( property_exists( $this, $key ) )
			{
				$this->$key = $value;
			}
		}

		$state = $this->store();

		if( !$state )
		{
			Foundry::logError( __FILE__, __LINE__, $this->getError() );
			return false;
		}

		return true;
	}

	// Overwrite of the original delete function to include more hooks
	public function delete( $pk = null )
	{
		$arguments	= array( &$this );

		// Trigger beforeDelete event
		$dispatcher = Foundry::dispatcher();
		$dispatcher->trigger( SOCIAL_APPS_GROUP_USER, 'onBeforeDeleteComment' , $arguments );

		$state = parent::delete( $pk );

		if( $state )
		{
			// Clear out all the likes for this comment
			$likesModel = Foundry::model( 'likes' );
			$likesModel->delete( $this->uid, 'comments' );

			// Trigger afterDelete event
			$dispatcher->trigger( SOCIAL_APPS_GROUP_USER, 'onAfterDeleteComment', $arguments );
		}

		return $state;
	}

	public function like()
	{
		$dispatcher = Foundry::dispatcher();

		$likes = Foundry::likes( $this->id, 'comments' );

		$verb = $likes->hasLiked() ? 'unlike' : 'like';

		$beforeTrigger = 'onBefore' . ucfirst( $verb ) . 'Comment';

		$dispatcher->trigger( SOCIAL_APPS_GROUP_USER, $beforeTrigger, array( $this->element, $this->uid, $this ) );

		$state = $likes->toggle();

		if( !$state )
		{
			return false;
		}

		$afterTrigger = 'onAfter' . ucfirst( $verb ) . 'Comment';

		$dispatcher->trigger( SOCIAL_APPS_GROUP_USER, $afterTrigger, array( $this->element, $this->uid, $this ) );

		if( $verb === 'like' && $this->created_by != Foundry::user()->id )
		{
			$emailOptions = array(
				'title'		=> JText::sprintf( 'COM_EASYSOCIAL_COMMENTS_LIKE_EMAIL_TITLE', Foundry::user()->getName() ),
				'template'	=> 'site/comments/new.comment.like',
				'params'	=> array(
					'actor'	=> Foundry::user()->getName()
				)
			);

			$systemOptions = array(
				'uid'		=> $this->id,
				'actor_id'	=> Foundry::user()->id,
				'type'		=> 'comments',
				'url'		=> $this->getParams()->get( 'url', '' ),
				'title'		=> JText::_( 'COM_EASYSOCIAL_COMMENTS_LIKE_SYSTEM_TITLE' )
			);

			Foundry::notify( 'comments.like', array( $this->created_by ), $emailOptions, $systemOptions );
		}

		return $likes;
	}

	// This will return HTML of 1 single comment block
	public function renderHTML()
	{
		$user		= Foundry::user( $this->created_by );

		$isAuthor	= $this->isAuthor();

		$likes		= Foundry::likes( $this->id, 'comments' );

		$theme		= Foundry::get( 'themes' );

		$theme->set( 'comment', $this );
		$theme->set( 'user', $user );
		$theme->set( 'isAuthor', $isAuthor );
		$theme->set( 'likes', $likes );

		$html		= $theme->output( 'site/comments/item' );

		return $html;
	}

	public function getPermalink()
	{
		$base = $this->getParams()->get( 'url' );

		if( empty( $base ) )
		{
			return false;
		}

		// FRoute it
		// $base = FRoute::_( $base );

		$base .= '#commentid=' . $this->id;

		return $base;
	}

	public function getComment()
	{
		$comment = $this->comment;

		$stringLib = Foundry::get( 'string' );

		$comment	= $stringLib->parseBBCode( $comment );
		$comment 	= $stringLib->replaceHyperlinks( $comment );

		return $comment;
	}

	public function getDate( $format = '' )
	{
		$config = Foundry::config();

		$date = Foundry::date( $this->created );

		$elapsed = $config->get( 'comments_elapsed_time', true );

		// If format is passed in as true or false, this means disregard the elapsed time settings and obey the decision of format
		if( $format === true || $format === false )
		{
			$elapsed = $format;

			$format = '';
		}

		if( $elapsed && empty( $format ) )
		{
			return $date->toElapsed();
		}

		if( empty( $format ) )
		{
			return $this->created;
		}

		return $date->format( $format );
	}

	public function getApp()
	{
		static $apps = array();

		if( empty( $apps[$this->element] ) )
		{
			$app = Foundry::table( 'apps' );

			$app->loadByElement( $this->element, SOCIAL_APPS_GROUP_USER, SOCIAL_APPS_TYPE_APPS );

			$apps[$this->element] = $app;
		}

		return $apps[$this->element];
	}

	public function isAuthor( $userid = null )
	{
		if( is_null( $userid ) )
		{
			$userid = Foundry::user()->id;
		}

		return $this->created_by == $userid;
	}

	public function getParams()
	{
		if( !$this->params instanceof SocialRegistry )
		{
			$this->params = Foundry::registry( $this->params );
		}

		return $this->params;
	}

	public function setParam( $key, $value )
	{
		if( !$this->params instanceof SocialRegistry )
		{
			$this->params = Foundry::registry( $this->params );
		}

		$this->params->set( $key, $value );

		return true;
	}
}
