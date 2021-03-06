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

Foundry::import( 'admin:/views/views' );

class EasySocialViewPrivacy extends EasySocialAdminView
{
	/**
	 * Main method to display the privacy view.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	null
	 *
	 * @author	Sam <sam@stackideas.com>
	 */
	public function display( $tpl = null )
	{
		// Add heading here.
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_PRIVACY' ) );

		// Set page icon
		$this->setIcon( 'icon-jar jar-lightbulb_cfl_on' );

		// Add description here.
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_PRIVACY' ) );

		$state 	= JRequest::getInt( 'state' , 1 );

		$model 		= Foundry::model( 'Privacy' , array( 'initState' => true ) );
		$limit 		= $model->getState( 'limit' );
		$ordering 	= $model->getState( 'ordering' );
		$direction	= $model->getState( 'direction' );
		$search 	= $model->getState( 'search' );

		$privacy	= $model->getList();

		// Get pagination
		$pagination = $model->getPagination();

		$this->set( 'ordering'	, $ordering );
		$this->set( 'direction'	, $direction );
		$this->set( 'limit'		, $limit );
		$this->set( 'search'	, $search );
		$this->set( 'pagination', $pagination );
		$this->set( 'privacy'	, $privacy );
		$this->set( 'state' 	, $state );

		echo parent::display( 'admin/privacy/default' );
	}

	/**
	 * Post process points saving
	 *
	 * @since	1.0
	 * @access	public
	 * @return	null
	 *
	 * @author	Sam <sam@stackideas.com>
	 */
	public function save( $task , $privacy )
	{
		Foundry::info()->set( $this->getMessage() );

		if( $this->hasErrors() )
		{
			$this->redirect( 'index.php?option=com_easysocial&view=privacy&layout=form&id=' . $privacy->id );
			$this->close();
		}

		if( $task == 'apply' )
		{
			$this->redirect( 'index.php?option=com_easysocial&view=privacy&layout=form&id=' . $privacy->id );
			$this->close();
		}

		$this->redirect( 'index.php?option=com_easysocial&view=privacy' );
		$this->close();
	}

	/**
	 * Main method to display the form.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	null
	 *
	 * @author	Sam <sam@stackideas.com>
	 */
	public function form( $tpl = null )
	{
		// Get the id from the request.
		$id 	= JRequest::getInt( 'id' , 0 );

		// Get the table object
		$privacy	= Foundry::table( 'Privacy' );
		$state 		= $privacy->load( $id );

		// Add heading here.
		$this->setHeading( JText::_( $privacy->description ) );

		// Set page icon
		$this->setIcon( 'icon-jar jar-lightbulb_cfl_on' );

		// Add description here.
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_EDIT_PRIVACY' ) );

		// Add Joomla buttons here
		JToolbarHelper::cancel();
		JToolbarHelper::divider();
		JToolbarHelper::apply( 'apply' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE' ) , false , false );
		JToolbarHelper::save( 'save' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE_AND_CLOSE' ) );

		$this->set( 'privacy'	, $privacy );

		echo parent::display( 'admin/privacy/form' );
	}

	/**
	 * Redirects user back to the points listing once it's installed
	 *
	 * @since	1.0
	 * @access	public
	 * @return	null
	 *
	 * @author	Sam <sam@stackideas.com>
	 */
	public function upload()
	{
		// Get info object.
		$info 	= Foundry::info();
		$info->set( $this->getMessage() );

		return $this->redirect( 'index.php?option=com_easysocial&view=privacy&layout=install' );
	}

	/**
	 * Displays the installation layout for points.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	null
	 *
	 * @author	Sam <sam@stackideas.com>
	 */
	public function install( $tpl = null )
	{
		// Add heading here.
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_INSTALL_PRIVACY' ) );

		// Set page icon
		$this->setIcon( 'icon-jar jar-imac_up' );

		// Add description here.
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_INSTALL_PRIVACY' ) );


		echo parent::display( 'admin/privacy/install' );
	}

	/**
	 * Displays the discover layout for points.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	null
	 *
	 * @author	Sam <sam@stackideas.com>
	 */
	public function discover( $tpl = null )
	{
		// Add heading here.
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_DISCOVER_PRIVACY' ) );

		// Set page icon
		$this->setIcon( 'icon-jar jar-cloud_up' );

		// Add description here.
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_INSTALL_PRIVACY' ) );


		echo parent::display( 'admin/privacy/discover' );
	}

	/**
	 * Post processing for deleting an item
	 *
	 * @since	1.0
	 * @access	public
	 * @return
	 */
	public function cancel()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=privacy' );
		$this->close();
	}
}
