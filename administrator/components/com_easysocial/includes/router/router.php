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

/**
 * Component's router.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialRouter
{
	/**
	 * Stores itself to be used statically.
	 * @var	SocialRouter
	 */
	static $instances	= array();

	private $adapter 	= null;

	/**
	 * Creates a copy of it self and return to the caller.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialParameter
	 *
	 */
	public static function getInstance( $view )
	{
		if( !isset( self::$instances[ $view ] ) )
		{
			self::$instances[ $view ]	= new self( $view );
		}

		return self::$instances[ $view ];
	}

	/**
	 * Class Constructur.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The type of routing object.
	 */
	public function __construct( $view )
	{
		$file 	= dirname( __FILE__ ) . '/adapters/' . $view . '.php';

		if( !JFile::exists( $file ) )
		{
			return false;
		}

		require_once( $file );

		$className 		= 'SocialRouter' . ucfirst( $view );
		$this->adapter 	= new $className( $view );
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function parse( &$segments )
	{
		if( is_null( $this->adapter) )
		{
			return array();
		}

		$vars	= $this->adapter->parse( $segments );

		return $vars;
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function build( &$menu , &$query )
	{
		if( is_null( $this->adapter) )
		{
			return array();
		}

		if( !method_exists( $this->adapter , 'build' ) )
		{
			dump( $this->adapter );
			dump( 'here' );
		}

		$segments	= $this->adapter->build( $menu , $query );

		return $segments;
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function route()
	{
		$args 		= func_get_args();

		if( count( $args ) > 0 )
		{
			$options 	= $args[ 0 ];

			$args[0]['ssl'] 		= isset( $options[ 'ssl' ] ) ? $options[ 'ssl' ] : null;
			$args[0]['tokenize']	= isset( $options[ 'tokenize' ] ) ? $options[ 'tokenize' ] : null;
			$args[0]['external'] 	= isset( $options[ 'external' ]  ) ? $options[ 'external' ] : null;
			$args[0]['tmpl']		= isset( $options[ 'tmpl' ] ) ? $options[ 'tmpl' ] : null;
			$args[0]['controller']	= isset( $options[ 'controller' ] ) ? $options[ 'controller' ] : null;
		}
		else
		{
			$args[0]			= array();
			$args[0]['ssl']		= null;
			$args[0]['tokenize'] = null;
			$args[0]['external'] = null;
			$args[0]['tmpl']	= null;
			$args[0]['controller'] = '';
		}

		return call_user_func_array( array( $this->adapter , __FUNCTION__ ) , $args );
	}
}

abstract class SocialRouterAdapter
{
	static $base 	= 'index.php?option=com_easysocial';

	public $name;

	public function __construct( $view )
	{
		Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );

		$this->name 	= $view;
	}

	/**
	 * Translates a url
	 *
	 * @since	1.0
	 * @access	public
	 */
	public static function translate( $str )
	{
		Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );

		$str 	= JString::strtoupper( $str );

		$text 	= 'COM_EASYSOCIAL_ROUTER_' . $str;

		return JText::_( $text );
	}

	/**
	 * Builds the URLs for apps view
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	An array of request arguments
	 * @param	bool	Determines if the url should be xhtml compliant
	 * @return	url	 	The string of the URL
	 */
	public function route( $options = array() , $xhtml = true )
	{
		$url		= self::$base . '&view=' . $this->name;
		$ssl 		= $options[ 'ssl' ];
		$tokenize	= $options[ 'tokenize' ];
		$external	= $options[ 'external' ];
		$tmpl 		= $options[ 'tmpl' ];
		$layout 	= isset( $options[ 'layout' ] ) ? $options[ 'layout' ] : '';

		// Determines if this is a request to the controller
		$controller	= $options[ 'controller' ];
		$data 		= array();

		unset( $options[ 'ssl' ] , $options[ 'tokenize' ] , $options[ 'external' ] , $options[ 'tmpl' ] , $options[ 'controller' ] );

		if( $options )
		{
			foreach( $options as $key => $value )
			{
				$data[]	= $key . '=' . $value;
			}
		}

		$options 	= implode( '&' , $data );
		$join 		= !empty( $options ) ? '&' : '';
		$url 		= $url . $join . $options;

		return FRoute::_( $url , $xhtml , array( $this->name , $layout ) , $ssl , $tokenize , $external , $tmpl , $controller );
	}

	/**
	 * Retrieves the user id
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getUserId( $permalink )
	{
		static $loaded 	= array();

		// Joomla always replaces the first : with a -
		$permalink	= str_ireplace( ':' , '-' , $permalink );
		
		if( !isset( $loaded[ $permalink ] ) )
		{
			$config 	= Foundry::config();

			// Always test for the user's stored permalink first.
			$model 		= Foundry::model( 'Users' );
			$id 		= $model->getUserFromPermalink( $permalink );

			if( $id )
			{
				$loaded[ $permalink ]	= $id;

				return $loaded[ $permalink ];
			}

			// Always test for the user's stored permalink first.
			$id 		= $model->getUserFromAlias( $permalink );

			if( $id )
			{
				$loaded[ $permalink ]	= $id;

				return $loaded[ $permalink ];
			}

			// If there's no permalink or alias found for the user, we know the syntax
			// by default would be ID:Username or ID:Full Name
			$loaded[ $permalink ]		= $this->getIdFromPermalink( $permalink );

			return $loaded[ $permalink ];
		}

		return $loaded[ $permalink ];
	}

	/**
	 * Returns the user's permalink
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getUserPermalink( $fragment )
	{
		static $users = array();

		if( !isset( $users[ $fragment ] ) )
		{
			$config	= Foundry::config();

			// Since id is always in ID:alias format.
			$id		= explode( ':' , $fragment );
			
			$segment	= '';

			if( count( $id ) == 1 )
			{
				$segment	= $id[ 0 ];
			}
			else
			{
				// Check whether this is a user alias.
				$permalink 	= $id[ 1 ];

				// If this is an alias that the user set, just use it as is
				$model 	= Foundry::model( 'Users' );
				if( $config->get( 'users.aliasName' ) == 'username' || $model->isValidUserPermalink( $permalink ) )
				{
					$segment	= $permalink;
				}
				else
				{
					// Otherwise, this is a real name and we have to always prepend the id.
					$segment	= $id[ 0 ] . ':' . $permalink;
				}
			}

			$users[ $fragment ]	= $segment;
		}

		return $users[ $fragment ];
	}
	
	/**
	 * Retrieves the id based on the permalink
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getIdFromPermalink( $permalink )
	{
		$id 	= $permalink;

		if( strpos( $permalink , ':' ) !== false )
		{
			$parts 	= explode( ':' , $permalink , 2 );

			$id 	= $parts[ 0 ];
		}

		return $id;
	}

	/**
	 * Retrieves a list of layouts from a particular view
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The name of the view
	 * @return
	 */
	public function getAvailableLayouts( $viewName )
	{
		$viewName	= (string) $viewName;
		$file 		= SOCIAL_SITE . '/views/' . strtolower( $viewName ) . '/view.html.php';

		jimport( 'joomla.filesystem.file' );

		if( !JFile::exists( $file ) )
		{
			return array();
		}

		require_once( $file );


		$layouts 	= get_class_methods( 'EasySocialView' . $viewName );

		return $layouts;

	}
}
