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

// Include main view.
Foundry::import( 'admin:/views/views' );

class EasySocialViewAlerts extends EasySocialAdminView
{
	/**
	 * Main method to display the badges view.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function display( $tpl = null )
	{
		// Add heading here.
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_ALERTS' ) );

		// Set page icon
		$this->setIcon( 'icon-jar jar-tv_widescreen_down' );
		
		// Add description here.
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_ALERTS' ) );
		
		// Default filters
		$options 		= array();

		JToolbarHelper::custom( 'emailPublish' , 'publish' , '' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_PUBLISH_EMAIL' ) );
		JToolbarHelper::custom( 'systemPublish' , 'publish' , '' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_PUBLISH_SYSTEM' ) );
		JToolbarHelper::divider();
		JToolbarHelper::custom( 'emailUnpublish' , 'unpublish' , '' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_UNPUBLISH_EMAIL' ) );
		JToolbarHelper::custom( 'systemUnpublish' , 'unpublish' , '' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_UNPUBLISH_SYSTEM' ) );

		// Load badges model.
		$model		= Foundry::model( 'Alert' , array( 'initState' => true ) );

		// Get the search query from post
		$search		= JRequest::getVar( 'search' , $model->getState( 'search' ) );

		// Get the current ordering.
		$ordering 	= JRequest::getWord( 'ordering' , $model->getState( 'ordering' ) );
		$direction 	= JRequest::getWord( 'direction' , $model->getState( 'direction' ) );
		$extension 	= JRequest::getWord( 'extension' , $model->getState( 'extension' ) );
		$state	 	= JRequest::getVar( 'state', $model->getState( 'state' ) );
		$limit 		= $model->getState( 'limit' );

		// Get the badges
		$alerts		= $model->getItems();

		// Get pagination
		$pagination 	= $model->getPagination();

		$this->set( 'limit'			, $limit );
		$this->set( 'search'		, $search );
		$this->set( 'ordering'		, $ordering );
		$this->set( 'direction'		, $direction );
		$this->set( 'state'			, $state );
		$this->set( 'alerts'		, $alerts );
		$this->set( 'pagination'	, $pagination );

		echo parent::display( 'admin/alerts/default' );
	}

	/**
	 * Displays the discover layout for points.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function discover( $tpl = null )
	{
		// Add heading here.	
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_DISCOVER_ALERTS' ) );

		// Set page icon
		$this->setIcon( 'icon-jar jar-cloud_up' );

		// Add description here.
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_DISCOVER_ALERTS' ) );


		echo parent::display( 'admin/alerts/discover' );
	}

	/**
	 * Post process after alerts has been published / unpublished
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The current task
	 */
	public function togglePublish( $task = null )
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=alerts' );
	}
}