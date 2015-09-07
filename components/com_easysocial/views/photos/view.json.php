<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Import parent view
Foundry::import( 'site:/views/views' );

class EasySocialViewPhotos extends EasySocialSiteView
{
	/**
	 * Displays the photo item
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function item()
	{
		echo parent::display( 'site/photos/default.item' );
	}

	/**
	 * Displays the photo form
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function uploadStory( $photo=null , $paths=array() )
	{
		$json = Foundry::json();

		// If there was an error uploading,
		// return error message.
		if ($this->hasErrors()) {
			echo Foundry::makeJSON($this->getMessage());
			exit;
		}

		// Photo html
		$theme = Foundry::themes();
		$theme->set( 'photo' , $photo );

		$html 	= $theme->output( 'apps/user/photos/story.attachment.item' );

		$response = new stdClass();
		$response->data = $photo->export();
		$response->html = $html;

		echo Foundry::makeJSON( $response );
		exit;
	}

	/**
	 * Displays the photo form
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function upload( $photo=null , $paths=array() )
	{
		$json = Foundry::json();

		// If there was an error uploading,
		// return error message.
		if ($this->hasErrors()) {
			echo Foundry::makeJSON($this->getMessage());
			exit;
		}

		// Get the current logged in user.
		$my = Foundry::user();

		// Get layout
		$layout = JRequest::getCmd('layout', 'item');

		$options = array(
			'viewer' => $my->id,
			'layout' => $layout,
			'showResponse' => false,
			'showTags'     => false
		);

		$html = Foundry::photo( $photo->id )->renderItem( $options );

		$response = new stdClass();
		$response->data = $photo->export();
		$response->html = $html;

		echo Foundry::makeJSON( $response );
		exit;
	}
}
