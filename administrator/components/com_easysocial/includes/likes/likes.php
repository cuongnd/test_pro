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

class SocialLikes
{
	/**
	 * A list of items that the current user has liked
	 * @var	Array
	 */
	var $data 		= array();
	var $uid 		= null;
	var $element 	= null;
	var $group 		= null;

	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct( $uid = null, $element = null, $group = SOCIAL_APPS_GROUP_USER )
	{
		$this->uid  	= $uid;
		$this->element 	= $element;
		$this->group 	= $group;

		if( !is_null( $uid ) && !is_null( $element ) )
		{
			$this->get( $uid , $element, $group );
		}
	}

	public static function factory( $uid = null, $element = null, $group = SOCIAL_APPS_GROUP_USER )
	{
		return new self( $uid, $element, $group);
	}

	/**
	 * Determines if the provided user has liked the object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 		The user id to check upon
	 * @param	int 		The target unique id.
	 * @param	string		The target unique type.
	 * @return	bool		True if user has liked the item, false otherwise.
	 */
	public function hasLiked( $uid = null , $element = null, $group = SOCIAL_APPS_GROUP_USER, $userId = null )
	{
		if( is_null( $uid ) )
		{
			$uid = $this->uid;
		}

		if( is_null( $element ) )
		{
			$element = $this->element;
		}

		if( is_null( $userId ) )
		{
			$userId = Foundry::user()->id;
		}

		$model 		= Foundry::model( 'Likes' );
		$hasLiked 	= $model->hasLiked( $uid , $this->formKeys( $element, $group ) , $userId );

		return $hasLiked;
	}

	/**
	 * Get's the likes data for a particular item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique id to lookup for.
	 * @param	string	The unique element to lookup for
	 * @return	SocialLikes		Return itself for chaining.
	 */
	public function getCount( $uid = null , $element = null, $group = SOCIAL_APPS_GROUP_USER )
	{
		$likeCnt 	= 0;

		if( is_null( $uid ) )
		{
			$uid = $this->uid;
		}

		if( is_null( $element ) )
		{
			$element = $this->element;
		}

		$model = Foundry::model( 'Likes' );
		$likeCnt = $model->getLikesCount( $uid, $this->formKeys( $element, $group ) );

		return $likeCnt;
	}

	/**
	 * Get's the likes data for a particular item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique id to lookup for.
	 * @param	string	The unique element to lookup for
	 * @return	SocialLikes		Return itself for chaining.
	 */
	public function get( $id , $element, $group = SOCIAL_APPS_GROUP_USER )
	{
		$model 			= Foundry::model( 'Likes' );

		// Build the key
		$key 			= $this->formKeys( $element , $group );

		// Get the likes
		$this->data		= $model->getLikes( $id , $key );
		$this->uid		= $id;
		$this->element	= $element;

		return $this;
	}

	private function formKeys( $element , $group )
	{
		return $element . '.' . $group;
	}

	public function button()
	{
		$my 		= Foundry::user();
		$model 		= Foundry::model( 'Likes' );
		$hasLiked 	= $model->hasLiked( $this->uid , $this->formKeys( $this->element, $this->group ) , $my->id );

		$text = JText::_( 'COM_EASYSOCIAL_LIKES_LIKE' );

		if( $hasLiked )
		{
			$text = JText::_( 'COM_EASYSOCIAL_LIKES_UNLIKE' );
		}

		$themes 	= Foundry::get( 'Themes' );

		$themes->set( 'text', $text );
		$themes->set( 'my'	, $my );
		$themes->set( 'uid'	, $this->uid );
		$themes->set( 'element', $this->element );
		$themes->set( 'group', $this->group );


 		$html = $themes->output( 'site/likes/action' );
 		return $html;
	}


	/**
	 * Returns the likes html codes.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The html code for the likes string.
	 */
	public function toHTML()
	{
		// Get the count.
		$count 	= 0;
		$text 	= '';

		if( $this->data !== false )
		{
			$count = count( $this->data );
			$text  = $this->toString();
		}

		$themes 	= Foundry::get( 'Themes' );
		$themes->set( 'text'		, $text );
		$themes->set( 'count'	, $count );
		$themes->set( 'uid', $this->uid );
		$themes->set( 'element', $this->element );
		$themes->set( 'group', $this->group );


 		$html = $themes->output( 'site/likes/item' );

		return $html;
	}

	public function toString( $viewerId = null, $plaintext = false )
	{
		// Get current logged in user as we need to know if the viewer is themselves or not.
		$viewer 		= Foundry::user();

		if( !is_null( $viewerId ) )
		{
			$viewer 		= Foundry::user( $viewerId );
		}


		// Default return text.
		$text 		= '';

		// Determine if the viewer is the owner.
		$viewerIsOwner 	= false;

		// List of users which liked this item.
		$users 		= array();

		// If there's no likes at all, we should just return an empty string.
		if( !$this->data )
		{
			return $text;
		}

		$mydata = is_array( $this->data ) ? $this->data : array( $this->data );
		foreach( $mydata as $like )
		{
			$users[]	= $like->created_by;
		}

		// Ensure that the owner id will be the first all the time.
		if( in_array( $viewer->id , $users ) )
		{
			$viewerIsOwner 	= true;

			// lets rebuild the users array.
			$tmpUsers = array();
			foreach( $users as $userId )
			{
				if( $userId != $viewer->id )
				{
					$tmpUsers[] = $userId;
				}
			}
			$users = $tmpUsers;
			array_unshift( $users , $viewer->id );
		}

		// Maximum names to display in the likes.
		$max 		= SOCIAL_LIKES_MAX_NAME;

		// Count the number of users that liked this item.
		$total		= count( $users );
		$break		= 0;
		$items		= array();
		$remainder	= array();

		if( $total == 1 )
		{
			$items	= $users;
		}
		else
		{

			// If there's always lesser items, then we just show all users.
			if( SOCIAL_LIKES_MAX_NAME >= $total )
			{
				$items	= $users;
			}

			if( $total > SOCIAL_LIKES_MAX_NAME )
			{
				// If there's more item than the allowed names, we need to break it

				// We only want to show the maximum of ( SOCIAL_LIKES_MAX_NAME - 1 ) names.
				$break		= SOCIAL_LIKES_MAX_NAME - 1;

				$items 		= array_slice( $users, 0 , $break );
				$remainder	= array_slice( $users, $break );
			}
		}

		if( $total == 1 )
		{
			$usePlural 	= true;

			// If the only user is the current viewer, it should not be plural
			$item 	= $items[ 0 ];
			$user 	= Foundry::user( $item );

			if( $user->isViewer() )
			{
				$usePlural 	= false;
			}
		}
		else
		{
			$usePlural 	= false;
		}

		$theme 		= Foundry::get( 'Themes' );
		$theme->set( 'items' 		, $items );
		$theme->set( 'usePlural'	, $usePlural );
		$theme->set( 'total'		, $total );
		$theme->set( 'remainder'	, $remainder );
		$theme->set( 'uid' 			, $this->uid );
		$theme->set( 'element'		, $this->element );

		// dump( SOCIAL_LIKES_MAX_NAME );

		$tmpl = 'string';
		if( $plaintext )
		{
			$tmpl .= '.plain';
		}

		$tpl 		= 'site/likes/' . $tmpl;
 
		$text 		= $theme->output( $tpl );
		return $text;
	}

	public function toArray()
	{
		return $this->data;
	}

	/**
	 * Allows 3rd party implementation to delete likes related to an object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int			The unique item id that is being liked
	 * @param	string		The unique item element that is being liked
	 * @param	int 		The current user that liked the item
	 * @return 	boolean 	true or false.
	 */
	public function delete( $uid = null , $element = null, $group = SOCIAL_APPS_GROUP_USER, $userId = null )
	{
		if( is_null( $uid ) )
		{
			$uid = $this->uid;
		}

		if( is_null( $element ) )
		{
			$element = $this->element;
		}

		if( is_null( $userId ) )
		{
			$userId = Foundry::user()->id;
		}

		$like 	= Foundry::table( 'Likes' );

		// Check if the user has already liked this item before.
		$exists = $like->load( array( 'uid' => $uid , 'type' => $this->formKeys( $element, $group ) , 'created_by' => $userId ) );

		// If item has been liked before, return false.
		if( !$exists )
		{
			return false;
		}

		$state 	= $like->delete();

		if( !$state )
		{
			return false;
		}

		$key 		= $uid . '.' . $this->formKeys( $element, $group );
		$likeModel = Foundry::model( 'Likes' );
		$likeModel->removeLikeItem( $key, $userId );

		return true;
	}

	/**
	 * Allows 3rd party implementation to toggle likes to an object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int			The unique item id that is being liked
	 * @param	string		The unique item element that is being liked
	 * @param	int 		The current user that liked the item
	 * @return	SocialTableLikes
	 */
	public function toggle( $uid = null , $element = null , $group = SOCIAL_APPS_GROUP_USER, $userId = null )
	{
		if( is_null( $uid ) )
		{
			$uid = $this->uid;
		}

		if( is_null( $element ) )
		{
			$element = $this->element;
		}

		if( is_null( $userId ) )
		{
			$userId = Foundry::user()->id;
		}

		$like 	= Foundry::table( 'Likes' );

		// Check if the user has already liked this item before.
		$exists = $like->load( array( 'uid' => $uid , 'type' => $this->formKeys( $element, $group ) , 'created_by' => $userId ) );

		// If item has been liked before, return false.
		if( $exists )
		{
			$state 	= $this->delete( $uid , $element , $group, $userId );

			return $state;
		}

		return $this->add( $uid , $element , $group, $userId );
	}

	/**
	 * Allows 3rd party implementation to add likes to an object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int			The unique item id that is being liked
	 * @param	string		The unique item element that is being liked
	 * @param	int 		The current user that liked the item
	 * @return	SocialTableLikes
	 */
	public function add( $uid = null , $element = null , $group = SOCIAL_APPS_GROUP_USER, $userId = null )
	{
		if( is_null( $uid ) )
		{
			$uid = $this->uid;
		}

		if( is_null( $element ) )
		{
			$element = $this->element;
		}

		if( is_null( $userId ) )
		{
			$userId = Foundry::user()->id;
		}

		$like 	= Foundry::table( 'Likes' );

		// Check if the user has already liked this item before.
		$exists = $like->load( array( 'uid' => $uid , 'type' => $this->formKeys( $element, $group ) , 'created_by' => $userId ) );

		// If item has been liked before, return false.
		if( $exists )
		{
			return false;
		}

		$like->uid 	= $uid;
		$like->type = $this->formKeys( $element, $group );
		$like->created_by  = $userId;

		$state 	= $like->store();

		if( !$state )
		{
			return false;
		}

		// add into static variable
		$key 		= $uid . '.' . $this->formKeys( $element, $group );
		$likeModel = Foundry::model( 'Likes' );
		$likeModel->setLikeItem( $key, $like );

		return $like;
	}

	/**
	 * Retrieve a list of users who liked this item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool	Determines if we should return a user object.
	 * @return
	 */
	public function getParticipants( $userObject = true )
	{
		$model = Foundry::model( 'likes' );
		$users = $model->getLikerIds( $this->uid, $this->formKeys( $this->element, $this->group ) );

		$objects = array();

		if( $users && $userObject )
		{
			foreach( $users as $user )
			{
				$objects[] = Foundry::user( $user );
			}

			return $objects;
		}

		return $users;
	}

	public function getLikedUsersDialog()
	{
		$users = $this->getParticipants();

		$theme = Foundry::themes();

		$theme->set( 'users', $users );

		$html = $theme->output( 'site/likes/dialog.likedUsers' );

		return $html;
	}

}
