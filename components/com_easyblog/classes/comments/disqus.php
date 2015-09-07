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

class EasyBlogCommentDisqus
{
	public static function getHTML( $blog )
	{
		$config	= EasyBlogHelper::getConfig();
		$theme	= new CodeThemes();
		$code	= $config->get('comment_disqus_code');

		if( empty($code) )
		{
			return '';
		}

		$code	= JString::str_ireplace('Please enable JavaScript to view the comments powered by Disqus.','' , $code );
		$code	= JString::str_ireplace('blog comments powered by Disqus','',$code);

		$theme->set( 'code'	, $code );
		$theme->set( 'blog' , $blog );
		return $theme->fetch( 'comment.disqus.php' );
	}
}
