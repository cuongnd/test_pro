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

class SocialMaintenance
{
	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		//do nohting.
	}

	public static function factory()
	{
		return new self();
	}

	public function debug()
	{
		var_dump( $this->session_id );
		exit;
	}


	public function cleanup()
	{
		// Clean up temporary files from uploader
		$this->cleanupUploader();

		// Clean up temporary data
		$this->cleanFromTmp();

		//clearing tmp date from social_registration.
		$this->cleanFromRegistration();

	}

	/**
	 * Clean up temporary uploader files
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function cleanupUploader()
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();
		$now	= Foundry::date();

		$query	= 'DELETE FROM `#__social_uploader` WHERE `created` <=' . $db->Quote( $now->toSql() );

		$sql->raw( $query );

		$db->setQuery( $sql );
		$db->Query();
	}

	/**
	 * Cleanup temporary uploaded files
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	private function cleanFromTmp()
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		$now    = Foundry::date();
		$query = 'delete from `#__social_tmp`';
		$query .= ' where `expired` <= ' . $db->Quote( $now->toMySQL() );

		$sql->raw( $query );
		$db->setQuery( $sql );
		$db->query();
	}

	private function cleanFromRegistration()
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();
		$now    = Foundry::date();

		// clean the registration temp data for records that exceeded 1 hour.
		$query = 'delete from `#__social_registrations`';
		$query .= ' where date_add( `created` , INTERVAL 60 MINUTE) <= ' . $db->Quote( $now->toMySQL() );

		$sql->raw( $query );
		$db->setQuery( $sql );
		$db->query();

	}



}
