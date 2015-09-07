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

jimport('joomla.application.component.model');

Foundry::import( 'admin:/includes/model' );

class EasySocialModelLinks extends EasySocialModel
{
	private $data			= null;

	public function __construct( $config = array() )
	{
		parent::__construct( 'links' , $config );
	}

	/**
	 * Purges the URL cache from the site
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool	True on success, false otherwise.
	 */
	public function clear()
	{
		$db 	= Foundry::db();

		$sql 	= $db->sql();
		$sql->delete( '#__social_links' );

		$db->setQuery( $sql );
		return $db->Query();
	}

	/**
	 * Purges the URL cache from the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The number of days interval
	 * @return	bool	True on success, false otherwise.
	 */
	public function clearExpired( $interval )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();
		$date 	= Foundry::date();

		$query 	= 'DELETE FROM `#__social_links` WHERE DATE_ADD( `created` , INTERVAL ' . $interval . ' DAY) <= ' . $db->Quote( $date->toMySQL() );

		$sql->raw( $query );

		$db->setQuery( $sql );

		return $db->Query();
	}
}
