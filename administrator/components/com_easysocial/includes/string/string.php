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

jimport( 'joomla.filesystem.file' );

class SocialString
{
	private $adapter	= null;

	public function __construct()
	{
		return $this;
	}

	/**
	 * Always create a new copy.
	 *
	 * @since 	1.0
	 * @access	public
	 * @param	null
	 */
	public static function factory()
	{
		return new self();
	}

	public function __call( $method , $arguments )
	{
	    if( method_exists( $this->adapter , $method ) )
	    {
			return call_user_func_array( array( $this->adapter , $method ) , $arguments );
		}

		return false;
	}

	/**
	 * Computes a noun given the string and count
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function computeNoun( $string , $count )
	{
		$config 		= Foundry::config();
		$zeroAsPlural	= $config->get( 'string.pluralzero' , true );

		// Always use plural
		$text 		= $string . '_PLURAL';

		if( $count == 1 || $count == -1 || ( $count == 0 && !$zeroAsPlural ) )
		{
			$text 	= $string 	. '_SINGULAR';
		}

		return $text;
	}

	/**
	 * Determines the type of parameters parsed to this method and automatically
	 * returns a stream-ish like content.
	 *
	 * E.g: user1 and user2,
	 * 		user1 , user2 and user3
	 * 		user1 , user2 , user3 and 2 others
	 *
	 * @param	Array	$people
	 * @param	boolean	$linkUsers
	 * @param	int $showLimit
	 */
	public function namesToStream( $users , $linkUsers = true , $limit = 3 , $uppercase = true , $boldNames = false )
	{
		// Ensure that users is an array
		$users 	= Foundry::makeArray( $users );

		// Ensure that they are all SocialUser objects
		$users 	= Foundry::user( $users );

		$theme 	= Foundry::themes();

		$theme->set( 'users'		, $users );
		$theme->set( 'boldNames'	, $boldNames );
		$theme->set( 'linkUsers' 	, $linkUsers );
		$theme->set( 'total' 		, count( $users ) );
		$theme->set( 'limit'		, $limit );
		$theme->set( 'uppercase'	, $uppercase );

		$message 	=  $theme->output( 'site/utilities/users' );

		return $message;
	}

	/**
	 * Replaces email text with html codes
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function replaceEmails( $text )
	{
		$pattern 	= '/(\S+@\S+\.\S+)/';
    	$replace	= '<a href="mailto:$1">$1</a>';

	    return preg_replace( $pattern , $replace, $text);
	}

	/**
	 * Replaces hyperlink text with html anchors
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The string to look into
	 * @return	
	 */
	public static function replaceHyperlinks( $text )
	{
		$pattern		= '@(?i)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';

		preg_match_all( $pattern , $text , $matches );

		$targetBlank	= ' target="_blank"';

		if( isset( $matches[ 0 ] ) && is_array( $matches[ 0 ] ) )
		{
			// to avoid infinite loop, unique the matches
			$uniques = array_unique($matches[ 0 ]);

			foreach( $uniques as $match )
			{
				$matchProtocol 	= $match;

				if( stristr( $matchProtocol , 'http://' ) === false && stristr( $matchProtocol , 'https://' ) === false && stristr( $matchProtocol , 'ftp://' ) === false )
				{
					$matchProtocol	= 'http://' . $matchProtocol;
				}

				$text   = str_ireplace( $match , '<a href="' . $matchProtocol . '"' . $targetBlank . '>' . $match . '</a>' , $text );
			}
		}

		$text	= str_ireplace( '&quot;' , '"', $text );
		return $text;
	}

	/**
	 * Determines the type of parameters parsed to this method and automatically
	 * returns a stream-ish like string.
	 *
	 * E.g: name1 , name2 and name3
	 *
	 * @param	Array of object containing name and link property
	 * @return 	string
	 */
	public function beautifyNamestoStream( $data )
	{
		$datatring 	= '';
		$j              = 0;
		$cntData       = count( $data );
		foreach( $data as $item )
		{

			if( empty( $datatring ) )
			{
				$text			= '<a href="' . $item->link . '">' . $item->name . '</a>';
			    $datatring	= $text;
			}
			else
			{
			    if( ($j + 1) == $cntData)
			    {
					$text   		= '<a href="' . $item->link . '">' . $item->name . '</a>';
			        $datatring  	= $datatring . ' and ' . $text;
			    }
			    else
			    {
			        $datatring  = $datatring . ', ' . $text;
			    }
			}

			$j++;
		}

		return $datatring;
	}

	/**
	 * Convert special characters to HTML entities
	 *
	 * @param	string
	 * @return  string
	 */
	public function escape( $var )
	{
		return htmlspecialchars( $var, ENT_COMPAT, 'UTF-8' );
	}

	/**
	 * Convert bbcode data into valid html codes
	 *
	 * @param	string
	 * @return  string (in html)
	 */
	public function parseBBCode( $string )
	{
		// @TODO: Configurable option to determine if the bbcode should perform the following
		$options	= array( 'censor' => true , 'emoticons' => true );
		$bbcode 	= Foundry::get( 'BBCode' );

		$string 	= $bbcode->parse( $string , $options );

		return $string;
	}
}
