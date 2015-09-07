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

jimport('joomla.utilities.date');

class EasyBlogDate
{
	private $date 		= null;

	public function __construct( $current = '', $tzoffset = null )
	{
		$this->date 	= JFactory::getDate( $current, $tzoffset);
	}

	public function toMySQL()
	{
		if( EasyBlogHelper::getJoomlaVersion() >= '3.0' )
		{
			return $this->date->toSql();
		}

		return $this->date->toMySQL();
	}

	public function toFormat( $format='%Y-%m-%d %H:%M:%S')
	{

		if( EasyBlogHelper::getJoomlaVersion() >= '1.6' )
		{
			if(  JString::stristr( $format, '%' ) !== false )
			{
				$format = EasyBlogHelper::getHelper( 'date' )->strftimeToDate( $format );
			}

			return $this->date->format( $format );
		}
		else
		{
			return $this->date->toFormat( $format );
		}
	}

	public function __call( $method , $args )
	{
		return call_user_func_array( array( $this->date , $method ) , $args );
	}

}
