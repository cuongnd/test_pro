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

class SocialUserAppLocations extends SocialAppItem
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Prepares the story panel
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStory
	 * @return	
	 */
	public function onPrepareStoryPanel( $story )
	{
		// Create a new panel
		$panel = $story->createPlugin( 'locations' , 'panel' );

		// Panel buttons
		$theme		= Foundry::get( 'Themes' );

		$panel->button->html  = $theme->output( 'themes:/apps/user/locations/story.panel.button' );
		$panel->content->html = $theme->output( 'themes:/apps/user/locations/story.panel.content' );

		// Panel script
		$script = Foundry::get('Script');
		$panel->script = $script->output('apps:/user/locations/story');

		return $panel;
	}
}
