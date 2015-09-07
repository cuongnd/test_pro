<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

class SocialCrawlerImages
{	
	/**
	 * Ruleset to process document title
	 *
	 * @params	string $contents	The html contents that needs to be parsed.
	 * @return	boolean				True on success false otherwise.	 	 	 
	 */	 	
	public function process( $parser , &$contents , $uri )
	{
		$result 	= array();

		if( !$parser )
		{
			return $result;
		}
		
		// Find all image tags on the page.
		$images 	= $parser->find( 'img' );

		foreach( $images as $image )
		{
			// If there's a ../ , we need to replace it.
			if( stristr( $image->src , '/../' ) !== false )
			{
				$image->src 	= str_ireplace( '/../' , '/' , $image->src );
			}

			if( stristr( $image->src , 'http://' ) === false && stristr( $image->src , 'https://' ) === false )
			{
				$image->src 	= $uri . '/' . $image->src;
			}

			$result[]	= $image->src;
		}

		// Ensure that there are no duplicate images.
		$result 	= array_values( array_unique( $result , SORT_STRING ) );

		return $result;
	}
}
