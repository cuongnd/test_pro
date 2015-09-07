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

// Include main views file.
Foundry::import( 'admin:/views/views' );

class EasySocialViewApps extends EasySocialAdminView
{
	/**
	 * Default application listings page.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function display( $tpl = null )
	{
		// Set the page heading
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_APPS' ) );

		// Set the page icon
		$this->setIcon( 'icon-jar jar-store' );

		// Set the page description
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_APPS' ) );

		// Add Joomla buttons here.
		JToolbarHelper::publishList();
		JToolbarHelper::unpublishList();
		JToolbarHelper::divider();
		JToolbarHelper::deleteList( '' , 'uninstall' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_UNINSTALL' ) );
		
		// Get the applications model.
		$model 		= Foundry::model( 'Apps' , array( 'initState' => true ) );

		// Get the current ordering.
		$search 	= JRequest::getVar( 'search' , $model->getState( 'search' ) );
		$filter		= JRequest::getCmd( 'filter' , $model->getState( 'filter' ) );
		$state		= JRequest::getVar( 'state' , $model->getState( 'state' ) );
		$ordering 	= $model->getState( 'ordering' );
		$direction	= $model->getState( 'direction' );
		$limit 		= $model->getState( 'limit' );
		$search 	= $model->getState( 'search' );

		// Load the applications.
		$apps 		= $model->getItemsWithState();

		// Get the pagination.
		$pagination	= $model->getPagination();

		$this->set( 'search' 	, $search );
		$this->set( 'limit'		, $limit );
		$this->set( 'ordering'	, $ordering );
		$this->set( 'direction'	, $direction );
		$this->set( 'filter', $filter );
		$this->set( 'state'	, $state );
		$this->set( 'apps'	, $apps );
		$this->set( 'pagination'	, $pagination );
		
		parent::display( 'admin/apps/default' );
	}
	
	/**
	 * Displays the installation page.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 */
	public function install()
	{
		$info	= Foundry::info();

		$info->set( $this->getMessage() );

		// Set the page heading
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_APPS' ) );
		$this->setIcon( 'icon-jar jar-window_sidebar' );
		// Set the page description
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_APPS_INSTALLER' ) );

		// Set the default temporary path.
		$jConfig 		= JFactory::getConfig();
		$temporaryPath	= $jConfig->get( 'tmp_path' );

		// Retrieve folders.
		$appsModel		= Foundry::model( 'Apps' );
		$directories	= $appsModel->getDirectoryPermissions();
		
		$this->set( 'directories'	, $directories );
		$this->set( 'temporaryPath' , $temporaryPath );

		parent::display( 'admin/apps/install.form' );
	}

	/**
	 * Post process after discovered items are purged
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function purgeDiscovered()
	{
		Foundry::info()->set( $this->getMessage() );
		
		$this->redirect( 'index.php?option=com_easysocial&view=apps&layout=discover' );
	}

	/**
	 * Displays the installation page.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 */
	public function discover()
	{
		Foundry::info()->set( $this->getMessage() );

		// Set the page heading
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_DISCOVER_APPS' ) );

		$this->setIcon( 'icon-jar jar-cloud_down' );
		// Set the page description
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_DISCOVER_APPS' ) );

		// Add Joomla buttons here.
		JToolbarHelper::custom( 'installDiscovered' , 'upload' , '' , JText::_( 'COM_EASYSOCIAL_INSTALL_SELECTED_BUTTON' ) , false );
		JToolbarHelper::divider();
		JToolbarHelper::custom( 'discover' , 'refresh' , '' , JText::_( 'COM_EASYSOCIAL_DISCOVER_BUTTON' ) , false );
		JToolbarHelper::custom( 'purgeDiscovered' , 'trash' , '' , JText::_( 'COM_EASYSOCIAL_PURGE_CACHE_BUTTON' ) , false );
		
		// Get the applications model.
		$model 		= Foundry::model( 'Apps' , array( 'initState' => true ) );

		// Get the current ordering.
		$search 	= JRequest::getVar( 'search' , $model->getState( 'search' ) );
		$filter		= JRequest::getCmd( 'filter' , $model->getState( 'filter' ) );
		$ordering 	= $model->getState( 'ordering' );
		$direction	= $model->getState( 'direction' );
		$limit 		= $model->getState( 'limit' );
		$search 	= $model->getState( 'search' );

		// Load the applications.
		$apps 		= $model->getItemsWithState( array( 'discover' => true ));

		// Get the pagination.
		$pagination	= $model->getPagination();

		$this->set( 'search' 	, $search );
		$this->set( 'limit'		, $limit );
		$this->set( 'ordering'	, $ordering );
		$this->set( 'direction'	, $direction );
		$this->set( 'filter', $filter );
		$this->set( 'apps'	, $apps );
		$this->set( 'pagination'	, $pagination );
		
		parent::display( 'admin/apps/discover' );
	}

	/**
	 * Post process after installing discovered apps
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function installDiscovered()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=apps&layout=discover' );
	}

	/**
	 * Displays installation completed page.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	stdclass	A stdclass containing `output` which is from the callback method and `desc` which is the application description.
	 * @return	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function installCompleted( $app )
	{
		// Set the page heading
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_APPS_INSTALL_SUCCESS' ) );
		$this->setIcon( 'icon-jar jar-tick' );
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_APPS_INSTALL_SUCCESS' ) );

		$session		= JFactory::getSession();

		$session->set( 'application.queue' , null );

		// Get the apps meta.
		$meta 		= $app->getMeta();

		$this->set( 'meta'		, $meta );
		$this->set( 'app'		, $app );
		$this->set( 'output'	, $app->result->output );
		$this->set( 'desc'		, $meta->desc );

		echo parent::display( 'admin/apps/install.completed' );
	}

	/**
	 * Post process after app is published
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function publish()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=apps' );
	}

	/**
	 * Post process after app is unpublished
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function unpublish()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=apps' );
	}

	/**
	 * Post process after apps has been uninstalled
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function uninstall()
	{
		Foundry::info()->set( $this->getMessage() );
		
		$this->redirect( 'index.php?option=com_easysocial&view=apps' );
		$this->close();
	}

	/**
	 * Post process after an app is saved
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function store( $app = null , $task = '' )
	{
		Foundry::info()->set( $this->getMessage() );

		if( $task == 'apply' )
		{
			$this->redirect( 'index.php?option=com_easysocial&view=apps&layout=form&id=' . $app->id );
			$this->close();
		}

		if( $task == 'save' )
		{
			$this->redirect( 'index.php?option=com_easysocial&view=apps' );
			$this->close();
		}
	}

	/**
	 * Displays the application form page.
	 *
	 * @since	1.0
	 * @access	public
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function form()
	{
		// Get the application id from request.
		$id 		= JRequest::getInt( 'id' );

		// Load the application.
		$app 		= Foundry::table( 'App' );
		$app->load( $id );

		if( !$id || !$app->id )
		{
			// App has to have a valid id.
			Foundry::info()->set( false , JText::_( 'COM_EASYSOCIAL_APP_INVALID_ID' ) , SOCIAL_MSG_ERROR );
			$this->redirect( 'index.php?option=com_easysocial&view=apps' );
			$this->close();
		}

		// Set the page heading
		$this->setHeading( $app->get( 'title' ) );

		$this->setIconUrl( $app->getIcon( 'large' ) , false );

		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_APPS_CONFIGURATION' ) );

		// Load app's language file.
		Foundry::language()->loadApp( $app->group , $app->element );
		
		JToolbarHelper::cancel();
		JToolbarHelper::divider();
		JToolbarHelper::apply();
		JToolbarHelper::save();
		
		$this->set( 'app'	, $app );

		parent::display( 'admin/apps/form' );
	}

	/**
	 * Displays when the installation is completed
	 *
	 * @access	public
	 */
	public function completed( $app )
	{
		$this->set( 'app'		, $app );
		
		// Display the success messages.
		parent::display( 'admin.installer.completed' );
		
		// Display the form again so that the user can continue with the installation if needed.
		$this->display();
	}
	
	public function errors( $response )
	{
		$this->set( 'response' , $response );
		
		parent::display( 'admin.installer.errors' );
	}
}