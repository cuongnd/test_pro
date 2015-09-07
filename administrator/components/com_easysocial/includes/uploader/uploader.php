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

Foundry::import( 'admin.includes.nodes.nodes' );
Foundry::import( 'admin:/tables/table' );

class SocialUploader
{
	public $name = 'file';
	public $maxsize = null;

	public function __construct($options=array())
	{
		if (isset($options['name'])) {
			$this->name = $options['name'];
		}

		if (isset($options['maxsize'])) {
			$this->maxsize = $options['maxsize'];
		}
	}

	public static function factory($options=array())
	{
		$obj = new self($options);
		return $obj;
	}

	/**
	 * Generates a unique token for the current session. 
	 * Without this token, the caller isn't allowed to upload the file.
	 *
	 * @since	1.0
	 * @param	null
	 * @return	string		A 12 digit token that can only be used once.
	 */
	public function generateToken()
	{
		// Generate a unique id.
		$id 	= uniqid();

		// Add md5 hash
		$id 	= md5( $id );

		$table			= Foundry::table( 'UploaderToken' );
		$table->token	= $id;
		$table->created = Foundry::date()->toMySQL();

		$table->store();

		return $id;
	}

	public function getFile($name=null)
	{
		// Check if post_max_size is exceeded.
		if (empty($_FILES) && empty($_POST)) {
			return Foundry::exception('COM_EASYSOCIAL_EXCEPTION_UPLOAD_POST_SIZE');
		}

		// Get the file
		if (empty($name)) $name = $this->name;
		$file = JRequest::getVar($name, '', 'FILES');

		// Check for invalid file object
		if (empty($file)) {
			return Foundry::exception('COM_EASYSOCIAL_EXCEPTION_UPLOAD_NO_OBJECT');
		}

		// If there's an error in this file
		if ($file['error']) {
			return Foundry::exception($file, SOCIAL_EXCEPTION_UPLOAD);
		}

		// Check if file exceeds max upload filesize
		$maxsize = Foundry::math()->convertBytes($this->maxsize);

		if ($maxsize > 0 && $file['size'] > $maxsize) {
			return Foundry::exception(
				JText::sprintf(
					'COM_EASYSOCIAL_EXCEPTION_UPLOAD_MAX_SIZE',
					Foundry::math()->convertUnits($maxsize, 'B', 'MB', false, true)
				)
			);
		}

		// Return file
		return $file;
	}
}