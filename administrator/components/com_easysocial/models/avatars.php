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

jimport('joomla.application.component.model');

Foundry::import( 'admin:/includes/model' );

class EasySocialModelAvatars extends EasySocialModel
{
	function __construct()
	{
		parent::__construct( 'avatars' );
	}

	/**
	 * Deletes a list of default avatars given the unique id and type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function deleteDefaultAvatars( $uid , $type = SOCIAL_TYPE_PROFILES )
	{
		$avatars 	= $this->getDefaultAvatars( $uid , $type );

		if( !$avatars )
		{
			return;
		}

		jimport( 'joomla.filesystem.folder' );

		$config		= Foundry::config();

		// Build the path to the default avatars.

		// Get the default avatars storage location.
		$path 	= JPATH_ROOT . '/' . Foundry::cleanPath( $config->get( 'avatars.storage.container' ) ) . '/' . Foundry::cleanPath( $config->get( 'avatars.storage.default' ) );
		$path 	= $path . '/' . Foundry::cleanPath( $config->get( 'avatars.storage.defaults.' . $type ) );

		if( !JFolder::exists( $path ) )
		{
			$this->setError( JText::_( 'Default avatars path does not exist.' ) );
			return false;
		}

		$state = JFolder::delete( $path );

		return $state;
	}

	/**
	 * Retrieves the photo object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique id
	 * @param	string	The unique type. E.g: @SOCIAL_TYPE_USER
	 * @return	Array	A list of default avatars.
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getPhoto( $uid , $type = SOCIAL_TYPE_USER )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$sql->select( '#__social_avatars' , 'a' );
		$sql->column( 'b.*' );
		$sql->join( '#__social_photos' , 'b' , 'INNER' );
		$sql->on( 'a.photo_id' , 'b.id' );
		$sql->where( 'a.uid' , $uid );
		$sql->where( 'a.type' , $type );

		$db->setQuery( $sql );

		$result		= $db->loadObject();

		if( !$result )
		{
			return $result;
		}

		$photo 	= Foundry::table( 'Photo' );
		$photo->bind( $result );

		return $photo;
	}

	/**
	 * Retrieves a list of avatars on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getAvatars( $options = array() )
	{
		$db		= Foundry::db();
		$sql 	= $db->sql();

		$sql->select( '#__social_avatars' );

		$storage	= isset( $options[ 'storage' ] ) ? $options[ 'storage' ] : '';

		if( $storage )
		{
			$sql->where( 'storage' , $storage );
		}

		$uploaded	= isset( $options[ 'uploaded' ] ) ? $options[ 'uploaded' ] : '';

		if( $uploaded )
		{
			$sql->where( 'avatar_id' , 0 );
			$sql->where( 'small' , '' , '!=' );
			$sql->where( 'medium' , '' , '!=' );
			$sql->where( 'square' , '' , '!=' );
			$sql->where( 'large' , '' , '!=' );
		}
		
		$limit 	= isset( $options[ 'limit' ] ) ? $options[ 'limit' ] : 10;

		$sql->limit( $limit );

		$db->setQuery( $sql );

		$rows 	= $db->loadObjectList();

		if( !$rows )
		{
			return $rows;
		}

		$avatars 	= array();

		foreach( $rows as $row )
		{
			$avatar 	= Foundry::table( 'Avatar' );
			$avatar->bind( $row );

			$avatars[]	= $avatar;
		}

		return $avatars;
	}

	/**
	 * Retrieves a list of default avatars for a specific type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique id
	 * @param	string	The unique type. E.g: @SOCIAL_TYPE_USER
	 * @return	Array	A list of default avatars.
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getDefaultAvatars( $uid , $type = SOCIAL_TYPE_PROFILES )
	{
		$db 		= Foundry::db();
		$query		= array();

		$query[]	= 'SELECT * FROM ' . $db->nameQuote( '#__social_default_avatars' );
		$query[]	= 'WHERE ' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $uid );
		$query[]	= 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );

		$query 		= implode( ' ' , $query );
		$db->setQuery( $query );

		$result		= $db->loadObjectList();

		if( !$result )
		{
			return $result;
		}

		$avatars    = array();

		foreach( $result as $row )
		{
			$avatar = Foundry::table( 'DefaultAvatar' );
			$avatar->bind( $row );

			$avatars[]  = $avatar;
		}

		return $avatars;
	}

	/**
	 * Determines if one can use the default avatar given the unique id, unique type and default avatar id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function isAllowed( $id , $uid , $type = SOCIAL_TYPE_PROFILES )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$sql->select( '#__social_default_avatars' );
		$sql->where( 'id' , $id );
		$sql->where( 'uid' , $uid );
		$sql->where( 'type' , $type );

		$db->setQuery( $sql );

		$allowed 	= $db->loadResult() > 0 ? true : false;

		return $allowed;

	}
}
