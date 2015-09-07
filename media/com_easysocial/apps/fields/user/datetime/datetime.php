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
 * Field application for date time
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialFieldsUserDateTime extends SocialFieldItem
{
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
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegister( &$post, &$registration )
	{
		$value = isset( $post[$this->inputName] ) ? $post[$this->inputName] : '';

		$value = $this->escape( $value );

		if( $this->params->get( 'calendar' ) )
		{
			$this->set( 'date', $value );
		}
		else
		{
			$data = $this->getValue( $post[$this->inputName] );

			// Get the day data.
			$day 	= isset( $data->day ) ? $this->escape( $data->day ) : '';

			// Get the month data.
			$month	= isset( $data->month ) ? $this->escape( $data->month ) : '';

			// Get the year data.
			$year 	= isset( $data->year ) ? $this->escape( $data->year ) : '';

			$this->set( 'day' 	, $day );
			$this->set( 'month'	, $month );
			$this->set( 'year'	, $year );
		}

		// Get any errors for this field.
		$error		= $registration->getErrors( $this->inputName );

		$this->set( 'error', $error );

		// Display the output.
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
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegisterValidate( &$post, SocialTableRegistration &$registration )
	{
		return $this->validateDatetime( $post );
	}

	/**
	 * Executes before a user's registration is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onRegisterBeforeSave( &$post )
	{
		$value 		= isset( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';
		$data		= $this->getValue( $value );

		if( !empty( $data->year ) && !empty( $data->month ) && !empty( $data->day ) )
		{
			// Let's set this value back to the proper element.
			$date = Foundry::date( $data->year . '-' . $data->month . '-' . $data->day, false );
			$raw = $data->year . ' ' . $date->toFormat( 'F' ) . ' ' . $data->day;
			$post[ $this->inputName ] = array( 'data' => $date->toMySQL(), 'raw' => $raw );
		}
		else
		{
			unset( $post[ $this->inputName ] );
		}

		return true;
	}

	/**
	 * Displays the field input for user when they edit their profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array
	 * @param	SocialTableRegistration
	 * @return	string	The html output.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		$value 	= !empty( $post[$this->inputName ] ) ? $post[$this->inputName] : $this->value;

		// Since we know values are stored as xx/xx/xxxx
		$value 	= $this->escape( str_ireplace( '/', '-', $value ) );

		if( $this->params->get( 'calendar' ) )
		{
			if( !empty( $value ) )
			{
				$date 	= Foundry::date( $value, false );

				$format = $this->getDateFormat();

				$value 	= $date->toFormat( $format );
			}

			$this->set( 'date', $value );
		}
		else
		{
			$day = '';
			$month = '';
			$year = '';

			if( !empty( $value ) )
			{
				$date 	= Foundry::date( $value, false );

				$day 	= $date->toFormat( 'j' );
				$month 	= $date->toFormat( 'n' );
				$year 	= $date->toFormat( 'Y' );
			}

			$this->set( 'day' 	, $day );
			$this->set( 'month'	, $month );
			$this->set( 'year'	, $year );
		}

		// Check for errors
		$error = $this->getError( $errors );

		// Set errors.
		$this->set( 'error', $error );

		// Display the output.
		return $this->display();
	}

	/**
	 * Determines whether there's any errors in the submission in the registration form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEditValidate( &$post )
	{
		return $this->validateDatetime( $post );
	}

	/**
	 * Executes before a user's registration is saved.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @param	SocialUser	The user object
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function onEditBeforeSave( &$post, SocialUser &$user )
	{
		$value 		= isset( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';
		$data		= $this->getValue( $value );

		if( !empty( $data->year ) && !empty( $data->month ) && !empty( $data->day ) )
		{
			// Let's set this value back to the proper element.
			$date = Foundry::date( $data->year . '-' . $data->month . '-' . $data->day, false );
			$raw = $data->year . ' ' . $date->toFormat( 'F' ) . ' ' . $data->day;
			$post[ $this->inputName ] = array( 'data' => $date->toMySQL(), 'raw' => $raw );
		}
		else
		{
			unset( $post[ $this->inputName ] );
		}

		return true;
	}

	/**
	 * Displays the sample html codes when the field is added into the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The html output.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function onSample()
	{
		return $this->display();
	}

	private function getValue( $data )
	{
		// If current setup is to use calendar, we need to obtain the proper values
		if( $this->params->get( 'calendar' ) )
		{
			if( empty( $data ) )
			{
				return false;
			}

			$obj = $this->reconstructCalendarDate( $data );

			return $obj;
		}

		// Since the values are stored differently we need to compute the date back.
		$data = Foundry::json()->decode( $data );

		$year 	= isset( $data->year ) ? $data->year : '';
		$month	= isset( $data->month ) ? $data->month : '';
		$day 	= isset( $data->day ) ? $data->day : '';

		$obj 		= new stdClass();
		$obj->year	= $year;
		$obj->month	= $month;
		$obj->day 	= $day;

		return $obj;
	}

	private function reconstructCalendarDate( $data )
	{
		$format = $this->getDateFormat();

		$format = explode( '/', $format );

		$data = explode( '/', $data );

		$date = new stdClass();

		foreach( $format as $i => $f )
		{
			if( $f === 'd' )
			{
				$date->day = $data[$i];
			}

			if( $f === 'm' )
			{
				$date->month = $data[$i];
			}

			if( $f === 'Y' )
			{
				$date->year = $data[$i];
			}
		}

		return $date;
	}

	/**
	 * Performs php validation on this field
	 *
	 * @since	1.0
	 * @access	public
	 * @param	array 	The posted data.
	 * @return	bool	Determines if the system should proceed or throw errors.
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function validateDatetime( &$post )
	{
		$value 		= isset( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : '';

		// Determines if this field is required
		$required 	= $this->isRequired();

		if( $required && empty( $value ) )
		{
			return $this->setError( JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_PLEASE_ENTER_DATE' ) );
		}

		$data	= $this->getValue( $value );

		if( $required && ( empty( $data->year ) || empty( $data->month ) || empty( $data->day ) || !strtotime( $data->day . '-' . $data->month . '-' . $data->year ) ) )
		{
			if( $this->params->get( 'calendar' ) )
			{
				return $this->setError( JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_PLEASE_SELECT_DATETIME' ) );
			}

			return $this->setError( JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_PLEASE_ENTER_DATE' ) );
		}

		// Check for year range
		if( !empty( $data->year ) && ( $data->year < $this->params->get( 'yearfrom' ) || $data->year > $this->params->get( 'yearto' ) ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_YEAR_OUT_OF_RANGE' ) );
			return false;
		}

		if( empty( $data->year ) && empty( $data->month ) && empty( $data->day ) )
		{
			// If all data are empty, then just unset it
			$post[ $this->inputName ] = '';

			return true;
		}
		else
		{
			if( empty( $data->year ) || empty( $data->month ) || empty( $data->day ) || !strtotime( $data->day . '-' . $data->month . '-' . $data->year ) )
			{
				$post[ $this->inputName ] = '';

				// We set the error msg on info instead of field error because the value will be reverted to the original value
				Foundry::info()->set( (object) array( 'message' => JText::_( 'PLG_FIELDS_DATETIME_VALIDATION_INVALID_DATE_FORMAT' ), 'type' => SOCIAL_MSG_ERROR ) );

				// Manually set this flag to true instead of using setError
				$this->hasErrors = true;

				return false;
			}
		}

		return true;
	}

	private function getDateFormat()
	{
		$format = '';

		switch( $this->params->get( 'date_format') )
		{
			case 2:
			case '2':
				$format = 'm/d/Y';
				break;

			case 3:
			case '3':
				$format = 'Y/d/m';
				break;

			case 4:
			case '4':
				$format = 'Y/m/d';
				break;

			case 1:
			case '1':
			default:
				$format = 'd/m/Y';
				break;
		}

		return $format;
	}

	public function onDisplay( $user )
	{
		$value 	= $this->value;

		if( !$value )
		{
			return;
		}

		if( !$this->allowedPrivacy( $user ) )
		{
			return;
		}

		$date 	= JFactory::getDate( $value );

		$format = 'd M Y';

		switch( $this->params->get( 'date_format' ) )
		{
			case 2:
			case '2':
				$format = 'M d, Y';
				break;
			case 3:
			case '3':
				$format = 'Y d M';
				break;
			case 4:
			case '4':
				$format = 'Y M d';
				break;
		}

		// Push variables into theme.
		$this->set( 'date', $date );
		$this->set( 'format', $format );

		return $this->display( 'display' );
	}
}
