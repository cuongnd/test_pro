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

// Include necessary libraries here.
require_once( dirname( __FILE__ ) . '/plugin.php' );
require_once( dirname( __FILE__ ) . '/panel.php' );
require_once( dirname( __FILE__ ) . '/attachment.php' );

/**
 * A story class.
 *
 * @since	1.0
 */
class SocialStory
{
	private $story 	 = null;
	public $id       = null;
	public $moduleId = null;

	/**
	 * The unique target id.
	 * @var int
	 */
	public $target   = null;

	/**
	 * The unique target type.
	 * @var string
	 */
	public $targetType 	= null;

	/**
	 * Determines the type of the story.
	 * @var string
	 */
	public $type 	= null;

	public $attachments = array();
	public $panels = array();
	public $plugins = array();

	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function __construct( $type )
	{
		$this->type = $type;

		// Generate a unique id for the current stream object.
		$this->id = uniqid();
		$this->moduleId = 'story-' . $this->id;
	}

	/**
	 * Allows caller to specify the target id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function setTarget( $targetId , $targetType = SOCIAL_TYPE_USER )
	{
		$this->target	= $targetId;
	}

	/**
	 * Returns the target id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTarget()
	{
		return $this->target;
	}

	/**
	 * Creates a new stream item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function create( $content , $contextIds , $contextType , $actorId, $targetId = null , $location = null , $with = '' )
	{
		// Store this into the stream now.
		$stream 		= Foundry::stream();

		if( ! is_array( $contextIds ) )
		{
			$contextIds = array( $contextIds );
		}

		// this is to satisfy the setContext method.
		$contextId = $contextIds[0];

		// Get the stream template
		$template 		= $stream->getTemplate();
		$template->setActor( $actorId , $this->type );
		$template->setContext( $contextId , $contextType );
		$template->setContent( $content );

		$verb = ( $contextType == 'photos' ) ? 'share' : 'create';
		$template->setVerb( $verb );


		$pRule = ( $contextType == 'photos' ) ? 'photos.view' : 'story.view';

		$template->setPublicStream( $pRule );

		// Set the users tagged in the  stream.
		$template->setWith( $with );

		// Set the location of the stream
		$template->setLocation( $location );

		if( $targetId )
		{
			$template->setTarget( $targetId );
		}

		if( $contextType == 'photos' )
		{
			if( count( $contextIds ) > 0 )
			{
				foreach( $contextIds as $photoId )
				{
					$template->setChild( $photoId );
				}
			}
		}

		$dispatcher		= Foundry::dispatcher();

		$args 	= array( &$template , &$stream , &$content );

		// @trigger onBeforeStorySave
		$dispatcher->trigger( SOCIAL_TYPE_USER , 'onBeforeStorySave' , $args );

		// Create the new stream item.
		$streamItem 	= $stream->add( $template );

		// Set the notification type
		$notificationType 	= SOCIAL_TYPE_STORY;

		// Construct our new arguments
		$args 			= array( &$stream , &$streamItem , &$template );

		// @trigger onAfterStorySave
		$dispatcher->trigger( SOCIAL_TYPE_USER , 'onAfterStorySave' , $args );

		// Send a notification to the recipient.
		if( $targetId && $actorId != $targetId )
		{
			$this->notify( $targetId , $streamItem , $template->content , $contextIds , $contextType , $notificationType );
		}

		return $streamItem;
	}

	/**
	 * Notifies a user when someone posted something on their timeline
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int				The target user id.
	 * @param	SocialStream	The stream table
	 * @return
	 */
	public function notify( $id , $stream , $content , $contextIds , $contextType , $notificationType )
	{
		$recipient 	= Foundry::user( $id );
		$actor 		= Foundry::user( $stream->actor_id );

		// Add notification to the requester that the user accepted his friend request.
		$systemOptions	= array(
			// The unique node id here is the #__social_friend id.
			'uid'			=> $stream->id,
			'title'			=> JText::sprintf( 'COM_EASYSOCIAL_PROFILE_NOTIFICATIONS_USER_POSTED_ON_TIMELINE' , $content  ),
			'actor_id'		=> $actor->id,
			'target_id'		=> $recipient->id,
			'context_ids'	=> Foundry::json()->encode( $contextIds ),
			'context_type'	=> $contextType,
			'type'			=> $notificationType,
			'url'			=> FRoute::stream( array( 'layout' => 'item' , 'id' => $stream->uid ) )
		);

		$emailOptions = array(
			'title'	=> JText::sprintf( 'COM_EASYSOCIAL_PROFILE_EMAIL_NOTIFICATIONS_TITLE_USER_POSTED_ON_TIMELINE', $actor->getName() ),
			'template' => 'site/profile/post.story',
			'params' => array(
				'posterName' => $actor->getName(),
				'posterAvatar' => $actor->getAvatar(),
				'posterLink' => $actor->getPermalink(),
				'recipientName' => $recipient->getName(),
				'permalink' => FRoute::stream( array( 'layout' => 'item' , 'id' => $stream->uid ) ),
				'content' => $content
			)
		);


		// Send notification to the target
		$state 		= Foundry::notify( 'profile.story' , array( $recipient->id ) , $emailOptions , $systemOptions );

		return $state;
	}

	/**
	 * Object initialisation for the class to fetch the appropriate user
	 * object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param   null
	 * @return  SocialStream	The stream object.
	 */
	public static function factory( $type )
	{
		return new self( $type );
	}

	/**
	 * Get's a template object for story.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function createPlugin($name, $type='plugin')
	{
		$pluginClass = 'SocialStory' . ucfirst($type);

		$plugin = new $pluginClass($name, $this);

		return $plugin;
	}

	/**
	 * Trigger to prepare the story item before being output.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function prepare()
	{
		// Get apps library.
		$apps = Foundry::getInstance( 'Apps' );

		// Pass arguments by reference.
		$args = array( &$this );

		$plugins = array();

		// Try to load user apps
		$state = $apps->load( $this->type );

		if( !$state )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'STORY: No applications loaded.' );
		}
		else
		{
			// Only go through dispatcher when there is some apps loaded, otherwise it's pointless.
			$dispatcher = Foundry::dispatcher();

			// StoryAttachment service
			$attachments = $dispatcher->trigger( $this->type, 'onPrepareStoryAttachment', $args );

			if( $attachments )
			{
				foreach( $attachments as $attachment ) {
					if ( $attachment instanceof SocialStoryAttachment ) {
						$this->attachments[] = $attachment;
						$this->plugins[]     = $attachment; // also add to plugins
					}
				}
			}

			// StoryPanel service
			$panels = $dispatcher->trigger( $this->type , 'onPrepareStoryPanel' , $args );

			if( $panels )
			{
				foreach( $panels as $panel ) {
					if ( $panel instanceof SocialStoryPanel ) {
						$this->panels[]  = $panel;
						$this->plugins[] = $panel; // also add to plugins
					}
				}
			}

			// Story service
			$plugins = $dispatcher->trigger( $this->type , 'onPrepareStory' , $args, array('friends', 'locations'));

			if( $plugins )
			{
				foreach( $plugins as $plugin ) {
					if ( $plugin instanceof SocialStoryPlugin ) {
						$this->plugins[] = $plugin;
					}
				}
			}
		}

		// Story attachment panel (Core panel)
		$theme = Foundry::get('Themes');
		$theme->set('story', $this);

		// $attachmentPanel = $this->createPlugin("attachment", "panel");
		// $attachmentPanel->button->classname = "attachment";
		// $attachmentPanel->button->html  = $theme->output('site/story/attachment.button');
		// $attachmentPanel->content->html = $theme->output('site/story/attachment');

		// $this->panels[]  = $attachmentPanel;
		// $this->plugins[] = $attachmentPanel;

		return $plugins;
	}

	/**
	 * Get's the content in html form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool	Determine whether or not to show the current status.
	 * @return
	 */
	public function html( $showCurrentStory = true )
	{
		$my 		= Foundry::user();
		// Let's test if the current viewer is allowed to view this profile.
		if( $my->id != $this->target )
		{
			$privacy 	= Foundry::privacy( $my->id );
			$state = $privacy->validate( 'profiles.post.status' , $this->target , SOCIAL_TYPE_USER );

			if( ! $state )
			{
				return '';
			}
		}

		// Prepare the story.
		$this->prepare();

		$theme = Foundry::get('Themes');
		$theme->set( 'story', $this );
		$output = $theme->output( 'site/story/default' );

		return $output;
	}

	/**
	 * Get's the content in json form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool	Determine whether or not to show the current status.
	 * @return
	 */
	public function json()
	{
		$json 	= Foundry::json();

		$obj 	= (object) $this->story;

		$output = $json->encode( $obj );

		return $output;
	}

}

