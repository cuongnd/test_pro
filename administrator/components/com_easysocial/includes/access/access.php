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
 * Access object.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialAccess
{
	/**
	 * The unique id that is associated with the access rules.
	 * @var	int
	 */
	private $uid 	= null;

	/**
	 * The unique type that is associated with the access rules.
	 * @var	string
	 */
	private $type 	= null;

	/**
	 * The Registry that stores the user access.
	 * @var Array
	 */
	private $access	= null;

	/**
	 * Cache the default values so that it only load once.
	 * @var string
	 */
	private $default 	= null;

	/**
	 * Class Constructor
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct( $userId = null, $type = SOCIAL_TYPE_USER )
	{
		$this->loadAccess( $userId, $type );
	}

	private function loadAccess( $userId = null, $type = SOCIAL_TYPE_USER )
	{
		// This is to prevent unnecessary multiple loading per user id
		static $loadedAccess = array();

		$profileId = null;

		// If type is user then we deduce the profile id from the user
		if( $type === SOCIAL_TYPE_USER )
		{
			// Get the user object
			$my 		= Foundry::user( $userId );
			$profileId	= $my->profile_id;
		}

		// If type is profile, then we just directly use it as profile id
		if( $type === SOCIAL_TYPE_PROFILES )
		{
			$profileId = $userId;
		}


		if( empty( $loadedAccess[ $profileId ] ) )
		{
			$default 	= $this->getDefaultValues();

			// Load up the access based on the profile
			$model	 		= Foundry::model( 'Access' );
			$storedAccess	= $model->getParams( $profileId , SOCIAL_TYPE_PROFILES );

			// Merge all the group registries first.
			$registry 	= Foundry::registry( $storedAccess );

			// Merge the default with the stored access settings
			$default->mergeObjects( $registry );

			$loadedAccess[ $profileId ]	= $default;
		}

		$this->access = $loadedAccess[ $profileId ];

		return $this;
	}

	/**
	 * Get default values from the files first.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getDefaultValues()
	{
		if( empty( $this->default ) )
		{
			// Load up the default access first
			// The default path for the raw configuration.
			$file 	= SOCIAL_ADMIN_DEFAULTS . '/access.json';

			$registry 	= Foundry::registry( $file );

			$this->default 	= $registry;
		}

		return $this->default;
	}

	/**
	 * Factory method to create a new access object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	The unique id that is tied to the access.
	 * @param	int		The unique type that is tied to the access.
	 */
	public static function factory( $userId = null, $type = SOCIAL_TYPE_USER )
	{
		$obj 	= new self( $userId, $type );

		return $obj;
	}

	/**
	 * Detect if the user is allowed to perform specific actions.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function get( $rule )
	{
		if( !$this->access )
		{
			return false;
		}

		return $this->access->get( $rule );
	}

	/**
	 * Detect if the user is allowed to perform specific actions.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function allowed( $rule , $default = true )
	{
		if( !$this->access )
		{
			return false;
		}

		return $this->access->get( $rule , $default );
	}

	/**
	 * Determines if a rule item exceeded the usage.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int 	The number of usage allowed
	 * @return	bool	True if allowed, false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function exceeded( $rule , $usage , $default = true )
	{
		$limit 		= (int) $this->access->get( $rule , $default );
		$exceeded	= $usage >= $limit;

		return $exceeded;
	}
}
