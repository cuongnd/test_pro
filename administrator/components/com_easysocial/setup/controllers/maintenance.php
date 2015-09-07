<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Include parent library
require_once( dirname( __FILE__ ) . '/controller.php' );

class EasySocialControllerMaintenance extends EasySocialSetupController
{
	public function __construct()
	{
		// Include foundry's library, since we know that foundry is already available here.
		$this->foundry();
	}

	/**
	 * Synchronizes database tables
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function syncDB()
	{
		// Get this installations version
		$version	= $this->getInstalledVersion();

		// Get previous version installed
		$previous	= $this->getPreviousVersion();

		// Get total tables affected
		$affected	= Foundry::syncDB( $previous );

		// If the previous version is empty, we can skip this altogether as we know this is a fresh installation
		if( $previous && $affected !== false )
		{
			// Get list of folders from previous version installed to this version.
			$result 	= $this->getResultObj( JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_DB_SYNCED' , $version ) , 1 , JText::_( 'COM_EASYSOCIAL_INSTALLATION_STEP_SUCCESS' ) );
		}
		else
		{
			$result 	= $this->getResultObj( JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_DB_NOTHING_TO_SYNC' , $version ) , 1 , JText::_( 'COM_EASYSOCIAL_INSTALLATION_STEP_SUCCESS' ) );
		}

		// @TODO: In the future synchronize database table indexes here.

		// Update the version in the database to the latest now
		$config 	= Foundry::table( 'Config' );
		$exists		= $config->load( array( 'type' => 'version' ) );
		$config->type	= 'version';
		$config->value	= $version;

		$config->store();

		return $this->output( $result );
	}

	/**
	 * Synchronize Users on the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function syncUsers()
	{
		// Hardcoded to sync 50 users at a time.
		$limit 		= 100;

		// Fetch first $limit items to be processed.
		$db 		= Foundry::db();
		$query 		= array();

		$query[]	= 'SELECT a.' . $db->nameQuote( 'id' ) . ' FROM ' . $db->nameQuote( '#__users' ) . ' AS a';
		$query[]	= 'WHERE a.' . $db->nameQuote( 'id' ) . ' NOT IN( SELECT b.' . $db->nameQuote( 'user_id' ) . ' FROM ' . $db->nameQuote( '#__social_users' ) . ' AS b )';
		$query[]	= 'LIMIT 0,' . $limit;

		$db->setQuery( $query );
		$items 		= $db->loadObjectList();

		$totalItems = count( $items );

		if( !$items )
		{
			// Nothing to process here.
			$result 		= new stdClass();
			$result->state 	= 1;

			$result 	= $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_USERS_NO_UPDATES' ) , 1 , JText::_( 'COM_EASYSOCIAL_INSTALLATION_STEP_SUCCESS' ) );
			return $this->output( $result );
		}

		// Initialize all these users.
		$users 		= Foundry::user( $items );

		// we need to sync the user into indexer
		foreach( $users as $user )
		{
			$indexer = Foundry::get( 'Indexer' );

			$contentSnapshot	= array();
			$contentSnapshot[] 	= $user->getName( 'realname' );
			// $contentSnapshot[] 	= $user->email;

			$idxTemplate = $indexer->getTemplate();

			$content = implode( ' ', $contentSnapshot );
			$idxTemplate->setContent( $user->getName( 'realname' ), $content );

			$url = ''; //FRoute::_( 'index.php?option=com_easysocial&view=profile&id=' . $user->id );
			$idxTemplate->setSource($user->id, SOCIAL_INDEXER_TYPE_USERS, $user->id, $url);

			$date = Foundry::date();
			$idxTemplate->setLastUpdate( $date->toMySQL() );

			$indexer->index( $idxTemplate );
		}

		// Detect if there are any more records.
		$query 		= array();
		$query[]	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__users' ) . ' AS a';
		$query[]	= 'WHERE a.' . $db->nameQuote( 'id' ) . ' NOT IN( SELECT b.' . $db->nameQuote( 'user_id' ) . ' FROM ' . $db->nameQuote( '#__social_users' ) . ' AS b )';

		$db->setQuery( $query );
		$total 		= $db->loadResult();

		$result 	= $this->getResultObj( JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_USERS_SYNCED' , $totalItems ) , 2 , JText::_( 'COM_EASYSOCIAL_INSTALLATION_STEP_SUCCESS' ) );

		return $this->output( $result );
	}

	/**
	 * Synchronize users with the default profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function syncProfiles()
	{
		// Hardcoded to sync 1005128 users at a time.
		$limit 		= 100;

		// Fetch first $limit items to be processed.
		$db 		= Foundry::db();
		$sql 		= $db->sql();

		$query 		= array();
		$query[]	= 'SELECT a.' . $db->nameQuote( 'id' ) . ' FROM ' . $db->nameQuote( '#__users' ) . ' AS a';
		$query[]	= 'WHERE a.' . $db->nameQuote( 'id' ) . ' NOT IN( SELECT b.' . $db->nameQuote( 'user_id' ) . ' FROM ' . $db->nameQuote( '#__social_profiles_maps' ) . ' AS b )';
		$query[]	= 'LIMIT 0,' . $limit;

		$db->setQuery( $query );
		$items 		= $db->loadObjectList();

		if( !$items )
		{
			// Nothing to process here.
			$result 		= new stdClass();
			$result->state 	= 1;

			$result 	= $this->getResultObj( JText::_( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_PROFILES_NO_UPDATES' ) , 1 , JText::_( 'COM_EASYSOCIAL_INSTALLATION_STEP_SUCCESS' ) );
			$this->output( $result );
		}

		// Get the default profile id that we should use.
		$model 		= Foundry::model( 'Profiles' );
		$profile 	= $model->getDefaultProfile();

		// Get the total users that needs to be fixed.
		$totalItems = count( $items );

		foreach( $items as $item )
		{
			// Insert a new record
			$profileMap 				= Foundry::table( 'ProfileMap' );

			// Get the de
			$profileMap->profile_id		= $profile->id;
			$profileMap->user_id 		= $item->id;
			$profileMap->state 			= SOCIAL_STATE_PUBLISHED;

			$profileMap->store();
		}

		// Detect if there are any more records.
		$query 		= array();
		$query[]	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__users' ) . ' AS a';
		$query[]	= 'WHERE a.' . $db->nameQuote( 'id' ) . ' NOT IN( SELECT b.' . $db->nameQuote( 'user_id' ) . ' FROM ' . $db->nameQuote( '#__social_profiles_maps' ) . ' AS b )';

		$db->setQuery( $query );
		$total 		= $db->loadResult();

		$result 	= $this->getResultObj( JText::sprintf( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_PROFILES_SYNCHRONIZED_USERS' , $totalItems ) , 2 , JText::_( 'COM_EASYSOCIAL_INSTALLATION_STEP_SUCCESS' ) );

		return $this->output( $result );

	}

}



