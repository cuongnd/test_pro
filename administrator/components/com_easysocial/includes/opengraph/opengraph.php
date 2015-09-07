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

class SocialOpengraph
{
	private $properties 	= array();

	/**
	 * This is the factory method to ensure that this class is always created all the time.
	 * Usage: Foundry::get( 'Template' );
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public static function getInstance()
	{
		static $obj = null;

		if( !$obj )
		{
			$obj 	= new self();
		}

		return $obj;
	}

	public function addImage( $image , $width = null , $height = null )
	{
		$config 	= Foundry::config();

		// Only proceed when opengraph is enabled
		if( !$config->get( 'oauth.facebook.opengraph.enabled' ) )
		{
			return;
		}

		$obj 			= new stdClass();

		$obj->url 		= $image;
		$obj->width 	= $width;
		$obj->height 	= $height;

		if( !isset( $this->properties[ 'image' ] ) )
		{
			$this->properties[ 'image' ]	= array();
		}

		$this->properties[ 'image' ][]	= $obj;

		return $this;
	}

	public function addUrl( $url )
	{
		$this->properties[ 'url' ]	= $url;

		return $this;
	}

	/**
	 * Adds opengraph data for the user
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function addProfile( SocialUser $user )
	{
		$config 	= Foundry::config();

		// Only proceed when opengraph is enabled
		if( !$config->get( 'oauth.facebook.opengraph.enabled' ) )
		{
			return;
		}

		$this->properties[ 'type' ]		= 'profile';

		$this->properties[ 'title' ]	= JText::sprintf( 'COM_EASYSOCIAL_OPENGRAPH_PROFILE_TITLE' , ucfirst( $user->getName() ) );

		$this->addImage( $user->getAvatar( SOCIAL_AVATAR_MEDIUM ) , SOCIAL_AVATAR_MEDIUM_WIDTH , SOCIAL_AVATAR_MEDIUM_HEIGHT );

		$this->addUrl( $user->getPermalink( true , true ) );

		return $this;
	}

	/**
	 * Adds the open graph tags on a page
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function render()
	{
		require_once( dirname( __FILE__ ) . '/renderer.php' );

		foreach( $this->properties as $property => $data )
		{
			if( method_exists( 'OpengraphRenderer' , $property ) )
			{
				OpengraphRenderer::$property( $data );
			}
		}

		return true;
	}
}
