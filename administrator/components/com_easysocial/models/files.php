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

class EasySocialModelFiles extends EasySocialModel
{
	private $data			= null;
	protected $pagination		= null;
	
	function __construct()
	{
		parent::__construct( 'files' );
	}

	/**
	 * Retrieves the pagination object based on the current query.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if ( empty( $this->pagination ) )
		{
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination( $this->total , $this->getState('limitstart') , $this->getState('limit') );
		}

		return $this->pagination;
	}

	/**
	 * Retrieves a list of files
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getItems( $options = array() )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$sql->select( '#__social_files' );

		$storage 	= isset( $options[ 'storage' ] ) ? $options[ 'storage' ] : '';

		if( $storage )
		{
			$sql->where( 'storage' , $storage );
		}

		$db->setQuery( $sql );

		$result		= $db->loadObjectList();

		if( !$result )
		{
			return $result;
		}

		$files 	= array();
		
		foreach( $result as $row )
		{
			$file 	= Foundry::table( 'File' );
			$file->bind( $row );

			$files[]	= $file;
		}

		return $files;
	}

	/**
	 * Retrieves a list of files for a particular type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param 	int 		$uid 		The unique id of the type.
	 * @param 	string		$type 		The unique string of the type.
	 * @param	Array 		$options	A list of options. ( state )
	 *
	 * @return	mixed 					False if none found, Array of SocialTableUploads if found.
	 */
	public function getFiles( $uid , $type , $options = array() )
	{
		$db 		= Foundry::db();

		$query 		= array();

		$query[]	= 'SELECT * FROM ' . $db->nameQuote( '#__social_files' );
		$query[]	= 'WHERE ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );

		// Ensure that uid is in an array form.
		$uid 		= Foundry::makeArray( $uid );

		$query[]	= 'AND ' . $db->nameQuote( 'uid' ) . ' IN (';

		foreach( $uid as $id )
		{
			$query[]	= $db->Quote( $id );

			if( next( $uid ) !== false )
			{
				$query[]	= ',';
			}
		}

		$query[]	= ')';

		if( isset( $options[ 'state' ] ) )
		{
			$publishOption 	= $options[ 'state' ] ? '1' : '0';

			$query	.= ' AND ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( $publishOption );
		}

		$query 		= implode( ' ' , $query );

		$db->setQuery( $query );

		$result 		= $db->loadObjectList();

		if( !$result )
		{
			return false;
		}

		$files 	= array();

		foreach( $result as $row )
		{
			$file 	= Foundry::table( 'File' );
			$file->bind( $row );

			$files[]	= $file;
		}

		return $files;
	}
}