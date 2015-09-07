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

// Include the fields library
Foundry::import( 'admin:/includes/fields/fields' );

// Include helper file.
Foundry::import( 'fields:/user/address/helper' );

/**
 * Field application for Address
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserAddress extends SocialFieldItem
{
	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Displays the field input for user when they register their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegister( &$post, &$registration )
	{
		// Get the default value
		$value 	= $this->getValue();

		if( isset( $post[ $this->inputName ] ) )
		{
			$value 	= $this->getValue( $post[ $this->inputName ] );
		}

		// Get the countries list
		$countries 	= SocialFieldsUserAddressHelper::getCountries( $this->params->get( 'sort_type' ), $this->params->get( 'title_type' ) );

		// Set the default values
		$this->set( 'value'		, $value );

		// Set the countries
		$this->set( 'countries' , $countries );

		// Get the requirements and set the required parameters
		$required = array(
			'address1'	=> $this->params->get( 'required_address1' ),
			'address2'	=> $this->params->get( 'required_address2' ),
			'city'		=> $this->params->get( 'required_city' ),
			'state'		=> $this->params->get( 'required_state' ),
			'zip'		=> $this->params->get( 'required_zip' ),
			'country'	=> $this->params->get( 'required_country' )
		);

		// Set the jsonencoded string for required data
		$this->set( 'required', $this->escape( Foundry::json()->encode( $required ) ) );

		// Set custom required option
		$isRequired = false;
		foreach( $required as $key => $value )
		{
			if( $value )
			{
				$isRequired = true;
				break;
			}
		}

		$this->set( 'options', array( 'required' => $isRequired ) );

		// Detect if there's any errors
		$error	= $registration->getErrors( $this->inputName );

		// Set the error
		$this->set( 'error', $error );

		return $this->display();
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialTableRegistration		The registration ORM table.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterValidate( &$post, SocialTableRegistration &$registration )
	{
		return $this->validateInput( $post );
	}

	/**
	 * Executes before a user's registration is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialTableRegistration		The registration ORM table.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onRegisterBeforeSave( &$post )
	{
		$address 	= $this->getValue( $post[ $this->inputName ] );

		if( !$address )
		{
			return;
		}

		if( $this->params->get( 'geocode' ) )
		{
			$geocode 		= Foundry::get( 'GeoCode' );
			$coordinates	= $geocode->address( $address->address1 . ',' . $address->city  );

			$address->latitude 	= $coordinates->lat;
			$address->longitude	= $coordinates->lng;

			$post[ $this->inputName ]	= Foundry::makeJSON( $address );
		}
	}

	/**
	 * Displays the field input for user when they edit their account.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialUser		The user that is being edited.
	 * @param	Array			The post data.
	 * @param	Array			The error data.
	 * @return	string			The html string of the field
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		// Check if there is values in the post first
		$value = !empty( $post[$this->inputName] ) ? $post[$this->inputName] : $this->value;

		// Get the value
		$value 	= $this->getValue( $value );

		// Set the value.
		$this->set( 'value', $value );

		// Get the countries list
		$countries 	= SocialFieldsUserAddressHelper::getCountries( $this->params->get( 'sort_type' ), $this->params->get( 'title_type' ) );

		// Set the countries
		$this->set( 'countries', $countries );

		// Get the requirements and set the required parameters
		$required = array(
			'address1'	=> $this->params->get( 'required_address1' ),
			'address2'	=> $this->params->get( 'required_address2' ),
			'city'		=> $this->params->get( 'required_city' ),
			'state'		=> $this->params->get( 'required_state' ),
			'zip'		=> $this->params->get( 'required_zip' ),
			'country'	=> $this->params->get( 'required_country' )
		);

		// Set the jsonencoded string for required data
		$this->set( 'required', $this->escape( Foundry::json()->encode( $required ) ) );

		// Set custom required option
		$isRequired = false;
		foreach( $required as $key => $value )
		{
			if( $value )
			{
				$isRequired = true;
				break;
			}
		}

		$this->set( 'options', array( 'required' => $isRequired ) );

		// Get field error
		$error = $this->getError( $errors );

		// Set field error
		$this->set( 'error', $error );

		return $this->display();
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 		The posted data.
	 * @param	SocialUser	The user object.
	 * @return	bool		Determines if the system should proceed or throw errors.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onEditValidate( &$post, &$user )
	{
		return $this->validateInput( $post );
	}

	public function onEditBeforeSave( &$post, &$user )
	{
		$data = $this->getValue( $post[ $this->inputName ] );

		$raw = implode( ' ', Foundry::makeArray( $data ) );

		$post[ $this->inputName ] = array( 'data' => Foundry::makeJSON( $data ), 'raw' => $raw );

		return true;
	}

	/**
	 * Converts the data into a correct value representation.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 */
	public function getValue( &$data = '', $init = true )
	{
		$value 	= '';

		// Try to extract the information since it is now a json string.
		if( !empty( $data ) )
		{
			$json 	= Foundry::json();
			$value 	= $json->decode( $data );
		}

		if( $init && empty( $value ) )
		{
			$value 		= new stdClass();
			$value->address1 	= '';
			$value->address2	= '';
			$value->city 		= '';
			$value->state 		= '';
			$value->zip 		= '';
			$value->country 	= '';

		}

		foreach( $value as $k => $v )
		{
			$value->$k = $this->escape( $v );
		}

		return $value;
	}

	/**
	 * Responsible to output the html codes that is displayed to
	 * a user when their profile is viewed.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function onDisplay( $user )
	{
		$value 	= $this->getValue( $this->value );

		if( !$value->address1 && !$value->address2 && !$value->city && !$value->state && !$value->zip && !$value->country )
		{
			return;
		}

		if( !$this->allowedPrivacy( $user ) )
		{
			return;
		}

		foreach( $value as $k => $v )
		{
			$value->$k = $this->escape( $v );
		}

		// Get country name
		if( !empty( $value->country ) )
		{
			$value->country = SocialFieldsUserAddressHelper::getCountryName( $value->country );
		}

		// Push vars to the theme
		$this->set( 'value' , $value );

		return $this->display( 'display' );
	}

	/**
	 * return formated string from the fields value
	 *
	 * @since	1.0
	 * @access	public
	 * @param	userfielddata
	 * @return	array array of objects with two attribute, ffriend_id, score
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onIndexer( $userFieldData )
	{
		if(! $this->field->searchable )
			return false;

		$data 		= $this->getValue( $userFieldData );
		$content 	= $data->address1 . ' ' . $data->address2 . ' ' . $data->city . ' ' . $data->state . ' ' . $data->country;

		if( $content )
			return $content;
		else
			return false;
	}


	/**
	 * return formated string from the fields value
	 *
	 * @since	1.0
	 * @access	public
	 * @param	userfielddata
	 * @return	array array of objects with two attribute, ffriend_id, score
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onIndexerSearch( $itemCreatorId, $keywords, $userFieldData )
	{
		if(! $this->field->searchable )
			return false;

		$data 		= $this->getValue( $userFieldData );

		// $data 	= $this->getValue( $this->value );


		$content 			= '';
		if( JString::stristr( $data->address1, $keywords ) !== false )
		{
			$content = $data->address1;
		}
		else if( JString::stristr( $data->address2, $keywords ) !== false )
		{
			$content = $data->address2;
		}
		else if( JString::stristr( $data->city, $keywords ) !== false )
		{
			$content = $data->city;
		}
		else if( JString::stristr( $data->state, $keywords ) !== false )
		{
			$content = $data->state;
		}
		else if( JString::stristr( $data->country, $keywords ) !== false )
		{
			$content = $data->country;
		}

		if( $content )
		{
			$my = Foundry::user();
			$privacyLib = Foundry::privacy( $my->id );

			if( ! $privacyLib->validate( 'core.view', $this->field->id, SOCIAL_TYPE_FIELD, $itemCreatorId ) )
			{
				return -1;
			}
			else
			{
				// okay this mean the user can view this fields. let hightlight the content.

				// building the pattern for regex replace
				$searchworda	= preg_replace('#\xE3\x80\x80#s', ' ', $keywords);
				$searchwords	= preg_split("/\s+/u", $searchworda);
				$needle			= $searchwords[0];
				$searchwords	= array_unique($searchwords);

				$pattern	= '#(';
				$x 			= 0;

				foreach ($searchwords as $k => $hlword)
				{
					$pattern 	.= $x == 0 ? '' : '|';
					$pattern	.= preg_quote( $hlword , '#' );
					$x++;
				}
				$pattern 		.= ')#iu';

				$content 	= preg_replace( $pattern , '<span class="search-highlight">\0</span>' , $content );
				$content 	= JText::sprintf( 'PLG_FIELDS_ADDRESS_SEARCH_RESULT', $content );
			}
		}

		if( $content )
			return $content;
		else
			return false;
	}


	/**
	 * return list of users which match the address data of current logged in user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialTableRegistration
	 * @return	array array of objects with two attribute, ffriend_id, score
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onFriendSuggestSearch( $user, $userFieldData )
	{
		// Get the value
		$data 	= $this->getValue( $userFieldData );

		if( empty( $data->city ) || empty( $data->state ) || empty( $data->country ) )
		{
			return false;
		}

		$searchphase   = '+' . $data->city . ' ' . $data->state . ' ' . $data->country;
		$searchphase  	= str_replace( ' ', ' +', $searchphase);

		$db = Foundry::db();

		$query = 'select a.' . $db->nameQuote( 'uid' ) . ' as ' . $db->nameQuote( 'ffriend_id' ) . ', MATCH( a.' . $db->nameQuote( 'raw' ) . ' ) AGAINST (' . $db->Quote( $searchphase ) . ' IN BOOLEAN MODE ) AS score';
		$query .= ' FROM ' . $db->nameQuote( '#__social_fields_data' ) . 'as a';
		$query .= ' WHERE MATCH( a.' . $db->nameQuote( 'raw' ) . ' ) AGAINST (' . $db->Quote( $searchphase ) . ' IN BOOLEAN MODE )';
		$query .= ' and a.' . $db->nameQuote( 'field_id' ) . ' = ' . $db->Quote( $this->field->id );
		$query .= ' and a.' . $db->nameQuote( 'uid' ) . ' != ' . $db->Quote( $user->id );

		$query .= ' and not exists (';
		$query .= ' 	select if(b.' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $user->id ) . ', b.' . $db->nameQuote( 'target_id' ) . ', b.' . $db->nameQuote( 'actor_id' ) . ') AS ' . $db->nameQuote( 'friend_id' );
		$query .= '  		FROM ' . $db->nameQuote( '#__social_friends' ) . ' as b WHERE ( b.' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $user->id ) . ' or b.' . $db->nameQuote( 'target_id' ) . ' = ' . $db->Quote( $user->id ) . ')';
		//$query .= ' 		and b.' . $db->nameQuote( 'state' ) . ' != ' . $db->Quote( SOCIAL_FRIENDS_STATE_REJECTED );
		$query .= ' 		and b.' . $db->nameQuote( 'state' ) . ' = ' . $db->Quote( SOCIAL_FRIENDS_STATE_FRIENDS );
		$query .= ' 		and if(b.' . $db->nameQuote( 'actor_id' ) . ' = ' . $db->Quote( $user->id ) . ', b.' . $db->nameQuote( 'target_id' ) . ', b.' . $db->nameQuote( 'actor_id' ) . ') = a.' . $db->nameQuote( 'uid' );
		$query .= ' )';
		$query .= ' order by score desc';

		$db->setQuery( $query );
		$result = $db->loadObjectList();

		if( count( $result ) > 0 )
		{
			// we need to reset the score because in friend mode, the score will be
			// use to show no. mutual friends.
			for( $i=0; $i < count( $result); $i++ )
			{
				$item =& $result[ $i ];
				$item->score = 0;
			}
		}

		return $result;
	}

	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onSample()
	{
		// Get the value.
		$value = $this->getValue();

		// Set the value.
		$this->set( 'value', $value );

		// Get the countries list.
		$countries 	= SocialFieldsUserAddressHelper::getCountries( $this->params->get( 'sort_type' ), $this->params->get( 'title_type' ) );

		// Set the countries.
		$this->set( 'countries', $countries );

		// Get the requirements and set the required parameters
		$required = array(
			'address1'	=> $this->params->get( 'required_address1' ),
			'address2'	=> $this->params->get( 'required_address2' ),
			'city'		=> $this->params->get( 'required_city' ),
			'state'		=> $this->params->get( 'required_state' ),
			'zip'		=> $this->params->get( 'required_zip' ),
			'country'	=> $this->params->get( 'required_country' )
		);

		// Set the jsonencoded string for required data
		$this->set( 'required', Foundry::json()->encode( $required ) );

		// Set custom required option
		$isRequired = false;
		foreach( $required as $key => $value )
		{
			if( $value )
			{
				$isRequired = true;
				break;
			}
		}

		$this->set( 'options', array( 'required' => $isRequired ) );

		return $this->display();
	}

	public function validateInput( &$post )
	{
		// Get the default value.
		$address 	= $this->getValue( $post[ $this->inputName ] );

		if( empty( $address->address1 ) && $this->params->get( 'required_address1' ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_ADDRESS_PLEASE_ENTER_ADDRESS1' ) );
		}

		if( empty( $address->address2 ) && $this->params->get( 'required_address2' ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_ADDRESS_PLEASE_ENTER_ADDRESS2' ) );
		}

		if( empty( $address->city ) && $this->params->get( 'required_city' ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_ADDRESS_PLEASE_ENTER_CITY' ) );
		}

		if( empty( $address->state ) && $this->params->get( 'required_state' ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_ADDRESS_PLEASE_ENTER_STATE' ) );
		}

		if( empty( $address->zip ) && $this->params->get( 'required_zip' ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_ADDRESS_PLEASE_ENTER_ZIP' ) );
		}

		if( empty( $address->country ) && $this->params->get( 'required_country' ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_ADDRESS_PLEASE_ENTER_COUNTRY' ) );
		}

		return true;
	}
}
