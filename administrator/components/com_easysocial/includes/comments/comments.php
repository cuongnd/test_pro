<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Jason Rey <jasonrey@stackideas.com>
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

class SocialComments
{
	static $instance	= null;
	static $blocks		= array();

	var $config 		= null;
	var $commentor 		= null;
	var $commentCount   = null;

	public function __construct()
	{
		// Object construct happens here
		$this->config	 	= 1;
		$this->commentor 	= array();
	}

	public static function getInstance()
	{
		if( !self::$instance )
		{
			self::$instance	= new self();
		}

		return self::$instance;
	}

	public static function factory( $uid = null, $element = null, $group = SOCIAL_APPS_GROUP_USER, $options = array() )
	{
		return new self( $uid, $element, $group, $options );
	}

	public function load( $uid, $element, $group = SOCIAL_APPS_GROUP_USER, $options = array() )
	{
		if( empty( self::$blocks[$group][$element][$uid] ) )
		{
			$class = new SocialCommentBlock( $uid, $element, $group, $options );

			self::$blocks[$group][$element][$uid] = $class;
		}

		self::$blocks[$group][$element][$uid]->loadOptions( $options );

		return self::$blocks[$group][$element][$uid];
	}
}

class SocialCommentBlock
{
	public $uid = '';
	public $element = '';
	public $group = '';
	public $options = array();

	public function __construct( $uid, $element, $group = SOCIAL_APPS_GROUP_USER, $options = array() )
	{
		$this->uid = $uid;
		$this->element = $element;
		$this->group = $group;

		$this->loadOptions( $options );
	}

	public function loadOptions( $options = array() )
	{
		if( !empty( $options['url'] ) )
		{
			$this->options['url'] = $options['url'];
		}
	}

	private function getElement()
	{
		$compositeKey = $this->element . '.' . $this->group;

		return $compositeKey;
	}

	/**
	 * Retrieves the comment count given the element and unique id
	 *
	 * @since	1.0
	 * @access	public
	 *
	 * @return	int		The total count of the comment block
	 */
	public function getCount()
	{
		$model 	= Foundry::model( 'Comments' );
		$count 	= $model->getCommentCount( array( 'element' => $this->getElement() , 'uid' => $this->uid ) );

		return $count;
	}
	/**
	 * Function to return HTML of 1 comments block
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array	$options	Various options to manipulate the comments
	 *
	 * @return	string	Html block of the comments
	 */
	public function getHtml( $options = array() )
	{
		// Ensure that language file is loaded
		Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );
		
		// Construct mandatory options
		$options['uid']			= $this->uid;
		$options['element']		= $this->getElement();
		$options[ 'hideEmpty' ] = isset( $options[ 'hideEmpty' ] ) ? $options[ 'hideEmpty' ] : false;

		$model	= Foundry::model( 'comments' );

		// Get the total comments first
		$total	= $model->getCommentCount( $options );

		// Construct bounderies
		if( !isset( $options['limit'] ) )
		{
			$options['limit']	= Foundry::config()->get( 'comments_initial_limit', 5 );
		}
		$options['start']		= max( $total - $options['limit'], 0 );

		// Construct ordering
		$options['order']		= 'created';
		$options['direction']	= 'asc';

		// Check if it is coming from a permalink
		$commentid				= JRequest::getInt( 'commentid', 0 );

		if( $commentid !== 0 )
		{
			$options['commentid'] = $commentid;

			// If permalink is detected, then no limit is required
			$options['limit'] = 0;
		}

		$comments	= array();
		$count		= 0;

		if( $total )
		{
			$comments	= $model->getComments( $options );
			$count		= count( $comments );
		}

		// @trigger: onPrepareComments
		$dispatcher = Foundry::dispatcher();
		$args 		= array( &$comments );

		$dispatcher->trigger( $this->group , 'onPrepareComments' , $args );

		// Check for permalink
		if( !empty( $options['url'] ) )
		{
			$this->options['url'] = $options['url'];
		}

		$themes		= Foundry::get( 'Themes' );

		$themes->set( 'hideEmpty'	, $options[ 'hideEmpty' ] );
		$themes->set( 'my'			, Foundry::user() );
		$themes->set( 'element'		, $this->element );
		$themes->set( 'group'		, $this->group );
		$themes->set( 'uid'			, $this->uid );
		$themes->set( 'total'		, $total );
		$themes->set( 'count'		, $count );
		$themes->set( 'comments'	, $comments );

		if( !empty( $this->options['url'] ) )
		{
			$themes->set( 'url', $this->options['url'] );
		}

		$html = $themes->output( 'site/comments/frame' );

		return $html;
	}

	public function delete()
	{
		$model = Foundry::model( 'comments' );

		$comments = $model->getComments( array(
			'element' => $this->getElement(),
			'uid' => $this->uid,
			'limit' => 0
		) );

		foreach( $comments as $comment )
		{
			$comment->delete();
		}

		return true;
	}

	// @TODO: Shift this to comment app
	public function parentItemDeleted()
	{
		$model = Foundry::model( 'comments' );
		$state = $model->deleteCommentBlock( $this->uid, $this->getElement() );

		return $state;
	}

	public function getParticipants( $options = array() , $userObject = true )
	{
		$model = Foundry::model( 'comments' );

		$result = $model->getParticipants( $this->uid, $this->getElement(), $options );

		$users = array();

		if( !$result )
		{
			return $users;
		}

		if( !$userObject )
		{
			return $result;
		}

		foreach( $result as $id )
		{
			$users[$id] = Foundry::user( $id );
		}

		return $users;
	}
}
