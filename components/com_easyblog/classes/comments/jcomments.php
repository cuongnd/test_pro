<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.filesystem.file' );

class EasyBlogCommentJComments
{
	public static function getHTML( $blog )
	{
		$theme	= new CodeThemes();
		$file	= JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_jcomments' . DIRECTORY_SEPARATOR . 'jcomments.php';

		if( !JFile::exists( $file ) )
		{
			return '';
		}

		include_once( JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_jcomments' . DIRECTORY_SEPARATOR . 'jcomments.php' );

		$theme->set( 'blog' , $blog );
		return $theme->fetch( 'comment.jcomments.php' );
	}
}
