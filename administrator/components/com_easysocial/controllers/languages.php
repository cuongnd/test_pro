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

Foundry::import( 'admin:/controllers/controller' );

class EasySocialControllerLanguages extends EasySocialController
{
	/**
	 * Purges the cache of language items
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function purge()
	{
		// Check for request forgeries here
		Foundry::checkToken();

		// Get the current view
		$view 		= $this->getCurrentView();

		$model	 = Foundry::model( 'Languages' );
		$model->purge();

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_LANGUAGES_PURGED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Installs a language file
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function install()
	{
		// Check for request forgeries here
		Foundry::checkToken();

		// Get the current view
		$view 		= $this->getCurrentView();

		$ids 		= JRequest::getVar( 'cid' );

		if( !$ids )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_LANGUAGES_INVALID_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		$ids 		= Foundry::makeArray( $ids );

		foreach( $ids as $id )
		{
			$table 	= Foundry::table( 'Language' );
			$table->load( $id );

			$table->install();
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_LANGUAGES_INSTALLED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}


	/**
	 * Retrieves a list of languages from API server
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getLanguages()
	{
		// Check for request forgeries here
		Foundry::checkToken();

		// Get the current view
		$view 		= $this->getCurrentView();

		// Get the api key
		$config 	= Foundry::config();
		$key 		= $config->get( 'general.key' );

		// Start connecting
		$connector 	= Foundry::connector();
		$connector->addUrl( SOCIAL_UPDATER_LANGUAGE );
		$connector->setMethod( 'POST' );
		$connector->addQuery( 'key' , $key );
		$connector->connect();
		
		$result 	= $connector->getResult( SOCIAL_UPDATER_LANGUAGE );

		// @TODO: Check for errors here.

		$obj		= Foundry::makeObject( $result );

		foreach( $obj->languages as $language )
		{
			// @TODO: Check if the language was previously installed thorugh our system.
			// If it does, load it instead of overwriting it.
			$table 		= Foundry::table( 'Language' );
			$exists 	= $table->load( array( 'locale' => $language->locale ) );

			// We do not want to bind the id
			unset( $language->id );

			if( !$exists )
			{
				// Since this is the retrieval, the state should always be disabled
				$table->state	= SOCIAL_STATE_UNPUBLISHED;
			}
			
			if( $exists && $table->state == SOCIAL_LANGUAGES_INSTALLED )
			{
				// Then check if the language needs to be updated. If it does, update the ->state to SOCIAL_LANGUAGES_NEEDS_UPDATING
				// We need to check if the language updated time is greater than the local updated time
				$languageTime 		= strtotime( $language->updated );
				$localLanguageTime	= strtotime( $table->updated );

				if( $languageTime > $localLanguageTime && $table->state == SOCIAL_LANGUAGES_INSTALLED )
				{
					$table->state	= SOCIAL_LANGUAGES_NEEDS_UPDATING;
				}
			}

			// Set the title
			$table->title 		= $language->title;

			// Set the locale
			$table->locale		= $language->locale;

			// Set the translator
			$table->translator	= $language->translator;

			// Set the updated time
			$table->updated 	= $language->updated;

			// Update the progress
			$table->progress 	= $language->progress;

			// Update the table with the appropriate params
			$params = Foundry::registry();

			$params->set( 'download' , $language->download );
			$params->set( 'md5' , $language->md5 );
			$table->params 	= $params->toString();

			$table->store();
		}

		return $view->call( __FUNCTION__ );
	}
}