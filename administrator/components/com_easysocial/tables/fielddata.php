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

Foundry::import( 'admin:/tables/table' );

class SocialTableFieldData extends SocialTable
{
	/**
	 * The unique id for this item.
	 * @var int
	 */
	public $id			= null;

	/**
	 * The unique field id.
	 * @var int
	 */
	public $field_id	= null;

	/**
	 * The unique item id. E.g: user id.
	 * @var int
	 */
	public $uid 		= null;

	/**
	 * The unique item type. E.g: 'user'
	 * @var string
	 */
	public $type		= null;

	/**
	 * The value of the specific field item in json format
	 * @var string
	 */
	public $data		= null;

	/**
	 * The value of the specific field item in raw string format
	 * @var string
	 */
	public $raw		= null;

	/**
	 * The field params
	 * @var string
	 */
	public $params = null;

	public function __construct(& $db )
	{
		parent::__construct( '#__social_fields_data' , 'id' , $db );
	}

	public function loadByField( $fieldId , $uid , $type )
	{
		$db 		= Foundry::db();
		$query		= array();
		$query[]	= 'SELECT * FROM ' . $db->nameQuote( $this->_tbl );
		$query[]	= 'WHERE ' . $db->nameQuote( 'field_id' ) . '=' . $db->Quote( $fieldId );
		$query[]	= 'AND ' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $uid );
		$query[]	= 'AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );

		$query 		= implode( ' ' , $query );
		$db->setQuery( $query );

		$result 	= $db->loadObject();

		if( !$result )
		{
			return false;
		}

		return parent::bind( $result );
	}

	public function exists( $fieldId , $uid , $type )
	{
		$where 	= array();
		$where[ 'field_id' ] 	= $fieldId;
		$where[ 'uid' ]			= $uid;
		$where[ 'type' ]		= $type;

		return parent::exists( $where );
	}
}
