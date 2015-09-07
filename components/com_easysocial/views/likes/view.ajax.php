<?php
/**
* @package		Social
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'site:/views/views' );

class EasySocialViewLikes extends EasySocialSiteView
{
	/**
	 * Returns an ajax chain.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The verb that we have performed.
	 */
	public function toggle( $verb = '' , $id = null , $type = null, $group = SOCIAL_APPS_GROUP_USER )
	{
		// Load ajax lib
		$ajax	= Foundry::ajax();

		// Determine if there's any errors on the form.
		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		// Set the label
		$label	= $verb == 'like' ? JText::_( 'COM_EASYSOCIAL_LIKES_UNLIKE' ) : JText::_( 'COM_EASYSOCIAL_LIKES_LIKE' );

		// Set the message
		$likes 		= Foundry::likes( $id , $type, $group );
		$likes->get( $id , $type, $group );

		$likeCnt	= count( $likes->data );

		$isHidden	= ( $likeCnt > 0 ) ? false : true;

		$contents 	= $likes->toString();

		return $ajax->resolve( $contents , $label, $isHidden, $verb, $likeCnt );
	}


	/**
	 * Returns an ajax chain.
	 *
	 * @since	1.0
	 * @access	public
	 * @param
	 */
	public function showOthers( $users )
	{
		$ajax	= Foundry::ajax();
		$html 	= '';

		// Get user list
		$theme 		= Foundry::get( 'Themes' );
		$theme->set( 'users', $users );
		$html 		= $theme->output( 'site/users/simplelist' );

		return $ajax->resolve( $html );
	}
}
