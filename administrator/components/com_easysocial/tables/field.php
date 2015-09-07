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

// Import main table.
Foundry::import( 'admin:/tables/table' );

/**
 * Object mapping for Field table.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.0
 */
class SocialTableField extends SocialTable
{
	/**
	 * The unique id of the field item.
	 * @var	int
	 */
	public $id			= null;

	/**
	 * The unique key of the field item.
	 * @var	int
	 */
	public $unique_key	= null;

	/**
	 * The foreign key to #__social_apps
	 * @var	int
	 */
	public $app_id 		= null;

	/**
	 * The foreign key to #__social_fields_steps
	 * @var	int
	 */
	public $step_id 	= null;

	/**
	 * The title for the field
	 * @var	int
	 */
	public $title 		= null;

	/**
	 * Determines whether or not the title should be displayed in the form.
	 * @var	bool
	 */
	public $display_title 	= null;

	/**
	 * The tooltip or description for the field
	 * @var	string
	 */
	public $description 	= null;

	/**
	 * Determines whether or not the title should be displayed in the form.
	 * @var	bool
	 */
	public $display_description 	= null;

	/**
	 * The default value for the field.
	 * @var	mixed
	 */
	public $default			= null;

	/**
	 * The validation for this field.
	 * @var	mixed
	 */
	public $validation 		= null;

	/**
	 * The state of this field.
	 * @var	int
	 */
	public $state 			= null;

	/**
	 * Determines if this field can be searchable.
	 * @var	mixed
	 */
	public $searchable 		= null;

	/**
	 * Determines if this field is required
	 * @var	bool
	 */
	public $required 		= null;

	/**
	 * Stores the JSON string of the raw parameters from Registry
	 * @var	string
	 */
	public $params 			= null;

	/**
	 * Stores the ordering number of the field. With 0 being the lowest order.
	 * @var	int
	 */
	public $ordering 		= null;

	/**
	 * Determines if this is a core field that cannot be deleted.
	 * @var	int
	 */
	public $core 			= null;

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
	 * Determines if this field is used in friend suggestion.
	 * @var	mixed
	 */
	public $friend_suggest 		= null;

	// Non table related.
	// Used in field processing
	private		$childs			= null;
	public		$value			= null;
	protected	$value_binary	= null;
	public		$element		= null;
	protected	$smartfield		= null;
	public 		$data 			= null;
	public 		$raw 			= null;

	// Extra constant information
	// Used to process/generate/combine core parameters and user parameters
	private $coreParams = array(
		'visible_registration',
		'visible_edit',
		'visible_display',
		'title',
		'display_title',
		'description',
		'display_description',
		'default',
		'validation',
		'required',
		'searchable',
		'unique_key',
		'friend_suggest'
	);



	// use to optimize the db queries
	static $_fieldoptions = array();


	/**
	 * Class Constructor
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function __construct(& $db )
	{
		parent::__construct( '#__social_fields' , 'id' , $db );
	}


	/**
	 * Override parent's store implementation
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An associative array or object to bind to the JTable instance.
	 * @param   Array	An optional array or space separated list of properties to ignore while binding.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function bind( $data , $ignore = array() )
	{
	    $state	= parent::bind( $data , $ignore );

	    if( is_object( $data ) )
	    {
		    if( isset( $data->element ) )
		    {
		        $this->element  = $data->element;
			}

			if( isset( $data->value ) )
			{
			    $this->value    = $data->value;
			}

			if( isset( $data->value_binary ) )
			{
			    $this->value_binary = $data->value_binary;
			}
		}

		// @rule: params should not be an array
		if( is_array( $this->params ) )
		{
		    $this->bindParams( $this->params );
		}

	    return $state;
	}

	public function bindParams( $data )
	{
		$params	= Foundry::get( 'Parameter' , '' );
		$params->bind( $data );
		$this->params	= $params->toJSON();
	}

	/*
	 * Binds a specific request for the chainable fields.
	 *
	 * @param   $data   Array   An array of string values
	 * @param   $fields Array   An array of field id's.
	 *
	 */
	public function bindSmartFields( $data ,$fields , $fieldId )
	{
	    $total  = count( $data );

	    for( $i = 0; $i < $total; $i++ )
	    {
	        $rule   = Foundry::table( 'FieldRule' );

	        // The field that should be automatically loaded
	        $rule->set( 'field_id'		, $fields[ $i ] );

	        // The field that is dependent on
	        $rule->set( 'parent_id'		, $fieldId );

	        // Matching text
	        $rule->set( 'match_text' 	, $data[ $i ] );

	        $rule->store();
		}

        return true;;
	}

	/*
	 * Binds child objects during posts
	 *
	 */
	public function bindChilds( $data )
	{
		if( empty( $data ) )
		{
			return false;
		}

		$this->childs	= $data;

		return true;
	}


	/**
	 * Override parent's store implementation
	 *
	 * @since	1.0
	 * @access	public
	 * @param	bool	True to update fields even if they are null.
	 * @param   bool	True to reset the default values before loading the new row.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function store( $updateNulls = false )
	{
		// Set the element back again.
		if( isset( $this->element ) )
		{
			$element 	= $this->element;

			unset( $this->element );
		}

		// Update ordering column if this is a new item.
		if( !$this->id && is_null( $this->ordering ) )
		{
			$this->ordering = $this->getNextOrder( array( 'step_id' => $this->step_id ) );
		}

		$status	= parent::store( $updateNulls );

		// Set the element back again.
		if( isset( $element ) )
		{
			$this->element 	= $element;
		}

		return $status;
	}

	/**
	 * Retrieves a list of options for this particular field.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getOptions( $key = null )
	{
		$options = array();

		if( !isset( self::$_fieldoptions[ $this->id ] ) )
		{
			self::$_fieldoptions[ $this->id ] = array();

			$db		= Foundry::db();
			$sql	= $db->sql();

			$sql->select( '#__social_fields_options' );
			$sql->where( 'parent_id', $this->id );
			$sql->order( 'key' );
			$sql->order( 'ordering' );

			$db->setQuery( $sql );

			$result = $db->loadObjectList();

			foreach( $result as $row )
			{
				self::$_fieldoptions[ $this->id ][ $row->key ][] = $row;
			}
		}

		$data = self::$_fieldoptions[ $this->id ];

		foreach( $data as $rKey => $rows )
		{
			if( is_array( $rows ) )
			{
				foreach( $rows as $row )
				{
					$option = Foundry::table( 'FieldOptions' );
					$option->bind( $row );

					$option->label = $option->title;

					// If no key is provided we group result based on key and id
					if( is_null( $key ) )
					{
						$options[$row->key][$row->id]  = $option;
					}
					else
					{
						$options[$row->id] = $option;
					}
				}
			}
		}

		return $options;
	}


	/**
	 * set fields options in batch for later reference.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function setBatchFieldOptions( $fieldIds )
	{
		if( empty( $fieldIds ) )
		{
			return;
		}

		$db		= Foundry::db();
		$sql	= $db->sql();

		$ids 	= implode( ',', $fieldIds );

		$sql->select( '#__social_fields_options' )
			->where( 'parent_id', $fieldIds, 'in' )
			->order( 'parent_id' )
			->order( 'key' )
			->order( 'ordering' );

		$db->setQuery( $sql );

		$results = $db->loadObjectList();

		if( $results )
		{
			foreach( $results as $result )
			{
				self::$_fieldoptions[ $result->parent_id ][ $result->key ][] = $result;
			}
		}

		// lets check if all the passed in fields has result or not.
		foreach( $fieldIds as $fid )
		{
			if( !isset( self::$_fieldoptions[ $fid ] ) )
			{
				self::$_fieldoptions[ $fid ] = array();
			}
		}
	}

	public function getElement()
	{
	    if( isset( $this->element ) && !empty( $this->element ) )
	    {
	        return $this->element;
		}
	}

	public function getValue()
	{
	    return $this->value;
	}

	public function render( $element )
	{
		$path	= SOCIAL_MEDIA . DS . 'fields' . DS . strtolower( $element ) . DS . strtolower( $element ) . '.xml';

		if( Foundry::get( 'Files' )->exists($path) )
		{
			return parent::renderParams( $this->params , $path );
		}

		// params.xml file not found
		return;
	}

	/*
	 * Determines if the current field is required or not.
	 *
	 * @param   null
	 * @return  boolean     True if required, false otherwise
	 */
	public function isRequired()
	{
	    return (bool) $this->required;
	}

	public function export()
	{
	    $obj    	= new stdClass();
	    $properties = get_class_vars( get_class( $this ) );

	    foreach( $properties as $key => $value )
	    {
	        if( $key[0] != '_' )
	        {
	            $obj->$key      = $this->$key;
			}
		}

	    return (array) $obj;
	}

	public function getChildFields( $match = '' )
	{
	    $db 	= Foundry::db();
	    $query  = 'SELECT field_id FROM ' . $db->nameQuote( '#__social_fields_rules' ) . ' '
	            . 'WHERE ' . $db->nameQuote( 'parent_id' ) . ' = ' . $db->Quote( $this->id ) . ' '
				. 'AND ' . $db->nameQuote( 'match_text' ) . ' LIKE ' . $db->Quote( '%' . $match . '%' );
		$db->setQuery( $query );

		$result	= $db->loadColumn();

		return $result;
	}

	/**
	 * Get's the stored parameters for this field.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	JRegistry	Registry of the field parameters
	 */
	public function getParams( $jsonString = false )
	{
		static $cachedParams 	= array();

		$index 	= $this->id;
		$index 	.= $jsonString ? '1' : '0';

		if( !isset( $cachedParams[ $index ] ) )
		{
			$params		= Foundry::json()->decode( $this->params );

			if( !is_object( $params ) )
			{
				$params = new stdClass();
			}

			// Manually assign all the core columns into the parameter object
			foreach( $this->coreParams as $coreParam )
			{
				$params->$coreParam = $this->$coreParam;
			}

			if( $jsonString )
			{
				$params = Foundry::json()->encode( $params );
			}
			else
			{
				$params = Foundry::registry( $params );
			}

			$cachedParams[ $index ]	= $params;
		}

		return $cachedParams[ $index ];
	}

	/**
	 * Given a set of parameter, process the argument as params of this field
	 *
	 * @since	1.0
	 * @access	public
	 * @param	mixed	String or array
	 * @return	bool
	 */
	public function processParams( $params )
	{
		$jsonLib	= Foundry::json();

		if( is_string( $params ) )
		{
			$params = $jsonLib->decode( $params );
		}

		// Get the config parameters for this app
		$configParameters = Foundry::fields()->getFieldConfigParameters( $this->app_id );

		// Get the default values of this app
		$defaults = $this->getConfigDefaultValues();

		// Process core params first by extracting the values out from the parameter object
		foreach( $this->coreParams as $coreParam )
		{
			if( isset( $params->$coreParam ) )
			{
				$this->$coreParam = $params->$coreParam;
				unset( $params->$coreParam );
			}
			else
			{
				// Check for enforced value
				if( property_exists( $configParameters, $coreParam ) && is_bool( $configParameters->$coreParam ) )
				{
					$this->$coreParam = $configParameters->$coreParam ? 1 : 0;
				}
				else
				{
					if( $this->isNew() && property_exists( $defaults, $coreParam ) )
					{
						$this->$coreParam = $defaults->$coreParam;
					}
				}
			}

			// Remove coreParam from defaults so that only extended params remains in the default
			if( property_exists( $defaults, $coreParam ) )
			{
				unset( $defaults->$coreParam );
			}
		}

		// If this is a new field, then check if remaining param is empty to fill in the default values
		if( $this->isNew() )
		{
			foreach( $defaults as $key => $value )
			{
				if( !property_exists( $params, $key ) )
				{
					$params->$key = $value;
				}
			}
		}

		// Convert it to array for empty checking purposes
		$tmp = (array) $params;

		// Store remaining params from the parameter object in params
		if( !empty( $tmp ) )
		{
			$this->params = $jsonLib->encode( $params );
		}

		return true;
	}

	/**
	 * Generate a unique key for this field
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	Unique key of this field
	 */
	public function generateUniqueKey( $existingKeys = null )
	{
		// If this field has not beed saved, then return false
		if( !$this->id )
		{
			return false;
		}

		// If this field doesn't have a valid app_id, then return false
		if( !$this->app_id )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: Application id is invalid to generate a unique key.' );

			return false;
		}

		$appTable = Foundry::table( 'app' );
		$appTable->load( $this->app_id );

		if( !$appTable->element )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'FIELDS: This application does not have a proper element name to generate a unique key.' );

			return false;
		}

		if( is_null( $existingKeys ) )
		{
			$model = Foundry::model( 'fields' );
			$existingKeys = $model->getProfileUniqueKeys( $this->step_id, $this->id );
		}

		$uniqueIndex = 0;

		// Filter by related uniquekeys
		if( $existingKeys )
		{
			foreach( $existingKeys as $key )
			{
				if( stripos( $key, $appTable->element ) !== false )
				{
					$tmp = explode( '-', $key );

					if( count( $tmp ) === 1 && $uniqueIndex === 0 )
					{
						$uniqueIndex = 1;
					}

					if( count( $tmp ) === 2 && $uniqueIndex <= $tmp[1] )
					{
						$uniqueIndex = $tmp[1] + 1;
					}
				}
			}
		}

		$this->unique_key = JString::strtoupper( $appTable->element );

		if( $uniqueIndex !== 0 )
		{
			$this->unique_key .= '-' . $uniqueIndex;
		}

		return $this->unique_key;
	}

	/**
	 * Checks unique key for this field
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	Unique key of this field
	 */
	public function checkUniqueKey()
	{
		// Check if this unique key is used
		$model = Foundry::model( 'fields' );
		$keys = $model->getProfileUniqueKeys( $this->step_id, $this->id );


		if( empty( $this->unique_key ) || empty( $keys ) || in_array( $this->unique_key, $keys ) ) {
			$this->generateUniqueKey( $keys );
		}

		return $this->unique_key;
	}

	/**
	 * Override the parent's delete method to carry out extra maintenance action
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $table 	= Foundry::table( 'Field' );
	 * $table->delete();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Mixed	An optional primary key value to delete.  If not set the instance property value is used.
	 * @return	bool	True on success, false otherwise.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function delete( $pk = null )
	{
		$fieldid	= $this->id;
		$result		= parent::delete( $pk );

		$model		= Foundry::model( 'fields' );
		$model->deleteOptions( $fieldid );

		return $result;
	}

	/**
	 * Function to check if this field is a new field
	 *
	 * @since	1.0
	 * @access	public
	 *
	 * @return	bool	True if the field is new, false otherwise.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function isNew()
	{
		return !($this->id > 0);
	}

	/**
	 * Function to get the app table of this field
	 *
	 * @since	1.0
	 * @access	public
	 *
	 * @return	object	The app table object
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function getApp()
	{
		static $app = array();

		if( empty( $app[$this->app_id] ) )
		{
			$app[$this->app_id] = Foundry::table( 'app' );
			$state = $app[$this->app_id]->load( $this->app_id );

			if( !$state )
			{
				return false;
			}
		}

		return $app[$this->app_id];
	}

	/**
	 * Function to get the app table of this field
	 *
	 * @since	1.0
	 * @access	public
	 *
	 * @return	object	The app table object
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function getDataTable( $uid = null, $type = SOCIAL_TYPE_USER )
	{
		if( is_null( $uid ) && $type === SOCIAL_TYPE_USER )
		{
			$uid = Foundry::user()->id;
		}

		$table = Foundry::table( 'fielddata' );
		$state = $table->loadByField( $this->id, $uid, $type );

		if( !$state )
		{
			$table->uid = $uid;
			$table->type = $type;
		}

		return $table;
	}

	/**
	 * Retrieves the icon class for this field
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getIcon()
	{
		$params 	= $this->getParams();

		$icon 		= $params->get( 'icon' , '' );

		return $icon;
	}

	public function getConfigDefaultValues()
	{
		$config = Foundry::fields()->getFieldConfigParameters( $this->app_id );

		$defaults = new stdClass();

		// Filter out only name and default values
		foreach( $config as $name => $params )
		{
			if( is_bool( $params ) )
			{
				$defaults->$name = $params ? 1 : 0;
			}
			else
			{
				if( isset( $params->type ) && $params->type == 'checkbox' )
				{
					$defaults->$name = array();

					foreach( $params->option as $option )
					{
						if( isset( $option->default ) && $option->default )
						{
							$defaults->{$name}[] = $option->value;
						}
					}
				}
				else
				{
					if( property_exists( $params, 'default' ) )
					{
						$defaults->$name = $params->default;
					}
				}
			}
		}

		return $defaults;
	}
}
