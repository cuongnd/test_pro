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

Foundry::import( 'admin:/tables/table' );

class NotesTableNote extends SocialTable
{
	public $id 		= null;
	public $user_id	= null;
	public $title 	= null;
	public $alias	= null;
	public $content	= null;
	public $created	= null;
	public $params	= null;

	public function __construct(& $db )
	{
		parent::__construct( '#__social_notes' , 'id' , $db );
	}

	public function store( $updateNulls = false )
	{
		// @TODO: Automatically set the alias
		if( !$this->alias )
		{

		}

		$state 	= parent::store();

		return $state;
	}

	/**
	 * Creates a new stream record
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createStream( $verb )
	{
		// Add activity logging when a friend connection has been made.
		// Activity logging.
		$stream				= Foundry::stream();
		$streamTemplate		= $stream->getTemplate();

		// Set the actor.
		$streamTemplate->setActor( $this->user_id , SOCIAL_TYPE_USER );

		// Set the context.
		$streamTemplate->setContext( $this->id , 'notes' );

		// Set the verb.
		$streamTemplate->setVerb( $verb );

		$streamTemplate->setPublicStream( 'core.view' );

		// Create the stream data.
		$stream->add( $streamTemplate );
	}

	/**
	 * Overrides parent's delete behavior
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function delete( $pk = null )
	{
		$state	= parent::delete( $pk );

		// Delete streams that are related to this note.
		$stream 	= Foundry::stream();
		$stream->delete( $this->id , 'notes' );

		return $state;
	}
}
