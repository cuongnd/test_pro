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

// Include necessary libraries here.
require_once( dirname( __FILE__ ) . '/dependencies.php' );

// Include stream data template here.
require_once( dirname( __FILE__ ) . '/template.php' );

// Include stream data template here.
require_once( SOCIAL_LIB . '/privacy/option.php' );

class SocialStream
{
	/**
	 * Contains a list of stream data.
	 * @var	Array
	 */
	private $data 		= null;

	/*
	 * this nextStartDate used as pagination.
	 */
	private $nextdate = null;

	/*
	 * this nextEndDate used as pagination.
	 */
	private $enddate = null;


	/*
	 * this nextlimit used as actvities log pagination.
	 */
	private $nextlimit = null;

	/**
	 * Determines if the current request is for a single item output.
	 * @var boolean
	 */
	private $singleItem 	= false;

	/**
	 * Determines the current filter type.
	 * @var string
	 */
	public $filter 			= null;

	/**
	 * Determines if the current retrieval is for guest viewing or not.
	 * @var string
	 */
	public $guest 			= null;


	/**
	 * options
	 * @var string
	 */
	public $options 			= null;


	/**
	 * public stream pagination
	 *
	 */
	public $limit  			= 0;
	public $startlimit  	= 0;


	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function __construct()
	{
		$this->filter 	= 'all';
		$this->guest 	= false;
		$this->options 	= array();
	}

	/**
	 * Delete stream items given the app type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param
	 */
	public function delete( $contextId , $contextType , $actorId = '' )
	{
		// Load dispatcher.
		$dispatcher 	= Foundry::dispatcher();

		$args 			= array( $contextId , $contextType );

		// Trigger onBeforeStreamDelete
		$dispatcher->trigger( SOCIAL_APPS_GROUP_USER , 'onBeforeStreamDelete', $args );

		$model 	= Foundry::model( 'Stream' );

		$model->delete( $contextId , $contextType , $actorId );

		// Trigger onAfterStreamDelete
		$dispatcher->trigger( SOCIAL_APPS_GROUP_USER , 'onAfterStreamDelete', $args );
	}

	/**
	 * Object initialisation for the class to fetch the appropriate user
	 * object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param   null
	 * @return  SocialStream	The stream object.
	 */
	public static function factory()
	{
		return new self();
	}

	/**
	 * Creates the stream template
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTemplate()
	{
		$template 	= new SocialStreamTemplate();

		return $template;
	}

	/**
	 * Creates a new stream item.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * // Load up the library.
	 * $stream 		= Foundry::get( 'Stream' );
	 *
	 * // We need to generate the stream template.
	 * $template 	= $stream->getTemplate();
	 *
	 * // Set actors.
	 * $template->setActor( $id , $type );
	 *
	 * // Set verb
	 * $template->setVerb( 'create' );
	 *
	 * // Create the stream item.
	 * $stream->add( $template );
	 *
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	mixed						Accepts array, object or SocialStreamTemplate which represents the stream's data.
	 * @return	SocialTableStreamItem		Returns the new stream id if success, false otherwise.
	 */
	public function add( SocialStreamTemplate $data )
	{
		// Let's try to aggregate the stream item.
		// Get the stream model
		$model 		= Foundry::model( 'Stream' );

		// Get the config obj.
		$config 	= Foundry::config();

		// The duration between activities.
		$duration 	= $config->get( 'stream.aggregation.duration' );

		// Determine which context types should be aggregated.
		$aggregateContext 	= $config->get( 'stream.aggregation.contexts' );

		if( count( $data->childs ) > 0 )
		{
			// reset this flag to false whenever there are items in child property.
			$data->isAggregate = false;
		}

		// Get the unique id if necessary.
		$uid 		= $model->updateStream( $data );


		if( count( $data->childs ) > 0  )
		{
			foreach( $data->childs as $contextId )
			{
				// Load the stream item table
				$item 	= Foundry::table( 'StreamItem' );
				$item->bind( $data );

				//override contextId
				$item->context_id 	= $contextId;

				// Set the uid for the item.
				$item->uid 	= $uid;

				// Let's try to store the stream item now.
				$state 	= $item->store();

				if( !$state )
				{
					Foundry::logError( __FILE__ , __LINE__ , 'STREAM: There was some errors saving the stream item. Message: ' . $item->getError() );

					return false;
				}
			}
		}
		else
		{

			// Load the stream item table
			$item 	= Foundry::table( 'StreamItem' );
			$item->bind( $data );

			// Set the uid for the item.
			$item->uid 	= $uid;

			// set context item's params
			$item->params = $data->item_params;

			// Let's try to store the stream item now.
			$state 	= $item->store();

			if( !$state )
			{
				Foundry::logError( __FILE__ , __LINE__ , 'STREAM: There was some errors saving the stream item. Message: ' . $item->getError() );

				return false;
			}

		}

		//tag the with
		$model->setWith( $uid, $data->with );

		return $item;
	}


	/**
	 * stream's with tagging.
	 * return array of foundry user object.
	 */
	private function getStreamTagWith( $streamId )
	{
		$model = Foundry::model( 'Stream' );
		return $model->getTagging( $streamId, 'with' );
	}

	/**
	 * stream's mentions tagging.
	 * return array of objects with:
	 *        $obj->user   : foundry::user(),
	 *        $obj->offset : int,
	 *        $obj->length : int
	 */
	private function getStreamTagMention( $streamId )
	{
		$model = Foundry::model( 'Stream' );
		return $model->getTagging( $streamId, 'mention' );
	}

	/**
	 * Formats a stream item with the necessary data.
	 *
	 * Example:
	 * <code>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array
	 *
	 */
	public function format( $items , $context = 'all', $viewer = null, $loadCoreAction = true, $defaultEvent = 'onPrepareStream' )
	{
		$my 			= is_null( $viewer ) ? Foundry::user() : Foundry::user( $viewer );

		if( empty( $defaultEvent ) )
		{
			$defaultEvent = 'onPrepareStream';
		}

		$isStream 		= ( $defaultEvent == 'onPrepareStream' ) ? true : false;

		//var_dump( $isStream );

		if( !$items )
		{
			return $items;
		}

		$data 		= array();

		$activeUser = Foundry::user();

		// Get stream model
		$model 			= Foundry::model( 'Stream' );

		//current user being view.
		$targetUser  = JRequest::getVar( 'id', '' );

		if( empty( $targetUser ) )
		{
			$targetUser = $my->id;
		}

		if( $targetUser && strpos( $targetUser, ':') )
		{
			$tmp 		= explode( ':', $targetUser);
			$targetUser = $tmp[0];
		}

		// Get template configuration
		$templateConfig 	= Foundry::themes()->getConfig();

		// Format items with appropriate objects.
		foreach( $items as &$row )
		{
			//$uid			= ( $defaultEvent == 'onPrepareActivity') ? $row->uid : $row->id;
			$uid			= $row->id;
			$lastupdate		= $row->modified;

			// Obtain related activities for aggregation.
			$relatedActivities		= null;

			if( $isStream )
			{
				$relatedActivities = $model->getRelatedActivities( $uid , $row->context_type, $viewer );
			}
			else
			{
				$relatedActivities = $model->getActivityItem( $uid );
			}

			// If there is no data at all, then there's something really wrong.
			if( !$relatedActivities )
			{
				Foundry::logError( __FILE__ , __LINE__ , 'STREAM: There is no activity for id: ' . $row->id . ' , type: ' . $row->context_type );
				continue;
			}

			$aggregatedData			= $this->buildAggregatedData( $relatedActivities );

			// Get the stream item.
			$streamItem 			= new SocialStreamItem();

			// Set the actors (Aggregated actors )
			$streamItem->actors 	= $aggregatedData->actors;

			// Set the content
			$streamItem->content	= $row->content;

			// Set the targets (Aggregated targets )
			$streamItem->targets 	= $aggregatedData->targets;

			// Set the context ids. ( Aggregated ids )
			$streamItem->contextIds	= $aggregatedData->contextIds;

			// Set the context params. ( Aggregated params )
			$streamItem->contextParams	= $aggregatedData->params;

			// main stream params
			$streamItem->params 		= $row->params;

 			// Set the stream uid / activity id.
			$streamItem->uid		= $uid;

			// Set stream lapsed time
			$streamItem->lapsed		= Foundry::date( $row->modified )->toLapsed();

			$streamItem->created 	= Foundry::date( $row->created );

			// Set the actor with the user object.
			$streamItem->actor 		= Foundry::user( $row->actor_id );

			// Set the context id.
			$streamItem->contextId	= $aggregatedData->contextIds[ 0 ];

			// Set the verb for this item.
			$streamItem->verb 		= $aggregatedData->verbs[ 0 ];

			// Set the context type.
			$streamItem->context 	= $row->context_type;

			// Set the title of the stream item.
			$streamItem->title 		= $row->title;

			// stream display type
			$streamItem->display 	= $row->stream_type;

			// Define an empty color
			$streamItem->color 		= '';

			// Define an empty favicon
			$streamItem->icon		= '';

			// Always enable labels
			$streamItem->label	 	= true;

			// @TODO: Since our stream has a unique favi on for each item. We need to get it here.
			// Each application is responsible to override this favicon, or stream wil use the context type.
			$streamItem->favicon	= $row->context_type;

			$streamItem->type 		= $row->context_type;

			$streamDateDisplay			= $templateConfig->get( 'stream_datestyle' );

			$streamItem->friendlyDate	= $streamItem->lapsed;

			if( $streamDateDisplay == 'datetime' )
			{
				$streamItem->friendlyDate	= $streamItem->created->toFormat( $templateConfig->get( 'stream_dateformat_format' , 'Y-m-d H:i' ) );
			}

			// getting the the with and mention tagging for the stream, only if the item is a stream.
			$streamItem->with 		= array();
			$streamItem->mention 	= array();

			if( $isStream )
			{
				$streamItem->with 		= $this->getStreamTagWith( $uid );
				$streamItem->mention 	= $this->getStreamTagMention( $uid );
			}

			// Format the users that are tagged in this stream.
			if( !empty( $row->location_id ) )
			{
				$location 	= Foundry::table( 'Location' );
				$location->load( $row->location_id );

				$streamItem->location		= $location;
			}


			// target user. this target user is different from the targets. this is the user who are being viewed currently.
			$streamItem->targetUser = $targetUser;

			// privacy
			$streamItem->privacy    = null;

			// Check if the content is not empty. We need to perform some formatings
			if( isset( $streamItem->content ) && !empty( $streamItem->content ) )
			{
				// Apply bbcode
				$content		= Foundry::string()->parseBBCode( $streamItem->content );

				// Apply e-mail replacements
				$content 		= Foundry::string()->replaceEmails( $content );

				// Apply hyperlinks
				$content 		= Foundry::string()->replaceHyperlinks( $content );

				// Some app might want the raw contents
				$streamItem->content_raw	= $streamItem->content;
				$streamItem->content		= $content;
			}

			// streams actions.
			$streamItem->comments 	= ( $defaultEvent == 'onPrepareStream' ) ? true : false;
			$streamItem->likes 		= ( $defaultEvent == 'onPrepareStream' ) ? true : false;
			$streamItem->repost 	= ( $defaultEvent == 'onPrepareStream' ) ? true : false;


			// @trigger onPrepareStream / onPrepareActivity
			$result					= $this->{$defaultEvent}( $streamItem );

			// Allow app to stop loading / generating the stream and
			// if there is still no title, we need to skip this stream altogether.
			if( $result === false || !$streamItem->title )
			{
				continue;
			}

			//this mean the plugin did not set any privacy. lets use the stream / activity.
			if( is_null( $streamItem->privacy ) )
			{
				$privacyObj = Foundry::privacy( $activeUser->id );


				$privacy 	= ( isset( $row->privacy ) ) ? $row->privacy : null;
				$pUid 		= $uid;

				if( count( $streamItem->contextIds ) == 1 && is_null( $privacy ) )
				{
					$sModel = Foundry::model( 'Stream' );
					$tmpId  = ( $defaultEvent == 'onPrepareActivityLog') ? $row->uid : $row->id;
					$aItem 	= $sModel->getActivityItem( $tmpId, 'uid' );

					if( $aItem )
					{
						$pUid 	= $aItem[0]->id;
					}
				}

				if(! $privacyObj->validate( 'core.view', $pUid, SOCIAL_TYPE_ACTIVITY, $streamItem->actor->id ) )
				{
					continue;
				}

				$streamItem->privacy = $privacyObj->form( $pUid, SOCIAL_TYPE_ACTIVITY, $streamItem->actor->id );
			}


			// comments
			if( isset( $streamItem->comments ) && $streamItem->comments )
			{
				if(! $streamItem->comments instanceof SocialCommentBlock )
				{
					$streamItem->comments	= Foundry::comments( $streamItem->uid , $streamItem->context , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::stream( array( 'layout' => 'item', 'id' => $streamItem->uid ) ) ) );
				}
			}

			//likes
			if( isset( $streamItem->likes ) && $streamItem->likes )
			{
				if(! $streamItem->likes instanceof SocialLikes )
				{
					$likes 			= Foundry::likes();
					$likes->get( $streamItem->uid , $streamItem->context );

					$streamItem->likes = $likes;
				}
			}

			//repost
			if( isset( $streamItem->repost ) && $streamItem->repost )
			{
				if(! $streamItem->repost instanceof SocialLikes )
				{
					$repost 		= Foundry::get( 'Repost', $streamItem->uid , SOCIAL_TYPE_STREAM );
					$streamItem->repost = $repost;
				}
			}


			// Now we have all the appropriate data, populate the actions
			$streamItem->actions 	= $this->getActions( $streamItem );

			// Re-assign stream item to the result list.
			$data[]	= $streamItem;
		}

		// here we know, the result from queries contain some records but it might return empty data due to privacy.
		// if that is the case, then we return TRUE so that the library will go retrieve the next set of data.
		if( count( $data ) <= 0 )
		{
			return true;
		}

		return $data;
	}

	public function getActivityNextLimit()
	{
		return $this->nextlimit;
	}

	/**
	 * Some desc
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getLogs( $max = 10 )
	{
		$model 	= Foundry::model( 'Activities' );
		$result	= $model->getData( $max );

		$data 	= $this->format( $result );

		return $data;
	}

	/**
	 * Retrieves a list of stream item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	 //public function getActivityLogs( $uId, $uType = SOCIAL_TYPE_USER, $context = SOCIAL_STREAM_CONTEXT_TYPE_ALL , $filter = 'all')
	public function getActivityLogs( $options = array() )
	{

		$uId 		= isset( $options['uId'] ) 		? $options['uId'] : '';
		$uType 		= isset( $options['uType'] ) 	? $options['uType'] : SOCIAL_TYPE_USER;
		$context 	= isset( $options['context'] ) 	? $options['context'] : SOCIAL_STREAM_CONTEXT_TYPE_ALL;
		$filter 	= isset( $options['filter'] ) 	? $options['filter'] : 'all';
		$max 		= isset( $options['max'] ) 		? $options['max'] : '';

	 	if( empty( $uId ) )
	 	{
	 		$uId = Foundry::user()->id;
	 	}

		if( empty( $context ) )
		{
			$context = SOCIAL_STREAM_CONTEXT_TYPE_ALL;
		}


		$activity		 = Foundry::model( 'Activities' );
		$result 		 = 	$activity->getItems(
									array( 'uId' => $uId,
										   'uType' => $uType,
										   'context' => $context,
										   'filter' => $filter,
										   'max' 	=> $max
										)
									);



		$this->nextlimit = $activity->getNextLimit();

		// If there's nothing, just return a boolean value.
		if( !$result )
		{
			return false;
		}

		// register the resultset.
		$streamModel		 = Foundry::model( 'Stream' );
		$streamModel->setBatchActivityItems( $result );


		$data	= $this->format( $result , $context, null, false, 'onPrepareActivityLog' );

		if( is_bool( $data ) )
			return array();

		for( $i = 0; $i < count($data); $i++ )
		{
			$item =& $data[$i];

			$tbl = Foundry::table( 'StreamHide' );
			$tbl->load( $item->uid, $uId, SOCIAL_STREAM_HIDE_TYPE_ACTIVITY );


			$isHidden = false;
			if( $tbl->id )
				$isHidden = true;

			$item->isHidden = $isHidden;
		}


		$this->data = $data;
		return $this->data;
	 }

	/**
	 * Retrieves a single stream item.
	 *
	 * @since	1.0
	 */
	public function getItem( $streamId )
	{
		$model 				= Foundry::model( 'Stream' );
		$result 			= $model->getStreamData( array( 'streamId' => $streamId , 'context' => 'all', 'ignoreUser' => true ) );

		$this->data 		= $this->format( $result );
		$this->singleItem	= true;

		return $this->data;
	}


	/**
	 * Retrieves a single stream item actor.
	 * return: SocialUser object, all false if not found.
	 * @since	1.0
	 */
	public function getStreamActor( $streamId )
	{
		$model 	= Foundry::model( 'Stream' );
		$actor 	= $model->getStreamActor( $streamId );
		return $actor;
	}

	public function getPublicStream( $limit = 10, $startlimit = 0 )
	{
		$this->guest = true;

		$viewerId 	= Foundry::user()->id;
		$context	= SOCIAL_STREAM_CONTEXT_TYPE_ALL;

		$attempts = 2;
		$keepSearching = true;

		$model		= Foundry::model( 'Stream' );

		$this->startlimit = $startlimit;

		do
		{
			$options	= array(
							'userid' 		=> '0',
							'context' 		=> $context,
							'direction' 	=> 'older',
							'limit' 		=> $limit,
							'startlimit' 	=> $startlimit,
							'guest' 		=> true,
							'viewer' 		=> $viewerId
						);

			$result		= $model->getStreamData( $options );

			// If there's nothing, just return a boolean value.
			if( !$result )
			{
				$this->startlimit = 0; // so that the next cycle will stop
				return $this;
			}

			$requireSearch =  $this->format( $result , $context, $viewerId );

			if( $requireSearch !== true )
			{
				$this->data = $requireSearch;
				$keepSearching = false;
			}

			$attempts--;

			$startlimit 	  = $startlimit + $limit;
			$this->startlimit = $startlimit ;

		} while( $keepSearching === true && $attempts > 0 );

	}

	/**
	 * Retrieves a list of stream item.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	//public function get( $userId = '', $listId = null, $viewerId = null,  $context = SOCIAL_STREAM_CONTEXT_TYPE_ALL , $type = SOCIAL_TYPE_USER , $limitStart = '', $direction = 'older'  )
	public function get( $options = array() )
	{
		$users 		= array();

		// lets process default values
		$userId 	= isset( $options['userId'] ) 	? $options['userId'] : null;
		$listId 	= isset( $options['listId'] ) 	? $options['listId'] : null;
		$context 	= isset( $options['context'] ) 	? $options['context'] : SOCIAL_STREAM_CONTEXT_TYPE_ALL;
		$type 		= isset( $options['type'] ) 	? $options['type'] : SOCIAL_TYPE_USER;
		$limitStart = isset( $options['limitStart'] ) ? $options['limitStart'] : '';
		$limitEnd 	= isset( $options['limitEnd'] ) ? $options['limitEnd'] : '';
		$direction 	= isset( $options['direction'] )  ? $options['direction'] : 'older';
		$viewerId 	= isset( $options['viewerId'] )   ? $options['viewerId'] : null;
		$guest 		= isset( $options['guest'] )   ? $options['guest'] : false;


		// If viewer is null, we assume the caller wants to fetch from the current user's perspective.
		if( is_null( $viewerId ) )
		{
			$viewerId 	= Foundry::user()->id;
		}

		// Ensure that the user id's are in an array form.
		$user 		= Foundry::user();
		$userId     = ( empty( $userId ) ) ? $user->id : $userId;
		$userId		= Foundry::makeArray( $userId );

		if( empty( $context ) )
		{
			$context = SOCIAL_STREAM_CONTEXT_TYPE_ALL;
		}

		$isFollow = false;

		if( $type == 'follow' )
		{
			$this->filter 	= 'follow';

			// reset the type to user and update the isFollow flag.
			$type 		= SOCIAL_TYPE_USER;
			$isFollow 	= true;
		}

		if( $listId )
		{
			$this->filter 	= 'list';
		}

		if( $guest )
		{
			$this->filter 	= 'everyone';
		}

		// Get stream model to fetch those records.
		$model		= Foundry::model( 'Stream' );
		$data		= array();

		//$this->data		= $this->format( $result , $context, $viewerId );
		$keepSearching = true;
		$tryLimit      = 2;

		do
		{
			$options	= array(
							'userid' => $userId,
							'list' => $listId,
							'context' => $context,
							'type' => $type,
							'limitstart' => $limitStart,
							'limitend' => $limitEnd,
							'viewer' => $viewerId,
							'isfollow' => $isFollow,
							'direction' => $direction,
							'guest' 	=> $guest
						);

			$this->options = $options;

			$result		= $model->getStreamData( $options );

			// If there's nothing, just return a boolean value.
			if( !$result )
			{
				$this->nextdate = '';
				return $this;
			}

			$requireSearch =  $this->format( $result , $context, $viewerId );

			if( $requireSearch !== true )
			{
				$this->data = $requireSearch;
				$keepSearching = false;
			}
			else
			{
				// get the next limit date for query.
				if( $direction == 'later' )
				{
					$limitStart = $model->getCurrentStartDate();
				}
				else
				{
					$limitStart = $model->getNextStartDate();
				}
			}

			$tryLimit--;

		} while( $keepSearching === true && $tryLimit > 0);

		if( $direction == 'later' )
		{
			$this->nextdate = $model->getCurrentStartDate();
		}
		else
		{
			$this->nextdate = $model->getNextStartDate();
		}


		$this->enddate = $model->getNextEndDate();

		return $this;
	}

	public function getCount()
	{
		if( $this->data )
		{
			return count( $this->data );
		}
		else
		{
			return '0';
		}
	}


	/**
	 * Returns next start date used in stream pagination
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string - in mysql date format.
	 * @return
	 */
	public function getNextStartDate()
	{
		return $this->nextdate;
	}

	/**
	 * Returns next end date used in stream pagination
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string - in mysql date format.
	 * @return
	 */
	public function getNextEndDate()
	{
		return $this->enddate;
	}

	/**
	 * Returns next limit used in public stream pagination
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string - in mysql date format.
	 * @return
	 */
	public function getNextStartLimit()
	{
		return $this->startlimit;
	}


	/**
	 * Returns a html formatted data for the stream
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function html( $loadmore = false, $customEmptyMsg = '' )
	{
		$theme 		= Foundry::get( 'Themes' );
		$output 	= '';

		$view 		= JRequest::getVar( 'view' );

		$isGuest    = $this->guest;

		if( ! $isGuest )
		{
			//let check one more time if current viewer is guest or not.
			$my = Foundry::user();
			$isGuest = ( $my->id == 0 ) ? true : $isGuest;
		}

		$theme->set( 'view' , $view );

		if (!empty($this->story))
		{
			$theme->set('story', $this->story);
		}

		if( $loadmore )
		{
			if( $this->data )
			{
				foreach( $this->data as $stream )
				{
					$output .= $theme->loadTemplate( 'site/stream/default.item' , array( 'stream' => $stream ) );
				}
			}
		}
		else
		{
			if( $this->singleItem )
			{
				if( empty( $this->data ) || count( $this->data ) == 0 || $this->data === true )
				{
					$output = JText::_( 'COM_EASYSOCIAL_STREAM_CONTENT_NOT_AVAILABLE' );
					return $output;
				}


				$theme->set( 'stream' , $this->data[ 0 ] );

				$output 	= $theme->output( 'site/stream/default.item' );
			}
			else
			{
				// Define empty messages here
				$empty 		= ( $customEmptyMsg ) ? $customEmptyMsg : JText::_( 'COM_EASYSOCIAL_STREAM_NO_STREAM_ITEM' );

				if( $this->filter == 'follow' )
				{
					$empty 	= ( $customEmptyMsg ) ? $customEmptyMsg : JText::_( 'COM_EASYSOCIAL_STREAM_NO_STREAM_ITEM_FROM_FOLLOWING' );
				}

				if( $this->filter == 'list' )
				{
					$empty	= ( $customEmptyMsg ) ? $customEmptyMsg : JText::_( 'COM_EASYSOCIAL_STREAM_NO_STREAM_ITEM_FROM_LIST' );
				}

				$theme->set( 'empty'	, $empty );
				$theme->set( 'streams' , $this->data );
				$theme->set( 'nextdate' , $this->nextdate );
				$theme->set( 'enddate' , $this->enddate );

				$theme->set( 'guest' , $isGuest );
				$theme->set( 'nextlimit', $this->startlimit );


				$output 	= $theme->output( 'site/stream/default' );
			}
		}

		return $output;
	}

	public function action()
	{
		$theme 		= Foundry::get( 'Themes' );
		$theme->set( 'stream' , $this->data[ 0 ] );

		$output = $theme->output( 'site/stream/actions' );

		return $output;
	}

	/**
	 * Return the raw data for the stream.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function json()
	{
		//@TODO: Perhaps there's something that we need to modify here for json type?

		$json 	= Foundry::json();
		$output = $json->encode( $this->data );

		return $output;
	}

	/**
	 * Return the raw data for the stream.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function toArray()
	{
		return $this->data;
	}

	/**
	 * Prepares core actions
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function prepareCoreActions( SocialStreamItem &$item )
	{
		jimport( 'joomla.filesystem.file' );
		jimport( 'joomla.filesystem.folder' );

		// Default value.
		$result		= array();

		// Get core actions.
		$path 		= dirname( __FILE__ ) . '/actions';
		$config 	= Foundry::config();
		$actions 	= $config->get( 'stream.actions' );

		foreach( $actions as $action )
		{
			// Include the file library.
			require_once( $path . '/' . $action . '.php' );

			// Replace all spaces with underscores.
			$name 		= str_ireplace( ' ' , '_' , $action );

			// Build index key here.
			$key 		= strtolower( $name );

			// Get class name.
			$className 	= 'SocialStreamAction' . ucfirst( $name );

			// Instantiate the action object.
			$actionObj	= new $className( $item );

			// Set the actions.
			$result[ $action ]	= $actionObj;
		}

		return $result;
	}

	/**
	 * Prepares stream actions.
	 *
	 * @since	1.0
	 * @access	public
	 */
	private function onPrepareStreamActions( SocialStreamItem &$item )
	{
		// Get apps library.
		$apps 	= Foundry::getInstance( 'Apps' );

		// Try to load user apps
		$state 	= $apps->load( SOCIAL_APPS_GROUP_USER );

		// By default return true.
		$result 	= true;

		if( !$state )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'STREAMS: No applications loaded.' );
		}
		else
		{
			// Only go through dispatcher when there is some apps loaded, otherwise it's pointless.
			$dispatcher		= Foundry::dispatcher();

			// Pass arguments by reference.
			$args 			= array( &$item );

			// @trigger: onPrepareStream for the specific context
			$dispatcher->trigger( SOCIAL_APPS_GROUP_USER , 'onPrepareStreamActions' , $args , $item->context );

			// @TODO: Check each actions and ensure that they are instance of ISocialStreamAction
		}

		return true;
	}

	/**
	 * Prepares a stream item.
	 *
	 * @since	1.0
	 * @access	public
	 */
	private function onPrepareStream( SocialStreamItem &$item, $includePrivacy = true )
	{
		// Get apps library.
		$result = $this->onPrepareEvent( 'onPrepareStream', $item, $includePrivacy );
		return $result;
	}

	/**
	 * Prepares a stream item for activity logs
	 *
	 * @since	1.0
	 * @access	public
	 */
	private function onPrepareActivityLog( SocialStreamItem &$item, $includePrivacy = true )
	{
		// Get apps library.
		$result = $this->onPrepareEvent( __FUNCTION__ , $item, $includePrivacy );
		return $result;
	}


	private function onPrepareEvent( $eventName, SocialStreamItem &$item, $includePrivacy = true )
	{
		// Get apps library.
		$apps 	= Foundry::getInstance( 'Apps' );

		// Try to load user apps
		$state 	= $apps->load( SOCIAL_APPS_GROUP_USER );

		// By default return true.
		$result 	= true;


		if( !$state )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'STREAMS: No applications loaded.' );

			return $result;
		}

		// Only go through dispatcher when there is some apps loaded, otherwise it's pointless.
		$dispatcher		= Foundry::dispatcher();

		// Pass arguments by reference.
		$args 			= array( &$item, $includePrivacy );

		// @trigger: onPrepareStream for the specific context
		$result 		= $dispatcher->trigger( SOCIAL_APPS_GROUP_USER , $eventName , $args , $item->context );


		return $result;
	}

	/**
	 * Build the aggregated data
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function buildAggregatedData( $activities )
	{
		// If there's no activity at all, it should fail here.
		// There should be at least 1 activity.
		if( !$activities )
		{
			return false;
		}

		$data 					= new stdClass();
		$data->contextIds		= array();
		$data->actors 			= array();
		$data->targets 			= array();
		$data->verbs 			= array();
		$data->params 			= array();

		// Temporary data
		$actorIds 		= array();
		$targetIds		= array();

		foreach( $activities as $activity )
		{
			// Assign actor into temporary data only when actor id is valid.
			if( $activity->actor_id )
			{
				$actorIds[]			= $activity->actor_id;
			}

			// Assign target into temporary data only when target id is valid.
			if( $activity->target_id )
			{
				if( !( $activity->context_type == 'photos' && $activity->verb == 'add' )
					&& !( $activity->context_type == 'shares' && $activity->verb == 'add.stream' ) )
				{
					$targetIds[]		= $activity->target_id;
				}
			}

			// Assign context ids.
			$data->contextIds[]	= $activity->context_id;

			// Assign the verbs.
			$data->verbs[]		= $activity->verb;

			// Assign the params
			$data->params[ $activity->context_id ]	= isset( $activity->params ) ? $activity->params : '';
		}

		// Pre load users.
		$userIds	= array_merge( $data->actors , $data->targets );
		Foundry::user( $userIds );


		// Build the actor's data
		if( $actorIds )
		{
			$actorIds = array_unique( $actorIds );
			foreach( $actorIds as $actorId )
			{
				$user 			= Foundry::user( $actorId );

				$data->actors[]	= $user;
			}
		}

		// Build the target's data.
		if( $targetIds )
		{
			$targetIds = array_unique( $targetIds );
			foreach( $targetIds as $targetId )
			{
				$user 				= Foundry::user( $targetId );
				$data->targets[]	= $user;
			}
		}

		return $data;
	}

	/**
	 * Displays the actions block that is used on a stream
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getActions( $options = array() )
	// public function getActions( $date = null , $comments = null , $likes = null , $repost = null , $privacy = null , $uid = null )
	{
		$theme			= Foundry::themes();

		// If the options is a stream item object, we need to map it accordingly.
		if( $options instanceof SocialStreamItem )
		{
			// Set the friendly date
			$friendlyDate 	= $options->friendlyDate;

			// Set the comments
			$comments 		= isset( $options->comments ) ? $options->comments : '';

			// Set the likes
			$likes 			= isset( $options->likes ) ? $options->likes : '';

			// Set the repost
			$repost 		= isset( $options->repost ) ? $options->repost : '';

			// Set the privacy
			$privacy 		= isset( $options->privacy ) ? $options->privacy : '';

			// Set the stream's uid
			$uid 			= $options->uid;

			$icon 			= isset( $options->icon ) ? $options->icon : '';
		}
		else
		{
			// Set the default friendly date
			$friendlyDate	= false;

			$date 			= isset( $options[ 'date' ] ) ? $options[ 'date' ] : null;
			$comments 		= isset( $options[ 'comments' ] ) ? $options[ 'comments' ] : '';
			$likes 			= isset( $options[ 'likes' ] ) ? $options[ 'likes' ] : '';
			$repost 		= isset( $options[ 'repost' ] ) ? $options[ 'repost' ] : '';
			$uid 			= isset( $options[ 'uid' ] ) ? $options[ 'uid' ] : '';
			$privacy 		= isset( $options[ 'privacy' ] ) ? $options[ 'privacy' ] : '';
			$icon 			= isset( $options[ 'icon' ] ) ? $options[ 'icon' ] : '';

			if( !is_null( $date ) )
			{
				$friendlyDate 	= Foundry::date( $date )->toLapsed();
			}
		}

		$theme->set( 'icon'			, $icon );
		$theme->set( 'friendlyDate' , $friendlyDate );
		$theme->set( 'privacy'	, $privacy );
		$theme->set( 'uid'		, $uid );
		$theme->set( 'comments' , $comments );
		$theme->set( 'likes' , $likes );
		$theme->set( 'repost' , $repost );

		$output 	= $theme->output( 'site/stream/actions' );

		return $output;
	}

	/**
	 * Translate stream's date time.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	private function translateDate( $day, $hour, $min )
	{
		$dayString  = '';
		$timeformat = '%I:%M %p';


		$day 	= ( $day < 0 ) ? '0' : $day;
		$hour 	= ( $hour < 0 ) ? '0' : $hour;
		$min 	= ( $day < 0 ) ? '0' : $min;

		// today
		if( $day == 0)
		{
			if( $min > 60 )
			{
				$dayString  = $hour . JText::_( 'COM_EASYSOCIAL_STREAM_X_HOURS_AGO');
			} else if( $min <= 0)
			{
			    $dayString  = JText::_( 'COM_EASYSOCIAL_STREAM_LESS_THAN_ONE_MIN_AGO' );
			}
			else
			{
			    $dayString  = $min . JText::_( 'COM_EASYSOCIAL_STREAM_X_MINS_AGO');
			}
		}
		elseif ( $day == 1 )
		{
			$time 	= Foundry::date( '-' . $min . ' mins' );

			$dayString  = JText::_( 'COM_EASYSOCIAL_STREAM_YESTERDAY_AT' ) . $time->toFormat($timeformat);
		}
		elseif( $day > 1 && $day <= 7)
		{
			$dayString		= Foundry::get( 'Date', '-' . $min . ' mins')->toFormat( '%A ' . JText::_( 'COM_EASYSOCIAL_STREAM_DATE_AT' ) . ' ' . $timeformat);
		}
		else
		{
			$dayString		= Foundry::get( 'Date', '-' . $min . ' mins')->toFormat('%b %d ' . JText::_( 'COM_EASYSOCIAL_STREAM_DATE_AT' ) . ' ' . $timeformat);
		}


		return $dayString;
	}

}
