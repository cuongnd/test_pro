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
Foundry::import( 'fields:/user/country/helper' );

class SocialFieldsUserCountry extends SocialFieldItem
{
	public function onRegister( &$post, &$registration )
	{
		$value = $this->params->get( 'default' );

		if( !empty( $post[ $this->inputName ] ) )
		{
			$value = $post[ $this->inputName ];
		}

		$countries = SocialFieldsUserCountryHelper::getHTMLContentCountries( $this->params->get( 'sort_type' ), $this->params->get( 'title_type' ) );

		$this->set( 'countries', $countries );

		return $this->display();
	}

	public function onRegisterValidate( &$post )
	{
		return $this->validateInput( $post );
	}

	public function onEdit( &$post, &$user, $errors )
	{
		$countries = SocialFieldsUserCountryHelper::getHTMLContentCountries( $this->params->get( 'sort_type' ), $this->params->get( 'title_type' ) );

		$this->set( 'countries', $countries );

		$value = !empty( $post[ $this->inputName ] ) ? $post[ $this->inputName ] : $this->value;

		$selected = Foundry::json()->decode( $value );

		if( !empty( $selected ) && $this->params->get( 'select_type' ) === 'textboxlist' )
		{
			$tmp = array();

			foreach( $selected as $selected )
			{
				$name = SocialFieldsUserCountryHelper::getCountryName( $selected );

				if( $name )
				{
					$t = new stdClass();
					$t->id = $selected;
					$t->title = $name;

					$tmp[] = $t;
				}
			}

			$selected = $tmp;
		}

		$this->set( 'selected', $selected );

		return $this->display();
	}

	public function onEditValidate( &$post )
	{
		return $this->validateInput( $post );
	}

	public function onSample()
	{
		$countries = SocialFieldsUserCountryHelper::getHTMLContentCountries( $this->params->get( 'sort_type' ), $this->params->get( 'title_type' ) );

		$this->set( 'countries', $countries );

		$this->display();
	}

	private function validateInput( &$post )
	{
		if( $this->isRequired() && empty( $post[ $this->inputName ] ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_COUNTRY_VALIDATION_REQUIRED' ) );
			return false;
		}

		$value = !empty( $post[ $this->inputName ] ) ? Foundry::json()->decode( $post[ $this->inputName ] ) : array();
		$count = count( $value );

		if( $this->params->get( 'min' ) > 0 && $count < $this->params->get( 'min' ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_COUNTRY_VALIDATION_MINIMUM_ERROR' ) );
			return false;
		}

		if( $this->params->get( 'max' ) > 0 && $count > $this->params->get( 'max' ) )
		{
			$this->setError( JText::_( 'PLG_FIELDS_COUNTRY_VALIDATION_MAXIMUM_ERROR' ) );
			return false;
		}

		return true;
	}

	public function onDisplay( $user )
	{
		$value		= $this->value;

		if( !$value )
		{
			return;
		}

		$value = Foundry::makeObject( $value );

		if( !$this->allowedPrivacy( $user ) )
		{
			return;
		}

		$countries = array();

		foreach( $value as $v )
		{
			$country = SocialFieldsUserCountryHelper::getCountryName( $v );

			if( $country )
			{
				$countries[] = $country;
			}
		}

		if( count( $countries ) === 0 )
		{
			return;
		}

		$this->set( 'countries', $countries );

		return $this->display( 'display' );
	}
}
