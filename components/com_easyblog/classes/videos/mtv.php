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

class EasyBlogVideoMtv
{
	private function getCode( $url )
	{
		preg_match( '/\/videos\/.*\/(.*)(?=\/)/i' , $url , $matches );

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
			return '<embed src="http://media.mtvnservices.com/mgid:uma:video:mtv.com:' . $code . '" width="' . $width . '" height="' . $height . '" type="application/x-shockwave-flash" flashVars="configParams=id%3D' . $code . '%26vid%3D' . $code . '%26uri%3Dmgid%3Auma%3Avideo%3Amtv.com%3A' . $code . '" allowFullScreen="true" allowScriptAccess="always" base="."></embed>';
		}
		return false;
	}
}