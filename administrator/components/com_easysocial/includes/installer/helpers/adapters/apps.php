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

/**
 * Installation library for apps. Handles most of the apps installation request here.not in
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 *
 */
class SocialInstallerApps extends JObject
{
	const RECURSIVE_SEARCH		= true;
	const RETRIEVE_FULL_PATH	= true;

	// Error messages
	const XML_NOT_FOUND			= 100;
	const XML_NOT_VALID			= 200;

	private $allowed			= array( 'fields' , 'widgets' , 'apps' );
	private	$dom				= null;

	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialInstallerJoomla
	 * @return	null
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function __construct( $installer )
	{
		$this->installer	= $installer;
	}

	/**
	 * Discovers the app
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function discover()
	{
		// Check if this element is already installed before
		$element 	= $this->installer->element;
		$group 		= $this->installer->group;
		$type 		= $this->installer->type;

		$model 		= Foundry::model( 'Apps' );
		$installed	= $model->isAppInstalled( $element , $group , $type );

		// If app has already been installed, skip this
		if( $installed )
		{
			return false;
		}

		// Store it into the database once the installation is successful.
		$app 	= Foundry::table( 'App' );

		// Set the type of the application.
		$app->type		= SOCIAL_APPS_TYPE_APPS;

		// Set the group of the application
		$app->group 	= $group;

		// Set the element of the application.
		$app->element	= $element;

		// Determines if the app is a core app.
		$app->core 		= $this->installer->isCore();

		// Determines if the app is a unique app.
		$app->unique	= $this->installer->isUnique();

		// Set the application title.
		$app->title 	= $this->installer->getTitle();

		// Set the application alias
		$app->alias 	= $this->installer->getAlias();

		// Set the parameters for the app.
		$params 		= $this->installer->getParams();
		$app->params 	= $params->toString();

		// Determine if this app has a widget layout
		$app->widget	= $this->installer->isWidget();

		// Determines if this app is used for processing only
		$app->system 		=  $this->installer->isSystem();

		// Determines if this app is installable by the user
		$app->installable	= $this->installer->isInstallable();

		// Set the version for the app so we don't need to always query the file.
		$app->version	= $this->installer->getVersion();

		// Set the app state to discovered
		$app->state 	= SOCIAL_APP_STATE_DISCOVERED;

		// Set the creation date for the app.
		$app->created 			= Foundry::date()->toMySQL();

		// Try to store the app.
		$state					= $app->store();

		// If there's problem storing the app, set the errors here.
		if( !$state )
		{
			$this->setError( $app->getError() );

			return false;
		}

		return true;
	}

	/**
	 * Initiates the installation process.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	StdClass	A stdclass object
	 */
	public function install()
	{
		// Get the element from the installer.
		$element 		= $this->installer->element;

		// Get the group from the installer.
		$group 			= $this->installer->group;

		// Get the destination path.
		$destination	= SOCIAL_APPS . '/' . $group . '/' . $element;

		// Create the destination folder if it doesn't exist.
		$state 			= $this->installer->createFolder( $destination );

		// Copy packaged files into the destination folder.
		$state 			= $this->installer->copyContents( $destination , $element );

		// Copy manifest file into destination folder.
		$manifestFile 	= $destination . '/' . $element . '.xml';
		$this->installer->copyManifest( $manifestFile );

		// Initialize any necessary SQL queries from the plugin.
		$this->installer->runQueries();

		// Allow application to run it's own `install` method if necessary.
		$installerFile 	= $destination . '/install.php';
		$response		= $this->installer->callback( 'install' , $installerFile );

		if( $response === false )
		{
			$this->installer->callback( 'error' , $installerFile );

			// Display some errors here.
			return false;
		}

		// Store it into the database once the installation is successful.
		$app 	= Foundry::table( 'App' );
		$app->loadByElement( $element , $group , SOCIAL_APPS_TYPE_APPS );

		// Set the type of the application.
		$app->type		= SOCIAL_APPS_TYPE_APPS;

		// Set the group of the application
		$app->group 	= $group;

		// Set the element of the application.
		$app->element	= $element;

		// Determines if the app is a core app.
		$app->core 		= $this->installer->isCore();

		// Determines if this app is used for processing only
		$app->system 		=  $this->installer->isSystem();
		
		// Determines if the app is a unique app.
		$app->unique	= $this->installer->isUnique();

		// Set the application title.
		$app->title 	= $this->installer->getTitle();

		// Set the application alias
		$app->alias 	= $this->installer->getAlias();

		// Set the parameters for the app.
		$params 		= $this->installer->getParams();
		$app->params 	= $params->toString();

		// Determine if this app has a widget layout
		$app->widget	= $this->installer->isWidget();

		// Determines if this app is installable by the user
		$app->installable	= $this->installer->isInstallable();

		// Set the version for the app so we don't need to always query the file.
		$app->version	= $this->installer->getVersion();

		// If this is new application, we try to unpublish it by default.
		if( is_null( $app->id ) || !$app->id )
		{
			$app->state			= SOCIAL_STATE_UNPUBLISHED;
		}

		// If the previous state was "discovered", we need to set it to unpublished
		if( $app->state == SOCIAL_APP_STATE_DISCOVERED )
		{
			$app->state 	= SOCIAL_STATE_UNPUBLISHED;
		}

		if( $app->core )
		{
			$app->state 	= SOCIAL_STATE_PUBLISHED;
		}

		// Set the creation date for the app.
		$app->created 			= Foundry::date()->toMySQL();

		// Try to store the app.
		$state					= $app->store();

		// If there's problem storing the app, set the errors here.
		if( !$state )
		{
			$this->setError( $app->getError() );
		}

		// Process available views from the app
		$this->installer->installViews( $app );

		// Install the alert rules
		$app->installAlerts();

		// Triggers the success callback here. Plugin might want to perform specific stuffs.
		$result 		= new stdClass();

		$result->output	= $this->installer->callback( 'success' , $installerFile );

		// Return the application description.
		$description 	= trim( $this->installer->getDescription() );

		$result->desc 	= JText::_( trim( $this->installer->getDescription() ) );

		// Set a temporary variable.
		$app->result 	= $result;

		return $app;
	}
}
