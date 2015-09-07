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
require_once( JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_easyblog' . DIRECTORY_SEPARATOR . 'constants.php' );
require_once( EBLOG_HELPERS . DIRECTORY_SEPARATOR . 'router.php' );

class EasyBlogTableCategoryAcl extends EasyBlogTable
{
	/*
	 * The id of the category acl
	 * @var int
	 */
	var $id 			= null;

	/*
	 * The category id
	 * @var int
	 */
	var $category_id	= null;

	/*
	 * Category acl content id (joomla group id)
	 * @var int
	 */
	var $content_id     = null;


	/*
	 * Category acl type (group)
	 * @var string
	 */
	var $type			= null;

	/*
	 * Category status
	 * @var int
	 */
	var $status			= null;



	/**
	 * Constructor for this class.
	 *
	 * @return
	 * @param object $db
	 */
	function __construct(& $db )
	{
		parent::__construct( '#__easyblog_category_acl' , 'id' , $db );
	}

}
