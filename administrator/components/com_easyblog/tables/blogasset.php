<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'table.php' );

class EasyBlogTableBlogAsset extends EasyBlogTable
{

	var $id 		= null;
	var $post_id 	= null;
	var $type		= null;
	var $value 		= null;

	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_post_assets' , 'id' , $db );
	}

	public function loadByPost( $postId )
	{
		$db 	= $this->getDBO();
		$query	= 'SELECT * FROM ' . EasyBlogHelper::getHelper( 'SQL' )->nameQuote( $this->_tbl ) . ' '
				. 'WHERE ' . EasyBlogHelper::getHelper( 'SQL' )->nameQuote( 'post_id' ) . '=' . $db->Quote( $postId );
		$db->setQuery( $query );

		$result = $db->loadObject();

		$this->bind( $result );
	}
}
