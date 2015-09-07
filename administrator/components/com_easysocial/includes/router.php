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

Foundry::import( 'admin:/includes/router/router' );

/**
 * Routing library for EasySocial
 *
 * @since	3.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class FRoute
{
	static $base 	= 'index.php?option=com_easysocial';
	static $views 	= array(
								'activities',
								'albums',
								'apps',
								'badges',
								'conversations',
								'dashboard',
								'fields',
								'friends',
								'followers',
								'profile',
								'unity',
								'users',
								'stream',
								'notifications',
								'leaderboard',
								'points',
								'photos',
								'registration',
								'search',
								'login',
								'unity'
							);
    static $_cache = null;
    static $_query_cache = null;
	/**
	 * Translates URL to SEF friendly
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function _( $url , $xhtml = false , $view = array() , $ssl = null , $tokenize = false , $external = false , $tmpl = '' , $controller = '' )
	{
		if( $tokenize )
		{
			$url 	.= '&' . Foundry::token() . '=1';
		}

		// If view is provided, check for the item id.
		if( !empty( $view ) )
		{
			// Get the item id.
			$itemId 	= self::getItemId( $view[0] , $view[ 1 ] );

			if( $itemId )
			{
				$url 	= $url . '&Itemid=' . $itemId;
			}
		}

		if( !empty( $controller ) )
		{
			$url	= $url . '&controller=' . $controller;
		}

		// If this is an external URL, we want to fetch the full URL.
		if( $external )
		{
			return FRoute::external( $url , $xhtml , $ssl , $tmpl );
		}

		if( !empty( $controller ) )
		{
			$url 	= JRoute::_( $url , $xhtml );
			return $url;			
		}

		// We don't want to do any sef routing here.
		if( $tmpl == 'component' )
		{
			return $url;
		}

		return JRoute::_( $url , $xhtml , $ssl );
	}

	/**
	 * Returns the raw url without going through any sef urls.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public static function raw( $url )
	{
		$uri 	= rtrim( JURI::root() , '/' ) . '/' . $url;

		return $uri;
	}
	
	/**
	 * Builds an external URL that may be used in an email or other external apps
	 *
	 * @since	1.0
	 * @access	public
	 * @return
	 */
	public static function external( $url , $xhtml = false , $ssl = null , $tmpl = false )
	{
		$uri 	= JURI::getInstance();

		// If this is an external URL, we will not want to xhtml it.
		$xhtml 	= false;

		// Determine if the current browser is from the back end.
		$app 	= JFactory::getApplication();

		if( $app->isAdmin() )
		{
			jimport( 'joomla.libraries.cms.router' );

			// Reset the application
			JFactory::$application = JApplication::getInstance('site');
		}

		// Send the URL for processing only if tmpl != component
		if( $tmpl != 'component' )
		{
			$url 	= FRoute::_( $url , $xhtml , $ssl );
		}

		// Remove the /administrator/ part from the URL.
		$url 	= str_ireplace( '/administrator/' , '/' , $url );
		$url 	= ltrim( $url , '/' );

		// We need to use $uri->toString() because JURI::root() may contain a subfolder which will be duplicated
		// since $url already has the subfolder.
		$url	= $uri->toString( array( 'scheme' , 'host' , 'port' ) ) . '/' . $url;

		return $url;
	}

	public static function tokenize( $url , $xhtml = false , $ssl = null )
	{
		$url 	.= '&' . Foundry::token() . '=1';

		return FRoute::_( $url , $xhtml , $ssl );
	}

	/**
	 * Retrieves the current url that is being accessed.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool	Determines if we should append this as a callback url.
	 * @return	string	The current url.
	 */
	public static function current( $isCallback = false )
	{
		$uri 	= JRequest::getURI();

		if( $isCallback )
		{
			return '&callback=' . base64_encode( $uri );
		}

		return $uri;
	}

	/**
	 * Retrieves the item id of the current view.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function getItemId( $view , $layout = '' )
	{
		static $views 	= array();

		$db 	= Foundry::db();
		$sql 	= $db->sql();

		// lets get the very 1st menu item created for ES and use it as default menu item.
		// incase there is no menu item setup for the current view.
		if( !isset( $views[ 'system-default' ] ) )
		{
			// Get the first public page available if there's any
			$sql->select( '#__menu' );
			$sql->where( 'published' , SOCIAL_STATE_PUBLISHED );
            if (!isset(self::$_cache[md5($sql)]))
            {

                $db->setQuery( $sql );

                $listmenu = $db->loadResult( );
                self::$_cache[md5($sql)] = $listmenu;

                $error = $db->getErrorMsg();
            }
            else
            {
                $listmenu = self::$_cache[md5($sql)];
            }
            foreach($listmenu as $menu)
            {
                if(strpos($menu->link,'index.php?option=com_easysocial&view=unity'))
                {
                    $id=$menu->id;
                    break;
                }
            }
           /* $sql->where( 'link' , 'index.php?option=com_easysocial&view=unity%' , 'LIKE' );

			$db->setQuery( $sql );
			$id 	= $db->loadResult();*/


			if( !$id )
			{
				$sql->select( '#__menu' );

				$sql->where( 'published' , SOCIAL_STATE_PUBLISHED );
                if (!isset(self::$_cache[md5($sql)]))
                {

                    $db->setQuery( $sql );

                    $listmenu = $db->loadResult( );
                    self::$_cache[md5($sql)] = $listmenu;

                    $error = $db->getErrorMsg();
                }
                else
                {
                    $listmenu = self::$_cache[md5($sql)];
                }
                foreach($listmenu as $menu)
                {
                    if(strpos($menu->link,'index.php?option=com_easysocial'))
                    {
                        $id=$menu->id;
                        break;
                    }
                }
			}

			if( !$id )
			{
				// Try to get from the current Itemid in query string
				$id 	= JRequest::getInt( 'Itemid' , 0 );
			}

			if( !$id )
			{
				// Try to get
				$id 	= false;
			}


			// clear sql object
			$sql->clear();
			$views[ 'system-default' ] = $id;
		}

		// Now we'll try to get the current itemid for this view.
		$key 	= $view . $layout;

		if( !isset( $views[ $key ] ) )
		{
			// Get the view's
            $sql->select( '#__menu' );
            $sql->where( 'published' , SOCIAL_STATE_PUBLISHED );
            $url 	= 'index.php?option=com_easysocial&view=' . $view;
            if (!isset(self::$_cache[md5($sql)]))
            {

                $db->setQuery( $sql );

                $listmenu = $db->loadResult( );
                self::$_cache[md5($sql)] = $listmenu;

                $error = $db->getErrorMsg();
            }
            else
            {
                $listmenu = self::$_cache[md5($sql)];
            }
            foreach($listmenu as $menu)
            {
                if(strpos($menu->link,$url))
                {
                    $defaultViewId=$menu->id;
                    break;
                }
            }


			// If layout is provided, check for the exact menu.						
			if( $layout )
			{
                $sql->select( '#__menu' );
                $sql->where( 'published' , SOCIAL_STATE_PUBLISHED );
                $url 	= $url . '&layout=' . $layout;
                if (!isset(self::$_cache[md5($sql)]))
                {

                    $db->setQuery( $sql );

                    $listmenu = $db->loadResult( );
                    self::$_cache[md5($sql)] = $listmenu;

                    $error = $db->getErrorMsg();
                }
                else
                {
                    $listmenu = self::$_cache[md5($sql)];
                }
                foreach($listmenu as $menu)
                {
                    if(strpos($menu->link,$url))
                    {
                        $id=$menu->id;
                        break;
                    }
                }
			}
			else
			{
				$id 	= $defaultViewId;
			}

			// If there's no item id, intelligently detect which fallback to use.
			if( !$id )
			{
				if( $defaultViewId )
				{
					$id 	= $defaultViewId;
				}
				else
				{
					$id  	= $views[ 'system-default' ];	
				}
			}

			$views[ $key ]	= $id;
		}

		return $views[ $key ];
	}
	
	/**
	 * Builds the controller url
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public static function controller( $name , $params = array() , $xhtml = true , $ssl = null )
	{
		// For controller urls, we shouldn't pass it to the router.
		$url 	= 'index.php?option=com_easysocial&controller=' . $name;
	
		// Determines if this url is an external url.
		$external 	= isset( $params[ 'external' ] ) ? $params[ 'external' ] : false;
		unset( $params[ 'external' ] );

		if( $params )
		{
			foreach( $params as $key => $value )
			{
				$url 	.= '&' . $key . '=' . $value;
			}
		}

		$url 	= FRoute::_( $url , $xhtml , '' , $ssl , false , $external );

		return $url;
	}

	/**
	 * Returns the sef url for registration links.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * // Returns routed url to registration page.
	 * echo FRoute::registration();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 */
	public static function registration()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the sef url for apps
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function apps()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the sef url for following links
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function points()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the sef url for following links
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function followers()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the sef url for profile links.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * // Returns routed url to registration page.
	 * echo FRoute::profile();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		(Optional) User's id.
	 */
	public static function profile()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the sef url for login links.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * // Returns routed url to registration page.
	 * echo FRoute::profile();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		(Optional) User's id.
	 */
	public static function login()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the sef url for login links.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * // Returns routed url to registration page.
	 * echo FRoute::profile();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		(Optional) User's id.
	 */
	public static function friends()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Calls the adapter file
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function callStatic( $view , $args )
	{
		$router 	= Foundry::router( $view );

		return call_user_func_array( array( $router , 'route' ) , $args );
	}

	/**
	 * Returns the sef url for activity logs
	 *
	 * Example:
	 * <code>
	 * <?php
	 * // Returns routed url to registration page.
	 * echo FRoute::profile();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		(Optional) User's id.
	 */
	public static function activities()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the sef url for activity logs
	 *
	 * Example:
	 * <code>
	 * <?php
	 * // Returns routed url to registration page.
	 * echo FRoute::profile();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		(Optional) User's id.
	 */
	public static function leaderboard()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}


	/**
	 * Returns the sef url for login links.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * // Returns routed url to registration page.
	 * echo FRoute::profile();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		(Optional) User's id.
	 */
	public static function conversations()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the sef url for login links.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * // Returns routed url to registration page.
	 * echo FRoute::profile();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		(Optional) User's id.
	 */
	public static function stream()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the sef url for badge links.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * // Returns routed url to badges page
	 * echo FRoute::badges();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		(Optional) User's id.
	 */
	public static function badges()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the URL to users
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function users()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the URL to albums
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function albums()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the URL to albums
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function photos()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the URL to search
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function search()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the URL to unity
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function unity()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}


	/**
	 * Returns the URL to dashboard
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function dashboard()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the sef url for login links.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * // Returns routed url to registration page.
	 * echo FRoute::profile();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		(Optional) User's id.
	 */
	public static function notifications()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Returns the URL to fields
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @return
	 */
	public static function fields()
	{
		$args 	= func_get_args();
		return self::callStatic( __FUNCTION__ , $args );
	}

	/**
	 * Parses url
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function parse( &$segments )
	{
		$vars 	= array();

		$app 	= JFactory::getApplication();

		// If there is only 1 segment and the segment is index.php, it's just submitting
		if( count( $segments ) == 1 && $segments[ 0 ] == 'index.php' )
		{
			return array();
		}

		// Get the menu object.
		$menu 	= $app->getMenu();

		// Get the active menu object.
		$active = $menu->getActive();

		// Check if the view exists in the segments
		$view 			= '';
		$viewExists 	= false;

		foreach( self::$views as $systemView )
		{
			if( SocialRouterAdapter::translate( $systemView ) == $segments[ 0 ] )
			{
				$view 			= $systemView;
				$viewExists 	= true;
				break;
			}
		}


		if( !$viewExists && $active )
		{
			// If there is no view in the segments, we treat it that the user
			// has created a menu item on the site.
			$view 	= $active->query[ 'view' ];

			// Add the view to the top of the element
			array_unshift( $segments , $view );
		}


		$router 	= Foundry::router( $view );

		$vars 		= $router->parse( $segments );

		return $vars;
	}

	/**
	 * Build urls
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function build( &$query )
	{
		$app 		= JFactory::getApplication();
		$segments 	= array();

		// If the itemid is not set in the query, we need to get the current menu that
		// is being accessed on the site.
		if( !isset( $query[ 'Itemid' ] ) )
		{
			$active 	= $app->getMenu()->getActive();
		}
		else
		{
			$active 	= $app->getMenu()->getItem( $query[ 'Itemid' ] );
		}

		if( !isset( $query[ 'view' ] ) )
		{
			return $segments;
		}

		// Get the view.
		$view 		= isset( $query[ 'view' ] ) ? $query[ 'view' ] : $active->query[ 'view' ];

		// Initialize router object
		$router		= Foundry::router( $view );

		$segments	= $router->build( $active , $query );


		return $segments;
	}
}
