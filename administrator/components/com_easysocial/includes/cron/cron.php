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
 * Responsible to process cron services
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 *
 */
class SocialCron
{
	private $status 	= null;
	private $output 	= array();

	/**
	 * Factory method
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function factory()
	{
		$obj 	= new self();

		return $obj;
	}

	/**
	 * Dispatches Pending Emails
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function dispatchEmails()
	{
		$config		= Foundry::config();

		// Dispatch mails here.
		$mailer 	= Foundry::mailer();
		$state 		= $mailer->cron( $config->get( 'general.cron.limit' ) );

		return $state;
	}

	/**
	 * Triggers the cron service
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function execute()
	{
		$config 		= Foundry::config();

		// Check if we need a secure phrase.
		$requirePhrase 	= $config->get( 'general.cron.secure' );
		$storedPhrase 	= $config->get( 'general.cron.key' );
		$phrase 		= JRequest::getVar( 'phrase' , '' );

		if( $requirePhrase && empty( $phrase ) || ( $requirePhrase && $storedPhrase != $phrase ) )
		{
			$this->setStatus( 'failed' );
			$this->output( JText::_( 'COM_EASYSOCIAL_CRONJOB_PASSPHRASE_INVALID' ) );
		}

		// Data to be passed to the triggers.
		$data 			= array();

		// @trigger: fields.onBeforeCronExecute
		// Retrieve custom fields for the current step
		$fieldsModel 	= Foundry::model( 'Fields' );
		$customFields	= $fieldsModel->getCustomFields();

		$fields 	= Foundry::fields();
		$fields->trigger( 'onCronExecute' , SOCIAL_TYPE_USER , $customFields , $data );

		// @trigger: apps.onBeforeCronExecute
		$apps 		= Foundry::apps();
		$dispatcher	= Foundry::dispatcher();
		$dispatcher->trigger( SOCIAL_TYPE_USER , 'onCronExecute' , $data );

		// Array of states
		$state = array();

		// Process avatar storages here
		$state[] = $this->syncAvatars();

		// Process photo storages here
		$state[] = $this->syncPhotos();

		// Process file storages here
		$state[] = $this->syncConversationFiles();

		// Dispatch emails
		$state[] = $this->dispatchEmails();

		if( !in_array( false, $state ) )
		{
			$this->output( JText::_( 'COM_EASYSOCIAL_CRONJOB_PROCESSED' ) );
		}
		else
		{
			$this->output( JText::_( 'COM_EASYSOCIAL_CRONJOB_NOTHING_TO_EXECUTE' ) );
		}

		// Perform maintenance
		$maintenance	= Foundry::get( 'Maintenance' );
		$maintenance->cleanup();

		// @TODO: Run some sql optimizations here.


		// Purge expired URLs
		$result 	= $this->purgeExpiredUrls();

		$this->render();
	}

	public function syncConversationFiles()
	{
		$config 	= Foundry::config();
		$type		= $config->get( 'storage.conversations' );

		// We should check here against a list items that we should sync
		if( $type != 'joomla' )
		{
			// Here we need to synchronize joomla files over to the respective client.

			$storage	= Foundry::storage( $type );

			// Get the number of files to process at a time
			$limit 		= $config->get( 'storage.amazon.limit' );

			// Get a list of files to be synchronized over.
			$model		= Foundry::model( 'Files' );
			$files 		= $model->getItems( array( 'storage' => 'joomla' , 'limit' => 10 ) );
			$total 		= 0;

			foreach( $files as $file )
			{

				$source	= $file->getStoragePath() . '/' . $file->hash;
				$dest 	= $file->getStoragePath( true ) . '/' . $file->hash;

				$state 	= $storage->push( $file->name , $source , $dest );

				if( $state )
				{
					// Once the file is uploaded successfully delete the file physically.
					JFile::delete( $source );

					// Do something here.
					$file->storage 	= $type;

					$file->store();

					$total	+= 1;
				}
			}

			if( $total > 0 )
			{
				return JText::sprintf( '%1s files uploaded to remote storage' , $total );
			}
		}

		return JText::_( 'Nothing to process for conversation files' );
	}

	public function syncAvatars()
	{
		$config 	= Foundry::config();
		$type 		= $config->get( 'storage.photos' );

		if( $type != 'joomla' )
		{
			$storage	= Foundry::storage( $type );

			// Get the number of files to process at a time
			$limit 		= $config->get( 'storage.' . $type . '.limit' );

			// Get a list of avatars to be synchronized over.
			$model 		= Foundry::model( 'Avatars' );
			$options	= array( 'limit' => $limit , 'storage' => SOCIAL_STORAGE_JOOMLA , 'uploaded' => true );
			$avatars 	= $model->getAvatars( $options );
			$total 		= 0;

			if( $avatars )
			{
				foreach( $avatars as $avatar )
				{
					$small 		= $avatar->getPath( SOCIAL_AVATAR_SMALL , false );
					$medium		= $avatar->getPath( SOCIAL_AVATAR_MEDIUM , false );
					$large 		= $avatar->getPath( SOCIAL_AVATAR_LARGE , false );
					$square 	= $avatar->getPath( SOCIAL_AVATAR_SQUARE , false );

					$smallPath 	= JPATH_ROOT . '/' . $small;
					$mediumPath	= JPATH_ROOT . '/' . $medium;
					$largePath	= JPATH_ROOT . '/' . $large;
					$squarePath	= JPATH_ROOT . '/' . $square;

					if(
						$storage->push( $avatar->id , $smallPath , $small ) &&
						$storage->push( $avatar->id , $mediumPath , $medium ) &&
						$storage->push( $avatar->id , $largePath , $large ) &&
						$storage->push( $avatar->id , $squarePath , $square )
					)
					{
						$avatar->storage 	= $type;

						// Delete all the files now
						JFile::delete( $smallPath );
						JFile::delete( $mediumPath );
						JFile::delete( $largePath );
						JFile::delete( $squarePath );

						$avatar->store();
					}
				}

				if( $total > 0 )
				{
					$this->output( JText::sprintf( '%1s files uploaded to remote storage' , $total ) , 200 );
				}
			}

		}
	}

	public function syncPhotos()
	{
		$config 	= Foundry::config();
		$type 		= $config->get( 'storage.photos' );

		if( $type != 'joomla' )
		{
			$storage	= Foundry::storage( $type );

			// Get the number of files to process at a time
			$limit 		= $config->get( 'storage.' . $type . '.limit' );

			// Get a list of files to be synchronized over.
			$model		= Foundry::model( 'Photos' );
			$photos 	= $model->getPhotos( array( 'pagination' => $limit , 'storage' => SOCIAL_STORAGE_JOOMLA ) );
			$total 		= 0;

			$allowed 	= array( 'thumbnail' , 'large' , 'square' , 'featured' , 'medium' , 'original' );

			if( $photos )
			{
				foreach( $photos as $photo )
				{
					$album	= Foundry::table( 'Album' );
					$album->load( $photo->album_id );

					$basePath	= $photo->getStoragePath( $album );

					$metas 		= $model->getMeta( $photo->id , SOCIAL_PHOTOS_META_PATH );

					foreach( $metas as $meta )
					{
						$dest	= str_ireplace( JPATH_ROOT , '' , $meta->value  );
						$dest 	= ltrim( $dest , '/' );

						// We only want to upload certain files
						if( in_array( $meta->property , $allowed ) )
						{
							// Upload the file to the remote storage now
							$storage->push( $photo->title . $photo->getExtension() , $meta->value , $dest );

							// Delete the path.
							JFile::delete( $meta->value );
						}
					}

					$photo->storage 	= $type;
					$photo->store();
				}

				if( $total > 0 )
				{
					$this->output( JText::sprintf( '%1s files uploaded to remote storage' , $total ) , 200 );
				}
			}
		}
	}

	/**
	 * Sets the status
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function setStatus( $status )
	{
		$this->status 	= $status;
	}

	/**
	 * Displays the json codes
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The content of the item
	 * @param	string	The state of the item
	 * @return
	 */
	public function output( $contents , $status = '200' )
	{
		$output 			= new stdClass();

		$output->status 	= $status;
		$output->contents 	= $contents;
		$output->time 		= Foundry::date()->toMySQL();

		$this->output[]		= $output;
	}

	/**
	 * Purge expired urls
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function purgeExpiredUrls()
	{
		$config 	= Foundry::config();

		if( !$config->get( 'general.url.purge' ) )
		{
			return;
		}

		$model 	= Foundry::model( 'Links' );

		$state	= $model->clearExpired( $config->get( 'general.url.interval' ) );

		return $state;
	}

	/**
	 * Renders the cronjob output
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function render()
	{
		header('Content-type: text/x-json; UTF-8');

		echo Foundry::json()->encode( $this->output );
		exit;
	}
}
