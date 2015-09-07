<?php
/**
* @package		Social
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( 'JPATH_BASE' ) or die( 'Unauthorized Access' );

Foundry::import( 'admin:/tables/table' );

class SocialTableStreamTags extends SocialTable
{
	public $id			= null;
	public $stream_id	= null;
	public $uid			= null;
	public $utype		= null;
	public $with 		= null;
	public $offset 		= null;
	public $length 		= null;

	public function __construct( $db )
	{
		parent::__construct('#__social_stream_tags', 'id', $db);
	}

	public function toJSON()
	{
		return array('id' 		=> $this->id,
					 'stream_id' => $this->stream_id,
					 'uid' 		=> $this->uid,
					 'utype' 	=> $this->utype,
					 'with' 	=> $this->with,
					 'offset' 	=> $this->offset,
					 'length' 	=> $this->length
		 );
	}
}
