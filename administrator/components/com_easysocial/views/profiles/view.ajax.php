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

// Include the main views class
Foundry::import( 'admin:/views/views' );

class EasySocialViewProfiles extends EasySocialAdminView
{
	/**
	 * Processes the request to return a DefaultAvatar object in JSON format.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableDefaultAvatar	The avatar object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function uploadDefaultAvatars( $avatar )
	{
		// Get the ajax object.
		$ajax 	= Foundry::ajax();

		$avatars 	= array( $avatar );

		$theme 	= Foundry::themes();
		$theme->set( 'defaultAvatars' , $avatars );
		$output	= $theme->output( 'admin/profiles/avatar.item' );
		
		return $ajax->resolve( $output );
	}

	/**
	 * Displays a dialog confirmation before deleting a default avatar
	 *
	 * @since	1.0
	 * @access	public
	 * @return	
	 */
	public function confirmDeleteAvatar()
	{
		$ajax 	= Foundry::ajax();
		$theme 	= Foundry::themes();

		$contents	= $theme->output( 'admin/profiles/dialog.delete.avatar' );
		$ajax->resolve( $contents );
	}

	/**
	 * Allows caller to browse for a profile
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function browse()
	{
		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		$output	= $theme->output( 'admin/profiles/dialog.browse' );

		return $ajax->resolve( $output );
	}

	public function insertMember( $user )
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject();
		}

		$theme 	= Foundry::themes();
		$theme->set( 'user' , $user );
		$output = $theme->output( 'admin/profiles/form.members.item' );

		return $ajax->resolve( $output );
	}

	public function confirmDelete()
	{
		$ajax 	= Foundry::ajax();

		$theme	= Foundry::themes();

		$contents = $theme->output( 'admin/profiles/dialog.delete' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Displays confirmation to delete a custom field
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function confirmDeleteField()
	{
		$ajax 	= Foundry::ajax();

		$theme	= Foundry::themes();

		$contents = $theme->output( 'admin/profiles/dialog.delete.field' );

		return $ajax->resolve( $contents );
	}

	public function getFieldValues( $values )
	{
		Foundry::ajax()->resolve( $values );
	}

	public function getPageConfig( $params, $values, $html )
	{
		Foundry::ajax()->resolve( $params, $values, $html );
	}

	public function deleteField( $state )
	{
		Foundry::ajax()->resolve( $state );
	}

	public function deletePage( $state )
	{
		Foundry::ajax()->resolve( $state );
	}

	public function saveFields( $data )
	{
		if( $data === false )
		{
			return Foundry::ajax()->reject( $this->getError() );
		}

		Foundry::ajax()->resolve( $data );
	}
}
