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

Foundry::import( 'admin:/tables/table' );

/**
 * Object mapping for field's step table.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.0
 */
class SocialTableFieldStep extends SocialTable
{
	/**
	 * The unique id which is auto incremented.
	 * @var int
	 */
	public $id			= null;

	/**
	 * The unique item id.
	 * @var int
	 */
	public $uid			= null;

	/**
	 * The unique item type.
	 * @var string
	 */
	public $type		= null;

	/**
	 * The title of the workflow.
	 * @var string
	 */
	public $title		= null;

	/**
	 * The description set for this step.
	 * @var string
	 */
	public $description	= null;

	/**
	 * The state of the workflow.
	 * @var int
	 */
	public $state 		= null;

	/**
	 * The creation date time for this workflow.
	 * @var datetime
	 */
	public $created		= null;

	/**
	 * The ordering of the workflow.
	 * @var int
	 */
	public $sequence	= null;

	/**
	 * Determines if the page is visible during registration.
	 * @var	int
	 */
	public $visible_registration	= null;

	/**
	 * Determines if the page is visible during editing.
	 * @var	int
	 */
	public $visible_edit	= null;

	/**
	 * Determines if the page is visible during viewing.
	 * @var	int
	 */
	public $visible_display	= null;

	/**
	 * Class constructor.
	 *
	 * @access	public
	 * @param	JDatabase
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function __construct(& $db )
	{
		parent::__construct( '#__social_fields_steps' , 'id' , $db );
	}

	/**
	 * Retrieves a particular step based on the sequence and the workflow id.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $table 	= Foundry::table( 'Workflow' );
	 * $table->loadBySequence( JRequest::getInt( 'profileId' ) , JRequest::getInt( 'step' ) );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @param   int		The unique profile id.
	 * @param   int     The sequence / step.
	 * @return  bool	True on success, false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function loadBySequence( $uid , $type = SOCIAL_TYPE_USER , $sequence )
	{
		$db 		= Foundry::db();
		$query		= array();
		$query[]	= 'SELECT * FROM ' . $db->nameQuote( $this->_tbl );
		$query[]	= 'WHERE ' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $uid );
		$query[]	= 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );
		$query[]	= 'AND ' . $db->nameQuote( 'sequence' ) . '=' . $db->Quote( $sequence );
		$query 		= implode( ' ' , $query );

		$db->setQuery( $query );

		$row	= $db->loadObject();

		if( !$row )
		{
			return false;
		}

		return parent::bind( $row );
	}

	/**
	 * Override's parent store method as we need to get the sequence if it's not being set.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $table 	= Foundry::table( 'Workflow' );
	 * $table->load( JRequest::getInt( 'id' ) );
	 * $table->store();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool	True to update fields even if they are null.
	 * @return	bool	True on success, false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function store( $updateNulls = false )
	{
		// Get next sequence
		if( !$this->sequence )
		{
			$this->sequence	= $this->getNextSequence();
		}

		return parent::store( $updateNulls );
	}

	/**
	 * Determine's the next sequence in this series.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $table 	= Foundry::table( 'Workflow' );
	 * $table->load( JRequest::getInt( 'id' ) );
	 * $table->getNextSequence();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	int		The next sequence number for this profile type.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getNextSequence( $mode = null )
	{
		$db 		= Foundry::db();

		$query		= array();

		if( empty( $mode ) )
		{
			$query[]	= 'SELECT (MAX(' . $db->nameQuote( 'sequence' ) . ') + 1 )';
			$query[]	= 'FROM ' . $db->nameQuote( $this->_tbl );
			$query[]	= 'WHERE ' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $this->uid );
			$query[]	= 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $this->type );
			$query 		= implode( ' ' , $query );

			$db->setQuery( $query );
			$sequence	= $db->loadResult();

			return $sequence;
		}
		else
		{
			$query[]	= 'SELECT ' . $db->nameQuote( 'sequence' );
			$query[]	= 'FROM ' . $db->nameQuote( $this->_tbl );
			$query[]	= 'WHERE ' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $this->uid );
			$query[]	= 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $this->type );
			$query[]	= 'AND ' .$db->nameQuote( 'visible_' . $mode ) . '=' . $db->Quote( '1' );
			$query[]	= 'AND ' . $db->nameQuote( 'sequence' ) . '>' . $this->sequence;
			$query[]	= 'ORDER BY ' . $db->nameQuote( 'sequence' );
			$query[]	= 'LIMIT 1';

			$db->setQuery( $query );

			$result = $db->loadResult();

			if( empty( $result ) )
			{
				return false;
			}

			return $result;
		}
	}

	/**
	 * Update the sequence for any sequence larger or lesser given the conditions.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $table 	= Foundry::table( 'Workflow' );
	 * $table->load( JRequest::getInt( 'id' ) );
	 * $table->updateSequence( '2' , $profileId , 'add' );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The current sequence.
	 * @param	int 	The unique profile id.
	 * @param	string	The operation. 'add' or 'substract'
	 * @return	int		The next sequence number for this profile type.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function updateSequence( $currentSequence , $profileId , $operation = 'add' )
	{
		$operation  = $operation == 'add' ? '+' : '-';

		$db 	= Foundry::db();
		$query  = 'UPDATE ' . $db->nameQuote( $this->_tbl ) . ' '
				. 'SET ' . $db->nameQuote( 'sequence' ) . ' = (' . $db->nameQuote( 'sequence' ) . ' ' . $operation . ' 1 ) '
				. 'WHERE ' . $db->nameQuote( 'sequence' ) . ' > ' . $db->Quote( $currentSequence ) . ' '
				. 'AND ' . $db->nameQuote( 'uid' ) . ' = ' . $db->Quote( $profileId );
		$db->setQuery( $query );

		$db->Query();
	}

	/**
	 * Override the parent's delete method as we need to update the sequence when a
	 * workflow is deleted.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $table 	= Foundry::table( 'Workflow' );
	 * $table->delete();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Mixed	An optional primary key value to delete.  If not set the instance property value is used.
	 * @return	bool	True on success, false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function delete( $pk = null )
	{
		$stepid		= $this->id;
		$result		= parent::delete( $pk );

		if( $result )
		{
			$this->updateSequence( $this->sequence , $this->uid , 'substract' );

			if( $stepid != 0 )
			{
				// Get all the fields in this step
				$fields = $this->getStepFields();

				foreach( $fields as $field )
				{
					// Delete the fields
					$field->delete();
				}
			}
		}

		return $result;
	}

	/**
	 * Determines if this step is the last step.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $table 	= Foundry::table( 'fieldstep' );
	 * $table->load( JRequest::getInt( 'id' ) );
	 *
	 * //Returns true / false.
	 * $table->isFinalStep();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	bool	True if this is the last step.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function isFinalStep( $mode = null )
	{
		$next = $this->getNextSequence( $mode );

		return empty( $next );
	}

	/**
	 *	This will get all the fields in this step
	 *
	 *	@since	1.0
	 *	@access	public
	 *	@return Array	array of field table object
	 *
	 *	@author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function getStepFields()
	{
		$fieldsModel = Foundry::model( 'fields' );

		// Get all the fields in this step
		$fields = $fieldsModel->getCustomFields( array( 'step_id' => $this->id ) );

		return $fields;
	}

	/**
	 * Function to check if this step is a new step
	 *
	 * @since	1.0
	 * @access	public
	 *
	 * @return	bool	True if the step is new, false otherwise.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function isNew()
	{
		return !($this->id > 0);
	}

	/**
	 * Given a set of parameter, process the argument as params of this page
	 *
	 * @since	1.0
	 * @access	public
	 * @param	mixed	String or array
	 * @return	bool
	 */
	public function processParams( $step )
	{
		static $default = null;

		if( empty( $default ) )
		{
			$path		= SOCIAL_CONFIG_DEFAULTS . '/fields.header.json';
			$raw		= JFile::read( $path );

			$default	= Foundry::json()->decode( $raw );
		}

		$params = array( 'title', 'description', 'visible_registration', 'visible_edit', 'visible_display' );

		foreach( $params as $param )
		{
			$this->$param = isset( $step->$param ) ? $step->$param : ( $this->isNew() ? $default->$param->default : $this->$param ) ;
		}

		return $this;
	}
}
