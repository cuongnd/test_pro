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

// Include main view here.
Foundry::import( 'admin:/includes/views' );

class EasySocialSiteView extends EasySocialView
{
	public function __construct( $config = array() )
	{
		// We want to allow child classes to easily access theme configurations on the view
		$this->themeConfig	= Foundry::themes()->getConfig();
		
		parent::__construct( $config );
		
		// Check if there is a method isFeatureEnabled exists. If it does, we should do a check all the time.
		if( method_exists( $this , 'isFeatureEnabled' ) )
		{
			$this->isFeatureEnabled();
		}
	}

	/**
	 * Determines if the current view should be locked down.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool
	 */
	public function lockdown()
	{
		// Default, all views are locked down.
		$state 	= true;

		if( method_exists( $this , 'isLockDown' ) )
		{
			$state 	= $this->isLockDown();
		}

		return $state;
	}

	/**
	 * Responsible to render the views / layouts from the front end.
	 * This is a single point of entry function.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function display( $tpl = null )
	{
		$doc 		= JFactory::getDocument();
		$type 		= $doc->getType();
		$show		= JRequest::getString( 'show' );

		if( $type == 'html' )
		{
			// Include main structure here.
			$theme 		= Foundry::get( 'Themes' );

			// Capture output.
			ob_start();
			parent::display( $tpl );
			$contents 	= ob_get_contents();
			ob_end_clean();

			// Get the current view.
			$view 		= JRequest::getVar( 'view' , '' );
			$view 		= !empty( $view ) ? ' view-' . $view : '';
			
			// Get the current task
			$task 		= JRequest::getCmd( 'task' , '' );
			$task 		= !empty( $task ) ? ' task-' . $task : '';

			// Get any "id" or "cid" from the request.
			$object 	= JRequest::getInt( 'id' , JRequest::getInt( 'cid' , 0 ) );
			$object 	= !empty( $object ) ? ' object-' . $object : '';

			// Get any layout
			$layout 	= JRequest::getCmd( 'layout' , '' );
			$layout 	= !empty( $layout ) ? ' layout-' . $layout : '';

			$theme->set( 'layout'	, $layout );
			$theme->set( 'object'	, $object );
			$theme->set( 'task'		, $task );
			$theme->set( 'view'		, $view );
			$theme->set( 'show'		, $show );
			$theme->set( 'contents'	, $contents );
			$theme->set( 'toolbar'	, $this->getToolbar() );

			// Component template scripts
			$page       = Foundry::page();
			$scripts    = '<script type="text/javascript">' . implode('</script><script type="text/javascript">', $page->inlineScripts) . '</script>';
			$theme->set( 'scripts'  , $scripts );

			// Ensure component template scripts don't get added to the head.
			$page->inlineScripts = array();

			echo $theme->output( 'site/structure/default' );
			return;
		}


		return parent::display( $tpl );
	}

	/**
	 * Helper method to retrieve the toolbar's HTML code.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function getToolbar()
	{
		// The current logged in user.
		$my 		= Foundry::user();

		$toolbar	= Foundry::get( 'Toolbar' );

		return $toolbar->render();
	}
}
