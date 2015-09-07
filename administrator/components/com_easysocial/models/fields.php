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

class EasySocialModelFields extends EasySocialModel
{
	private $data			= null;
	protected $total			= null;

	function __construct()
	{
		parent::__construct( 'fields' );
	}

	/**
	 * Adds a new child item into `#__social_fields_options`.
	 * This uses the first in first out method. Before a new set of items are being inserted,
	 * the previous set would be deleted first.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int			The unique id for the field
	 * @param	string		The title for the child option.
	 * @return	boolean		True if success false otherwise.
	 */
	public function addChilds( $fieldId , $titles = array() )
	{
		if( !$titles )
		{
			return false;
		}

		$db 		= Foundry::db();
		$query 		= array();

		$query[]	= 'DELETE FROM ' . $db->nameQuote( '#__social_fields_options' );
		$query[]	= 'WHERE ' . $db->nameQuote( 'parent_id' ) . '=' . $db->Quote( $fieldId );

		$query 		= implode( ' ' , $query );
		$db->setQuery( $query );
		$db->Query();

		// Now let's loop through all the titles.
		$query		= array();
		$query[]	= 'INSERT INTO ' . $db->nameQuote( '#__social_fields_options' );
		$query[]	= '(' . $db->nameQuote( 'parent_id' ) . ',' . $db->nameQuote( 'title' ) . ')';

		$query[]	= 'VALUES';

		foreach( $titles as $title )
		{
			$query[]	= '(' . $db->Quote( $fieldId ) . ',' . $db->Quote( $title ) . ')';

			if( next( $titles ) !== false )
			{
				$query[]	= ',';
			}
		}

		// Glue the query back.
		$query 		= implode( ' ' , $query );
		$db->setQuery( $query );

		$db->Query();

		return true;
	}

	/**
	 * Retrieves the maximum sequence for a specific profile type.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $model 	= Foundry::model( 'Registration' );
	 * $model->getMaxSequence( $profileId )
	 *
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique profile id.
	 * @return	int		The last sequence for the profile type.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getMaxSequence( $uid , $type = SOCIAL_TYPE_USER, $mode = null )
	{
		$db         = Foundry::db();
		$query 		= array();
		$query[]	= 'SELECT MAX(' . $db->nameQuote( 'sequence' ) . ')';
		$query[]	= 'FROM ' . $db->nameQuote( '#__social_fields_steps' );
		$query[]	= 'WHERE ' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $uid );
		$query[]	= 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );

		if( !empty( $mode ) )
		{
			$query[] = 'AND ' . $db->nameQuote( 'visible_' . $mode ) . '=' . $db->Quote( '1' );
		}

		$query 		= implode( ' ' , $query );
		$db->setQuery( $query );

		$max		= (int) $db->loadResult();

		return $max;
	}


	/**
	 * Retrieve a list of field id's for this specific profile type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique id.
	 * @param	string	The unique item identifier.
	 * @return	Array	An array of field id's.
	 */
	public function getStorableFields( $uid , $type )
	{
		$db			= Foundry::db();
		$query		= array();

		$query[]	= 'SELECT a.' . $db->nameQuote( 'id' );
		$query[]	= 'FROM ' . $db->nameQuote( '#__social_fields' ) . ' AS a';
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__social_fields_steps' ) . ' AS b';
		$query[]	= 'ON a.' . $db->nameQuote( 'step_id' ) . ' = b.' . $db->nameQuote( 'id' );
		$query[]	= 'WHERE b.' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $uid );
		$query[]	= 'AND b.' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );
		$query[]	= 'AND a.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_STATE_PUBLISHED );

		// We don't want to store the core fields in our own database.
		// $query[]	= 'AND a.' . $db->nameQuote( 'core' ) . '=' . $db->Quote( 0 );

		$query 		= implode( ' ' , $query );

		$db->setQuery( $query );

		$ids	= $db->loadColumn();

		return $ids;
	}

	/**
	 * Retrieves the total number of steps for the particular profile type.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $model 	= Foundry::model( 'Profiles' );
	 *
	 * // Returns the count in integer.
	 * $model->getTotalSteps( JRequest::getInt( 'id' ) );
	 *
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param   int 	The profile id.
	 * @return  int		The number of steps involved for this profile type.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getTotalSteps( $uid , $type = SOCIAL_TYPE_USER )
	{
		$db			= Foundry::db();

		$query		= array();
		$query[]	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__social_fields_steps' );
		$query[]	= 'WHERE ' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $uid );
		$query[]	= 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );
		$query[]	= 'AND ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_STATE_PUBLISHED );

		$query 		= implode( ' ' , $query );

		$db->setQuery( $query );

		$result	= (int) $db->loadResult();

		return $result;
	}

	public function getItems( $options = array() )
	{
		$db		= Foundry::db();
		$query	= 'SELECT * FROM ' . $db->nameQuote( '#__social_apps' )
				. ' WHERE ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( SOCIAL_APPS_TYPE_FIELDS )
				. ' AND ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_STATE_PUBLISHED );

		if( is_array( $options ) )
		{
			foreach( $options as $key => $value )
			{
				$sql[]  = $this->_db->nameQuote( $key ) . '=' . $this->_db->Quote( $value );
			}
		}

		if( !empty( $sql ) )
		{
			$query	.= implode( ' AND ' , $sql );
		}

		$db->setQuery( $query );

		return $db->loadObjectList();
	}

	/**
	 * Get's a list of position given the current field id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique field id.
	 * @return	Array	An array of string positions
	 */
	public function getPositions( $fieldId )
	{
		$db 		= Foundry::db();

		$query		= array();
		$query[]	= 'SELECT * FROM ' . $db->nameQuote( '#__social_fields_position' );
		$query[]	= 'WHERE ' . $db->nameQuote( 'field_id' ) . '=' . $db->Quote( $fieldId );

		// Glue the query.
		$query		= implode( ' ' , $query );

		$db->setQuery( $query );

		$positions	= $db->loadObjectList();

		return $positions;
	}

	/**
	 * Retrieves fields from a specific position
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique item id.
	 * @param	string	The unique item type.
	 */
	public function getPositionData( $uid , $type , $position )
	{
		$db 		= Foundry::db();

		$query		= array();
		$query[]	= 'SELECT a.*,b.*, c.' . $db->nameQuote( 'element') . ' FROM ' . $db->nameQuote( '#__social_fields_data' ) . ' AS a';
		$query[]	= 'LEFT JOIN ' . $db->nameQuote( '#__social_fields' ) . ' AS b';
		$query[]	= 'ON a.' . $db->nameQuote( 'field_id' ) . ' = b.' . $db->nameQuote( 'id' );

		$query[]	= 'LEFT JOIN ' . $db->nameQuote( '#__social_apps' ) . ' AS c';
		$query[]	= 'ON b.' . $db->nameQuote( 'app_id' ) . ' = c.' . $db->nameQuote( 'id' );

		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__social_fields_position' ) . ' AS d';
		$query[]	= 'ON d.' . $db->nameQuote( 'field_id' ) . ' = a.' . $db->nameQuote( 'field_id' );

		$query[]	= 'WHERE a.' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $uid );
		$query[]	= 'AND a.' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );
		$query[]	= 'AND d.' . $db->nameQuote( 'position' ) . '=' . $db->Quote( $position );

		// Glue back the query.
		$query 		= implode( ' ' , $query );
		// dump( $query );
		$db->setQuery( $query );

		$data 	= $db->loadObjectList();

		return $data;
	}

	/**
	 * Retrieves a list of data for a type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique item id.
	 * @param	string	The unique item type.
	 */
	public function getFieldsData( $uid , $type )
	{
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$sql->select( '#__social_fields_data' , 'a' );
		$sql->column( 'a.*' );
		$sql->column( 'b.*' );
		$sql->column( 'c.element' );
		$sql->join( '#__social_fields' , 'b' , 'LEFT' );
		$sql->on( 'a.field_id' , 'b.id' );
		$sql->join( '#__social_apps' , 'c' );
		$sql->on( 'b.app_id' , 'c.id' );
		$sql->where( 'a.uid' , $uid );
		$sql->where( 'a.type' , $type );

		$db->setQuery( $sql );

		$data 	= $db->loadObjectList();

		if( !$data )
		{
			return false;
		}

		$fields 	= array();

		foreach( $data as $row )
		{
			$table		= Foundry::table( 'Field' );
			$table->bind( $row );

			$fields[]	= $table;
		}

		return $fields;
	}

	/*
	 * Retrieves a list of fields which is editable by the user.
	 *
	 */
	public function getFieldItems( &$groups )
	{
		$db     = Foundry::db();

		foreach( $groups as $group )
		{
			$query  = 'SELECT a.*,b.title AS addon_title , b.element AS addon_element FROM ' . $db->nameQuote( '#__social_fields' ) . ' AS a '
					. 'INNER JOIN ' . $db->nameQuote( '#__social_apps' ) . ' AS b '
					. 'ON b.id=a.field_id '
					. 'WHERE a.`group_id`=' . $db->Quote( $group->id );
			$db->setQuery( $query );

			$data	= $db->loadObjectList();

			$group->childs	= $data;
		}

		return $groups;
	}

	/*
	 * Metod to save a user's custom profile.
	 *
	 * @param   array   $post   Posted data from $_POST
	 * @param   SocialTablePerson   $user   A person node
	 */
	public function store( $post , SocialTablePerson $user )
	{
		// @rule: Prepare data to be passed on to the caller.
		$data       = array( &$post , $user );

		// @rule: Get applications.
		//$apps		= Foundry::get( 'Model' , 'Applications' )->getFields();

		// only get fields that associate with the profile type.
		// we do not want to load all the application fields.
		$fieldIds		= array();
		$fieldOptions	= array();
		foreach( $post as $key => $value)
		{
			if( stristr( $key , SOCIAL_CUSTOM_FIELD_PREFIX ) !== false )
			{
				$fieldIds[]		= str_ireplace( SOCIAL_CUSTOM_FIELD_PREFIX.'-', '', $key );
				$fieldOptions[]	= '';;
			}
		}
		$apps	= Foundry::get( 'Model', 'Applications' )->getFieldsByID( $fieldIds );

		// @trigger: onBeforeSave
		// Triggers all field applications which wants to manipulate data before saving.
		$result		= Foundry::get( 'Fields' )->onBeforeSave( $apps , $data );

		// @rule: Saving was intercepted by one of the field applications.
		if( in_array( false , $result , true ) )
		{
			return false;
		}

		// @rule: Since $post is passed by reference to caller, the data will automatically be modified.
		foreach( $post as $key => $value )
		{
			if( stristr( $key , SOCIAL_CUSTOM_FIELD_PREFIX ) !== false )
			{
				// @rule: Remove all unwanted data
				$id     = str_ireplace( SOCIAL_CUSTOM_FIELD_PREFIX.'-' , '' , $key );
				$user->updateField( $id , $value );
			}
		}

		// @trigger: onAfterSave
		// Triggers all field application which wants to manipulate data after saving
		Foundry::get( 'Fields' )->onAfterSave( $apps , $data );

		return true;
	}

	/**
	 * Returns a list of field type applications that are installed and published.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	Array	An array of SocialTableField item.
	 */
	public function getFieldApps( $publishedOnly = true )
	{
		$db 		= Foundry::db();

		$query		= array();
		$query[]	= 'SELECT a.* FROM ' . $db->nameQuote( '#__social_apps' ) . ' AS a';

		$query[]	= 'WHERE';
		$query[]	= 'a.' . $db->nameQuote( 'type' ) . '=' . $db->Quote( SOCIAL_APPS_TYPE_FIELDS );

		if( $publishedOnly )
			$query[]	= 'AND a.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_STATE_PUBLISHED );

		// Glue back the query.
		$query 		= implode( ' ' , $query );

		// Get data
		$db->setQuery( $query );
		$result 	= $db->loadObjectList();

		if( !$result )
		{
			return false;
		}

		$apps 	= array();

		foreach( $result as $row )
		{
			$app 		= Foundry::table( 'App' );
			$app->bind( $row );

			$apps[]		= $app;
		}

		return $apps;
	}

	/*
	 * Retrieves a list of fields which is editable by the user.
	 *
	 */
	public function getFields( $groups = array() , $uid = '' , $trigger = 'edit' , $type = SOCIAL_NODE_PROFILE )
	{
		$db     	= Foundry::db();
		$trigger	= 'on' . ucfirst( $trigger );

		for( $i = 0; $i < count( $groups ); $i++ )
		{
			$group  =& $groups[ $i ];
			$query  = 'SELECT a.*,b.element AS element, c.data AS value, c.data_binary AS value_binary FROM ' . $db->nameQuote( '#__social_fields' ) . ' AS a '
					. 'INNER JOIN ' . $db->nameQuote( '#__social_apps' ) . ' AS b '
					. 'ON b.id=a.field_id '
					. 'LEFT JOIN ' . $db->nameQuote( '#__social_nodes' ) . ' AS d '
					. 'ON d.uid=' . $db->Quote( $uid ) . ' '
					. 'AND d.type=' . $db->Quote( $type ) . ' '
					. 'LEFT JOIN ' . $db->nameQuote( '#__social_fields_data' ) . ' AS c '
					. 'ON a.id=c.field_id '
					. 'AND c.node_id=d.id '
					. 'WHERE a.`group_id`=' . $db->Quote( $group->id );
			$db->setQuery( $query );

			$data	= $db->loadObjectList();

			$fields = array();

			if( $data )
			{
				// Bind the fields to SocialTableField
				for( $x = 0; $x < count( $data ); $x++ )
				{
					$field      =& $data[ $x ];
					$item       = Foundry::table( 'Field' );
					$item->bind( $field );
					$fields[]   = $item;
				}

				// Destroy unused variables
				unset( $data );

				$fields		= Foundry::get( 'Fields' )->$trigger( $fields , array( Foundry::get( 'People' , $uid ) ) );
			}
			$group->childs	= $fields;
		}
		return $groups;
	}

	/*
	 * Retrieves a list of fields which is editable by the user.
	 *
	 */
	public function getRegistrationFields( &$groups , $post = array() )
	{
		$db     	= Foundry::db();

		for( $i = 0; $i < count( $groups ); $i++ )
		{
			$group  =& $groups[ $i ];
			$query  = 'SELECT a.*,b.element AS element FROM ' . $db->nameQuote( '#__social_fields' ) . ' AS a '
					. 'INNER JOIN ' . $db->nameQuote( '#__social_apps' ) . ' AS b '
					. 'ON b.id=a.field_id '
					. 'WHERE a.`group_id`=' . $db->Quote( $group->id ) . ' '
					. 'AND a.' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( SOCIAL_STATE_PUBLISHED );

			$db->setQuery( $query );

			$data	= $db->loadObjectList();

			$fields = array();

			if( $data )
			{
				// Bind the fields to SocialTableField
				for( $x = 0; $x < count( $data ); $x++ )
				{
					$field      =& $data[ $x ];
					$item       = Foundry::table( 'Field' );
					$item->bind( $field );
					$fields[]   = $item;
				}

				// Destroy unused variables
				unset( $data );

				$fields		= Foundry::get( 'Fields' )->onRegister( $fields , $post );
			}
			$group->childs	= $fields;
		}
		return $groups;
	}
	public function getElement( $fieldId )
	{
		$db		= Foundry::db();
		$query	= 'SELECT ' . $db->nameQuote('element') . ' '
				. 'FROM ' .$db->nameQuote('#__social_apps') . ' '
				. 'WHERE ' . $db->nameQuote('type') . ' = ' . $db->quote('fields') . ' '
				. 'AND ' . $db->nameQuote('id') . ' = ' . $db->quote($fieldId);
		$db->setQuery($query);
		$element	= $db->loadResult();

		return $element;
	}

	public function getPagination()
	{
		if ( empty( $this->pagination ) )
		{
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination( $this->total , $this->getState('limitstart') , $this->getState('limit') );
		}

		return $this->pagination;
	}

	/**
	 * Retrieves a list of fields that are created on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param   array   $options    A list of sql filters.
	 * @return array   An array of SocialTableField objects
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getCreatedFields( $options = array() )
	{
		$db 		= Foundry::db();
		$query		= array();
		$query[]	= 'SELECT b.*, a.' . $db->nameQuote( 'element' ) . ' AS ' . $db->nameQuote( 'element' ) . ' FROM ' . $db->nameQuote( '#__social_apps' ) . ' AS a';
		$query[]	= 'INNER JOIN ' . $db->nameQuote( '#__social_fields' ) . ' AS b';
		$query[]	= 'ON b.' . $db->nameQuote( 'app_id' ) . ' = a.' . $db->nameQuote( 'id' );
		$query[]	= 'WHERE a.' . $db->nameQuote( 'type' ) . '=' . $db->Quote( SOCIAL_APPS_TYPE_FIELDS );
		$query[]	= 'AND a.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_STATE_PUBLISHED );

		if( is_array( $options ) )
		{
			foreach( $options as $key => $value )
			{
				$query[]	= 'AND b.' . $db->nameQuote( $key ) . '=' . $db->Quote( $value );
			}
		}

		$query 		= implode( ' ' , $query );

		$countQuery	= str_ireplace( 'b.*, a.`element` AS `element`' , 'COUNT(1)' , $query );
		$this->setTotal( $countQuery );

		$result		= $this->getFieldsData( $query );
		$total  	= count( $result );
		$apps       = array();

		if( !$result )
		{
			return $apps;
		}

		// @rule: Bind them in the table representation layer
		for( $i = 0; $i < $total; $i++ )
		{
			$table	= Foundry::table( 'Field' );
			$table->bind( $result[ $i ] );

			$apps[] = $table;
		}
		return $apps;
	}


	/*
	 * Retrieves a list of fields that can be chained
	 *
	 * @param   array   $options    A list of sql filters.
	 * @returns array   An array of SocialTableField objects
	 */
	public function getChildFields( $nodeId )
	{
		$db     = Foundry::db();
		$query  = 'SELECT b.*, a.element AS `element` FROM ' . $db->nameQuote( '#__social_apps' ) . ' AS a '
				. 'INNER JOIN ' . $db->nameQuote( '#__social_fields' ) . ' AS b '
				. 'ON b.field_id=a.id '
				. ' WHERE a.' . $db->nameQuote( 'type' ) . '=' . $db->Quote( SOCIAL_APPS_TYPE_FIELDS )
				. ' AND a.' . $db->nameQuote( 'state' ) . '=' . $db->Quote( SOCIAL_STATE_PUBLISHED )
				. ' AND b.' . $db->nameQuote( 'node_id' ) . '=' . $db->Quote( $nodeId )
				. ' AND b.' . $db->nameQuote( 'group_id' ) . '!=' . $db->Quote( 0 );

		$db->setQuery( $query );
		$result     = $db->loadObjectList();
		$total  	= count( $result );
		$apps       = array();

		if( !$result )
		{
			return $apps;
		}

		// @rule: Bind them in the table representation layer
		for( $i = 0; $i < $total; $i++ )
		{
			$table	= Foundry::table( 'Field' );
			$table->bind( $result[ $i ] );

			$apps[] = $table;
		}
		return $apps;
	}

	/*
	 * Retrieves a list of fields that can be chained
	 *
	 * @param   array   $options    A list of sql filters.
	 * @returns array   An array of SocialTableField objects
	 */
	public function getChildItems( $fieldId )
	{
		$db     = Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_fields_rules' );
		$sql->where( 'parent_id', $fieldId );

		$db->setQuery( $sql );

		$result     = $db->loadObjectList();
		$total  	= count( $result );
		$rules      = array();

		if( !$result )
		{
			return $rules;
		}

		// @rule: Bind them in the table representation layer
		for( $i = 0; $i < $total; $i++ )
		{
			$table	= Foundry::table( 'FieldRule' );
			$table->bind( $result[ $i ] );

			$rules[] = $table;
		}
		return $rules;
	}

	/**
	 * Retrieves a list of fields which should be displayed during the registration process.
	 * This should not be called elsewhere apart from the registration since it uses different steps, for processes.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of options( 'step_id' , 'profile_id' )
	 * @return	Mixed	An array of group and field items as it's child items.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getCustomFieldsValue( $fieldId , $uid , $type )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_fields_data' );
		$sql->column( 'data' );
		$sql->where( 'field_id', $fieldId );
		$sql->where( 'uid', $uid );
		$sql->where( 'type', $type );

		$db->setQuery( $sql );

		$data		= $db->loadResult();

		return $data;
	}


	/**
	 * Retrieves a list of fields which should be displayed during the registration process.
	 * This should not be called elsewhere apart from the registration since it uses different steps, for processes.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of options( 'step_id' , 'profile_id' )
	 * @return	Mixed	An array of group and field items as it's child items.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getCustomFields( $options = array() )
	{
		$db     	= Foundry::db();
		$sql		= $db->sql();

		$fields 	= array();

		$sql->select( '#__social_fields', 'a' );
		$sql->column( 'a.*' );
		$sql->column( 'b.element', 'element' );
		$sql->column( 'c.field_id', 'smartfield' );
		$sql->column( 'd.uid', 'profile_id' );

		// Determines if we want to get the field data.
		if( isset( $options[ 'data' ] ) && isset( $options[ 'dataId' ] ) && isset( $options[ 'dataType' ] ) )
		{
			$sql->column( 'f.data', 'data' );
		}

		$sql->innerjoin( '#__social_apps', 'b' );
		$sql->on( 'b.id', 'a.app_id' );

		$sql->leftjoin( '#__social_fields_rules', 'c' );
		$sql->on( 'c.parent_id', 'a.id' );

		$sql->leftjoin( '#__social_fields_steps', 'd' );
		$sql->on( 'a.step_id', 'd.id' );

		// Gets field based on positions
		if( isset( $options[ 'position' ] ) )
		{
			$sql->innerjoin( '#__social_fields_position', 'e' );
			$sql->on( 'e.field_id', 'a.id' );
		}

		// Get field data if necessary.
		if( isset( $options[ 'data' ] ) && isset( $options[ 'dataId' ] ) && isset( $options[ 'dataType' ] ) )
		{
			$sql->leftjoin( '#__social_fields_data', 'f' );
			$sql->on( 'f.field_id', 'a.id' );
			$sql->on( 'f.uid', $options[ 'dataId' ] );
			$sql->on( 'f.type', $options[ 'dataType' ] );
		}

		if( isset( $options[ 'state' ] ) )
		{
			if( $options[ 'state' ] !== 'all' )
			{
				// Core fields should not be dependent on the state because it can never be unpublished.
				$sql->where( '(' );
				$sql->where( 'a.core', '1' );
				$sql->where( 'a.state', SOCIAL_STATE_PUBLISHED, '=', 'or' );
				$sql->where( ')' );
			}
		}

		// Test for unique key.
		if( isset( $options[ 'key' ] ) )
		{
			$sql->where( 'a.unique_key' , $options[ 'key' ] );
		}

		// Filter by visibility
		if( isset( $options[ 'visible' ] ) )
		{
			$sql->where( 'a.visible_' . $options[ 'visible' ], SOCIAL_STATE_PUBLISHED );
		}

		// If position is specified, only fetch data from proper positions.
		if( isset( $options[ 'position' ] ) )
		{
			$sql->where( 'e.position', $options[ 'position' ] );
		}

		// Make sure to load fields that are in the current step only if step id is null
		if( isset( $options[ 'step_id' ] ) )
		{
			$sql->where( 'a.step_id', $options[ 'step_id' ] );
		}

		// Detect if caller wants to filter by profile.
		if( isset( $options[ 'profile_id' ] ) )
		{
			$sql->where( 'd.uid', $options[ 'profile_id' ] );
		}

		// Filter by searchable fields
		if( isset( $options[ 'searchable' ] ) )
		{
			$sql->where( 'a.searchable', $options[ 'searchable' ] );
		}

		// Ordering should by default ordered by `ordering` column.
		$sql->order( 'ordering' );

		// // Debug
		// if( $key == 'ADDRESS' )
		// {
		// 	echo $sql->debug();
		// }

		// echo $sql;exit;

		$db->setQuery( $sql );

		$rows	= $db->loadObjectList();

		$fields 	= array();

		// Load language file from the back end as we need to translate them.
		Foundry::language()->load( 'com_easysocial' , JPATH_ADMINISTRATOR );

		// We need to bind the fields with SocialTableField
		$fieldIds = array();
		foreach( $rows as $row )
		{
			$field 	= Foundry::table( 'Field' );
			$field->bind( $row );

			$fieldIds[] = $field->id;
			$field->data 	= isset( $row->data ) ? $row->data : '';

			$field->profile_id = isset( $row->profile_id ) ? $row->profile_id : '';

			$fields[]	= $field;
		}

		// set the field options in batch.
		$field 	= Foundry::table( 'Field' );
		$field->setBatchFieldOptions( $fieldIds );


		return $fields;
	}

	// public function getSampleFields( $profileId )
	// {
	// 	$db		= Foundry::db();

	// 	$query	= 'SELECT a.*, c.' . $db->nameQuote( 'element' ) . ' FROM ' . $db->nameQuote( '#__social_fields' ) . ' AS a';
	// 	$query	.= ' INNER JOIN ' . $db->nameQuote( '#__social_fields_steps' ) . ' AS b';
	// 	$query	.= ' ON a.' . $db->nameQuote( 'step_id' ) . ' = b.' . $db->nameQuote( 'id' );
	// 	$query	.= ' INNER JOIN ' . $db->nameQuote( '#__social_apps' ) . ' AS c';
	// 	$query	.= ' ON a.' . $db->nameQuote( 'app_id' ) . ' = c.' . $db->nameQuote( 'id' );
	// 	$query	.= ' WHERE b.' . $db->nameQuote( 'uid' ) . ' = ' . $db->quote( $profileId );
	// 	$query	.= ' ORDER BY a.' . $db->nameQuote( 'step_id' ) . ', a.' . $db->nameQuote( 'ordering' );

	// 	$db->setQuery( $query );
	// 	$result = $db->loadObjectList();

	// 	$fields = array();

	// 	if( !empty( $result ) )
	// 	{
	// 		$lib = Foundry::getInstance( 'Fields' );
	// 		foreach( $result as $row )
	// 		{
	// 			$table	= Foundry::table( 'Field' );
	// 			$table->bind( $row );

	// 			$fields[] = $table;
	// 		}
	// 	}

	// 	return $fields;
	// }


	/**
	 * Removes all profile fields related to the unique item and id.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $profileId	= JRequest::getInt( 'id' );
	 * $model 	= Foundry::model( 'Profiles' );
	 * $model->removeFields( $profileId );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique profile id.
	 * @return	bool	True on success false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function deleteFields( $uid , $type = SOCIAL_TYPE_PROFILES )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_fields_steps' );
		$sql->where( 'uid', $uid );
		$sql->where( 'type', $type );

		$db->setQuery( $sql );

		$steps 		= $db->loadObjectList();

		// If there's no steps at all, we shouldn't be doing anything.
		if( !$steps )
		{
			return false;
		}

		foreach( $steps as $step )
		{
			// Delete the fields associated with this step.
			$sql->clear();

			$sql->delete( '#__social_fields' );
			$sql->where( 'step_id', $step->id );

			$db->setQuery( $sql );
			$db->Query();

			// Delete this step.
			$sql->clear();

			$sql->delete( '#__social_fields_steps' );
			$sql->where( 'id', $step->id );

			$db->setQuery( $sql );
			$db->Query();
		}

		return true;
	}

	public function deleteFieldsWithStep( $stepid )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->delete( '#__social_fields' );
		$sql->where( 'step_id', $stepid );

		$db->setQuery( $sql );
		$result = $db->query();

		return $result;
	}

	/**
	 * Helper function to retrieve the list of Joomla editors.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getEditors()
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__extensions' );
		$sql->where( 'folder', 'editors' );
		$sql->where( 'enabled', '1' );

		$db->setQuery( $sql );

		$result 	= $db->loadObjectList();

		// Load language strings.
		$lang		= JFactory::getLanguage();

		foreach( $result as $i => $option )
		{
			$lang->load('plg_editors_'.$option->element, JPATH_ADMINISTRATOR, null, false, false)
			||	$lang->load('plg_editors_'.$option->element, JPATH_PLUGINS .'/editors/'.$option->element, null, false, false)
			||	$lang->load('plg_editors_'.$option->element, JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
			||	$lang->load('plg_editors_'.$option->element, JPATH_PLUGINS .'/editors/'.$option->element, $lang->getDefault(), false, false);

			$option->name	= JText::_( $option->name );
		}

		return $result;
	}

	/**
	 * Retrieves a list of options for a particular field item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique field id. FK to `#__social_fields`.
	 * @return	Array
	 */
	public function getOptions( $fieldId )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_fields_options' );
		$sql->where( 'parent_id', $fieldId );
		$sql->order( 'key' );

		$db->setQuery( $sql );

		$result 	= $db->loadObjectList();

		$options	= array();

		if( !empty( $result ) )
		{
			foreach( $result as $row )
			{
				$options[$row->key][$row->id] = $row->title;
			}
		}

		return $options;
	}

	/**
	 * Delete options for a particular field item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The unique field id. FK to `#__social_fields`.
	 * @return	boolean	State of the action
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function deleteOptions( $fieldId )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->delete( '#__social_fields_options' );
		$sql->where( 'parent_id', $fieldId );

		$db->setQuery( $sql );

		return $db->query();
	}

	/**
	 * Get a list of unique keys
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		$stepId		The step id to look
	 * @param	int		$exclude	The id to exclude
	 *
	 * @return	Array	list of unique keys
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function getStepUniqueKeys( $stepId, $exclude = null )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_fields' );
		$sql->column( 'unique_key' );
		$sql->where( 'step_id', $stepId );

		if( !is_null( $exclude ) )
		{
			$sql->where( 'id', $exclude, '<>' );
		}

		$db->setQuery( $sql );

		return $db->loadColumn();
	}

	/**
	 * Get a list of unique keys
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		$stepId		The step id to look
	 * @param	int		$exclude	The id to exclude
	 *
	 * @return	Array	list of unique keys
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function getProfileUniqueKeys( $stepId, $exclude = null )
	{
		// Get the profile id from this step first
		$table = Foundry::table( 'fieldstep' );
		$table->load( $stepId );

		$profileId = $table->uid;

		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_fields_steps', 'a' )
			->column( 'b.unique_key' )
			->leftjoin( '#__social_fields', 'b' )
			->on( 'a.id', 'b.step_id' )
			->where( 'a.type', SOCIAL_TYPE_PROFILES )
			->where( 'a.uid', $profileId );

		if( !is_null( $exclude ) )
		{
			$sql->where( 'b.id', $exclude, '<>' );
		}

		$db->setQuery( $sql );

		return $db->loadColumn();
	}

	public function getFieldUniqueKeys( $app )
	{
		$db = Foundry::db();
		$sql = $db->sql();

		$sql->select( '#__social_fields' )
			->column( 'unique_key', 'unique_key', 'distinct' )
			->where( 'app_id', $app->id );

		$db->setQuery( $sql );
		$result = $db->loadColumn();

		$keys = array();

		foreach( $result as $row )
		{
			$data = new stdClass();
			$data->title = $row;
			$data->value = $row;

			$keys[] = $data;
		}

		return $keys;
	}
}
