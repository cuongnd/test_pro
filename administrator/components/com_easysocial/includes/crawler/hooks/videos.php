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

class SocialContentVideosParser
{
	private $patterns	= array(
									'youtube.com'		=> 'youtube',
									'youtu.be'			=> 'youtube',
									'vimeo.com'			=> 'vimeo',
									'yahoo.com'			=> 'yahoo',
									'metacafe.com'		=> 'metacafe',
									'google.com'		=> 'google',
									'mtv.com'			=> 'mtv',
									'liveleak.com'		=> 'liveleak',
									'revver.com'		=> 'revver',
									'dailymotion.com'	=> 'dailymotion'
								);

	public function strip( $content )
	{
	
		// In case Joomla tries to entity the contents, we need to replace accordingly.
		$content	= str_ireplace( '&quot;' , '"' , $content );
		
		$pattern	= array('/\{video:.*?\}/',
							'/\{"video":.*?\}/'
							);
							
		$replace    = array('','');
		

		return preg_replace( $pattern , $replace , $content );
	}
	
	public function process( $content )
	{
		// Match all 'http://' first.
		preg_match_all( $this->patterns , $content , $matches );

		var_dump( $matches );
// 		exit;	

// 		
// 		if( !empty( $videos ) )
// 		{
// 			$json	= new Services_JSON();
// 			foreach( $videos as $video )
// 			{
// 				$data	= $json->decode( $video );
// 				preg_match( '/http\:\/\/(.*)\//i' , $data->video , $matches );
// 				$url	= $matches[0];
// 				$url	= parse_url( $url );
// 				$url	= explode( '.' , $url[ 'host' ] );
// 				
// 				// Last two parts will always be the domain name.
// 				$url	= $url[ count( $url ) - 2 ] . '.' . $url[ count( $url ) - 1 ];
// 				
// 				if( !empty( $url ) && array_key_exists( $url , $this->patterns ) )
// 				{
// 					$provider	= JString::strtolower( $this->patterns[ $url ] );
// 					$path		= EBLOG_CLASSES . DS . 'videos' . DS . $provider . '.php';
// 					require_once( $path );
// 					
// 					$class	= 'EasyBlogVideo' . ucfirst( $this->patterns[ $url ] );
// 					
// 					if( class_exists( $class ) )
// 					{
// 						$object		= new $class();
// 						$html		= $object->getEmbedHTML( $data->video , $data->width , $data->height );
// 
// 						$content	= str_ireplace( $video , $html , $content );
// 					}
// 				}
// 			}
// 		}

		return $content;
	}
}