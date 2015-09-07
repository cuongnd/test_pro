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

Foundry::import( 'admin:/includes/apps/apps' );

class SocialUserAppMentions extends SocialAppItem
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Processes a saved story.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function onAfterStorySave( &$story , $creatorId )
	{
		// return;
		
		// $title 		= JRequest::getVar( 'link_title' , '' );
		// $content 	= JRequest::getVar( 'link_description' , '' );
		// $image 		= JRequest::getVar( 'link_image' , '' );

		// $registry 	= Foundry::registry();
		// $registry->set( 'title'		, $title );
		// $registry->set( 'content'	, $content );
		// $registry->set( 'image'		, $image );

		// $assets		= Foundry::table( 'StoryAsset' );
		// $assets->story_id 	= $story->id;
		// $assets->type 		= 'links';
		// $assets->data 		= $registry->toString();

		// $assets->store();
	}

	/**
	 * Prepares the story before displayed
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function onPrepareStoryContent( &$story )
	{
		// // Get the story assets for links
		// $filter 	= 'links';
		// $asset 		= $story->getAsset( $filter );

		// if( !$asset )
		// {
		// 	return;
		// }

		// $registry 	= $asset->getParams();

		// $this->set( 'registry'	, $registry );
	
		// $output 	= parent::display( 'story.content' );

		// return $output;
	}

	/**
	 * Prepares what should appear in the story form.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function onPrepareStoryAttachment( $story )
	{
		// return; 
		
		// $plugin = $story->createPlugin('links', 'attachment');

		// // Attachment button
		// $theme = Foundry::get('Themes');
		// $plugin->button->html = $theme->output('themes:/apps/user/links/story.attachment.button');

		// // Attachment script
		// $script = Foundry::get('Script');
		// $plugin->script = $script->output('apps:/user/links/story');

		// return $plugin;
	}
}
