<?php
/**
* @package		Social
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'site:/views/views' );

class EasySocialViewConversations extends EasySocialSiteView
{
	/**
	 * Display dialog to confirm deleting of attachment
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmDeleteAttachment()
	{
		// Users must be logged in
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		$id 	= JRequest::getInt( 'id' );
		$theme 	= Foundry::themes();

		$theme->set( 'id' , $id );

		$contents 	= $theme->output( 'site/conversations/dialog.delete.attachment' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Display post message after an attachment is deleted
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function attachmentDeleted()
	{
		// Users must be logged in
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		$id 	= JRequest::getInt( 'id' );
		$theme 	= Foundry::themes();

		$theme->set( 'id' , $id );

		$contents 	= $theme->output( 'site/conversations/dialog.attachment.deleted' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Post processing after deleting an attachment
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteAttachment()
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve();
	}

	/**
	 * Displays the composer form
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function composer()
	{
		// User needs to be logged in.
		Foundry::requireLogin();

		$ajax 		= Foundry::ajax();
		$id 		= JRequest::getInt( 'id' );

		$recipient	= Foundry::user( $id );

		$theme 		= Foundry::themes();
		$theme->set( 'recipient' , $recipient );

		$contents 	= $theme->output( 'site/conversations/dialog.compose' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Displays the sent confirmation dialog
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function sent()
	{
		// User needs to be logged in.
		Foundry::requireLogin();

		$ajax 		= Foundry::ajax();
		$id 		= JRequest::getInt( 'id' );

		$recipient	= Foundry::user( $id );

		$theme 		= Foundry::themes();
		$theme->set( 'recipient' , $recipient );

		$contents 	= $theme->output( 'site/conversations/dialog.sent' );

		return $ajax->resolve( $contents );
	}

	public function loadPrevious( $messages, $nextlimit )
	{
		// User needs to be logged in.
		Foundry::requireLogin();

		$ajax 		= Foundry::ajax();

		$theme 		= Foundry::themes();
		$theme->set( 'messages' , $messages );

		$contents 	= $theme->output( 'site/conversations/read.ajax' );

		return $ajax->resolve( $contents, $nextlimit );
	}

	/**
	 * Leave conversation confirmation form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmLeave()
	{
		// User must be logged in
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		$id 	= JRequest::getInt( 'id' );
		$theme 	= Foundry::themes();

		$theme->set( 'id' , $id );

		$contents 	= $theme->output('site/conversations/dialog.leave' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Allows user to add participant to an existing conversation
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function addParticipantsForm()
	{
		// User must be logged in
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		$id 	= JRequest::getInt( 'id' );

		// Load up the conversation
		$conversation 	= Foundry::table( 'Conversation' );
		$conversation->load( $id );

		// Check if the current user is a participant
		if( !$conversation->isParticipant() )
		{
			$this->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_NO_ACCESS' ) , SOCIAL_MSG_ERROR );

			return $ajax->reject( $this->getMessage() );
		}

		// Get a list of participants
		$participants 	= $conversation->getParticipants();
		$ids 			= array();


		foreach( $participants as $user )
		{
			$ids[]	= $user->id;
		}

		$theme 	= Foundry::themes();
		$theme->set( 'ids' , $ids );
		$theme->set( 'id' , $id );

		$contents	 = $theme->output( 'site/conversations/dialog.add.participant' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Displays the dialog to confirm unarchive
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmUnArchive()
	{
		// Require user to be logged in
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		$ids 	= JRequest::getVar( 'ids' );

		$theme->set( 'ids' , $ids );

		$contents 	= $theme->output( 'site/conversations/dialog.unarchive' );

		$ajax->resolve( $contents );
	}

	/**
	 * Displays the dialog to confirm archive
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmArchive()
	{
		// Require user to be logged in
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		$ids 	= JRequest::getVar( 'ids' );

		$theme->set( 'ids' , $ids );

		$contents 	= $theme->output( 'site/conversations/dialog.archive' );

		$ajax->resolve( $contents );
	}

	/**
	 * Displays the dialog to confirm deletion
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmDelete()
	{
		// Require user to be logged in
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		$theme 	= Foundry::themes();

		$ids 	= JRequest::getVar( 'ids' );

		$theme->set( 'ids' , $ids );

		$contents 	= $theme->output( 'site/conversations/dialog.delete' );

		$ajax->resolve( $contents );
	}

	/**
	 * Responsible to process an ajax call that tries to store a conversation.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableConversation
	 */
	public function store( $conversation = null )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$link	= FRoute::conversations( array( 'id' => $conversation->id , 'layout' => 'read' ) , false );

		return $ajax->resolve( $link );
	}

	public function search()
	{
		Foundry::requireLogin();

		$my			= Foundry::user();
		$config 	= Foundry::config();
		$options	= array(
								'sorting' 	=> $config->get( 'conversations.list.sorting'),
								'ordering'	=> $config->get( 'conversations.list.ordering' ),
								'search'	=> JRequest::getString( 'search' )
							);

		$model 			= Foundry::model( 'Conversations' );
		$conversations	= $model->getConversations( $my->get( 'node_id' ) , $options );
		$pagination		= $model->getPagination();

		$result			= array();

		foreach( $conversations as $conversation )
		{
			$result[]	= $conversation->export();
		}
		Foundry::get( 'AJAX' )->success( $result );
	}

	/**
	 * Handle output after conversation has been marked as unread
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function markUnread()
	{
		$ajax 	= Foundry::ajax();

		// Check if there's an error in this request
		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve();
	}

	/**
	 * Handle output after conversation has been marked as unread
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function markRead()
	{
		$ajax 	= Foundry::ajax();

		// Check if there's an error in this request
		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve();
	}

	/**
	 * Responsible to output a JSON encoded data.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableConversation			The conversation table.
	 * @param	SocialTableConversationMessage	The message table.
	 * @param	string	If form contains an uploder, a token is necessary (optional).
	 *
	 * @return	json
	 */
	public function reply( $conversation , $message )
	{
		// Get ajax library.
		$ajax 	= Foundry::ajax();

		// We know for the fact that guests can never access conversations.
		Foundry::requireLogin();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		// Assign missing properties
		$conversation->message 	= $message->message;

		// @trigger: onPrepareConversations
		$dispatcher = Foundry::dispatcher();
		$conversations 	= array( &$conversation );
		$args 		= array( &$conversations );

		$dispatcher->trigger( SOCIAL_TYPE_USER , 'onPrepareConversations' , $args );

		// We know for sure that the author is the current logged in user.
		$my		= Foundry::user();

		$theme 	= Foundry::themes();
		$theme->set( 'message'	, $message );
		$theme->set( 'conversation'	, $conversation );

		$content 	= $theme->output( 'site/conversations/read.item.message' );

		return $ajax->resolve( $content );
	}

	public function unarchive()
	{
		$errors	= $this->getErrors();

		// @TODO: Process errors here.
		if( $errors )
		{
		}

		Foundry::get( 'AJAX' )->success();
	}

	public function archive()
	{
		$errors	= $this->getErrors();

		// @TODO: Process errors here.
		if( $errors )
		{
		}

		Foundry::getInstance( 'AJAX' )->success();
	}

	/**
	 * Method to return the JSON response back to the caller to update the counter.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getCount( $total )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve( $total );
	}

	/**
	 * Method to return the JSON response back to the caller.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getItems()
	{
		// Ensure that the user is logged in.
		Foundry::requireLogin();

		$ajax 	= Foundry::ajax();

		// If there's any errors, throw them
		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}


		$my				= Foundry::user();
		$config 		= Foundry::config();

		// Get the mail box from the request.
		$mailbox 	= JRequest::getWord( 'mailbox' );
		$filter 	= JRequest::getWord( 'filter', '' );

		if( $filter == 'all' )
		{
			$filter = '';
		}

		$options 		= array(
								'sorting'	=> $this->themeConfig->get( 'conversation_sorting' ),
								'ordering'	=> $this->themeConfig->get( 'conversation_ordering' ),
								'limit'		=> $this->themeConfig->get( 'conversation_limit' )
								);

		// @TODO: In the future, we might want to separate mails in mailboxes.
		if( $mailbox == 'archives' )
		{
			$options[ 'archives' ]	= true;
		}

		if( $filter )
		{
			$options[ 'filter' ]	= $filter;
		}

		// Load the conversation model.
		$model 			= Foundry::model( 'Conversations' );
		$conversations	= $model->getConversations( $my->id , $options );
		$pagination		= $model->getPagination();

		$pagination->setVar( 'view' , 'conversations' );

		if( $mailbox == 'archives' )
		{
			$pagination->setVar( 'layout' , 'archives' );
		}

		if( $filter )
		{
			$pagination->setVar( 'filter' , $filter );
		}


		$this->set( 'pagination'		, $pagination );
		$this->set( 'conversations' 	, $conversations );

		$contents 		= parent::display( 'site/conversations/default.item' );

		return $ajax->resolve( $contents , empty( $conversations ) );

	}
}
