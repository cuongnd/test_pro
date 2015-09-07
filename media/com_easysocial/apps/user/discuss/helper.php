<?php
/**
* @package		%PACKAGE%
* @subpackge	%SUBPACKAGE%
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );


class SocialDiscussHelper extends SocialAppItem
{
	public static function exists()
	{
		$file 	= JPATH_ROOT . '/components/com_easydiscuss/helpers/helper.php';

		if( !JFile::exists( $file ) )
		{
			return false;
		}

		require_once( $file );

		return true;
	}

	public static function getPermalink( $postId )
	{
		if( !self::exists() )
		{
			return;
		}

		$itemId 	= DiscussRouter::getItemId();
		
		$permalink	= DiscussRouter::_( 'index.php?option=com_easydiscuss&view=post&id=' . $postId . '&Itemid=' . $itemId );

		return $permalink;
	}

	public static function getCategoryPermalink( $catId )
	{
		if( !self::exists() )
		{
			return;
		}

		$itemId 	= DiscussRouter::getItemIdByCategories( $catId );
		
		if( !$itemId )
		{
			$itemId 	= DiscussRouter::getItemId();
		}
		$permalink	= DiscussRouter::_( 'index.php?option=com_easydiscuss&view=categories&layout=listings&category_id=' . $catId . '&Itemid=' . $itemId );

		return $permalink;
	}
}
