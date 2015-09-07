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

// Include dependencies.
require_once( dirname( __FILE__ ) . '/dependencies.php' );

/**
 * Notification library.
 *
 * Example:
 * <code>
 * <?php
 * $notification 	= Foundry::getInstance( 'Notification' );
 * $notification->create();
 *
 * // Get's the notification list.
 * $notification->getHTML();
 *
 * ?>
 * </code>
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialNotification extends JObject
{
	/**
	 * Holds a copy of SocialNotification object.
	 * @var SocialNotification
	 */
	static $instance 	= null;

	/**
	 * The notification class is always a singleton object.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public static function getInstance()
	{
		if( is_null( self::$instance ) )
		{
			self::$instance	= new self();
		}

		return self::$instance;
	}

	/**
	 * Creates a new notification item.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $notification	= Foundry::getInstance( 'Notification' );
	 * 
	 * // Creates a new notification item.
	 * $notification->create( $options );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of key / value options that is to be binded to the ORM.
	 * @return	bool
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function create( SocialNotificationTemplate $template )
	{
		// Load the Notification table
		$table	= Foundry::table( 'Notification' );

		// Notification aggregation will only happen if there is the same `uid`,`type`
		if( $template->aggregate )
		{
			// Load any existing records to see if it exists.
			$type 	= $template->type;
			$uid 	= $template->uid;
			$targetId 	= $template->target_id;
			$targetType = $template->target_type;

			$exists	= $table->load( array( 'uid' => $uid , 'type' => $type , 'target_id' => $targetId , 'target_type' => $targetType ) );

			// If it doesn't exist, go through the normal routine of binding the item.
			if( !$exists )
			{
				$table->bind( $template );
			}
			else
			{
				$table->title 	= $template->title;
				
				// Reset to unread state since this is new.
				$table->state 	= SOCIAL_NOTIFICATION_STATE_UNREAD;
			}

			// Update this item to the latest since we want this to appear in the top of the list.
			$table->created	= Foundry::date()->toMySQL();
		}
		else
		{
			// Bind the template.
			$table->bind( $template );
		}

		$state 	= $table->store();

		if( !$state )
		{
			$this->setError( $table->getError() );
			return false;
		}

		return true;
	}

	/**
	 * Generates a new notification object template.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getTemplate()
	{
		$template 	= new SocialNotificationTemplate();

		return $template;
	}

	/**
	 * Marks an item as read
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique notification id.
	 * @return	bool
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function read( $id )
	{
		$table	= Foundry::table( 'Notification' );
		$table->load( $id );
		
		return $table->markAsRead();
	}

	/**
	 * Deletes a notification item from the site.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique notification id.
	 * @return	bool
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function delete( $id )
	{
		$table	= Foundry::table( 'Notification' );
		$table->load( $id );
		
		return $table->delete();
	}

	/**
	 * Hide's notification item but not delete. Still visible when viewing all notification items.
	 *
	 * Example:
	 * <code>
	 * <?php
	 *
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique notification id.
	 * @return	bool
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function hide( $id )
	{
		$table	= Foundry::table( 'Notification' );
		$table->load( $id );
		
		return $table->markAsHidden();
	}

	/**
	 * Retrieves the notification output.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The current user's id
	 * @return	string	The html output of the notifications list.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function toHTML( $userId )
	{
		$model	= Foundry::model( 'Notifications' );

		$items	= $model->getItems( array( 'user_id' => $userId ) );

		if( !$items )
		{
			return false;
		}

		// Trigger people apps
		Foundry::getInstance( 'Apps' )->load( 'people' );

		// @TODO: Retrieve applications and trigger onNotificationLoad
		$dispatcher	= Foundry::getInstance( 'Dispatcher' );

		// Trigger apps
		foreach( $items as $item )
		{
			$type 	= $item->type;
			$args	= array( &$item );

			// @trigger onNotificationLoad
			$state	= $dispatcher->trigger( SOCIAL_APPS_GROUP_USER , 'onNotificationLoad' , $args , $type );
		}

		$theme	= Foundry::get( 'Themes' );
		$theme->set( 'items' , $items );

		return $theme->output( 'site/notifications/default' );
	}

	/**
	 * Retrieves a list of notification items.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool	To aggregate the notification items or not.
	 * @return	Array	An array of @SocialTableNotification
	 */
	public function getItems( $options = array() )
	{
		$model 	= Foundry::model( 'Notifications' );

		$items	= $model->getItems( $options );

		if( !$items )
		{
			return false;
		}

		// Load user apps
		Foundry::apps()->load( SOCIAL_APPS_GROUP_USER );

		// @TODO: Retrieve applications and trigger onNotificationLoad
		$dispatcher 	= Foundry::dispatcher();

		// Trigger apps
		foreach( $items as $item )
		{
			// Add a `since` column to the result so that user's could use the `since` time format.
			$item->since	= Foundry::date( $item->created )->toLapsed();

			$args			= array( &$item );

			// @trigger onNotificationLoad
			$dispatcher->trigger( SOCIAL_APPS_GROUP_USER , 'onNotificationLoad' , $args );

			// Let's format the item title.
			$this->formatItem( $item );
		}

		// Group up items.
		if( isset( $options[ 'group' ] ) && $options[ 'group' ] == SOCIAL_NOTIFICATION_GROUP_ITEMS )
		{
			$items	= $this->group( $items );	
		}

		return $items;
	}

	/**
	 * Format the notification title
	 *
	 * @since	1.0
	 * @access	public
	 * @param	
	 * @return	
	 */
	public function formatItem( &$item )
	{
		// Escape the original title first.
		$item->title 	= Foundry::string()->escape( $item->title );

		// We have our own custom tags
		$item->title 	= $this->formatKnownTags( $item->title );

		// Replace actor first.
		$item->title 	= $this->formatActor( $item->title , $item->actor_id , $item->actor_type );

		// Replace target.
		$item->title 	= $this->formatTarget( $item->title , $item->target_id , $item->target_type );

		// Replace variables from parameters.
		$item->title 	= $this->formatParams( $item->title , $item->params );

		// Get the icon of this app if needed.
		$item->icon 	= $this->getIcon( $item );



		// Set the actor
		$item->user 	= Foundry::user( $item->actor_id );
	}

	public function formatKnownTags( $title )
	{
		$title 	= str_ireplace( '{b}' , '<b>' , $title );
		$title 	= str_ireplace( '{/b}' , '</b>' , $title );

		return $title;
	}

	/**
	 * Retrieves the icon for this notification item.
	 *
	 * Example:
	 * <code>
	 * <?php
	 *
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The icon's absolute url.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getIcon( &$item )
	{
		$obj 	= Foundry::makeObject( $item->params );

		if( isset( $obj->icon ) )
		{
			return $obj->icon;
		}

		// @TODO: Return a default notification icon.

		return false;
	}

	/**
	 * Replaces {ACTOR} with the proper actor data.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function formatParams( $content , $params )
	{
		$obj 	= Foundry::makeObject( $params );

		if( $obj )
		{
			$keys 	= get_object_vars( $obj );

			if( $keys )
			{
				foreach( $keys as $key => $value )
				{
					$content 	= str_ireplace( '{%' . $key . '%}' , $value , $content );
				}	
			}
		}
		

		return $content;
	}

	/**
	 * Replaces {ACTOR} with the proper actor data.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function formatActor( $content , $actorId , $actorType = SOCIAL_TYPE_USER )
	{
		// @TODO: Actor might not necessarily be a user.
		$actor 	= Foundry::user( $actorId , true );


		$theme 		= Foundry::themes();
		$theme->set( 'title', $actor->getName() );
		$theme->set( 'link'	, $actor->getPermalink() );

		$content 	= str_ireplace( '{ACTOR}' , $theme->output( 'site/notifications/actor' ) , $content );

		return $content;
	}

	/**
	 * Replaces {TARGET} with the proper actor data.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function formatTarget( $content , $targetId , $targetType = SOCIAL_TYPE_USER )
	{
		$output 	= '';

		// Get the current logged in user.
		if( $targetType == SOCIAL_TYPE_USER )
		{
			$target 	= Foundry::user( $targetId );

			$theme 		= Foundry::themes();
			$theme->set( 'title', $target->getStreamName() );
			$theme->set( 'link'	, $target->getPermalink() );

			$output 	= $theme->output( 'site/notifications/target' );
		}

		$content 	= str_ireplace( '{TARGET}' , $output , $content );

		return $content;
	}

	/**
	 * Group up items by days
	 *
	 * @since	1.0
	 * @access	private
	 * @param	Array	An array of @SocialTableNotification items.
	 * @return	Array	An array of aggregated items.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	private function group( &$items , $dateFormat = '')
	{
		$result	= array();
		
		foreach( $items as $item )
		{
			$today	= Foundry::date();
			$date 	= Foundry::date( $item->created );

			if( $today->format( 'j/n/Y' ) == $date->format( 'j/n/Y' ) )
			{
				$index 	= JText::_( 'Today' );
			}
			else
			{
				$index 	= $date->format( 'F j, Y' );
			}

			if( !isset( $result[ $index ] ) )
			{
				$result[ $index ]	= array();
			}

			$result[ $index ][]	= $item;
		}

		return $result;
	}

	/**
	 * Retrieves the notification output in JSON format.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The JSON string.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function toJSON()
	{

	}
}
