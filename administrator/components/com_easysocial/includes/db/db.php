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
 * DB layer for EasySocial.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialDb
{
	static $instance	= null;

	public $db 			= null;

	public static function getInstance()
	{
		if( is_null( self::$instance ) )
		{
			self::$instance	= new self();
		}

		return self::$instance;
	}

	public function __construct()
	{
		$codeName	= Foundry::getInstance( 'Version' )->getCodeName();
		$fileName	= strtolower( $codeName );
		$helperFile	= dirname( __FILE__ ) . '/helpers/' . $fileName . '.php';

		require_once( $helperFile );
		$className	= 'SocialDBHelper' . ucfirst( $codeName );

		$this->db	= new $className();
	}

	/**
	 * Synchronizes the database tables columns with the existing structure
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function sync( $from = '' )
	{
		// List down files within the updates folder
		$path	= SOCIAL_ADMIN . '/updates';

		jimport( 'joomla.filesystem.folder' );
		jimport( 'joomla.filesystem.file' );

		$result	= array();

		if( $from )
		{
			$folders 	= JFolder::folders( $path );

			if( $folders )
			{
				foreach( $folders as $folder )
				{
					// Because versions always increments, we don't need to worry about smaller than (<) versions. 
					// As long as the folder is greater than the installed version, we run updates on the folder.
					if( $folder > $from )
					{
						$fullPath	= $path . '/' . $folder;

						// Get a list of sql files to execute
						$files 		= JFolder::files( $fullPath , '.' , false , true );

						foreach( $files as $file )
						{
							$result	= array_merge( $result , Foundry::makeObject( $file ) );
						}
					}
				}
			}
		}
		else
		{
			$files	= JFolder::files( $path , '.' , true , true );
				
			// If there is nothing to process, skip this
			if( !$files )
			{
				return false;
			}

			foreach( $files as $file )
			{
				$result	= array_merge( $result , Foundry::makeObject( $file ) );
			}
		}

		if( !$result )
		{
			return false;
		}

		$tables		= array();
		$affected	= 0;

		foreach( $result as $row )
		{
			// Store the list of tables that needs to be queried
			if( !isset( $tables[ $row->table ] ) )
			{
				$tables[ $row->table ]	= $this->getTableColumns( $row->table );
			}

			// Check if the column is in the fields or not
			$exists		= in_array( $row->column , $tables[ $row->table ] );
			
			if( !$exists )
			{
				$sql	= $this->sql();
				$sql->raw( $row->query );

				$this->setQuery( $sql );
				$this->Query();

				$affected	+= 1;
			}
		}

		return $affected;
	}

	/**
	 * Retrieve table columns
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getTableColumns( $tableName )
	{
		$t_start = microtime(true);
		$sql	= $this->sql();

		$query	= 'SHOW FIELDS FROM ' . $this->nameQuote( $tableName );

		$sql->raw( $query );

		$this->setQuery( $sql );

		$rows	= $this->loadObjectList();
		$fields	= array();

		foreach( $rows as $row )
		{
			$fields[]	= $row->Field;
		}
		
		return $fields;
	}

	/**
	 * Helper to load our own sql string helper.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function sql()
	{
		$sql 	= Foundry::sql();

		return $sql;
	}

	/**
	 * Override JDatabase setQuery behavior.
	 */
	public function setQuery( $query , $offset = 0 , $limit = 0 )
	{
		if( is_array( $query ) )
		{
			$query 	= implode( ' ' , $query );
		}

		return call_user_func_array( array( $this->db , __FUNCTION__ ) , array( $query , $offset , $limit ) );
	}

	public function __call( $method , $args )
	{
		return call_user_func_array( array( $this->db , $method ) , $args );
	}
}