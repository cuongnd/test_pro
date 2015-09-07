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


class SocialStreamItem
{
	var $uid			= null;
	var $title			= null;
	var $content  		= null;
	var $preview		= null;
	var $display		= null;
	var $friendlyTS		= null;
	var $actor_id		= null;
	var $type			= null;
	var $with 			= null;
	var $location 		= null;


	public function __construct() {}

	/**
	 * Retrieves a set of assets associated with this stream item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAssets( $uid = '')
	{
		$model 	= Foundry::model( 'Stream' );

		$uid = ( $uid ) ? $uid : $this->uid;

		if( !$this->type || !$uid )
		{
			return array();
		}

		$result	= $model->getAssets( $uid , $this->type );


		$assets	= array();

		foreach( $result as $row )
		{
			$assets[]	= Foundry::registry( $row->data );
		}
		return $assets;
	}
}

/**
 * Any tables that wants to implement a stream interface will need to implement this.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
interface ISocialStreamItemTable
{
	public function addStream( $verb );
	public function removeStream();
}

/**
 * Action interface.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
interface ISocialStreamAction
{
	/**
	 * Class Constructor.
	 *
	 * @since	1.0
	 * @param	SocialStreamItem
	 */
	public function __construct( SocialStreamItem &$item );

	/**
	 * Responsible to output the title of the stream action.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getTitle();

	/**
	 * Responsible to output the contents of the stream action
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getContents();

	/**
	 * Responsible to output the action link
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getLink();

	/**
	 * Responsible to return the unique key
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getKey();

	/**
	 * Responsible to determine if the content should be hidden
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function isHidden();
}
