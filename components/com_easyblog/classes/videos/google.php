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

class EasyBlogVideoGoogle
{
	private function getCode( $url )
	{
		preg_match( '/videoplay\?docid=(.*)(?=\#)/i' , $url , $matches );

		if( !empty( $matches ) )
		{
			return $matches[1];
		}
		
		return false;
	}
	
	public function getEmbedHTML( $url , $width , $height )
	{
		$code	= $this->getCode( $url );
		
		if( $code )
		{
			return '<embed id=VideoPlayback src=http://video.google.com/googleplayer.swf?docid=' . $code . '&hl=en&fs=true style=width:' . $width . 'px;height:' . $height . 'px allowFullScreen=true allowScriptAccess=always type=application/x-shockwave-flash> </embed>';
		}
		return false;
	}
}