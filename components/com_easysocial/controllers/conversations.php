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

Foundry::import( 'site:/controllers/controller' );

class EasySocialControllerConversations extends EasySocialController
{

	/**
	 * Logics to create a new conversations.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function store()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Ensure that the user is logged in.
		Foundry::requireLogin();

		// Get the current logged in user.
		$my 		= Foundry::user();

		// Get list of recipients.
		$recipients	= JRequest::getVar( 'uid' );

		// Ensure that the recipients is an array.
		$recipients = Foundry::makeArray( $recipients );

		// Get the view.
		$view 		= $this->getCurrentView();

		// Get configuration
		$config 	= Foundry::config();

		// Check if user is allowed to create new conversations
		$access 	= Foundry::access();

		if( !$access->allowed( 'conversations.create' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_NOT_ALLOWED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// If recipients is not provided, we need to throw an error.
		if( empty( $recipients ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_RECIPIENTS' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Check if the creator is allowed to send a message to the target
		$privacy 	= $my->getPrivacy();

		// Ensure that the recipients is not only itself.
		foreach( $recipients as $recipient )
		{
			// When user tries to enter it's own id, we should just break out of this function.
			if( $recipient == $my->id )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_CANNOT_SEND_TO_SELF' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Check if the creator is allowed
			if( !$privacy->validate( 'profiles.post.message' , $recipient, SOCIAL_TYPE_USER ) )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_CANNOT_SEND_TO_USER_DUE_TO_PRIVACY' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}
		}

		// Get the message that is being posted.
		$msg		= JRequest::getVar( 'message' , '' , 'REQUEST' , 'none' , JREQUEST_ALLOWHTML );

		// Message should not be empty.
		if( empty( $msg ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_MESSAGE' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Filter recipients and ensure all the user id's are proper!
		$total			= count( $recipients );

		// If there is more than 1 recipient and group conversations is disabled, throw some errors
		if( $total > 1 && !$config->get( 'conversations.multiple' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_GROUP_CONVERSATIONS_DISABLED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Go through all the recipient and make sure that they are valid.
		for( $i = 0; $i < $total; $i++ )
		{
			$userId	= $recipients[ $i ];
			$user 	= Foundry::user( $userId );

			if( !$user || empty( $userId ) )
			{
				unset( $recipients[ $i ] );
			}
		}

		// After processing the recipients list, and no longer has any recipients, stop the user.
		if( empty( $recipients ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_RECIPIENTS' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Get the conversation table.
		$conversation	= Foundry::table( 'Conversation' );

		// Determine the type of message this is by the number of recipients.
		$type		= count( $recipients ) > 1 ? SOCIAL_CONVERSATION_MULTIPLE : SOCIAL_CONVERSATION_SINGLE;

		// For single recipients, we try to reuse back previous conversations
		// so that it will be like a long chat of history.
		if( $type == SOCIAL_CONVERSATION_SINGLE )
		{
			// We know that the $recipients[0] is always the target user.
			$state 	= $conversation->loadByRelation( $my->id , $recipients[ 0 ] , SOCIAL_CONVERSATION_SINGLE );
		}

		// @points: conversation.create.group
		// Assign points when user starts new group conversation
		if( count( $recipients ) > 1 )
		{
			$points = Foundry::points();
			$points->assign( 'conversation.create.group' , 'com_easysocial' , $my->id );
		}

		// Set the conversation creator.
		$conversation->created_by	= $my->id;

		// Set the last replied date.
		$conversation->lastreplied 	= Foundry::date()->toMySQL();

		// Set the conversation type.
		$conversation->type 		= $type;

		// Let's try to create the conversation now.
		$state 		= $conversation->store();

		// If there's an error storing the conversation, break.
		if( !$state )
		{
			$view->setMessage( JText::_( $conversation->getError() ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// @rule: Store conversation message
		$message	= Foundry::table( 'ConversationMessage' );
		$post	 	= JRequest::get( 'POST' );

		// Bind the message data.
		$message->bind( $post );

		// Set the conversation id since we have the conversation id now.
		$message->conversation_id 	= $conversation->id;

		// Sets the message type.
		$message->type 				= SOCIAL_CONVERSATION_TYPE_MESSAGE;

		// Set the creation date.
		$message->created 			= Foundry::date()->toMySQL();

		// Set the creator.
		$message->created_by 		= $my->id;

		// Try to store the message now.
		$state 	= $message->store();

		if( !$state )
		{
			$view->setMessage( JText::_( $message->getError() ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Add users to the message maps.
		array_unshift( $recipients , $my->id );

		$model 	= Foundry::model( 'Conversations' );

		// Add the recipient as a participant of this conversation.
		$model->addParticipants( $conversation->id , $recipients );

		// Add the message maps so that the recipient can view the message
		$model->addMessageMaps( $conversation->id , $message->id , $recipients , $my->id );

		// Process attachments here.
		if( $config->get( 'conversations.attachments.enabled' ) )
		{
			$attachments	= JRequest::getVar( 'upload-id' );

			// If there are attachments, store them appropriately.
			if( $attachments )
			{
				$message->bindTemporaryFiles( $attachments );
			}
		}

		// Bind message location if necessary.
		if( $config->get( 'conversations.location' ) )
		{
			$address 		= JRequest::getVar( 'address' , '' );
			$latitude 		= JRequest::getVar( 'latitude' , '' );
			$longitude 		= JRequest::getVar( 'longitude' , '' );

			if( !empty( $address ) && !empty( $latitude ) && !empty( $longitude ) )
			{
				$location 				= Foundry::table( 'Location' );
				$location->loadByType( $message->id , SOCIAL_TYPE_CONVERSATIONS , $my->id );

				$location->address		= $address;
				$location->latitude		= $latitude;
				$location->longitude	= $longitude;
				$location->user_id 		= $this->created_by;
				$location->type 		= SOCIAL_TYPE_CONVERSATIONS;
				$location->uid 			= $message->id;

				$state 	= $location->store();
			}
		}

		// Send notification email to recipients
		foreach( $recipients as $recipientId )
		{
			// We should not send a notification to ourself.
			if( $recipientId != $my->id )
			{
				$recipient 	= Foundry::user( $recipientId );

				// Add new notification item
				$mailParams 	= Foundry::registry();
				$mailParams->set( 'name'			, $recipient->getName() );
				$mailParams->set( 'authorName'		, $my->getName() );
				$mailParams->set( 'authorAvatar'	, $my->getAvatar() );
				$mailParams->set( 'authorLink'		, $my->getPermalink() );
				$mailParams->set( 'message'			, $message->message );
				$mailParams->set( 'messageDate'		, $message->created );
				$mailParams->set( 'conversationLink', $conversation->getPermalink() );

				// Send a notification for all participants in this thread.
				$state 	= Foundry::notify( 'conversations.new' , array( $recipientId ) , array( 'title' => JText::sprintf( 'COM_EASYSOCIAL_CONVERSATIONS_NEW_EMAIL_TITLE' , $my->getName() ) , 'params' => $mailParams ) , false );
			}
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_MESSAGE_SENT' ) , SOCIAL_MSG_SUCCESS );

		// Pass this back to the view.
		return $view->call( __FUNCTION__ , $conversation );
	}

	public function loadPrevious()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// We know for the fact that guests can never access conversations.
		Foundry::requireLogin();

		$view 			= $this->getCurrentView();
		$my 			= Foundry::user();

		$id 			= JRequest::getInt( 'id', 0 );
		$limitstart 	= JRequest::getInt( 'limitstart', 0 );

		$model 			= Foundry::model( 'Conversations' );

		// override the startlimit
		$model->setState( 'limitstart'	, $limitstart );

		$limit 			= Foundry::themes()->getConfig()->get( 'messages_limit' );

		$messages		= $model->setLimit( $limit )->getMessages( $id , $my->id );

		$pagination 	= $model->getPagination();

		$nextlimit  	= ( $limitstart + $pagination->limit >= $pagination->total ) ? 0 : $limitstart + $pagination->limit;

		return $view->call( __FUNCTION__ , $messages, $nextlimit );
	}



	/**
	 * Processes a new reply for an existing conversation
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function reply()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// We know for the fact that guests can never access conversations.
		Foundry::requireLogin();

		// Get the current logged in user.
		$my 		= Foundry::user();

		// Get the message from the request. It should support raw codes.
		$msg		= JRequest::getVar( 'message' , '' );

		// Get the conversation id from the request.
		$id 		= JRequest::getInt( 'id' );

		// Get the current view.
		$view 		= $this->getCurrentView();

		// Get the configuration object.
		$config 	= Foundry::config();

		// Try to load the conversation.
		$conversation 	= Foundry::table( 'Conversation' );
		$state			= $conversation->load( $id );

		// If conversation id is invalid or not supplied, we need to throw some errors.
		if( !$id || !$conversation->load( $id ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_INVALID_ID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Load the conversation model.
		$model 			= Foundry::model( 'Conversations' );

		// Let's try to store the message now.
		$message 		= $model->addReply( $id , $msg , $my->id );

		if( !$message )
		{
			$view->setMessage( $model->getError() , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		//set the message->day to 0. This 'day' variable is need in themes file.
		$message->day = 0;

		if( $config->get( 'conversations.attachments.enabled' ) )
		{
			// Process attachments here.
			$attachments	= JRequest::getVar( 'upload-id' );

			// If there are attachments, store them appropriately.
			if( $attachments )
			{
				$message->bindTemporaryFiles( $attachments );
			}
		}

		if( $config->get( 'conversations.location' ) )
		{
			// Let's try to process the location if necessary.
			$address 		= JRequest::getVar( 'address' , '' );
			$latitude 		= JRequest::getVar( 'latitude' , '' );
			$longitude 		= JRequest::getVar( 'longitude' , '' );

			if( !empty( $address ) && !empty( $latitude ) && !empty( $longitude ) )
			{
				$location 				= Foundry::table( 'Location' );
				$location->loadByType( $message->id , SOCIAL_TYPE_CONVERSATIONS , $my->id );

				$location->address		= $address;
				$location->latitude		= $latitude;
				$location->longitude	= $longitude;
				$location->user_id 		= $my->id;
				$location->type 		= SOCIAL_TYPE_CONVERSATIONS;
				$location->uid 			= $message->id;

				$location->store();
			}
		}

		// Get recipients of this conversation.
		$recipients 	= $conversation->getParticipants( array( $my->id ) );

		foreach( $recipients as $recipient )
		{
			// Add new notification item
			$mailParams 	= Foundry::registry();
			$title 			= JText::sprintf( 'COM_EASYSOCIAL_CONVERSATIONS_REPLY_EMAIL_TITLE' , $my->getName() );
			$mailParams->set( 'name'			, $recipient->getName() );
			$mailParams->set( 'authorName'		, $my->getName() );
			$mailParams->set( 'authorAvatar'	, $my->getAvatar() );
			$mailParams->set( 'authorLink'		, $my->getPermalink() );
			$mailParams->set( 'message'			, $message->message );
			$mailParams->set( 'messageDate'		, $message->created );
			$mailParams->set( 'conversationLink', $conversation->getPermalink() );

			// Send a notification for all participants in this thread.
			$state 	= Foundry::notify( 'conversations.reply' , array( $recipient ) , array( 'title' => $title , 'params' => $mailParams ) , false );
		}

		// Return message back to the view.
		return $view->call( __FUNCTION__ , $conversation , $message );
	}

	/**
	 * Deletes an attachment
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteAttachment()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Ensure that the user is logged in
		Foundry::requireLogin();

		// Get the current view
		$view 	= $this->getCurrentView();

		// Get the id of the attachment
		$id 	= JRequest::getInt( 'id' );

		$file 	= Foundry::table( 'File' );
		$file->load( $id );

		// Check if the file is owned by the user.
		$allowed 	= $file->deleteable();

		if( !$allowed )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_NOT_ALLOWED_TO_DELETE_ATTACHMENT' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$state 	= $file->delete();

		if( !$state )
		{
			$view->setMessage( $attachment->getError() , SOCIAL_MSG_ERROR );

			return $view->call( __FUNCTION__ );
		}

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Deletes a conversation from the site.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function delete()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Check if the user is really logged in.
		Foundry::requireLogin();

		// Get the current logged in user.
		$my 	= Foundry::user();

		// Get the current view.
		$view 	= $this->getCurrentView();

		// Get the id's that needs to be deleted.
		$ids 	= JRequest::getVar( 'ids' );

		// Ensure that id's is an array.
		Foundry::makeArray( $ids );

		// Let's loop through each of the ids.
		foreach( $ids as $id )
		{
			$id				= (int) $id;

			$conversation	= Foundry::table( 'Conversation' );
			$state			= $conversation->load( $id );

			if( !$id || !$state )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_INVALID_ID' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Determines if the user has access to this conversation
			$hasAccess 	= $conversation->hasAccess( $my->id );

			if( !$hasAccess )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_NO_ACCESS' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Let's try to delete the conversation now.
			$state 	= $conversation->delete( $my->id );

			// If there's an error deleting, spit it out.
			if( !$state )
			{
				$view->setMessage( $conversation->getError() , SOCIAL_MSG_ERROR );

				return $view->call( __FUNCTION__ );
			}
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_DELETED_SUCCESSFULLY' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Unarchives a conversation
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function unarchive()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Guests does not have access to conversations.
		Foundry::requireLogin();

		$ids	= JRequest::getVar( 'id' );
		$ids	= Foundry::makeArray( $ids );
		$my 	= Foundry::user();

		// Get the current view.
		$view 	= Foundry::view( 'Conversations' , false );

		foreach( $ids as $id )
		{
			// Make sure that all requests are properly sanitized here.
			$id				= (int) $id;
			$conversation 	= Foundry::table( 'Conversation' );
			$state 			= $conversation->load( $id );

			if( !$state || !$id )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_INVALID_ID' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Check if the user has access to this conversation.
			if( !$conversation->hasAccess( $my->id ) )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_NO_ACCESS' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Try to unarchive the conversation.
			if( !$conversation->unarchive( $my->id ) )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_UNARCHIVING_CONVERSATION' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_CONVERSATION_UNARCHIVED' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Archives a conversation
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function archive()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Guests does not have access to conversations.
		Foundry::requireLogin();

		$ids	= JRequest::getVar( 'id' );

		// Ensure that $ids is now an array.
		$ids 	= Foundry::makeArray( $ids );

		// Get current logged in user.
		$my 	= Foundry::user();

		// Get current view.
		$view 	= Foundry::view( 'Conversations' , false );

		foreach( $ids as $id )
		{
			// Make sure id is properly typecasted into integer value.
			$id	= (int) $id;

			$conversation	= Foundry::table( 'Conversation' );
			$state 			= $conversation->load( $id );

			// Test if the conversation exist in the system.
			if( !$state || !$id )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_INVALID_ID' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Test if user has access to the conversation.
			if( !$conversation->hasAccess( $my->id ) )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_NO_ACCESS' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Test if there's any problem archiving the conversation.
			if( !$conversation->archive( $my->id ) )
			{
				$view->setMessage( $conversation->getErro() , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_CONVERSATION_ARCHIVED' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Mark a conversation as unread for a specific node.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function markUnread()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// We know for the fact that guests can never access conversations.
		Foundry::requireLogin();

		// Get a list of id's from the request.
		$ids	= JRequest::getVar( 'ids' );

		// Ensure that id's is always an array.
		$ids	= Foundry::makeArray( $ids );

		// Get the current logged in user.
		$my 	= Foundry::user();

		// Load view.
		$view 	= Foundry::view( 'Conversations' , false );

		// If there's no id's passed, we should just ignore this and throw some errors.
		if( !$ids )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_INVALID_ID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Let's loop through the id's.
		foreach( $ids as $id )
		{
			// Ensure that the id is valid integer.
			$id	= (int) $id;

			// Get the conversation table.
			$conversation	= Foundry::table( 'Conversation' );
			$state 			= $conversation->load( $id );

			if( !$state || !$id )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_INVALID_ID' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Check if the user has access to mark this as unread.
			if( !$conversation->hasAccess( $my->id ) )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_NO_ACCESS' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Mark this item as unread for the current user.
			$conversation->markAsUnread( $my->id );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_MARKED_AS_UNREAD' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Mark a conversation as read for a specific node.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function markRead()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// We know for the fact that guests can never access conversations.
		Foundry::requireLogin();

		// Get a list of id's from the request.
		$ids	= JRequest::getVar( 'ids' );

		// Ensure that id's is always an array.
		$ids	= Foundry::makeArray( $ids );

		// Get the current logged in user.
		$my 	= Foundry::user();

		// Load view.
		$view 	= Foundry::view( 'Conversations' , false );

		// If there's no id's passed, we should just ignore this and throw some errors.
		if( !$ids )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_INVALID_ID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		// Let's loop through the id's.
		foreach( $ids as $id )
		{
			// Ensure that the id is valid integer.
			$id	= (int) $id;

			// Get the conversation table.
			$conversation	= Foundry::table( 'Conversation' );
			$state 			= $conversation->load( $id );

			if( !$state || !$id )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_INVALID_ID' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Check if the user has access to mark this as unread.
			if( !$conversation->hasAccess( $my->id ) )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_NO_ACCESS' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ );
			}

			// Mark this item as unread for the current user.
			$conversation->markAsRead( $my->id );
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATION_MARKED_AS_READ' ) , SOCIAL_MSG_SUCCESS );

		return $view->call( __FUNCTION__ );
	}

	/**
	 * Allow a user to leave a conversation.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function leave()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// We know for the fact that guests can never access conversations.
		Foundry::requireLogin();

		// Get the conversation id.
		$id		= JRequest::getInt( 'id' );

		// Get the current view.
		$my 	= Foundry::user();

		// Get the current view.
		$view	= $this->getCurrentView();

		// Try to load the conversation
		$conversation	= Foundry::table( 'Conversation' );
		$state 			= $conversation->load( $id );

		if( !$state || !$id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_INVALID_ID' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $conversation );
		}

		// Check if the user has access to this conversation
		if( !$conversation->hasAccess( $my->id ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_NO_ACCESS' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $conversation );
		}

		// Let's try to leave the conversation.
		$state 	= $conversation->leave( $my->id );

		if( !$state )
		{
			$view->setMessage( $conversation->getError() , SOCIAL_MSG_ERROR );
			return $view->call(__FUNCTION__ , $conversation );
		}

		// Now we need to send notification to existing participants
		$participants 	= $conversation->getParticipants( array( $my->id ) );

		if( $participants )
		{
			foreach( $participants as $participant )
			{
				$title 			= JText::sprintf( 'COM_EASYSOCIAL_CONVERSATIONS_EMAILS_LEFT_CONVERSATION' , $my->getName() );

				// Add new notification item
				$mailParams 	= Foundry::registry();
				$mailParams->set( 'name'			, $participant->getName() );
				$mailParams->set( 'authorName'		, $my->getName() );
				$mailParams->set( 'authorAvatar'	, $my->getAvatar() );
				$mailParams->set( 'authorLink'		, $my->getPermalink() );
				$mailParams->set( 'conversationLink', $conversation->getPermalink() );

				// Send a notification for all participants in this thread.
				$state 	= Foundry::notify( 'conversations.leave' , array( $participant->id ) , array( 'title' => $title , 'params' => $mailParams ) , false );
			}
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_LEFT_CONVERSATION_SUCCESS' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ );
	}

	/**
	 * Adds a user into an existing conversation.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function addParticipant()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// We know for the fact that guests can never access conversations.
		Foundry::requireLogin();

		// Get the conversation id.
		$id		= JRequest::getInt( 'id' );

		// Load the current conversation
		$conversation	= Foundry::table( 'Conversation' );
		$state 			= $conversation->load( $id );

		// Get current logged in user.
		$my 			= Foundry::user();

		// Get current view
		$view 			= $this->getCurrentView();

		// Get config
		$config 		= Foundry::config();

		// Check if multiple conversations is enabled
		if( !$config->get( 'conversations.multiple' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_GROUP_CONVERSATIONS_DISABLED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $conversation );
		}

		// Check that there are recipients.
		if( !$state || !$id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_INVALID_CONVERSATION_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $conversation );
		}

		// Check if the user is allowed to add people to the conversation
		if( !$conversation->isParticipant() )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_NOT_ALLOWED_ACCESS_TO_CONVERSATION' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $conversation );
		}

		// Get the new recipients.
		$recipients 	= JRequest::getVar( 'uid' );

		// Ensure that the recipients is in an array form.
		$recipients 	= Foundry::makeArray( $recipients );

		// Check that there are recipients.
		if( !$recipients || empty( $recipients ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_INVALID_RECIPIENTS_PROVIDED' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ , $conversation );
		}

		// Get the current logged in user.
		$my 	= Foundry::user();

		// Let's go through the list of recipients and add them to the conversation.
		foreach( $recipients as &$id )
		{
			// Run cleanup on the node id to make sure that they are all typecasted to integer.
			$id 	= (int) $id;
			$state 	= $conversation->addParticipant( $my->id , $id );

			if( !$state )
			{
				$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_ADDING_PARTICIPANT' ) , SOCIAL_MSG_ERROR );
				return $view->call( __FUNCTION__ , $conversation );
			}
		}

		// We need to update the conversation type to multiple
		$conversation->type 	= SOCIAL_CONVERSATION_MULTIPLE;
		$conversation->store();


		// Send notification email to recipients that got invited to the conversation
		foreach( $recipients as $recipientId )
		{
			$recipient 	= Foundry::user( $recipientId );

			// Add new notification item
			$mailParams 	= Foundry::registry();
			$mailParams->set( 'name'			, $recipient->getName() );
			$mailParams->set( 'authorName'		, $my->getName() );
			$mailParams->set( 'authorAvatar'	, $my->getAvatar() );
			$mailParams->set( 'authorLink'		, $my->getPermalink() );
			$mailParams->set( 'conversationLink', $conversation->getPermalink() );

			$title 	= JText::sprintf( 'COM_EASYSOCIAL_EMAILS_CONVERSATIONS_INVITED_TO_CONVERSATION' , $my->getName() );

			// Send a notification for all participants in this thread.
			$state 	= Foundry::notify( 'conversations.invite' , array( $recipientId ) , array( 'title' => $title , 'params' => $mailParams ) , false );
		}

		// Now we need to send notification to existing participants
		$participants 	= $conversation->getParticipants( array( $my->id ) );

		if( $participants )
		{
			foreach( $participants as $participant )
			{
				// Add new notification item
				$mailParams 	= Foundry::registry();
				$mailParams->set( 'total'			, count( $recipients ) );
				$mailParams->set( 'name'			, $participant->getName() );
				$mailParams->set( 'authorName'		, $my->getName() );
				$mailParams->set( 'authorAvatar'	, $my->getAvatar() );
				$mailParams->set( 'authorLink'		, $my->getPermalink() );
				$mailParams->set( 'conversationLink', $conversation->getPermalink() );

				// Send a notification for all participants in this thread.
				$state 	= Foundry::notify( 'conversations.invited' , false , array( 'params' => $mailParams ) );
			}
		}

		$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_MESSAGE_SENT' ) , SOCIAL_MSG_SUCCESS );

		// Set a success message.
		$view->setMessage( JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_ADDED_RECIPIENTS' ) , SOCIAL_MSG_SUCCESS );
		return $view->call( __FUNCTION__ , $conversation );
	}

	/**
	 * Returns a list of conversations.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function getCount()
	{
		// Check for request forgeries
		Foundry::checkToken();

		// Ensure that the user is logged in.
		Foundry::requireLogin();

		// Get the current logged in user.
		$my 		= Foundry::user();

		// Get the current view.
		$view 		= Foundry::view( 'Conversations' , false );

		// Get the model
		$model 		= Foundry::model( 'Conversations' );

		// Get the mail box from the request.
		$mailbox 	= JRequest::getWord( 'mailbox' );

		// Get the conversations for this inbox type.
		$total		= $model->getNewCount( $my->id , $mailbox );

		return $view->call( __FUNCTION__ , $total );
	}
}
