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

/**
 * Stream data template.
 * Example:
 *
 * <code>
 * </code>
 *
 * @since
 */
class SocialStreamTemplate extends JObject
{
	/**
	 * The actor's id. (Example 42 )
	 * @var int
	 * mantadory field
	 */
	public $actor_id		= null;

	/**
	 * The unique actor type (Example: user, group , photos)
	 * @var int
	 * optional - default to user
	 */
	public $actor_type		= '';

	/**
	 * the content type id of this item. ( Example, album, status, photos, group and etc)
	 * @var int
	 * mantadory field
	 */
	public $context_id	= null;

	/**
	 * the content type of this item. ( Example, album, status, photos, group and etc)
	 * @var string
	 * mantadory field
	 */
	public $context_type	= null;

	/**
	 * the stream type. ( full or mini )
	 * @var string
	 * opstional - default to full
	 */
	public $stream_type	= null;

	/**
	 * the action for the context ( example, add, update and etc )
	 * @var string
	 * mantadory field
	 */
	public $verb 			= null;

	/**
	 * the id of which the context object associate with. ( E.g album id, when the context is a photo type. Add photo in album xxx )
	 * @var int
	 * optional - default to 0
	 */
	public $target_id 		= 0;

	/**
	 * Stream title which is optional.
	 * @var string
	 * optional
	 */
	public $title 			= null;

	/**
	 * Stream content which is option. (Example: $1 something)
	 * @var string
	 * optional
	 */
	public $content			= null;

	/**
	 * @var int
	 * system uses
	 */
	public $uid 			= 0;

	/**
	 * creation date
	 * @var mysql date
     * system use
	 */
	public $created 		= null;

	/*
	 * to determine if the stream is a sitewide
	 * @var boolean
	 * system use
	 */

	public $sitewide		= null;


	/**
	 * If this stream is posted with a location, store the location id.
	 * @var int
	 */
	public $location_id = null;

	/**
	 * If this stream is posted with their friends store in json string.
	 * @var string
	 */
	public $with 		= null;


	/**
	 * to indicate this stream item should aggregate or not.
	 */
	public $isAggregate  = null;


	/**
	 * to indicate this stream should be a public stream or not.
	 */
	public $isPublic 	 = null;

	/**
	 * if this stream is posted with params
	 */
	public $params 	 = null;


	/**
	 * if context item is posted with params
	 */
	public $item_params 	 = null;

	/**
	 * this childs is to aggreate items of same type ONLY in within one stream.
	 * the requirement is to off isAggregate flag. else it will ignore this property.
	 */
	public $childs = null;

	/**
	 * Class Constructor.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function __construct()
	{
		// Set the creation date to the current time.
		$date 				= Foundry::date();
		$this->created 		= $date->toMySQL();
		$this->sitewide		= '0';
		$this->isAggregate 	= false;
		$this->isPublic 	= 0;
		$this->childs 		= array();
	}

	/**
	 *
	 * @since	1.0
	 * @access	public
	 * @param   title string ( optional )
	 * @return  null
	 */
	public function setTitle( $title )
	{
		$this->title 	= $title;
	}

	/**
	 *
	 * @since	1.0
	 * @access	public
	 * @param   content string ( optional )
	 * @return  null
	 */
	public function setContent( $content )
	{
		$this->content 	= $content;
	}

	/**
	 * Sets the actor object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The actor's id.
	 * @param	string	The actor's type.
	 */
	public function setActor( $id , $type )
	{
		// Set actors id
		$this->actor_id 	= $id;

		// Set actors type
		$this->actor_type	= $type;
	}

	/**
	 * Sets the context of this stream
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The context id.
	 * @param	string	The context type.
	 */
	public function setContext( $id , $type, $params = null )
	{
		// Set the context id.
		$this->context_id 		= $id;

		// Set the context type.
		$this->context_type 	= $type;

		if( $params )
		{
			if(! is_string( $params ) )
			{
				$this->item_params = Foundry::json()->encode( $params );
			}
			else
			{
				$this->item_params = $params;
			}

		}

	}

	/**
	 * Sets the verb of the stream item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The verb
	 */
	public function setVerb( $verb )
	{
		// Set the verb property.
		$this->verb = $verb;
	}

	/**
	 * Sets the target id.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The target id
	 */
	public function setTarget( $id )
	{
		$this->target_id 	= $id;
	}

	/**
	 * Sets the stream location
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function setLocation( $location = null )
	{
		if( !is_null( $location ) && is_object( $location ) )
		{
			$this->location_id 	= $location->id;
		}
	}


	/**
	 * Sets the users in the stream
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function setWith( $ids = '' )
	{
		// if( is_array( $ids ) )
		// {
		// 	$ids 	= Foundry::makeJSON( $ids );
		// }

		$this->with 	= $ids;
	}

	/**
	 * Sets the stream type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The target id
	 */
	public function setType( $type = 'full' )
	{
		$this->stream_type 	= $type;
	}

	public function setSiteWide( $isSideWide = true )
	{
		$this->sitewide = $isSideWide;
	}

	public function setAggregate( $aggregate = true )
	{
		// when this is true, it will aggregate based on current context and verb.
		$this->isAggregate = $aggregate;

	}

	public function setDate( $mySQLdate )
	{
		$this->created = $mySQLdate;
	}

	public function setPublicStream( $keys, $privacy = null )
	{
		if( $this->actor_type == SOCIAL_STREAM_ACTOR_TYPE_USER )
		{
			if(! is_null( $privacy ) && $privacy == SOCIAL_PRIVACY_PUBLIC )
			{
				$this->isPublic = 1;
			}
			else
			{
				// we need to test the user privacy for this rule.
				$rules 	= explode( '.', $keys );
				$key  	= array_shift( $rules );
				$rule 	= implode( '.', $rules );

				$privacyLib 	= Foundry::privacy( $this->actor_id );
				$targetValue  	= $privacyLib->getValue( $key, $rule );

				if( is_array( $targetValue ) )
				{
					$$privacy = $targetValue[0];
				}
				else
				{
					$privacy = $targetValue;
				}

				if( $privacy == SOCIAL_PRIVACY_PUBLIC )
				{
					$this->isPublic = 1;
				}
			}
		}
	}

	/**
	 * Sets the stream params
	 *
	 * @since	1.0
	 * @access	public
	 * @param	json string only!
	 * @return
	 */
	public function setParams( $params )
	{
		if( ! $params )
			return;

		if(! is_string( $params ) )
		{
			$this->params = Foundry::json()->encode( $params );
		}
		else
		{
			$this->params = $params;
		}
	}

	/*
	 * This functin allow user to aggreate items of same type ONLY in within one stream.
	 * when there are child items, the isAggreate will be off by default when processing streams aggregation.
	 * E.g. of when this function take action:
	 *		Imagine if you wanna agreate photos activity logs for one single stream but DO NOT wish to aggregate with other photos stream.
	 *      If that is the case, then you will need to use this function so that stream lib will only aggreate the photos items in this single stream.
	 */
	public function setChild( $contextId )
	{
		if( $contextId )
		{
			$this->childs[] = $contextId;
		}
	}

}
