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

class ThemesHelperBootstrap
{
	/**
	 * Renders publish / unpublish icon.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	object	The object to check against.
	 * @param	string	The controller to be called.
	 * @param	string	The key for the object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public static function popover( $title = '' , $content = '' , $placement = '' , $placeholder = '' , $html = false )
	{
		$theme 	= Foundry::get( 'Themes' );

		$theme->set( 'title'	, $title );
		$theme->set( 'content'	, $content );
		$theme->set( 'placement', $placement );
		$theme->set( 'placeholder' , $placeholder );
		$theme->set( 'html' , $html );

		return $theme->output( 'admin/html/bootstrap.popover' );
	}
}