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

// Necessary to import the custom view.
Foundry::import( 'site:/views/views' );

class EasySocialViewNotifications extends EasySocialSiteView
{
	/**
	 * Counter checks for new friend notifications
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		Total number of new friend requests.
	 */
	public function friendsCounter( $total = 0 )
	{
		$ajax 	= Foundry::ajax();

		return $ajax->resolve( $total );
	}

	/**
	 * Counter checks for new system notifications
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		Total number of new friend requests.
	 */
	public function getSystemCounter( $total = 0 )
	{
		$ajax 	= Foundry::ajax();

		return $ajax->resolve( $total );
	}

	/**
	 * Counter checks for new system notifications
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		Total number of new friend requests.
	 */
	public function getConversationCounter( $total = 0 )
	{
		$ajax 	= Foundry::ajax();

		return $ajax->resolve( $total );
	}

	/**
	 * Returns a list of new notifications
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The list of conversation items
	 */
	public function getConversationItems( $conversations )
	{
		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		$theme->set( 'conversations' , $conversations );

		$layout = JRequest::getWord( "layout" , "toolbar" );

		if( $layout == 'toolbar' )
		{
			$output = $theme->output( 'site/toolbar/default.conversations.item' );
		}
		else
		{
			$output = $theme->output( 'site/notifications/popbox.conversations' );
		}

		return $ajax->resolve( $output );
	}

	/**
	 * Returns a list of new notifications
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		Total number of new friend requests.
	 */
	public function getSystemItems( $items )
	{
		$ajax 	= Foundry::ajax();

		$result	= array();

		$theme	= Foundry::themes();

		$theme->set( 'notifications' , $items );

		$layout = JRequest::getWord( "layout" , "toolbar" );

		if( $layout == 'toolbar' )
		{
			$content = $theme->output( 'site/toolbar/default.notifications.item' );
		}
		else
		{
			$content = $theme->output( 'site/notifications/popbox.notifications' );
		}

		return $ajax->resolve( $content );
	}

	/**
	 * Counter checks for new friend notifications
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		Total number of new friend requests.
	 */
	public function friendsRequests( $items )
	{
		$ajax 	= Foundry::ajax();

		$result	= array();

		if( $items )
		{
			// Format return result.
			foreach( $items as &$item )
			{
				// Get the actor that added the current user.
				$item->user 	= Foundry::user( $item->actor_id );
			}
		}

		$theme 	= Foundry::themes();
		$theme->set( 'connections' , $items );

		$layout = JRequest::getWord( "layout" , "toolbar" );

		if( $layout == 'toolbar' )
		{
			$content = $theme->output( 'site/toolbar/default.friends.item' );
		}
		else
		{
			$content = $theme->output( 'site/notifications/popbox.friends' );
		}


		return $ajax->resolve( $content );
	}


	/**
	 * Post processing after a state has been set
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function setState()
	{
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve();
	}

	/**
	 * Post processing after a state has been set
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function setAllState()
	{
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve();
	}



	/**
	 * Counter checks for new friend notifications
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		Total number of new friend requests.
	 */
	public function clearAllConfirm()
	{
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$theme = Foundry::themes();
		$content 	= $theme->output( 'site/notifications/dialog.clearall' );

		return $ajax->resolve( $content );
	}

	/**
	 * Counter checks for new friend notifications
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		Total number of new friend requests.
	 */
	public function clearConfirm()
	{
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$theme = Foundry::themes();
		$content 	= $theme->output( 'site/notifications/dialog.clear' );

		return $ajax->resolve( $content );
	}


	public function loadmore( $items, $nextlimit )
	{
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$content = '';
		if( count( $items ) > 0 )
		{
			$theme = Foundry::themes();

			$theme->set( 'items', $items );
			$content 	= $theme->output( 'site/notifications/default.item' );
		}

		return $ajax->resolve( $content, $nextlimit );

	}

}
