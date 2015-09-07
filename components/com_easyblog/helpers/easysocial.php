<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2011 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.filesystem.file' );

class EasyBlogEasySocialHelper
{
	static $file 	= null;
	private $exists	= false;
	private $config = null;

	public function __construct()
	{
		$lang		= JFactory::getLanguage();
		$lang->load( 'com_easyblog' , JPATH_ROOT );

		self::$file 		= JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';

		$this->exists		= $this->exists();
		$this->config		= EasyBlogHelper::getConfig();
	}

	/**
	 * Determines if EasySocial is installed on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function exists()
	{
		jimport( 'joomla.filesystem.file' );

		$file 	= JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';

		if( !JFile::exists( $file ) )
		{
			return false;
		}

		include_once( $file );

		return true;
	}

	/**
	 * Retrieves EasySocial's toolbar
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getToolbar()
	{
		$toolbar 	= Foundry::get( 'Toolbar' );
		$output 	= $toolbar->render();

		return $output;
	}

	/**
	 * Initializes EasySocial
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function init()
	{
		static $loaded 	= false;

		if( !$loaded )
		{
			require_once( self::$file );

			$document 	= JFactory::getDocument();

			if( $document->getType() == 'html' )
			{
				// We also need to render the styling from EasySocial.
				$doc 		= Foundry::document();
				$doc->init();

				$page 		= Foundry::page();
				$page->processScripts();

			}

			Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );

			$loaded 	= true;
		}

		return $loaded;
	}

	/**
	 * Displays the user's points
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPoints( $id )
	{
		$config = EasyBlogHelper::getConfig();

		if( !$config->get( 'integrations_easysocial_points' ) )
		{
			return;
		}

		$theme 	= new CodeThemes();

		$user 	= Foundry::user( $id );

		$theme->set( 'user' , $user );
		$output = $theme->fetch( 'easysocial.points.php' );

		return $output;
	}

	/**
	 * Displays comments
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getCommentHTML( $blog )
	{
		$url 		= EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $blog->id );
		$comments 	= Foundry::comments( $blog->id , 'blog' , SOCIAL_APPS_GROUP_USER , $url );

		$theme 	= new CodeThemes();
		$theme->set( 'blog' , $blog );
		$theme->set( 'comments' , $comments );
		$output 	= $theme->fetch( 'easysocial.comments.php' );

		return $output;
	}

	/**
	 * Assign badge
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function assignBadge( $rule , $message , $creatorId = null )
	{
		if( !$this->exists() )
		{
			return false;
		}

		$creator 	= Foundry::user( $creatorId );

		$badge 	= Foundry::badges();
		$state 	= $badge->log( 'com_easyblog' , $rule , $creator->id , $message );

		return $state;
	}


	/**
	 * Assign points
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function assignPoints( $rule , $creatorId = null )
	{
		if( !$this->exists() )
		{
			return false;
		}

		$creator 	= Foundry::user( $creatorId );

		$points		= Foundry::points();
		$state 		= $points->assign( $rule , 'com_easyblog' , $creator->id );

		return $state;
	}

	/**
	 * Creates a new stream for new blog post
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createBlogStream( $blog )
	{
		$stream 	= Foundry::stream();
		$template 	= $stream->getTemplate();

		// Get the stream template
		$template->setActor( $blog->created_by , SOCIAL_TYPE_USER );
		$template->setContext( $blog->id , 'blog' );
		$template->setContent( $blog->content );

		$template->setVerb( 'create' );

		$state 	= $stream->add( $template );

		return $state;
	}

	/**
	 * Creates a new stream for new blog post
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createFeaturedBlogStream( $blog )
	{
		$stream 	= Foundry::stream();
		$template 	= $stream->getTemplate();

		// Get the stream template
		$template->setActor( $blog->created_by , SOCIAL_TYPE_USER );
		$template->setContext( $blog->id , 'blog' );
		$template->setContent( $blog->text );

		$template->setVerb( 'featured' );

		$state 	= $stream->add( $template );

		return $state;
	}

	/**
	 * Notify site subscribers whenever a new blog post is created
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function notifySubscribers( EasyBlogTableBlog $blog , $action , $comment = null )
	{
		// We don't want to notify via e-mail
		$emailOptions 	= false;
		$recipients 	= array();
		$rule 			= '';

		if( $action == 'new.post' )
		{
			$recipients 	= $blog->getRegisteredSubscribers( 'new' , array( $blog->created_by ) );
			$permalink	 	= EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $blog->id );
			$image 			= '';

			if( $blog->getImage() )
			{
				$image 	= $blog->getImage()->getSource( 'frontpage' );
			}

			$options 	= array( 'uid' => $blog->id , 'title' => JText::sprintf( 'COM_EASYBLOG_EASYSOCIAL_NOTIFICATION_NEW_BLOG_POST' , $blog->title ) , 'type' => 'blog' , 'url' => $permalink , 'image' => $image );

			$rule 		= 'blog.create';
		}

		if( $action == 'new.comment' )
		{
			$recipients		= $comment->getSubscribers( $blog , array( $comment->created_by ) );
			$recipients 	= array_merge( $recipients , array( $blog->created_by ) );

			$permalink	 	= EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $blog->id );
			$image 			= '';

			if( $blog->getImage() )
			{
				$image 	= $blog->getImage()->getSource( 'frontpage' );
			}

			$content 	= JString::substr( strip_tags( $comment->comment ) , 0 , 50 );

			$options 	= array( 'uid' => $blog->id , 'title' => JText::sprintf( 'COM_EASYBLOG_EASYSOCIAL_NOTIFICATION_NEW_COMMENT_ON_THE_BLOG_POST' , $content , $blog->title ) , 'type' => 'blog' , 'url' => $permalink , 'image' => $image , 'actor_id' => $comment->created_by );

			$rule 		= 'blog.comment';
		}


		if( $action == 'ratings.add' )
		{
			// Get blog post owner
			$recipients 	= array( $blog->created_by );
			$permalink	 	= EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $blog->id );
			$image 			= '';

			if( $blog->getImage() )
			{
				$image 	= $blog->getImage()->getSource( 'frontpage' );
			}

			$my 		= Foundry::user();

			$options 	= array( 'uid' => $blog->id , 'title' => JText::sprintf( 'COM_EASYBLOG_EASYSOCIAL_NOTIFICATION_NEW_RATINGS_FOR_YOUR_BLOG_POST' , $blog->title ) , 'type' => 'blog' , 'url' => $permalink , 'image' => $image , 'actor_id' => $my->id );

			$rule 		= 'blog.ratings';
		}

		if( empty( $rule ) )
		{
			return false;
		}

		// Send notifications to the receivers when they unlock the badge
		Foundry::notify( $rule , $recipients , $emailOptions , $options );
	}


	/**
	 * Creates a new stream for new comments in EasyBlog
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createCommentStream( $comment , $blog )
	{
		if( !$this->config->get( 'integrations_easysocial_stream_newcomment' )  )
		{
			return false;
		}

		if( !$this->exists() )
		{
			return false;
		}
		
		$stream 	= Foundry::stream();
		$template 	= $stream->getTemplate();

		// Get the stream template
		$template->setActor( $comment->created_by , SOCIAL_TYPE_USER );
		$template->setContext( $comment->id , 'blog' );
		$template->setContent( $comment->comment );

		$template->setVerb( 'create.comment' );

		$state 	= $stream->add( $template );

		return $state;
	}

	/**
	 * Creates a new stream for new comments in EasyBlog
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function addIndexerNewBlog( $blog )
	{
		if (!class_exists('Foundry')) return;

		$config 	= EasyBlogHelper::getConfig();

		$indexer 	= Foundry::get( 'Indexer', 'com_easyblog' );
		$template 	= $indexer->getTemplate();

		// getting the blog content
		$content 	= $blog->intro . $blog->content;


		$image 		= '';

		// @rule: Try to get the blog image.
		if( $blog->getImage() )
		{
			$image 	= $blog->getImage()->getSource( 'small' );
		}

		if( empty( $image ) )
		{
			// @rule: Match images from blog post
			$pattern	= '/<\s*img [^\>]*src\s*=\s*[\""\']?([^\""\'\s>]*)/i';
			preg_match( $pattern , $content , $matches );

			$image		= '';

			if( $matches )
			{
				$image		= isset( $matches[1] ) ? $matches[1] : '';

				if( JString::stristr( $matches[1], 'https://' ) === false && JString::stristr( $matches[1], 'http://' ) === false && !empty( $image ) )
				{
					$image	= rtrim(JURI::root(), '/') . '/' . ltrim( $image, '/');
				}
			}
		}

		if(! $image )
		{
			$image = rtrim( JURI::root() , '/' ) . '/components/com_easyblog/assets/images/default_facebook.png';
		}

		$content    = strip_tags( $content );

		if( JString::strlen( $content ) > $config->get( 'integrations_easysocial_indexer_newpost_length', 250 ) )
		{
			$content = JString::substr( $content, 0, $config->get( 'integrations_easysocial_indexer_newpost_length', 250 ) );
		}
		$template->setContent( $blog->title, $content );

		$url = EasyBlogRouter::_('index.php?option=com_easyblog&view=entry&id='.$blog->id);

		$template->setSource($blog->id, 'blog', $blog->created_by, $url);

		$template->setThumbnail( $image );

		$template->setLastUpdate( $blog->modified );

		$state = $indexer->index( $template );
		return $state;
	}

	public function updateBlogPrivacy( $blog )
	{
		$privacyLib = Foundry::privacy( $blog->created_by, SOCIAL_PRIVACY_TYPE_USER );
		$privacyLib->add( 'easyblog.blog.view', $blog->id, 'blog', $blog->private );
	}

	public function buildPrivacyQuery( $alias = 'a' )
	{

		$db		= EasyBlogHelper::db();
		$my		= JFactory::getUser();
		$config	= EasyBlogHelper::getConfig();


		$my			= JFactory::getUser();
		$esFriends	= Foundry::model( 'Friends' );

		$friends	= $esFriends->getFriends( $my->id, array( 'idonly' => true ) );

		if( $friends )
		{
			array_push($friends, $my->id);
		}

		$alias = $alias . '.';
		// Insert query here.
		$queryWhere	= ' AND (';
		$queryWhere	.= ' ( ' . $alias . '`private`= 0 ) OR';
		$queryWhere	.= ' ( (' . $alias . '`private` = 10) AND (' . $db->Quote( $my->id ) . ' > 0 ) ) OR';

		if( empty( $friends ) )
		{
			$queryWhere	.= ' ( ( ' . $alias . '`private` = 30 ) AND ( 1 = 2 ) ) OR';
		}
		else
		{
			$queryWhere	.= ' ( ( ' . $alias . '`private` = 30) AND ( ' . $alias . EasyBlogHelper::getHelper( 'SQL' )->nameQuote( 'created_by' ) . ' IN (' . implode( ',' , $friends ) . ') ) ) OR';
		}

		$queryWhere	.= ' ( (' . $alias . '`private` = 40) AND ( '. $alias . EasyBlogHelper::getHelper( 'SQL' )->nameQuote( 'created_by' ) .'=' . $my->id . ') )';
		$queryWhere	.= ' )';

		return $queryWhere;
	}

}
