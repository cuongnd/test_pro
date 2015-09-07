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
 * Profile view for Notes app.
 *
 * @since	1.0
 * @access	public
 */
class BirthdayFieldWidgetsProfile
{
	/**
	 * Renders the age of the user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAge( $value )
	{
		$currentYear 	= Foundry::date()->format( 'Y' );
		$birthYear 		= Foundry::date( $value )->format( 'Y' );

		$age 	= $currentYear - $birthYear;

		return $age;
	}

	/**
	 * Displays the age in the position profileHeaderA
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function profileHeaderA( $key , $user , $field )
	{
		// Get the current stored value.
		$value 	= $field->data;

		if( empty( $value ) )
		{
			return false;
		}

		$my = Foundry::user();
		$privacyLib = Foundry::privacy( $my->id );
		if( !$privacyLib->validate( 'core.view' , $field->id, SOCIAL_TYPE_FIELD , $user->id ) )
		{
			return;
		}

		// Compute the age now.
		$age 	= $this->getAge( $value );

		$theme 	= Foundry::themes();
		$theme->set( 'value'	, $age );
		$theme->set( 'params'	, $field->getParams() );

		echo $theme->output( 'fields/user/birthday/widgets/display' );
	}
}
