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

// Include parent model.
Foundry::import( 'admin:/includes/model' );

/**
 * Model for badges
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class EasySocialModelBadges extends EasySocialModel
{
	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	function __construct( $config = array() )
	{
		parent::__construct( 'badges' , $config );
	}

	/**
	 * Initializes all the generic states from the form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	protected function initStates()
	{
		$callback 		= JRequest::getVar( 'jscallback' , '' );
		$defaultFilter	= $callback ? SOCIAL_STATE_PUBLISHED : 'all';

		$filter 	= $this->getUserStateFromRequest( 'state' , $defaultFilter );
		$extension	= $this->getUserStateFromRequest( 'extension' , 'all' );


		$this->setState( 'state' , $filter );
		$this->setState( 'extension' , $extension );

		parent::initStates();
	}

	/**
	 * Scans through the given path and see if there are any *.points file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The path type. E.g: components , plugins, apps , modules
	 * @return
	 */
	public function scan( $path )
	{
		jimport( 'joomla.filesystem.folder' );

		$files 	= array();

		if( $path == 'admin' || $path == 'components' )
		{
			$directory	= JPATH_ROOT . '/administrator/components';
		}

		if( $path == 'site' )
		{
			$directory	= JPATH_ROOT . '/components';
		}

		if( $path == 'apps' )
		{
			$directory 	= SOCIAL_APPS;
		}

		if( $path == 'fields' )
		{
			$directory 	= SOCIAL_FIELDS;
		}

		if( $path == 'plugins' )
		{
			$directory 	= JPATH_ROOT . '/plugins';
		}

		if( $path == 'modules' )
		{
			$directory	 = JPATH_ROOT . '/modules';
		}

		$files 		= JFolder::files( $directory , '.badge$' , true , true );

		return $files;
	}

	/**
	 * Delete associations of a badge from a user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The badge id
	 * @return	bool	True on success, false otherwise.
	 */
	public function deleteAssociations( $badgeId , $userId = '' )
	{
		$db 		= Foundry::db();
		$sql		= $db->sql();

		// @TODO: Trigger before deleting badge associations

		$sql->delete( '#__social_badges_maps' );
		$sql->where( 'badge_id' , $badgeId );

		if( !empty( $userId ) )
		{
			$sql->where( 'user_id' , $userId );
		}

		$db->setQuery( $sql );

		$db->Query();

		// @TODO: Trigger after deleting badge associations

		return true;
	}

	/**
	 * Retrieve a number of users who achieved this badge.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	int		The total number of users who achieved this badge.
	 */
	public function getTotalAchievers( $badgeId )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_badges', 'a' );

		$sql->innerjoin( '#__social_badges_maps' , 'b' );
		$sql->on( 'a.id', 'b.badge_id' );


		$sql->join( '#__users' , 'uu' , 'INNER' );
		$sql->on( 'b.user_id' , 'uu.id' );
		$sql->where( 'uu.block' , '0' );


		$sql->where( 'a.id', $badgeId );

		$db->setQuery( $sql->getTotalSql() );

		$total	= $db->loadResult();

		return $total;
	}

	/**
	 * Retrieves the total number of badges a user has
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user's id.
	 * @return	int		The total number of users who achieved this badge.
	 */
	public function getTotalBadges( $userId )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_badges_maps' , 'a' );
		$sql->join( '#__social_badges' , 'b' , 'INNER' );
		$sql->on( 'a.badge_id' , 'b.id' );
		$sql->where( 'a.user_id' , $userId );
		$sql->where( 'b.state' , SOCIAL_STATE_PUBLISHED );

		$db->setQuery( $sql->getTotalSql() );

		$total	= $db->loadResult();

		return $total;
	}

	/**
	 * Retrieves the achievers of the provided badge id
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The badge id
	 * @return	Array	An array of SocialUser objects
	 */
	public function getAchievers( $badgeId, $options = array() )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_badges_maps' , 'a' );
		$sql->column( 'a.user_id' );

		$sql->join( '#__users' , 'uu' , 'INNER' );
		$sql->on( 'a.user_id' , 'uu.id' );
		$sql->where( 'uu.block' , '0' );

		$sql->join( '#__social_badges' , 'b' , 'INNER' );
		$sql->on( 'a.badge_id' , 'b.id' );
		$sql->where( 'a.badge_id' , $badgeId );

		if( isset( $options['limit'] ) && isset( $options['start'] ) )
		{
			$sql->limit( $options['start'], $options['limit'] );
		}

		$db->setQuery( $sql );

		$rows 	= $db->loadColumn();

		if( !$rows )
		{
			return $rows;
		}

		$users 	= Foundry::user( $rows );

		return $users;
	}

	/**
	 * Retrieve a list of badges from the site
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool	True if user had already achieved this badge.
	 */
	public function getExtensions()
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_badges' );
		$sql->column( 'DISTINCT `extension`' );

		$db->setQuery( $sql );

		$result 	= $db->loadObjectList();

		if( !$result )
		{
			return $result;
		}

		$extension 	= array();

		foreach( $result as $row )
		{
			$extensions[]	= $row->extension;
		}

		return $extensions;
	}

	/**
	 * Retrieve a list of badges from the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of options
	 * @return	Array	An array of SocialBadgeTable objects.
	 */
	public function getItemsWithState( $options = array() )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$sql->select( '#__social_badges' );

		$extension 	= $this->getState( 'extension' );

		if( $extension != 'all' && !is_null( $extension ) )
		{
			$sql->where( 'extension' , $extension );
		}

		// Check for search
		$search 	= $this->getState( 'search' );

		if( $search )
		{
			$sql->where( 'title' , '%' . $search . '%' , 'LIKE' );
		}

		// Check for ordering
		$ordering 	= $this->getState( 'ordering' );

		if( $ordering )
		{
			$direction	 = $this->getState( 'direction' ) ? $this->getState( 'direction' ) : 'DESC';

			$sql->order( $ordering , $direction );
		}

		// Check for state
		$state 		= $this->getState( 'state' );

		if( $state != 'all' && !is_null( $state ) )
		{
			$sql->where( 'state' , $state );
		}

		$limit 	= isset( $options[ 'limit' ] ) ? $options[ 'limit' ] : 0;

		if( $limit != 0 )
		{
			$this->setState( 'limit' , $limit );

			// Get the limitstart.
			$limitstart 	= $this->getUserStateFromRequest( 'limitstart' , 0 );
			$limitstart 	= ( $limit != 0 ? ( floor( $limitstart / $limit ) * $limit ) : 0 );

			$this->setState( 'limitstart' , $limitstart );

			// Set the total number of items.
			$this->setTotal( $sql->getTotalSql() );

			// Get the list of users
			$result 	= $this->getData( $sql->getSql() );
		}
		else
		{
			$db->setQuery( $sql );
			$result 	= $db->loadObjectList();
		}

		if( !$result )
		{
			return $result;
		}

		$badges 	= array();

		// Load the admin language file whenever there's badges.
		JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

		foreach( $result as $row )
		{
			$badge 	= Foundry::table( 'Badge' );
			$badge->bind( $row );

			$badges[]	= $badge;
		}

		return $badges;
	}

	/**
	 * Retrieve a list of badges from the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of options
	 * @return	Array	An array of SocialBadgeTable objects.
	 */
	public function getItems( $options = array() )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$sql->select( '#__social_badges' );

		$extension 	= $this->getState( 'extension' );

		if( $extension != 'all' && !is_null( $extension ) )
		{
			$sql->where( 'extension' , $extension );
		}

		// Check for search
		$search 	= $this->getState( 'search' );

		if( $search )
		{
			$sql->where( 'title' , '%' . $search . '%' , 'LIKE' );
		}

		// Check for ordering
		$ordering 	= $this->getState( 'ordering' );

		if( $ordering )
		{
			$direction	 = $this->getState( 'direction' ) ? $this->getState( 'direction' ) : 'DESC';

			$sql->order( $ordering , $direction );
		}

		// Check for state
		$state 		= isset( $options[ 'state' ] ) ? $options[ 'state' ] : null;

		if( !is_null( $state ) )
		{
			$sql->where( 'state' , $state );
		}

		$limit 	= isset( $options[ 'limit' ] ) ? $options[ 'limit' ] : 0;

		if( $limit != 0 )
		{
			$this->setState( 'limit' , $limit );

			// Get the limitstart.
			$limitstart 	= $this->getUserStateFromRequest( 'limitstart' , 0 );
			$limitstart 	= ( $limit != 0 ? ( floor( $limitstart / $limit ) * $limit ) : 0 );

			$this->setState( 'limitstart' , $limitstart );

			// Set the total number of items.
			$this->setTotal( $sql->getTotalSql() );

			// Get the list of users
			$result 	= $this->getData( $sql->getSql() );
		}
		else
		{
			$db->setQuery( $sql );
			$result 	= $db->loadObjectList();
		}

		if( !$result )
		{
			return $result;
		}

		$badges 	= array();

		// Load the admin language file whenever there's badges.
		JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

		foreach( $result as $row )
		{
			$badge 	= Foundry::table( 'Badge' );
			$badge->bind( $row );

			$badges[]	= $badge;
		}

		return $badges;
	}

	/**
	 * Retrieves a list of badges earned by a specific user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getBadges( $userId )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_badges' , 'a' );
		$sql->column( 'a.*' );
		$sql->column( 'b.custom_message' , 'custom_message' );
		$sql->column( 'b.created' , 'achieved_date' );
		$sql->join( '#__social_badges_maps' , 'b' );
		$sql->on( 'b.badge_id' , 'a.id' );
		$sql->where( 'b.user_id' , $userId );
		$sql->where( 'a.state' , SOCIAL_STATE_PUBLISHED );

		$db->setQuery( $sql );

		// Get a list of badges
		$result = $db->loadObjectList();

		if( !$result )
		{
			return $result;
		}

		$badges 	= array();

		$loadedLanguage = array();

		foreach( $result as $row )
		{
			$badge 	= Foundry::table( 'Badge' );
			$badge->bind( $row );

			if( !empty( $row->extension ) && $row->extension !== SOCIAL_COMPONENT_NAME && !in_array( $row->extension, $loadedLanguage ) )
			{
				Foundry::language()->load( $row->extension, JPATH_ROOT );
				Foundry::language()->load( $row->extension, JPATH_ADMINISTRATOR );
			}

			$badge->achieved_date	= $row->achieved_date;
			$badge->custom_message	= $row->custom_message;
			$badges[]	= $badge;
		}

		return $badges;
	}

	/**
	 * Determines if the user has achieved the badge before.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique badge id.
	 * @param	int		The user's id.
	 * @return	bool	True if user had already achieved this badge.
	 */
	public function hasAchieved( $badgeId , $userId )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		// Build the column selection
		$sql->select( '#__social_badges_maps' );

		// Build the where
		$sql->where( 'user_id'	, $userId );
		$sql->where( 'badge_id'	, $badgeId );

		// Execute this
		$db->setQuery( $sql->getTotalSql() );

		$achieved	= $db->loadResult() > 0;

		return $achieved;
	}

	/**
	 * Delete history of a badge from a user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The badge id
	 * @return	bool	True on success, false otherwise.
	 */
	public function deleteHistory( $badgeId , $userId = '' )
	{
		$db 		= Foundry::db();
		$sql		= $db->sql();

		// @TODO: Trigger before deleting badge history

		$sql->delete( '#__social_badges_history' );
		$sql->where( 'badge_id' , $badgeId );

		if( !empty( $userId ) )
		{
			$sql->where( 'user_id' , $userId );
		}

		$db->setQuery( $sql );

		$db->Query();

		// @TODO: Trigger after deleting badge history

		return true;
	}

	/**
	 * Determines if the user has reached the frequency of the badge threshold.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique badge id.
	 * @param	int		The user's id.
	 * @param	bool	Determines if caller wants to increment by one to determine if the frequency threshold is reached.
	 * @return
	 */
	public function hasReachedFrequency( $badgeId , $userId , $incrementByOne = true )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		// Build the column selection
		$sql->select( '#__social_badges', 'a' );
		$sql->column( 'COUNT(1)', 'total' );
		$sql->column( 'a.frequency', 'frequency' );

		// Build join query.
		//$sql->innerjoin( '#__social_badges_maps', 'b' );
		$sql->innerjoin( '#__social_badges_history', 'b' );
		$sql->on( 'b.badge_id', 'a.id' );

		// Build where conditions
		$sql->where( 'a.id', $badgeId );
		$sql->where( 'b.user_id', $userId );

		// Group results
		$sql->group( 'a.id' );

		$db->setQuery( $sql );

		$data 	= $db->loadObject();

		if( !$data )
		{
			return false;
		}

		if( $incrementByOne )
		{
			$data->total 	+= 1;
		}

		return $data->total >= $data->frequency;
	}


	/**
	 * Given a path to the file, install the badge rule file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The path to the .points file.
	 * @return	bool		True if success false otherwise.
	 */
	public function install( $path )
	{
		// Import platform's file library.
		jimport( 'joomla.filesystem.file' );

		// Read the contents
		$contents 	= JFile::read( $path );

		// If contents is empty, throw an error.
		if( empty( $contents ) )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'BADGES: Unable to read the file ' . $path );
			$this->setError( JText::_( 'COM_EASYSOCIAL_BADGES_UNABLE_TO_READ_BADGE_FILE' ) );
			return false;
		}

		$json 		= Foundry::json();
		$data 		= $json->decode( $contents );

		// @TODO: Double check that this file is a valid JSON file.

		// Ensure that it's in an array form.
		$data 		= Foundry::makeArray( $data );

		// Let's test if there's data.
		if( empty( $data ) )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'POINTS: Unable to read the file ' . $path );
			$this->setError( JText::_( 'COM_EASYSOCIAL_BADGES_UNABLE_TO_READ_BADGE_FILE' ) );
			return false;
		}

		$result 	= array();

		foreach( $data as $row )
		{
			// Load the tables
			$badge 	= Foundry::table( 'Badge' );

			// If this already exists, we need to skip this.
			$state 	= $badge->load( array( 'extension' => $row->extension , 'command' => $row->command ) );

			if( $state )
			{
				continue;
			}

			// Set to published by default.
			$badge->state 	= SOCIAL_STATE_PUBLISHED;

			// Bind the badge data.
			$badge->bind( $row );

			// Store it now.
			$badge->store();

			// Load language file.
			JFactory::getLanguage()->load( $row->extension , JPATH_ROOT . '/administrator' );

			$result[]	= JText::_( $badge->title );
		}

		return $result;
	}
}
