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

Foundry::import( 'site:/views/views' );

class EasySocialViewStream extends EasySocialSiteView
{

	/**
	 * Confirmation for deleting stream item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmDelete()
	{
		$ajax 	= Foundry::ajax();

		$theme 		= Foundry::themes();
		$contents	= $theme->output( 'site/stream/dialog.delete' );

		return $ajax->resolve( $contents );
	}

	public function delete()
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$contents = JText::_( 'COM_EASYSOCIAL_STREAM_FEED_DELETED_SUCCESSFULLY' );
		return $ajax->resolve( $contents );
	}


	public function getCurrentDate( $currentDate )
	{
		// Load ajax library.
		$ajax 	= Foundry::ajax();
		return $ajax->resolve( $currentDate );
	}

	public function getUpdates( $stream )
	{

		// Load ajax library.
		$ajax 	= Foundry::ajax();

		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		$content 	= $stream->html( true );
		$nextdate 	= Foundry::date()->toMySQL();

		return $ajax->resolve( $content, $nextdate );
	}

	public function checkUpdates( $data, $source, $type, $uid, $currentdate )
	{
		// Load ajax library.
		$ajax 	= Foundry::ajax();

		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		$content 	= '';
		if( count( $data ) > 0 )
		{
			//foreach( $data as $item )

			if( $type == 'list' )
			{
				$type = $type . '-' . $uid;
			}

			for( $i = 0; $i < count( $data ); $i++ )
			{
				$item =& $data[ $i ];

				if( $item['type'] == $type )
				{
					//debug
					//$item['cnt'] = 5;
					if( $item['cnt'] && $item['cnt'] > 0 )
					{
						$theme = Foundry::themes();

						$theme->set( 'count'  , $item['cnt'] );
						$theme->set( 'currentdate', $currentdate );
						$theme->set( 'type'	, $type );
						$theme->set( 'uid'	, $uid );

						$content = $theme->output( 'site/stream/update.notification' );
					}
				}
			}
		}

		// $content 	= $stream->html( true );
		// $startdate 	= Foundry::date()->toMySQL(); // always use the current date.
		// $total   	= $stream->getCount();

		// $content 	= '';
		// $total   	= 0;


		$startdate 	= Foundry::date()->toMySQL();


		// return $ajax->resolve( $content, $startdate );
		return $ajax->resolve( $data, $content, $startdate);

	}


	/**
	 * Responsible to return the ajax chains
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialAjax
	 */
	public function loadmoreGuest( $stream )
	{
		// Load ajax library.
		$ajax 	= Foundry::ajax();

		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		$content 	= $stream->html( true );
		$startlimit = $stream->getNextStartLimit();


		if( empty( $startlimit ) )
		{
			$startlimit = '';
		}

		return $ajax->resolve( $content, $startlimit );
	}


	/**
	 * Responsible to return the ajax chains
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialAjax
	 */
	public function loadmore( $stream )
	{
		// Load ajax library.
		$ajax 	= Foundry::ajax();

		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		$content 	= $stream->html( true );
		$startdate 	= $stream->getNextStartDate();
		$enddate 	= $stream->getNextEndDate();


		if( empty( $startdate ) )
		{
			$startdate = '';
		}

		if( empty( $enddate ) )
		{
			$enddate = '';
		}

		return $ajax->resolve( $content, $startdate, $enddate );
	}

	/**
	 * Responsible to return the ajax chains
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialAjax
	 */
	public function hide()
	{
		// Load ajax library.
		$ajax 	= Foundry::ajax();

		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		$theme 		= Foundry::themes();
		$contents	= $theme->output( 'site/stream/hidden' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Post processing after app is hidden
	 *
	 * @since	1.0
	 * @access	public
	 * @return	SocialAjax
	 */
	public function hideapp()
	{
		// Load ajax library.
		$ajax 	= Foundry::ajax();

		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		$context 	= JRequest::getVar( 'context' );

		$theme 		= Foundry::themes();
		$theme->set( 'context' , $context );
		$contents	= $theme->output( 'site/stream/hidden.app' );

		return $ajax->resolve( $contents );
	}

	public function unhide()
	{
		// Load ajax library.
		$ajax 	= Foundry::ajax();

		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		return $ajax->resolve();
	}

	public function unhideapp()
	{
		// Load ajax library.
		$ajax 	= Foundry::ajax();

		$error 	= $this->getError();

		if( $error )
		{
			return $ajax->reject( $error );
		}

		return $ajax->resolve();
	}

}
