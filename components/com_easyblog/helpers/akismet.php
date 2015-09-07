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

class EasyBlogAkismetHelper
{
	private $akismet	= null;

	private function init( $url = '' )
	{
		$config		= EasyBlogHelper::getConfig();

		if( !$config->get( 'comment_akismet_key' ) )
		{
			return false;
		}

		if( is_null( $this->akismet ) )
		{
			require_once( EBLOG_CLASSES . DIRECTORY_SEPARATOR . 'akismet.php' );

			$url			= !empty( $url ) ? $url : JURI::root();
			$this->akismet	= new Akismet( $url , $config->get( 'comment_akismet_key' ) );
		}

		return $this;
	}

	public function isSpam( $data )
	{
		if( !$this->akismet )
		{
			if( !$this->init() )
			{
				return false;
			}
		}

		$this->akismet->setComment( $data );

		// If there are errors, we just assume that everything is fine so the entire
		// operation will still work correctly.
		if( $this->akismet->errorsExist() )
		{
			return false;
		}

		return $this->akismet->isSpam();
	}
}
